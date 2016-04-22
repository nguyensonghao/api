<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Time extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'time';


	public function getTime ($userId, $type) {
		$time = Time::where('userId', $userId)->where('type', $type)->first();
		if (is_null($time)) {
			return array('status' => 304);
		} else {
			return array('status' => 200, 'result' => $time);
		}
	}
}
