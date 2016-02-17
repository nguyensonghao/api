<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Note extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'note';

	public function addNote ($noteName, $noteMean, $categoryId) {
		$note = new Note();
		if ($this->checkExistCategory($categoryId))
			return array('status' => 304);

		$note->noteName   = $noteName;
		$note->noteMean   = $noteMean;
		$note->categoryId = $categoryId;
		if ($note->save()) {
			return array('status' => 200);
		} else {
			return array('status' => 304);
		}
	}

	public function updateNote ($noteId, $note) {
		
	}

	public function deleteNote ($noteId) {
		if (Note::where('noteId', $noteId)->delete()) {
			return array('status' => 200);
		} else {
			return array('status' => 304);
		}
	}

	protected function checkExistCategory ($categoryId) {
		$category = Category::where('categoryId', $categoryId)->first();
		if (is_null($category))
			return false;
		else return true;
	}

}
