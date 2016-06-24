<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class SubjectClone extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'subjects_clone';

	public function __construct () {
		DB::connection()->disableQueryLog();
	}

	// status = 0 if word insert success
	// status = 1 if get url download image success
	// status = 2 if download image success

	public function insertSubjectClone ($subject) {
		$insert = array(
			'id_subject' => $subject['id'],
			'id_course' => $subject['id_course'],
			'name' => $subject['name'],			
			'status' => 0
		);
		
		return SubjectClone::insert($insert);
	}

	public function updateUrlDownload ($id_subject, $listUrl) {		
		try {
			$ulr1 = $listUrl[0]->url;
			$ulr2 = $listUrl[1]->url;
			$ulr3 = $listUrl[2]->url;
			return SubjectClone::where('id_subject', $id_subject)->update(array('url1' => $ulr1, 'url2' => $ulr2, 'url3' => $ulr3, 'status' => 1));
		} catch (Exception $e) {
			return false;
		}		
	}

	public function changeStatus ($idSubject, $status) {
		return SubjectClone::where('id_subject', $idSubject)->update(array('status' => $status));
	}


}
