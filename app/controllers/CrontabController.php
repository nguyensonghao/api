<?php 

class CrontabController extends BaseController {

	public function artisanQueue () {
		$shell_command = "php artisan queue:listen > /dev/null &";
		shell_exec($shell_command);
	}

}