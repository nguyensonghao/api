<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class ResetPassword extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'key_reset';

	public function createKeyReset ($email, $keyReset) {
		$reset = new ResetPassword();
		$reset->email  = $email;
		$reset->key    = $keyReset;
		$reset->status = 0;
		return $reset->save();
	}

	public function activeKeyReset ($keyReset) {
		$result = ResetPassword::where('key', $keyReset)->first();
		if (count($result) > 0 && $result->status == 0) {
			ActiveUser::where('key', $keyActive)->update(array('status' => 1));
			return $result;
		} else {
			return false;
		}
	}


}
