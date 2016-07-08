<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Premium extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'premium';	

	public function insertPremium ($userId, $deviceId, $transaction, $provider) {
		$premiumExits = Premium::where('userId', $userId)
		->where('deviceId', $deviceId)->where('transaction', $transaction)
		->where('provider', $provider)->first();

		// check exits premium key
		if (!is_null($premiumExits)) {
			return array('status' => 302);
		}

		$premiumValid = Premium::where('userId', $userId)->get();
		if (count($premiumValid) >= 4) {
			return array('status' => 304);	
		}

		// check exits userId
		$user = User::where('userId', $userId)->first();
		if (is_null($user)) {
			return array('status' => 308);
		}

		$premium = new Premium();
		$premium->userId = $userId;
		$premium->deviceId = $deviceId;
		$premium->transaction = $transaction;
		$premium->provider = $provider;

		if ($premium->save()) {
			return array('status' => 200);
		} else {
			return array('status' => 306);
		}
	}

	public function checkPremiumDevice ($userId, $deviceId) {
		$premium = Premium::where('userId', $userId)
		->where('deviceId', $deviceId)->first();

		if (is_null($premium)) {
			return array('status' => 306);
		} else {
			return array('status' => 200, 'premium' => $premium);
		}
	}

	public function checkPremiumUser ($userId) {
		$premium = Premium::where('userId', $userId)->first();		
		if (is_null($premium))
			return false;
		
		return true;
	}
}
