<?php
	$shell_command = "php artisan queue:listen > /dev/null &";
	shell_exec($shell_command);
?>