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

	public function rateMean ($wordId, $email) {
		$user = User::where('email', $email)->where('status', 1)->where('active', 1)->first();
		if (is_null($user)) {
			return array('status' => 304);
		} else {
			// If user reported this mean => Not continue to report second
			$report = ReportMean::where('wordId', $wordId)->where('email', $email)->first();
			if (is_null($report)) {
				$rateReport = new RateReport();
				$rateReport->email  = $email;
				$rateReport->wordId = $wordId;
				$rateReport->userId = $user->id;
				if ($rateReport->save()) {
					// Plus number rate of reportMean
					$numberRate = $report->rate + 1;
					ReportMean::where('wordId', $wordId)->where('email', $email)
					->update(array('rate' => $numberRate));
					return array('status' => 200);
				}) else {
					return array('status' => 306);	
				}
			} else {
				return array('status' => 302);
			}
		}
	}


}
