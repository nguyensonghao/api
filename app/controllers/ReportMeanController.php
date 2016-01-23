<?php 

class ReportMeanController extends BaseController {

	public $reportMean;
	public $validate;

	public function __construct () {
		$this->reportMean = new ReportMean();
		$this->validate   = new ValidateController();
	}

	public function actionAddReportMean () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$email    = $request->email;
	    @$mean     = $request->mean;
	    @$wordId   = $request->wordId;
	    if ($this->validate->validateSpecialChar($email) && $this->validate->validateEmail($email)
	    	&& $this->validate->validateSpecialChar($mean)) {
	    	return Response::json($this->reportMean->addReportMean($email, $mean, $wordId));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

}

?>