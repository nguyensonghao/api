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

	public function __construct () {
		DB::connection()->disableQueryLog();
	}

	public function addNote ($noteName, $noteMean, $categoryId, $date, $type, $idx) {
		$note = new Note();
		if (!$this->checkExistCategory($categoryId))
			return array('status' => 304);

		if (!$this->checkExistNote($noteName, $categoryId))
			return array('status' => 306);

		$date = date('Y-m-d H:i:s');
		$note->noteName = $noteName;
		$note->noteMean = $noteMean;
		$note->date     = $date;
		$note->type     = $type;
		$note->noteMean = $noteMean;
		$note->cateId   = $categoryId;
		$note->idx      = $idx;
		$note->status   = 0;
		$note->created_at = $date;
		if ($note->save()) {
			$id = Note::where('cateId', $categoryId)->where('noteName', $noteName)
			->where('date', $date)->first()->noteId;
			// Update lasted_update of user table
			User::updateLastest(Category::where('categoryId', $categoryId)->first()->userId, $date);
			return array('status' => 200, 'noteId' => $id, 'lastedUpdate' => $date);
		} else {
			return array('status' => 304);
		}
	}	

	public function deleteNote ($noteId) {
		$date = date('Y-m-d H:i:s');
		$noteName = Note::where('noteId', $noteId)->first()->noteName . (string)$date;
		if (Note::where('noteId', $noteId)->update(array(
				'noteName' => $noteName, 
				'status' => -1, 
				'updated_at' => $date))) {

			// Update lasted_update of user table
			User::updateLastest($userId, $date);
			return array('status' => 200, 'lastedUpdate' => $date);
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
			$note = DB::table('note')->where('cateId', $categoryId)
			->where('note.status', '<>', -1)
			->leftJoin('category', 'category.categoryId', '=', 'note.cateId')->get();
			$sizeNote = count($note);
			for ($j = 0; $j < $sizeNote; $j++) {
				array_push($listNote, $note[$j]);
			}
		}

		return $listNote;
	}

	public function pullData ($userId, $timeLocal, $timeStamp) {
		$listNote = DB::table('category')->select('note.noteId', 'note.noteMean', 'note.noteName', 'note.cateId', 'note.type', 'note.updated_at', 'note.idx')
		->where('userId', $userId)->join('note', 'note.cateId', '=', 'category.categoryId')
		->where('note.updated_at', '>', $timeLocal)->get();
		$size = count($listNote);

		if ($size != 0) {
			// Update update_at list cate return
			for ($i = 0; $i < $size; $i++) {
				DB::table('note')->where('noteId', $listNote[$i]->noteId)->update(array('updated_at' => $timeStamp));
			}
			return array('status' => 200, 'result' => $listNote);
		} else {
			return array('status' => 304);
		}
	}

	public function pushDataNew ($userId, $timeStamp, $listNote) {
		$list = json_decode(json_encode($listNote), true);
		$size = count($list);
		$listNoteReturn = [];
		for ($i = 0; $i < $size; $i++) {
			$list[$i]['updated_at'] = $timeStamp;
			$id = DB::table('note')->insertGetId($list[$i]);
			$query = $list[$i]['noteName'];			
			array_push($listNoteReturn, array('id' => $id, 'query' => $query));
		}

		return array('status' => 200, 'result' => $listNoteReturn);
	}

	public function updateDataChange ($listNote, $timeStamp) {
		$list = json_decode(json_encode($listNote), true);
		$size = count($list);
		for ($i = 0; $i < $size; $i++) {
			$note = $list[$i];
			$note['updated_at'] = $timeStamp;
			Note::where('noteId', $note['noteId'])->update($note);
		}

		return array('status' => 200);
	}

	protected function checkExistNote ($noteName, $cateId) {
		$note = Note::where('noteName', $noteName)->where('cateId', $cateId)->first();
		if (is_null ($note))
			return true;

		return false;
	}

	public function getListNoteServer ($userId, $skip, $lastedUpdate) {
		if (is_null($lastedUpdate)) {
			return Category::select('note.noteId', 'note.noteMean', 'note.noteName', 'note.cateId', 'note.type', 'note.updated_at', 'note.idx')
			->where('userId', $userId)->join('note', 'note.cateId', '=', 'category.categoryId')
			->skip($skip)->take(100)->get();
		} else {
			return Category::select('note.noteId', 'note.noteMean', 'note.noteName', 'note.cateId', 'note.type', 'note.updated_at', 'note.idx')
			->where('userId', $userId)->join('note', 'note.cateId', '=', 'category.categoryId')
			->where('note.updated_at', '>', $lastedUpdate)
			->skip($skip)->take(100)->get();
		}
	}

}
