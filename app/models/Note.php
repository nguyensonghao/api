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

	public function addNote ($noteName, $noteMean, $categoryId, $date, $type) {
		$note = new Note();
		if (!$this->checkExistCategory($categoryId))
			return array('status' => 304);

		$note->noteName = $noteName;
		$note->noteMean = $noteMean;
		$note->date     = $date;
		$note->type     = $type;
		$note->noteMean = $noteMean;
		$note->cateId   = $categoryId;
		if ($note->save()) {
			$id = Note::where('cateId', $categoryId)->where('noteName', $noteName)
			->where('date', $date)->first()->noteId;
			return array('status' => 200, 'noteId', $id);
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

	public function getNote ($myCategory) {
		$size = count($myCategory);
		$listNote = array();
		for ($i = 0; $i < $size; $i++) {
			$categoryId = $myCategory[$i]->categoryId;
			$note = Note::select('note.noteId, note.noteName, note.noteMean, note.date, category.categoryName, note.type')
			->where('cateId', $categoryId)
			->leftJoin('category', 'category.categoryId', '=', 'note.cateId')->get();
			$sizeNote = count($note);
			for ($j = 0; $j < $sizeNote; $j++) {
				array_push($listNote, $note[$j]);
			}
		}

		return $listNote;
	}

}
