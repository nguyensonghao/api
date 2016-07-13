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

	public function updateTimeServer ($timeStamp, $userId, $type) {
		$time = Time::where('type', $type)->where('userId', $userId)->first();
		if (is_null($time)) {
			Time::insert(array('type' => $type, 'userId' => $userId, 'time' => $timeStamp));
		} else {
			Time::where('type', $type)->where('userId', $userId)->update(array('time' => $timeStamp));
		}
	}

	public function actionPullNoteServer () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    @$timeLocal = $request->timeLocal;
	    @$timeStamp = $request->timeStamp;	    

	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($timeLocal)) {
	    	$listNote = $this->note->pullData($userId, $timeLocal, $timeStamp);
	    	// Update time Server
	    	$this->updateTimeServer($timeStamp, $userId, 'note');
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
	    @$timeStamp = $request->timeStamp;

	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($listNote)) {
	    	$result = $this->note->pushDataNew($userId, $timeStamp, json_decode($listNote));
	    	return Response::json($result);
	    } else {
	    	return Response::json(array('status' => 400));
	    }	    
	}

	public function actionUpdateNote () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);	
	    @$userId  = $request->userId;
	    @$listNote = $request->listNote;
	    @$timeStamp = $request->timeStamp;	    
	    
	    if ($this->validate->validateSpecialChar($listNote) && $this->validate->validateSpecialChar($userId)) {
	    	$result = $this->note->updateDataChange(json_decode($listNote), $timeStamp);
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
	    @$timeStamp = $request->timeStamp;
		if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($type)) {
			$result = $this->time->getTime($userId, $type, $timeStamp);
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
	    	// Update time Server
	    	$this->updateTimeServer($timeStamp, $userId, 'cate');
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
	    
	    if ($this->validate->validateSpecialChar($listCate) && $this->validate->validateSpecialChar($userId)) {
	    	$result = $this->cate->updateDataChange(json_decode($listCate), $timeStamp);
	    	return Response::json($result);
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function pullDataServer () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);	
	    @$userId  = $request->userId;
	    @$lastedUpdate = $request->lastedUpdate;
	    @$skipNote = $request->skipNote;
	    @$skipCate = $request->skipCate;

	    if (!isset($skipNote) || is_null($skipNote))
	    	$skipNote = 0;

	    if (!isset($skipCate) || is_null($skipCate))
	    	$skipCate = 0;

	    if (!isset($lastedUpdate))
	    	$lastedUpdate = null;

	    if ($this->validate->validateSpecialChar($userId)) {
	    	return Response::json(array(
	    		'status' => 200,
	    		'listNote' => $this->getListNote($userId, $skipNote, $lastedUpdate),
	    		'listCate' => $this->getListCate($userId, $skipCate, $lastedUpdate)
	    	));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	protected function getListNote ($userId, $skip, $lastedUpdate = null) {
		return $this->note->getListNoteServer($userId, $skip, $lastedUpdate);
	}

	protected function getListCate ($userId, $skip, $lastedUpdate = null) {
		return $this->cate->getListCateServer($userId, $skip, $lastedUpdate);
	}
}

?>