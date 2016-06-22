<?php 

class DecodePasswordController extends BaseController {

	public $listObjectEncryption;

	public function __construct () {
		$listObjectEncryption = array('46' => 'A', '31' => 'B', '93' => 'C', '24' => 'D', '37' => 'E', '84' => 'F', '41' => 'G', '71' => 'H', '16' => 'I', '49' => 'J', '52' => 'K', '59' => 'L', '45' => 'M', '96' => 'N', '51' => 'O', '36' => 'P', '95' => 'Q', '78' => 'R', '74' => 'S', '19' => 'T', '54' => 'U', '42' => 'V', '68' => 'W', '91' => 'X', '62' => 'Y', '65' => 'Z', '38' => 'a', '83' => 'b', '88' => 'c', '21' => 'd', '77' => 'e', '44' => 'f', '66' => 'g', '32' => 'h', '56' => 'i', '92' => 'j', '48' => 'k', '34' => 'l', '23' => 'm', '57' => 'n', '69' => 'o', '27' => 'p', '28' => 'q', '17' => 'r', '81' => 's', '14' => 't', '67' => 'u', '79' => 'v', '82' => 'w', '26' => 'x', '86' => 'y', '63' => 'z', '18' => '0', '89' => '1', '87' => '2', '22' => '3', '35' => '4', '58' => '5', '13' => '6', '99' => '7', '64' => '8', '55' => '9');
	}

	public function decodeTokenRequest ($tokenId) {		
		$decode = '';
		$key    = (int)substr($tokenId, -1);
		$tokenReally = substr($tokenId, $key * 2, 90);
		$size   = strlen($tokenReally);

		// Decode tokenID
		for ($i = 0; $i < $size; $i = $i + 2) {
			$number = substr($tokenReally, $i, 2);
			$c  = $this->convertStringformNumber($number);
			$decode .= $c;
		}

		$time   = (int)substr($decode, 32, 11);
	    $token  = substr($decode, 0, 32);

		return array('token' => $token, 'time' => $time);
	}

	public function convertStringformNumber ($number) {
		try {
			return $listObjectEncryption[$number];
		} catch (Exception $e) {
			return '';
		}
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