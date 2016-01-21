<?php 

class DateController {

	public function checkInvalidDate ($timeClient) {
		$date = new DateTime();
	    $timeServer = $date->getTimestamp();
	    $minus = $timeServer - $timeClient;
	    // Log::info($timeServer);
	    // Log::info($timeClient);

	    if ($minus > 60) 
	    	return false;
	    else 
	    	return true;
	}

}

?>