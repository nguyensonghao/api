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

	public function rateMean ($wordId, $email, $type) {
		if ($type == 'like')
			$rateType = 1;
		else
			$rateType = 0;

		$user   = User::where('email', $email)->where('status', 1)->where('active', 1)->first();
		$userId = $user->userId;
		$report = ReportMean::where('wordId', $wordId)->where('userId', $userId)->first();
		if (is_null($user) || is_null($report)) {
			return array('status' => 304);
		} else {
			$rate = RateReport::where('wordId', $wordId)->where('userId', $userId)->first();

			// User not rate for report
			if (is_null($rate)) {
				$rateReport = new RateReport();
				$rateReport->wordId = $wordId;
				$rateReport->userId = $userId;
				$rateReport->type   = $rateType;
				if ($rateReport->save()) {
					// Plus number rate of reportMean
					if ($type == 'like') {
						$numberRate = $report->like + 1;
						$result = ReportMean::where('wordId', $wordId)->where('userId', $userId)
					    ->update(array('like' => $numberRate));
					} else {
						$numberRate = $report->dislike + 1;
						$result = ReportMean::where('wordId', $wordId)->where('userId', $userId)
					    ->update(array('dislike' => $numberRate));
					}
					
					return array('status' => 200, 'result' => $result);
				} else {
					return array('status' => 306);	
				}

			// User rated for report
			} else {
				if ($rate->type == $rateType) {
					return array('status' => 302);	
				} else {
					if ($type == 'like') {
						$numberLike    = $report->like + 1;
						$numberdisLike = $report->dislike - 1;
						RateReport::where('wordId', $wordId)->where('userId', $userId)
						->update(array('type' => 1));
					} else {
						$numberLike    = $report->like - 1;
						$numberdisLike = $report->dislike + 1;
						RateReport::where('wordId', $wordId)->where('userId', $userId)
						->update(array('type' => 0));
					}

					$result = ReportMean::where('wordId', $wordId)->where('userId', $userId)
					->update(array('like' => $numberLike, 'dislike' => $numberdisLike));

					if ($result)
						return array('status' => 200, 'result' => $result);
					else 
						return array('status' => 306);	 

				}
				
			}
		}
	}


}
