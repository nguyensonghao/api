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

	public function insertSubject ($subject) {
		if (is_null($subject['mean']))
			$subject['mean'] = '';

		$subject['time_date'] = '';

		return Subject::insert($subject);
	}

	public function getLastIdSubject ($idCourse) {
		$idCourseTest = (int)($idCourse / 1000) * 1000;
		$idCoureFirst = (int)($idCourse / 1000000);
		$subject = Subject::where('id_course', '>=', $idCourseTest)
				->where('id_course', '<=', $idCourseTest + 100)
		        ->orderBy('id', 'desc')->first();

		if (is_null($subject)) {
			return $idCoureFirst * 1000000;
		} else {
			$id = (string)($subject->id);
			$first = (string)$idCoureFirst;
			$last = substr($id, 3, strlen($id) - 3);
			return $first . $last;
		}		
	}

}
