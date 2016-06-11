<?php
//	echo 'run crontab';
//	$shell_command = "php artisan queue:listen > /dev/null &";
//	shell_exec($shell_command);
	Cache::forever('new-report', function () {
		DB::table('report_mean')
		->select('word', 'mean', 'username', 'report_mean.created_at')
		->where('report_mean.type', 0)
		->where('report_mean.dislike', '<', 10)
		->where('report_mean.status', '<>', -1)
		->orderBy('report_mean.created_at', 'desc')
		->join('users', 'users.userId', '=', 'report_mean.userId');
		$listReport = $query->skip(0)->take(100)->get();
		$count = $query->count();
		return array('report' => $listReport, 'quantity' => $count);
	});
?>
