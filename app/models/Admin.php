<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Admin extends Eloquent {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'admin';

	public function __construct () {
		DB::connection()->disableQueryLog();
	}

	public function login ($username, $password) {
		return Admin::where('username', $username)->where('password', md5($password))
		->first();
	}

}
