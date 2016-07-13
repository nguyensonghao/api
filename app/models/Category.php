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

		if (!$this->checkExitsCate($userId, $categoryName))
			return array('status' => 306);
		
		$date = date('Y-m-d H:i:s');
		$category = new Category();
		$category->userId = $userId;
		$category->date   = $date;
		$category->categoryName = $categoryName;
		$category->status = 0;
		$category->created_at = $date;
		if ($category->save()) {
			$cate = Category::where('userId', $userId)->where('date', $date)
		    ->where('categoryName', $categoryName)->first();		    
		  	
		  	// Update lasted_update of user table
		    User::updateLastest($userId, $date);
			return array('status' => 200, 'cateId' => $cate->cateId, 'lastedUpdate' => $date);
		} else return array('status' => 304);			
	}

	public function updateCategory ($categoryId, $category, $userId) {
		$date = date('Y-m-d H:i:s');
		$category['updated_at'] = $date;
		if (Category::where('categoryId', $categoryId)->where('userId', $userId)
			->update($category)) {

			// Update lasted_update of user table
			User::updateLastest($userId, $date);
			return array('status' => 200, 'lastedUpdate' => $date);
		} else {
			return array('status' => 304);
		}
	}

	public function deleteCategory ($userId, $categoryId) {
		$date = date('Y-m-d H:i:s');
		$cate = Category::where('categoryId', $categoryId)->where('userId', $userId)->first();
		$categoryName = $cate->categoryName . (string)$date;
		if (Category::where('categoryId', $categoryId)->where('userId', $userId)
			->update(array(
				'categoryName' => $categoryName,
				'status' => -1,
				'updated_at' => $date))) {

		    // Delete all note in cate
			Note::where('cateId', $categoryId)->update(array('status' => -1, 'updated_at' => $date));

			// Update lasted_update of user table
			User::updateLastest($userId, $date);
			return array('status' => 200, 'lastedUpdate' => $date);
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
			$myCategory = Category::where('userId', $userId)->where('status', '<>', -1)->get();
			return $myCategory;
		} else {
			return [];
		}
	}

	public function updateTimeServer ($timeStamp, $userId, $type) {
		$time = Time::where('type', $type)->where('userId', $userId)->first();
		if (is_null($time)) {
			Time::insert(array('type' => $type, 'userId' => $userId, 'time' => $timeStamp));
		} else {
			Time::where('type', $type)->where('userId', $userId)->update(array('time' => $timeStamp));
		}
	}

	public function pullData ($userId, $timeLocal, $timeStamp) {
		$listCate = DB::table('category')->where('userId', $userId)
		->where('updated_at', '>', $timeLocal)->get();

		if (count($listCate) != 0) {
			// Update update_at list cate return
			DB::table('category')->where('userId', $userId)
			->where('updated_at', '>', $timeLocal)->update(array('updated_at' => $timeStamp));
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
			$cate = $list[$i];
			$cate['updated_at'] = $timeStamp;
			Category::where('categoryId', $cate['categoryId'])->update($cate);
		}

		return array('status' => 200);
	}

	protected function checkExitsCate ($userId, $categoryName) {
		$cate = Category::where('userId', $userId)->where('categoryName', $categoryName)->first();
		if (is_null ($cate)) 
			return true;

		return false;
	}

	public function getListCateServer ($userId, $skip, $lastedUpdate) {
		if (is_null($lastedUpdate)) {
			return Category::where('userId', $userId)->skip($skip)->take(100)->get();
		} else {
			return Category::where('userId', $userId)->where('updated_at', '>', $lastedUpdate)
			->skip($skip)->take(100)->get();
		}
	}

}
