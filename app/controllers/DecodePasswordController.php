<?php 

class DecodePasswordController extends BaseController {

	public function decodeTokenRequest ($tokenId) {
		return $tokenId;
	}

	public function responseUsertoClient ($user) {
		return array(
			'email'   => $user->email,
			'tokenId' => $user->tokenId,
			'status'  => true
		);
	}

	public function encodePassword ($password) {
		return md5($password);
	}

}

?>