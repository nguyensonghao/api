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
			$reportMean->userId = $user->id;
			$reportMean->email  = $email;
			$reportMean->mean   = $mean;
			$reportMean->status = 1;
			$reportMean->rate   = 0;
			$reportMean->wordId = $wordId;
			if ($reportMean->save()) {
				return array('status' => 200, 'result' => $reportMean);
			} else {
				return array('status' => 302);
			}
		}
	}

	public function getMean ($wordId) {
		$listReport = ReportMean::where('wordId', $wordId)->where('status', 1)->get();
		if (is_null($listReport)) {
			return array('status', 304);
		} else {
			return array('status' => 200, 'result' => $listReport);
		}
	}


}
