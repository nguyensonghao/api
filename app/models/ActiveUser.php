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
		$result = ActiveUser::where('key', $keyActive)->first();
		if (is_null($result)) {
			return false;
		} else {
			if ($result->status == 0) {
				return false;
			} else {
				ActiveUser::where('key', $keyActive)->update(array('status' => 1));	
			}
		}
	}


}
