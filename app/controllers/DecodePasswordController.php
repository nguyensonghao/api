<?php 

class DecodePasswordController extends BaseController {

	public $listObjectEncryption;

	public function __construct () {
		$listEncryption = '[{"number":"46","text":"A"},{"number":"31","text":"B"},{"number":"93","text":"C"},{"number":"24","text":"D"},{"number":"37","text":"E"},{"number":"84","text":"F"},{"number":"41","text":"G"},{"number":"71","text":"H"},{"number":"16","text":"I"},{"number":"49","text":"J"},{"number":"52","text":"K"},{"number":"59","text":"L"},{"number":"45","text":"M"},{"number":"96","text":"N"},{"number":"51","text":"O"},{"number":"36","text":"P"},{"number":"95","text":"Q"},{"number":"78","text":"R"},{"number":"74","text":"S"},{"number":"19","text":"T"},{"number":"54","text":"U"},{"number":"42","text":"V"},{"number":"68","text":"W"},{"number":"91","text":"X"},{"number":"62","text":"Y"},{"number":"65","text":"Z"},{"number":"38","text":"a"},{"number":"83","text":"b"},{"number":"88","text":"c"},{"number":"21","text":"d"},{"number":"77","text":"e"},{"number":"44","text":"f"},{"number":"66","text":"g"},{"number":"32","text":"h"},{"number":"56","text":"i"},{"number":"92","text":"j"},{"number":"48","text":"k"},{"number":"34","text":"l"},{"number":"23","text":"m"},{"number":"57","text":"n"},{"number":"69","text":"o"},{"number":"27","text":"p"},{"number":"28","text":"q"},{"number":"17","text":"r"},{"number":"81","text":"s"},{"number":"14","text":"t"},{"number":"67","text":"u"},{"number":"79","text":"v"},{"number":"82","text":"w"},{"number":"26","text":"x"},{"number":"86","text":"y"},{"number":"63","text":"z"},{"number":"18","text":"0"},{"number":"89","text":"1"},{"number":"87","text":"2"},{"number":"22","text":"3"},{"number":"35","text":"4"},{"number":"58","text":"5"},{"number":"13","text":"6"},{"number":"99","text":"7"},{"number":"64","text":"8"},{"number":"55","text":"9"}]';
		$this->listObjectEncryption = json_decode($listEncryption);
	}

	public function decodeTokenRequest ($tokenId) {		
		$key = (int)substr($tokenId, -1);
		$reallyToken = substr($tokenId, $key, $key + 45);	
		$size = strlen($reallyToken);
		$decode = '';
		for ($i = 0; $i < $size; $i = $i + 2) {
			$number = substr($tokenId, $i, $i + 2);
			$c  = $this->convertStringformNumber($number);
			$decode .= $c;
		}

		$token  = substr($decode, 0, -13);
	    $time   = substr($decode, 32, 10);
		return array('token' => $token, 'time' => $time);
	}

	public function getTokenId ($tokenId, $key) {

	}

	public function convertStringformNumber ($number) {
		$size = count($this->listObjectEncryption);
		for ($i = 0; $i < $size; $i++) {
			if ($this->listObjectEncryption[$i]['number'] == $number) {
				return $number;
			}
		}

		return '';
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