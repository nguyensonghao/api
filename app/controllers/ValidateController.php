<?php 

class ValidateController {

	public $listSpecialChar;

	public function __construct () {
		$this->listSpecialChar = ["'", "#"];
	}

	public function validateEmail ($email) {
		if ($email == null || $email == '')
			return false;

		if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false)
			return true;
		else
			return false;
	}

	public function validateSpecialChar ($value) {
		if ($value == null || $value == '')
			return false;
		
		$length = strlen($value);
		for ($i = 0; $length; $i++) {
			$char = $value[$i];
			if (in_array($char, $this->listSpecialChar))
				return false;
		}

		return true;
	}

}

?>