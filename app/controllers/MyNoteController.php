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
	    $myCategory = $this->category->getCategory($userId);
	    if ($myCategory != null) {
	    	$this->note->getNote($myCategory);
	    } else {
	    	$myNote = [];
	    }
	    return Response::json(array('category' => $myCategory, 'note' => $myNote));
	}

	public function addCategory () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$userId   = $request->userId;
	    @$date     = $request->date;
	    @$categoryName = $request->categoryName;

	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($categoryName)) {
			return Response::json($this->category->addCategory($userId, $categoryName, $date));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function addNote () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$noteName = $request->noteName;
	    @$noteMean = $request->noteMean;
	    @$date     = $request->date;
	    @$type     = $request->type;
	    @$categoryId = $request->categoryId;
	    if ($this->validate->validateSpecialChar($noteName) && $this->validate->validateSpecialChar($noteMean)
	    	&& $this->validate->validateSpecialChar($categoryId)) {
			return Response::json($this->note->addNote($noteName, $noteMean, $categoryId, $date, $type));
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