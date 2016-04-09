<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Course extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'courses';

	public function getList ($id_course) {
		$id_course = ((int)($id_course / 1000000)) * 1000000;
		return Course::where('id', '>', $id_course)->where('id', '<', $id_course + 10)->get();
	}


}
