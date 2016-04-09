<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Subject extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'subjects';	

	public function getList ($id_course) {
		$id = (string)$id_course;
		$id = substr($id, -1);
		$id = (int)$id;
		if ($id == 0) {
			return Subject::where('id_course', '>', $id_course)
			->where('id_course', '<', $id_course + 10)->get();
		} else {
			return Subject::where('id_course', $id_course)->get();
		}		
	}

}
