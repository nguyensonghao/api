<?php 

class AcountController extends BaseController {

	public $user;
	public $decodePassword;
	public $email;
	public $util;
	public $activeUser;

	public function __construct () {
		$this->user  = new User();
		$this->email = new EmailController();
		$this->util  = new UtilController();
		$this->activeUser = new ActiveUser();
		$this->decodePassword = new DecodePasswordController();

	}

	public function actionLogin () {
		// get data form client with method post
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$email    = $request->email;
	    @$password = $request->password;

		$user = array(
			'email'    => $email,
			'password' => $this->decodePassword->encodePassword($password)
		);

		// check login user
		$login = $this->user->checkLogin($user);
		if ($login == null) {
			return Response::json(array('status' => 304));
		} else if ($login->active == 0) {
			return Response::json(array('status' => 302));
		} else {
			return  Response::json($this->decodePassword->responseUsertoClient($login));
		}
	}

	public function actionInit () {
		// get data form client with method post
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$tokenId    = $request->tokenId;
	    return $this->user->checkStatus($tokenId);
	}

	public function actionRegister () {
		// get data form client with method post
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$email    = $request->email;
	    @$password = $request->password;
	    $password  = $this->decodePassword->encodePassword($password);
	    $keyActive = $this->util->generateRandomString(20);

	    $result = $this->user->registerUser(array('email' => $email, 'password' => $password), $keyActive);
	    if ($result['status'] == null)
	    	$this->email->sendMail($keyActive, $email);

	    return $result;

	}	

	public function actionLogout () {
		// get data form client with method post
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$email    = $request->email;
	    return Response::json($this->user->logoutUser($email));
	}

	public function actionActiveUser ($keyActive) {
		$result = $this->activeUser->active($keyActive);
		if (!$result) {
			return Response::json(array('status' => 304));
		} else {
			$this->user->activeUser($result->email);
			return Response::json(array('status' => 200));			
		}
	}
}

?>