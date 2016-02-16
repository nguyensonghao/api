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

		if (is_null($result)) {
			return array('status' => 304);
		} else if ($result->active == 0) {
			return array('status' => 302);
		} else {
			$result->password = null;
			$this->loginUser($user['email']);
			return array('status' => 200, 'result' => $result);
		}
	}

	protected function loginUser ($email) {
		// status = 0 when user logout, status = 1 when user logined
		return User::where('email', $email)->update(array('status' => 1));
	}

	// get information user when user logined
	public function checkStatus ($tokenId) {
		$result = User::where('tokenId', $tokenId)->first();
		if (is_null($result)) 
			return array('status' => 302);
		else {
			return array('status' => 200, 'result' => $result);
		}
	}

	public function registerUser ($user, $keyActive) {
		$userNew = new User();
		$userNew->email    = $user['email'];
		$userNew->password = $user['password'];
		$userNew->username = $user['username'];
		$tokenId = $this->generateRandomString(30);
		$userNew->tokenId  = $this->encodePassword($tokenId);
		$userNew->active   = 0;
		$userNew->status   = 0;

		// check email exits
		$userExits = User::where('email', $user['email'])->first();
		if (is_null($userExits)) {
			if ($userNew->save()) {
				// create random key active 
				$activeUser = new ActiveUser();
				$activeUser->email  = $user['email'];
				$activeUser->key    = $keyActive;
				$activeUser->status = 0;
				$activeUser->save();

				$user = User::where('email', $user['email'])
			        ->where('password', $user['password'])
			        ->first();

			    return array('status' => 200);
			} else {
				return array('status' => 302);
			}
		} else {
			return array('status' => 304);
		}
		
	}

	public function logoutUser ($email) {
		// status = 0 when user logout, status = 1 when user logined
		$tokenId = $this->generateRandomString(30);
		$tokenId = $this->encodePassword($tokenId);
		if (User::where('email', $email)->update(array('status' => 0, 'tokenId' => $tokenId))) {			
			return array('status' => 200);
		} else {
			return array('status' => 304);
		}

	}

	public function changePasswordUser ($passwordOld, $passwordNew, $email) {
		return User::where('email', $email)
		->where('password', $this->encodePassword($passwordOld))
		->update(array('password' => $this->encodePassword($passwordNew)));
	}

	public function checkExistEmail ($email) {
		$result = User::where('email', $email)->get();
		if (count($result) > 0)
			return true;
		else 
			return false;
	}

	public function activeUser ($email) {
		User::where('email', $email)->update(array('active' => 1));
	}

	public function changeUsername($email, $username) {
		$user = User::where('email', $email)
		->where('status', 1)->where('active', 1)->first();
		if (!is_null($user)) {
			if (User::where('email', $email)->update(array('username' => $username))) {
				$user->username = $username;
				return array('status' => 200, 'result' => $user);
			} else {
				return array('status' => 302);	
			}
		} else {
			return array('status' => 304);
		}
	}

	protected function generateRandomString($length) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	protected function encodePassword ($password) {
		return md5($password);
	}

}
