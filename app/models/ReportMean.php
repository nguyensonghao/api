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

	public function addReportMean ($email, $mean, $wordId) {
		$user = User::where('email', $email)->where('active', 1)->where('status', 1)->first();
		if (is_null($user)) {
			return array('status' => 304);
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

	public function getMean ($wordId) {
		$listReport = DB::table('report_mean')->where('wordId', $wordId)->where('status', 1)
		->join('users', 'users.userId', '=', 'report_mean.userId')->get();

		if (count($listReport) == 0) {
			return array('status' => 304);
		} else {
			return array('status' => 200, 'result' => $listReport);
		}
	}

	public function checkMean ($wordId, $email) {
		$userId = User::where('email', $email)->first()->userId;
		$report = ReportMean::where('wordId', $wordId)->where('userId', $userId)
		->where('status', 1)->first();
		if (is_null($report)) {
			return array('status' => 304);
		} else {
			return array('status' => 200, 'result' => $report);
		}
	}

	public function updateReportMean($email, $mean, $wordId) {
		$userId = User::where('email', $email)->first()->userId;
		$report = ReportMean::where('userId', $userId)->where('wordId', $wordId)
		->where('status', 1)->first();
		if (is_null($report)) {
			return array('status' => 304);
		} else {
			if (ReportMean::where('userId', $userId)->where('wordId', $wordId)
			->update(array('mean' => $mean))) {
				$report->mean = $mean;
				return array('status' => 200, 'result' => $report);
			} else {
				return array('status' => 302);
			}
		}
	}


}
