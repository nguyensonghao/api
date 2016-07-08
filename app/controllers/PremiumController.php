<?php 

class PremiumController extends BaseController {

	public $validate;
	public $premium;

	public function __construct () {
		$this->validate = new ValidateController();
		$this->premium = new Premium();
	}

	public function addPremiumUser () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$userId    = $request->userId;
	    @$deviceId = $request->deviceId;
	    @$transaction    = $request->transaction;
	    @$provider = $request->provider;

	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($deviceId)
	    	&& $this->validate->validateSpecialChar($transaction) && $this->validate->validateSpecialChar($provider)) {
	    	return Response::json($this->premium->insertPremium($userId, $deviceId, $transaction, $provider));
	    } else {
	    	return Response::json(array ('status' => 400));
	    }
	}

	public function checkPremiumDevice () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$userId    = $request->userId;
	    @$deviceId = $request->deviceId;

	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($deviceId)) {
	    	return Response::json($this->premium->checkPremiumDevice($userId, $deviceId));
	    } else {
	    	return Response::json(array ('status' => 400));	
	    }
	}

}


?>