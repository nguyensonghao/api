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

	public function getListExcute ($id_course, $id_subject) {
		$id = (string)$id_course;
		$id = substr($id, -1);
		$id = (int)$id;
		if ($id == 0) {
			if ($id_subject == 'all') {
				return Word::where('status', 1)->where('id_course', '>', $id_course)
				->where('id_course', '<', $id_course + 100)->paginate(20);
			} else {
				return Word::where('status', 1)->where('id_course', '>', $id_course)
				->where('id_course', '<', $id_course + 100)
			    ->where('id_subject', $id_subject)->paginate(20);
			}		
		} else {
			if ($id_subject == 'all') {
				return Word::where('status', 1)->where('id_course', $id_course)->paginate(20);
			} else {
				return Word::where('status', 1)->where('id_course', $id_course)
			    ->where('id_subject', $id_subject)->paginate(20);
			}		
		}		
	}

	public function getListNotExcute ($id_course, $id_subject) {
		$id = (string)$id_course;
		$id = substr($id, -1);
		$id = (int)$id;
		if ($id == 0) {
			if ($id_subject == 'all') {
				return Word::where('status', '<>', 1)->where('id_course', '>', $id_course)
				->where('id_course', '<', $id_course + 100)->paginate(20);
			} else {
				return Word::where('status', '<>', 1)->where('id_course', '>', $id_course)
				->where('id_course', '<', $id_course + 100)
			    ->where('id_subject', $id_subject)->paginate(20);
			}		
		} else {
			if ($id_subject == 'all') {
				return Word::where('status', '<>', 1)->where('id_course', $id_course)->paginate(20);
			} else {
				return Word::where('status', '<>', 1)->where('id_course', $id_course)
			    ->where('id_subject', $id_subject)->paginate(20);
			}		
		}		
	}

	public function completeImage ($id) {
		return Word::where('id', $id)->update(array('status' => 1));
	}

	public function updateMean ($id, $mean, $phonectic, $word, $des) {
		return Word::where('id', $id)->update(array('mean' => $mean, 'phonetic' => $phonectic, 'word' => $word, 'des' => $des));
	}

	public function insertWord ($word) {
		if (is_null($word['mean']))
			$word['meam'] = '';

		if (is_null($word['example']))
			$word['example'] = '';

		if (is_null($word['example_mean']))
			$word['example_mean'] = '';			

		if (is_null($word['phonetic']))
			$word['phonetic'] == null;

		if (is_null($word['des']))
			$word['des'] = '';

		$word['next_time'] = '';		
		$word['time_date'] = '';
		$word['num_ef'] = 0;
		$word['num_n'] = 0;
		$word['num_i'] = 0;
		$word['max_q'] = 0;

		return Word::insert($word);
	}

	public function getLastIdWord ($idCourse) {
		$idCourseTest = (int)($idCourse / 1000) * 1000;
		$idCoureFirst = (int)($idCourse / 1000000);
		$word = Word::where('id_course', '>=', $idCourseTest)
				->where('id_course', '<=', $idCourseTest + 100)
		        ->orderBy('id_word', 'desc')->first();

		if (is_null($word)) {
			return $idCoureFirst * 1000000;
		} else {
			$id = (string)($word->id_word);
			$first = (string)$idCoureFirst;
			$last = substr($id, 3, strlen($id) - 3);
			return $first . $last;
		}		
	}


}
