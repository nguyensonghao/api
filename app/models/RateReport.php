<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class RateReport extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'rate_report';

	public function __construct () {
		DB::connection()->disableQueryLog();
	}

	public function rateMean ($reportId, $userId, $type) {
		if ($type == 'like')
			$rateType = 1;
		else
			$rateType = 0;

		$user   = User::where('userId', $userId)->where('status', 1)->where('active', 1)->first();
		$report = ReportMean::where('reportId', $reportId)->first();
		if (is_null($user) || is_null($report)) {
			return array('status' => 304);
		} else {
			$rate = RateReport::where('reportId', $reportId)->where('userId', $userId)->first();

			// User not rate for report
			if (is_null($rate)) {
				$rateReport = new RateReport();
				$rateReport->reportId = $reportId;
				$rateReport->userId   = $userId;
				$rateReport->type     = $rateType;
				if ($rateReport->save()) {
					// Plus number rate of reportMean
					if ($type == 'like') {
						++ $report->like ;
						ReportMean::where('reportId', $reportId)
					    ->update(array('like' => $report->like));
					} else {
						++ $report->dislike ;
						ReportMean::where('reportId', $reportId)
					    ->update(array('dislike' => $report->dislike));
					}
					
					return array('status' => 200, 'result' => $report);
				} else {
					return array('status' => 306);	
				}

			// User rated for report
			} else {
				if ($rate->type == $rateType) {
					return array('status' => 302);	
				} else {
					if ($type == 'like') {
						++ $report->like;
						-- $report->dislike;

						if ($report->dislike < 0)
							$report->dislike = 0;

						RateReport::where('reportId', $reportId)->update(array('type' => 1));
					} else {
						-- $report->like;
						++ $report->dislike;

						if ($report->like < 0)
							$report->like = 0;

						RateReport::where('reportId', $reportId)
						->update(array('type' => 0));
					}

					if (ReportMean::where('reportId', $reportId)
					->update(array('like' => $report->like, 'dislike' => $report->dislike))) {
						return array('status' => 200, 'result' => $report);
					} else {
						return array('status' => 306);	 
					}

				}
				
			}
		}
	}

	public function getListRateMean ($userId) {
		if (is_null($userId)) {
			return array('status' => 304);
		} else {
			$result = RateReport::select('reportId', 'type')->where('userId', $userId)->get();
			return array('status' => 200, 'result' => $result);
		}
	}


}
