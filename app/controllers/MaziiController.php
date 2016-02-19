<?php 

class MaziiController extends BaseController {
	public $mazii;
	public $validate;

	public function __construct () {
		$this->mazii = new Mazii();
		$this->validate = new ValidateController();
	}

	public function actionCheckTrialUser () {
		// get data form client with method post
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    if ($this->validate->validateSpecialChar($userId)) {
	    	return Response::json($this->mazii->checkUserTrial($userId));
	    } else return Response::json(array('status' => 400));
	}
}

?>