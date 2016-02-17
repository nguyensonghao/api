<?php 

class MyNoteController extends BaseController {

	public $validate;
	public $category;
	public $note;

	public function __construct () {
		$this->validate = new ValidateController();
		$this->category = new Category();
		$this->note = new Note();
	}

	public function getMyNote () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$userId   = $request->userId;
	    $myNote    = $this->category->getMyNote($userId);
	    return Response::json($myNote);
	}

	public function addCategory () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$userId   = $request->userId;
	    @$categoryName = $request->categoryName;

	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($categoryName)) {
			return Response::json($this->category->addCategory($userId, $categoryName));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function addNote () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$noteName = $request->noteName;
	    @$noteMean = $request->noteMean;
	    @$categoryId = $request->categoryId;
	    if ($this->validate->validateSpecialChar($noteName) && $this->validate->validateSpecialChar($noteMean)
	    	&& $this->validate->validateSpecialChar($categoryId)) {
			return Response::json($this->note->addNote($noteName, $noteMean, $categoryId));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function updateCategory () {

	}

	public function updateNote () {

	}

	public function deleteCategory () {

	}

	public function deleteNote () {

	}

}

?>