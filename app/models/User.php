<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function checkLogin ($user) {
		$result = User::where('email', $user['email'])
		        ->where('password', $user['password'])
		        ->first();

		// Change status user = 0 when user logined
		if (is_array($result))
			$this->loginUser($user['email']);

		return $result;
	}

	protected function loginUser ($email) {
		// status = -1 when user logout, status = 0 when user logined
		return User::where('email', $email)->update(array('status' => 0));
	}

	// get information user when user logined
	public function checkStatus ($tokenId) {
		$result = User::where('tokenId', $tokenId)->first();
		if ($result == null) 
			return array('status' => 304);
		else {
			return $result;
		}
	}

	public function registerUser ($user, $keyActive) {
		$userNew = new User();
		$userNew->email    = $user['email'];
		$userNew->password = $user['password'];
		$userNew->tokenId  = $this->encodePassword($user['email']);
		$userNew->active   = 0;
		$userNew->status   = 0;
		Log::info($keyActive);

		// check email exits
		$userExits = User::where('email', $user['email'])->first();
		if ($userExits == null) {
			if ($userNew->save()) {
				// create random key active 
				$activeUser = new ActiveUser();
				$activeUser->email  = $user['email'];
				$activeUser->key    = $keyActive;
				$activeUser->status = 0;
				$activeUser->save();

				return User::where('email', $user['email'])
			        ->where('password', $user['password'])
			        ->first();
			} else {
				return array('status' => 302);
			}
		} else {
			return array('status' => 304);
		}
		
	}

	public function logoutUser ($email) {
		// status = -1 when user logout, status = 0 when user logined
		if (User::where('email', $email)->update(array('status' => -1)))
			return array('status' => 200);
		else 
			return array('status' => 304);

	}

	protected function encodePassword ($password) {
		return md5($password);
	}

	public function activeUser ($email) {
		User::where('email', $email)->update(array('active' => 1));
	}

}
