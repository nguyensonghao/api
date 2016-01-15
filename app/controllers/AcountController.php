<?php 

class AcountController extends BaseController {

	public $user;
	public $decodePassword;

	public function __construct () {
		$this->user = new User();
		$this->decodePassword = new DecodePasswordController();
	}

	public function actionLogin () {
		$user = array(
			'email'    => $_POST['email'],
			'password' => $_POST['password']
		);

		// check login user
		$login = $this->user->checkLogin($user);
		if ($login == null) {
			return Response::json(array('status' => false));
		} else {
			return  Response::json($this->decodePassword->responseUsertoClient($login));
		}
	}

	public function checkLoginStatus ($tokenId) {
		
	}
}

?>