<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class WordClone extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'words_clone';

	public function __construct () {
		DB::connection()->disableQueryLog();
	}

	// status = 0 if word insert success
	// status = 1 if get url download image success
	// status = 2 if download image success

	public function insertWordClone ($word) {
		$insert = array(
			'id_word' => $word['id_word'],
			'id_subject' => $word['id_subject'],
			'id_course' => $word['id_course'],
			'word' => $word['word'],
			'mean' => $word['mean'],
			'status' => 0
		);
		
		return WordClone::insert($insert);
	}

	public function updateUrlDownload ($id_word, $listUrl) {		
		try {
			$ulr1 = $listUrl[0]->url;
			$ulr2 = $listUrl[1]->url;
			$ulr3 = $listUrl[2]->url;
			return WordClone::where('id_word', $id_word)->update(array('url1' => $ulr1, 'url2' => $ulr2, 'url3' => $ulr3, 'status' => 1));
		} catch (Exception $e) {
			return false;
		}		
	}

	public function changeStatus ($idWord, $status) {
		return WordClone::where('id_word', $idWord)->update(array('status' => $status));
	}


}
