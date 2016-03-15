<?php 

class FlashcardController extends BaseController {
	public $validate;
	public $flashcard;

	public function __construct () {
		$this->validate = new ValidateController();
		$this->flashcard = new Flashcard();
	}

	public function getFlashCard () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$userId   = $request->userId;

	    if ($this->validate->validateSpecialChar($userId)) {
	    	return Response::json($this->flashcard->getFlashCard($userId));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function rememberFlashCard () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    @$wordId  = $request->wordId;
	    @$type    = $request->type;

	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($wordId)
	    	&& $this->validate->validateSpecialChar($type)) {
	    	return Response::json($this->flashcard->remember($userId, $wordId, $type));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function forgetFlashCard () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    @$wordId  = $request->wordId;
	    @$type    = $request->type;

	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($wordId)
	    	&& $this->validate->validateSpecialChar($type)) {
	    	return Response::json($this->flashcard->forget($userId, $wordId, $type));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

}

?>