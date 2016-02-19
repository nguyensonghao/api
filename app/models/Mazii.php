<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Mazii extends Eloquent {

	use UserTrait, RemindableTrait;

	protected $table = 'mazii';

	public function checkUserTrial ($userId) {
		if ($this->checkExitsUser($userId))
			return array('status' => 304);

		$user = Mazii::where('userId', $userId)->where('status', 1)->first();
		if (!is_null($user)) {
			return array('status' => 200);
		} else {
			return array('status' => 304);
		}
	}

	protected function checkExitsUser ($userId) {
		$user = User::where('userId', $userId)
		->where('active', 1)->first();
		if (!is_null($user)) {
			return true;
		} else return false;
	}

}