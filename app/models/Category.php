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
	protected $table = 'catagory';

	public function addCategory ($userId, $categoryName) {
		if (!$this->checkExitsUser($userId))
			return array('status' => 304);
			
		$category = new Category();
		$category->userId = $userId;
		$category->categoryName = $categoryName;
		if ($category->save())
			return array('status' => 200);
		else
			return array('status' => 304);
	}

	public function updateCategory ($categoryId, $category, $userId) {
		if (Category::where('categoryId', $categoryId)->where('userId', $userId)
			->update($category))
			return array('status' => 200);
		else
			return array('status' => 304);
	}

	public function deleteCategory ($categoryId, $userId) {
		if (Category::where('categoryId', $categoryId)->where('userId', $userId)->delete())
			return array('status' => 200);
		else
			return array('status' => 304);
	}

	protected function checkExitsUser ($userId) {
		$user = User::where('userId', $userId)->first();
		if (is_null($user))
			return false;
		else
			return true;
	}

	public function getMyNote ($userId) {
		if ($this->checkExitsUser()) {
			$myNote = Category::where('userId', $userId)
			->join('note', 'note.categoryId', '=', 'category.categoryId')->get();
			if ($myNote == null || !is_array($myNote) || count($myNote) == 0) {
				return array('status' => 304);
			} else {
				return array('status' => 200, 'result' => $myNote);
			}
		} else {
			return array('status' => 304);
		}
	}

}
