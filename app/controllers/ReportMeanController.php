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
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    @$mean    = $request->mean;
	    @$wordId  = $request->wordId;
	    @$word    = $request->word;
	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($mean)) {
	    	return Response::json($this->reportMean->addReportMean($userId, $mean, $wordId, $word));
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
	    @$reportId = $request->reportId;
	    @$userId   = $request->userId;
	    @$type     = $request->type;
	    if ($this->validate->validateSpecialChar($reportId) && $this->validate->validateSpecialChar($userId)) {
	    	return Response::json($this->rateReport->rateMean($reportId, $userId, $type));
	    } else {
	    	return Response::json(array('status' => 400));
	    }	
	}

	public function actionCheckMean () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$wordId  = $request->wordId;
	    @$userId  = $request->userId;
	    if ($this->validate->validateSpecialChar($wordId) && $this->validate->validateSpecialChar($userId)) {
	    	return Response::json($this->reportMean->checkMean($wordId, $userId));
	    } else {
	    	return Response::json(array('status' => 400));
	    }	
	}

	public function actionUpdateMean () {
		$postdata  = file_get_contents("php://input");
	    $request   = json_decode($postdata);
	    @$userId   = $request->userId;
	    @$mean     = $request->mean;
	    @$wordId   = $request->wordId;
	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($mean)) {
	    	return Response::json($this->reportMean->updateReportMean($userId, $mean, $wordId));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function actionGetRateReport () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    if ($this->validate->validateSpecialChar($userId)) {
	    	return Response::json($this->rateReport->getListRateMean($userId));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function actionDeleteMean () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$userId  = $request->userId;
	    @$reportId  = $request->reportId;
	    if ($this->validate->validateSpecialChar($userId) && $this->validate->validateSpecialChar($reportId)) {
	    	return Response::json($this->reportMean->deleteReportMean($userId, $reportId));
	    } else {
	    	return Response::json(array('status' => 400));
	    }
	}

	public function actionGetNew () {
		$postdata = file_get_contents("php://input");
	    $request  = json_decode($postdata);
	    @$skip    = $request->skip;
	    @$take    = $request->take;
	    $listReport = Cache::get('new-report');
	    $result = [];

	    // Check cache empty
	    if (is_null($listReport)) {
	    	Cache::put('new-report', function () {
	    		$this->reportMean->getNewCache();
	    	}, 5);
	    	return Response::json($this->reportMean->getNew($skip, $take));
	    } else {
	    	if ($skip + $take >= 100)
	    		return Response::json($this->reportMean->getNew($skip, $take));

	    	$count = $listReport['count'];
	    	for ($i = $skip; $i < $skip + $take; $i++)
	    		array_push($result, $listReport['result'][$i]);

	    	return array('status' => 200, 'result' => $result, 'count' => $count);
	    }
	}

}

?>