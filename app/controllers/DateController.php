<?php 

class DateController {

	public function checkInvalidDate ($timeClient) {
	    date_default_timezone_set("UTC");
		$date = new DateTime();
		$timeServer = $date->getTimestamp();
	    $minus = $timeServer - $timeClient;
	    if ($minus > 3000)
	    	return false;
	    else 
	    	return true;
	}

}

?>