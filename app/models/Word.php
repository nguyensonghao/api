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
				->where('id_course', '<', $id_course + 10)->paginate(20);
			} else {
				return Word::where('status', 1)->where('id_course', '>', $id_course)
				->where('id_course', '<', $id_course + 10)
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
				->where('id_course', '<', $id_course + 10)->paginate(20);
			} else {
				return Word::where('status', '<>', 1)->where('id_course', '>', $id_course)
				->where('id_course', '<', $id_course + 10)
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


}
