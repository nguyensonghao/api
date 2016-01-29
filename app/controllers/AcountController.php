<?php 

class AcountController extends BaseController {

	public $user;
	public $decodePassword;
	public $email;
	public $util;
	public $activeUser;
	public $resetPassword;
	public $dateExcute;
	public $validate;

	public function __construct () {
		$this->user  = new User();
		$this->email = new EmailController();
		$this->util  = new UtilController();
		$this->activeUser = new ActiveUser();
		$this->dateExcute = new DateController();
		$this->validate   = new ValidateController();
		$this->resetPassword  = new ResetPassword();
		$this->decodePassword = new DecodePasswordController();

	}

	public function actionLogin () {
		// get data form client with method post
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$email    = $request->email;
	    @$password = $request->password;

	    if ($this->validate->validateEmail($email) && $this->validate->validateSpecialChar($password)) {
	    	$user = array(
				'email'    => $email,
				'password' => $this->decodePassword->encodePassword($password)
			);

			return Response::json($this->user->checkLogin($user));
	    } else {
	    	return Response::json(array('status' => 400));
	    }

	}

	public function actionInit () {
		// get data form client with method post
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$token    = $request->tokenId;
	    $tokenId   = substr($token, 0, -13);
	    $time      = substr($token, 32, 10);
	    // Validate timeout
	    if (!$this->dateExcute->checkInvalidDate($time)) {
	    	return Response::json(array('status' => 304));
	    } else {
	    	return Response::json($result = $this->user->checkStatus($tokenId));
	    }
	    
	}

	public function actionRegister () {
		// get data form client with method post
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$email    = $request->email;
	    @$password = $request->password;
	    $username  = substr( $email, 0, strpos($email, '@'));

	    $password  = $this->decodePassword->encodePassword($password);
	    $keyActive = $this->util->generateRandomString(20);

	    if ($this->validate->validateEmail($email) && $this->validate->validateSpecialChar($password)) {
	    	$user = array(
	    		'email' => $email, 
	    		'password' => $password, 
	    		'username' => $username
	    	);

	    	$result = $this->user->registerUser($user, $keyActive);

		    if ($result['status'] != 304 && $result['status'] != 302)
		    	$this->email->sendMailActive($keyActive, $email);

		    return $result;	
	    } else {
	    	return Response::json(array('status' => 400));
	    }

	}	

	public function actionLogout () {
		// get data form client with method post
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$email    = $request->email;
	    if ($this->validate->validateEmail($email))
	    	return Response::json($this->user->logoutUser($email));
	    else 
	    	return Response::json(array('status' => 400));
	}

	public function actionActiveUser ($key) {
		if (!$this->validate->validateSpecialChar($key)) {
			return Response::json(array('status' => 400));
		} else {
			$result = $this->activeUser->active($key);
			if (!$result) {
				echo 'Tài khoản đã được kích hoạt hoặc tài khoản không tồn tại';
			} else {
				$this->user->activeUser($result->email);
				$this->email->sendMailActiveSuccess($result->email);
				$url = 'http://mazii.net';
				return Redirect::to($url);
			}
		}		
	}

	public function actionChangePassword () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$passwordOld = $request->passwordOld;
	    @$passwordNew = $request->passwordNew;
	    @$email       = $request->email;

	    if ($this->validate->validateEmail($email) && $this->validate->validateSpecialChar($passwordNew)
	     && $this->validate->validateSpecialChar($passwordOld)) {
	    	if ($this->user->changePasswordUser($passwordOld, $passwordNew, $email))
		    	return Response::json(array('status' => 200));
		    else 
		    	return Response::json(array('status' => 304));	
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	    
	}

	public function actionChangeUsername () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$username = $request->username;
	    @$email    = $request->email;

	    if ($this->validate->validateEmail($email) && $this->validate->validateSpecialChar($username)) {
	    	return Response::json($this->user->changeUsername($email, $username));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function actionResetPassword () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$email = $request->email;

	    if ($this->validate->validateEmail($email)) {
	    	if ($this->user->checkExistEmail($email)) {
		    	$keyReset = $this->util->generateRandomString(20);
		    	$result = $this->resetPassword->createKeyReset($email, $keyReset);
		    	if ($result == 1) {
		    		$this->email->sendMailResetPassword($keyReset, $email);
		    		return Response::json(array('status' => 200));
		    	} else if ($result == -1) {
		    		return Response::json(array('status' => 302));
		    	} else {
					return Response::json(array('status' => 306));
		    	}
		    } else {
		    	return Response::json(array('status' => 304));
		    }	
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	    
	}

	public function actionResetPasswordSysterm ($keyReset) {
		if ($this->validate->validateSpecialChar($keyReset)) {
			return Response::json(array('status' => 400));
		} else {
			$result = $this->resetPassword->activeKeyReset($keyReset);
			if (!$result) {
				return Response::json(array('status' => 304));
			} else {
				$url = 'http://mazii.net';
				return Redirect::to($url);
			}	
		}
	}

	public function actionResetPasswordReally () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$email    = $request->email;
	    @$password = $request->password;

	    if ($this->validate->validateEmail($email) && $this->validate->validateSpecialChar($password)) {
	    	$result = $this->resetPassword->actionResetPassword($email, $password);
		    if ($result) {
		    	$this->email->sendEmailResetPasswordSuccess($email);
		    	return Response::json(array('status' => 200));
		    } else {
		    	return Response::json(array('status' => 304));
		    }	
	    } else {
	    	return Response::json(array('status' => 304));
	    }
	    
	}
}

?>