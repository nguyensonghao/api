<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Flashcard extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'flashcard';

	public function getFlashCard ($userId, $type) {
		$listFlash = Flashcard::where('userId', $userId)->where('type', $type)
		->get();
		if (count($listFlash) == 0) {
			return array('status' => 304);
		} else {
			return array('status' => 200, 'result' => $listFlash);
		}
	}

	public function remember ($userId, $wordId, $type) {
		$flashcard = new Flashcard();
		$flashcard->userId = $userId;
		$flashcard->wordId = $wordId;
		$flashcard->type = $type;
		if ($flashcard->save()) {
			return array('status' => 200);
		} else {
			return array('status' => 304);
		}
	}

	public function forget ($userId, $wordId, $type) {
		if (Flashcard::where('userId', $userId)->where('wordId', $wordId)
		->where('type', $type)->delete()) {
			return array('status' => 200);
		} else {
			return array('status' => 304);
		}
	}

}
