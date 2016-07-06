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

	public function __construct () {
		DB::connection()->disableQueryLog();
	}

	public function addReportMean ($userId, $mean, $wordId, $wordName) {
		$user = User::where('userId', $userId)->where('active', 1)->where('status', 1)->first();
		if (is_null($user)) {
			return array('status' => 304);
		} else {
			$report = ReportMean::where('wordId', $wordId)->where('userId', $user->userId)->first();
			if (!is_null($report)) {
				return array('status' => 304);
			} else {
				$reportMean = new ReportMean();
				$reportMean->userId = $user->userId;
				$reportMean->mean   = $mean;
				$reportMean->status = 1;
				$reportMean->wordId = $wordId;
				$reportMean->word   = $wordName;
				$reportMean->like   = 0;
				$reportMean->type   = 0;
				$reportMean->dislike = 0;
				if ($reportMean->save()) {
					return array('status' => 200, 'result' => $reportMean);
				} else {
					return array('status' => 302);
				}
			}			
		}
	}

	public function addReportMeanMobile ($userId, $mean, $wordId, $wordName) {
		$user = User::where('userId', $userId)->where('active', 1)->where('status', 1)->first();
		if (is_null($user)) {
			return array('status' => 304);
		} else {
			$report = ReportMean::where('wordId', $wordId)->where('userId', $user->userId)->first();
			if (!is_null($report)) {
				return array('status' => 304);
			} else {
				$reportMean = new ReportMean();
				$reportMean->userId = $user->userId;
				$reportMean->mean   = $mean;
				$reportMean->status = 1;
				$reportMean->wordId = $wordId;
				$reportMean->word   = $wordName;
				$reportMean->like   = 0;
				$reportMean->type   = 1;
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
		$listReport = DB::table('report_mean')
		->select('report_mean.dislike', 'report_mean.like', 'report_mean.mean', 'report_mean.reportId', 'report_mean.status', 'report_mean.type', 'users.userId', 'users.username', 'report_mean.word', 'report_mean.wordId')
		->where('wordId', $wordId)
		->where('report_mean.type', 0)
		->where('report_mean.dislike', '<', 10)
		->where('report_mean.status', '<>', -1)
		->orderBy('report_mean.like', 'desc')
		->join('users', 'users.userId', '=', 'report_mean.userId')->get();

		if (count($listReport) == 0) {
			return array('status' => 304, 'wordId' => $wordId);
		} else {
			return array('status' => 200, 'result' => $listReport);
		}
	}

	public function getMeanMobile ($wordId) {
		$listReport = DB::table('report_mean')->where('wordId', $wordId)
		->where('report_mean.type', 1)
		->where('report_mean.dislike', '<', 10)
		->where('report_mean.status', '<>', -1)
		->orderBy('report_mean.like', 'desc')
		->join('users', 'users.userId', '=', 'report_mean.userId')->get();

		if (count($listReport) == 0) {
			return array('status' => 304, 'wordId' => $wordId);
		} else {
			return array('status' => 200, 'result' => $listReport);
		}
	}

	public function checkMean ($wordId, $userId) {
		$report = ReportMean::where('wordId', $wordId)->where('userId', $userId)
		->where('status', '<>', -1)->where('type', 0)->first();
		if (is_null($report)) {
			return array('status' => 304);
		} else {
			return array('status' => 200, 'result' => $report);
		}
	}

	public function checkMeanMobile ($wordId, $userId) {
		$report = ReportMean::where('wordId', $wordId)->where('userId', $userId)
		->where('status', '<>', -1)->where('type', 1)->first();
		if (is_null($report)) {
			return array('status' => 304);
		} else {
			return array('status' => 200, 'result' => $report);
		}
	}

	public function updateReportMean ($userId, $mean, $wordId) {
		$report = ReportMean::where('userId', $userId)->where('wordId', $wordId)
		->where('status', '<>', -1)->where('type', 0)->first();
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

	public function updateReportMeanMobile ($userId, $mean, $wordId) {
		$report = ReportMean::where('userId', $userId)->where('wordId', $wordId)
		->where('status', '<>', -1)->where('type', 1)->first();
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

	public function deleteReportMean ($userId, $reportId) {
		if (ReportMean::where('userId', $userId)->where('reportId', $reportId)
		->where('type', 0)->delete()) {
			RateReport::where('reportId', $reportId)->delete();
			return array('status' => 200);
		} else {
			return array('status' => 304);
		}
	}

	public function deleteReportMeanMobile ($userId, $reportId) {
		if (ReportMean::where('userId', $userId)->where('reportId', $reportId)
		->where('type', 1)->delete()) {
			RateReport::where('reportId', $reportId)->delete();
			return array('status' => 200);
		} else {
			return array('status' => 304);
		}
	}

	public function getNew ($skip, $take) {
		$query = DB::table('report_mean')
		->select('word', 'mean', 'username', 'report_mean.created_at')
		->where('report_mean.type', 0)
		->where('report_mean.dislike', '<', 10)
		->where('report_mean.status', '<>', -1)		
		->orderBy('report_mean.created_at', 'desc')
		->join('users', 'users.userId', '=', 'report_mean.userId');
		$listReport = $query->skip($skip)->take($take)->get();
		$count = $query->count();

		if (count($listReport) == 0) {
			return array('status' => 304);
		} else {
			return array('status' => 200, 'result' => $listReport, 'count' => $count);
		}
	}

	public function getNewCache () {
		$query = DB::table('report_mean')
		->select('word', 'mean', 'username', 'report_mean.created_at')
		->where('report_mean.type', 0)
		->where('report_mean.dislike', '<', 10)
		->where('report_mean.status', '<>', -1)		
		->orderBy('report_mean.created_at', 'desc')
		->join('users', 'users.userId', '=', 'report_mean.userId');
		$listReport = $query->skip(0)->take(100)->get();
		$count = $query->count();

		return array('result' => $listReport, 'count' => $count);
	}
}
