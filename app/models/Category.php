<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Category extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'category';

	public function addCategory ($userId, $categoryName, $date) {
		if (!$this->checkExitsUser($userId))
			return array('status' => 304);
			
		$category = new Category();
		$category->userId = $userId;
		$category->date   = $date;
		$category->categoryName = $categoryName;
		if ($category->save()) {
			$cateId = Category::where('userId', $userId)->where('date', $date)
		    ->where('categoryName', $categoryName)->first()->categoryId;
			return array('status' => 200, 'cateId' => $cateId);
		} else return array('status' => 304);			
	}

	public function updateCategory ($categoryId, $category, $userId) {
		if (Category::where('categoryId', $categoryId)->where('userId', $userId)
			->update($category))
			return array('status' => 200);
		else
			return array('status' => 304);
	}

	public function deleteCategory ($userId, $categoryId) {
		if (Category::where('categoryId', $categoryId)->where('userId', $userId)->delete()) {
			Note::where('cateId', $categoryId)->delete();
			return array('status' => 200);
		} else {
			return array('status' => 304);
		}
	}

	protected function checkExitsUser ($userId) {
		$user = User::where('userId', $userId)->first();
		if (is_null($user))
			return false;
		else
			return true;
	}

	public function getCategory ($userId) {
		if ($this->checkExitsUser($userId)) {
			$myCategory = Category::where('userId', $userId)->get();
			return $myCategory;
		} else {
			return [];
		}
	}

	public function pullData ($userId, $timeLocal) {
		$listCate = DB::table('category')->where('userId', $userId)
		->where('updated_at', '>', $timeLocal)->get();
		if (count($listCate) != 0) {
			return array('status' => 200, 'result' => $listCate);
		} else {
			return array('status' => 304);
		}
	}

	public function pushDataNew ($userId, $timeStamp, $listCate) {
		$list = json_decode(json_encode($listCate), true);
		$size = count($list);
		$listCateReturn = [];
		for ($i = 0; $i < $size; $i++) {
			$list[$i]['updated_at'] = $timeStamp;
			$id = DB::table('category')->insertGetId($list[$i]);
			$name = $list[$i]['categoryName'];
			array_push($listCateReturn, array('id' => $id, 'category' => $name));
		}

		return array('status' => 200, 'result' => $listCateReturn);
	}

	public function updateDataChange ($listCate, $timeStamp) {
		$list = json_decode(json_encode($listCate), true);
		$size = count($list);
		for ($i = 0; $i < $size; $i++) {
			$cate = $listCate[$i];
			$cate['updated_at'] = $timeStamp;
			Category::where('categoryId', $cate['categoryId'])->update($cate);
		}

		return array('status' => 200);
	}

}
