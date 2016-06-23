<?php 

class CrontabController extends BaseController {

	public $reportMean;

	public function __construct () {
		$this->reportMean = new ReportMean();
	}

	public function artisanQueue () {
		$shell_command = "php artisan queue:listen > /dev/null &";
		shell_exec($shell_command);
	}

	public function putNewReportCache () {
		Cache::forget('new-report');
		$reportCache = $this->reportMean->getNewCache();
    	Cache::put('new-report', $reportCache, 5);
	}

}