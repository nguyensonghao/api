<?php 

class DateController {

	public function checkInvalidDate ($timeClient) {
		$date = new DateTime();
	    $timeServer = $date->getTimestamp();
	    Log::info($timeServer);
	    $minus = $timeServer - $timeClient;
	    if ($minus > 60) 
	    	return false;
	    else 
	    	return true;
	}

}

?>