<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Word extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'words';	

	public function getListExcute () {
		return Word::where('status', 1)->paginate(20);
	}

	public function getListNotExcute () {
		return Word::where('status', '<>', 1)->paginate(20);
	}

	public function completeImage ($id) {
		return Word::where('id', $id)->update(array('status' => 1));
	}



}
