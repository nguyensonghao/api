<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class ReportMean extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'report_mean';

	public function addReportMean ($userId, $mean, $wordId) {
		$user = User::where('userId', $userId)->where('active', 1)->where('status', 1)->first();
		if (is_null($user)) {
			return array('status' => 304);
		} else {
			$report = ReportMean::where('wordId', $wordId)->where('userId', $user->userId)->first();
			if (!is_null($report)) {
				return array('status' => 302);
			} else {
				$reportMean = new ReportMean();
				$reportMean->userId = $user->userId;
				$reportMean->mean   = $mean;
				$reportMean->status = 1;
				$reportMean->wordId = $wordId;
				$reportMean->like   = 0;
				$reportMean->dislike = 0;
				if ($reportMean->save()) {
					return array('status' => 200, 'result' => $reportMean);
				} else {
					return array('status' => 302);
				}
			}			
		}
	}

	public function getMean ($wordId) {
		$listReport = DB::table('report_mean')->where('wordId', $wordId)
		->where('report_mean.status', 1)->where('report_mean.dislike', '<', 10)
		->where('report_mean.status', '<>', -1)
		->orderBy('report_mean.like', 'desc')
		->join('users', 'users.userId', '=', 'report_mean.userId')->get();

		if (count($listReport) == 0) {
			return array('status' => 304);
		} else {
			return array('status' => 200, 'result' => $listReport);
		}
	}

	public function checkMean ($wordId, $userId) {
		$report = ReportMean::where('wordId', $wordId)->where('userId', $userId)
		->where('status', 1)->first();
		if (is_null($report)) {
			return array('status' => 304);
		} else {
			return array('status' => 200, 'result' => $report);
		}
	}

	public function updateReportMean($userId, $mean, $wordId) {
		$report = ReportMean::where('userId', $userId)->where('wordId', $wordId)
		->where('status', 1)->first();
		if (is_null($report)) {
			return array('status' => 304);
		} else {
			if (ReportMean::where('userId', $userId)->where('wordId', $wordId)
			->update(array('mean' => $mean, 'status' => 1))) {
				$report->mean = $mean;
				return array('status' => 200, 'result' => $report);
			} else {
				return array('status' => 302);
			}
		}
	}

	public function deleteReportMean($userId, $reportId) {
		if (ReportMean::where('userId', $userId)->where('reportId', $reportId)
		->delete()) {
			RateReport::where('reportId', $reportId)->delete();
			return array('status' => 200);
		} else {
			return array('status' => 304);
		}
	}
}
