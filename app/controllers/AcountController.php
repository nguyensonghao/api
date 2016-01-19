<?php 

class AcountController extends BaseController {

	public $user;
	public $decodePassword;

	public function __construct () {
		$this->user = new User();
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
			return Response::json(array('status' => false));
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

	    return $this->user->registerUser(array('email' => $email, 'password' => $password));

	}	

	public function actionLogout () {
		// get data form client with method post
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$email    = $request->email;
	    return Response::json($this->user->logoutUser($email));
	}
}

?>