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
			ResetPassword::where('key', $keyReset)->update(array('status' => 1));
			return $result;
		} else {
			return false;
		}
	}

	public function actionResetPassword ($email, $password) {
		$result = ResetPassword::where('email', $email)->where('status', 1)->first();
		if (count($result) > 0) {
			if (ResetPassword::where('email', $email)->where('status', 1)->update(array('status' => 2)) ||
			User::where('email', $email)->update(array('password' => md5($password)))) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


}
