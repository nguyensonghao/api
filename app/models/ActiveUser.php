<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class ActiveUser extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'active_user';

	public function active ($keyActive) {
		$result = ActiveUser::where('key', $keyActive)->where('status', 0)->first();
		if (is_null($result)) {
			return false;
		} else {
			if (ActiveUser::where('key', $keyActive)->update(array('status' => 1))) {
				return $result;
			} else {
				return false;
			}
		}
	}


}
