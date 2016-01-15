<?php 

class DecodePasswordController extends BaseController {

	public function actionDecode ($password) {

	}

	public function responseUsertoClient ($user) {
		return array(
			'email'   => $user->email,
			'tokenId' => $user->tokenId,
			'status'  => true
		);
	}

}

?>