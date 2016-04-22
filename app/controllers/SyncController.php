<?php 

class SyncController extends BaseController {

	public $validate;
	public $note;
	public $time;

	public function __construct () {
		$this->validate = new ValidateController();
		$this->note = new Note();
		$this->time = new Time();
	}

	public function actionPullNoteServer () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    @$timeLocal = $request->timeLocal;
	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($timeLocal)) {
	    	$listNote = $this->note->pullData($userId, $timeLocal);
	    	return Response::json($listNote);
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function actionPushNoteNewServer () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    @$listNote = $request->listNote;
	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($listNote)) {
	    	$result = $this->note->pushDataNew($userId, json_decode($listNote));
	    	return Response::json($result);
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function actionGetTime () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    @$type    = $request->type;
		if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($type)) {
			$result = $this->time->getTime($userId, $type);
	    	return Response::json($result);
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}
}

?>