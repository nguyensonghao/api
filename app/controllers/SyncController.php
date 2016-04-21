<?php 

class SyncController extends BaseController {

	public $validate;
	public $note;

	public function __construct () {
		$this->validate = new ValidateController();
		$this->note = new Note();
	}

	public function actionPullNoteServer () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    @$timeLocal = $request->timeLocal;	    
	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($timeLocal)) {
	    	$listNote = $this->note->pullData($userId, $timeLocal);
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}
}

?>