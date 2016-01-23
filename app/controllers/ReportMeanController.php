<?php 

class ReportMeanController extends BaseController {

	public $reportMean;
	public $validate;
	public $rateReport;

	public function __construct () {
		$this->reportMean = new ReportMean();
		$this->validate   = new ValidateController();
		$this->rateReport = new RateReport();
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

	public function actionGetMean () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$wordId   = $request->wordId;
	    if ($this->validate->validateSpecialChar($wordId)) {
	    	return Response::json($this->reportMean->getMean($wordId));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function actionRateMean () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$wordId   = $request->wordId;
	    @$email    = $request->email;
	    if ($this->validate->validateSpecialChar($wordId) && $this->validate->validateEmail($email)
	    	&& $this->validate->validateSpecialChar($email)) {
	    	return Response::json($this->rateReport->rateMean($wordId, $email));
	    } else {
	    	return Response::json(array('status' => 400));
	    }	
	}

	public function actionCheckMean () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$wordId   = $request->wordId;
	    @$email    = $request->email;
	    if ($this->validate->validateSpecialChar($wordId) && $this->validate->validateEmail($email)
	    	&& $this->validate->validateSpecialChar($email)) {
	    	return Response::json($this->reportMean->checkMean($wordId, $email));
	    } else {
	    	return Response::json(array('status' => 400));
	    }	
	}


}

?>