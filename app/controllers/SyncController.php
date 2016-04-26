<?php 

class SyncController extends BaseController {

	public $validate;
	public $note;
	public $cate;
	public $time;

	public function __construct () {
		$this->validate = new ValidateController();
		$this->note = new Note();
		$this->cate = new Category();
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

	public function actionUpdateNote () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);	    
	    @$listNote = $request->listNote;
	    if ($this->validate->validateSpecialChar($listNote)) {
	    	$result = $this->note->updateDataChange(json_decode($listNote));
	    	return Response::json($result);
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function actionPullCateServer () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    @$timeLocal = $request->timeLocal;
	    @$timeStamp = $request->timeStamp;
	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($timeLocal)) {
	    	$listCate = $this->cate->pullData($userId, $timeLocal, $timeStamp);
	    	return Response::json($listCate);
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function actionPushCateNewServer () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    @$listCate = $request->listCate;
	    @$timeStamp = $request->timeStamp;

	    // Update time Server
	    $this->updateTimeServer($timeStamp, $userId, 'cate');

	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($listCate)) {
	    	$result = $this->cate->pushDataNew($userId, $timeStamp, json_decode($listCate));
	    	return Response::json($result);
	    } else {
	    	return Response::json(array('status' => 400));
	    }	    
	}

	public function actionUpdateCate () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);	
	    @$userId  = $request->userId;
	    @$listCate = $request->listCate;
	    @$timeStamp = $request->timeStamp;

	    // Update time Server
	    $this->updateTimeServer($timeStamp);
	    
	    if ($this->validate->validateSpecialChar($listCate) && $this->validate->validateSpecialChar($userId)) {
	    	$result = $this->cate->updateDataChange(json_decode($listCate), $timeStamp);
	    	return Response::json($result);
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	protected function updateTimeServer ($timeServer, $userId, $type) {
		$time = Time::where('type', $type)->where('userId', $userId)->first();
		if (is_null($time)) {
			Time::insert(array('type' => $type, 'userId' => $userId, 'time' => $timeStamp));
		} else {
			Time::where('type', $type)->where('userId', $userId)->update(array('time' => $timeStamp));
		}
	}
}

?>