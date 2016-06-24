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

	public function __construct () {
		DB::connection()->disableQueryLog();
	}

	public function createKeyReset ($email, $keyReset) {
		$result = ResetPassword::where('email', $email)->where('status', 0)->count();
		if ($result > 0) {
			return 0;
		} else {
			$reset = new ResetPassword();
			$reset->email  = $email;
			$reset->key    = $keyReset;
			$reset->status = 0;
			if ($reset->save())
				return 1;
			else
				return -1;
		}
	}

	// Chuyển trạng thái của key về 1 là đã click vào link email
	public function activeKeyReset ($keyReset) {
		$result = ResetPassword::where('key', $keyReset)->first();
		if (!is_null($result) && ($result->status == 0 || $result->status == 1)) {
			ResetPassword::where('key', $keyReset)->update(array('status' => 1));
			return $result;
		} else {
			return false;
		}
	}

	// Chỉ cho những tài khoản cái status = 1 có thể cấp lại mật khẩu
	public function actionResetPassword ($email, $password) {
		$result = ResetPassword::where('email', $email)->where('status', 1)->first();
		if (!is_null($result)) {
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
