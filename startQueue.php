<?php
	$shell_command = "sudo php artisan queue:listen > /dev/null &";
	shell_exec($shell_command);
?>
