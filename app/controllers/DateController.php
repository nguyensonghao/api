<?php 

class DateController {

	public function checkInvalidDate ($timeClient) {
	    $timeServer = $date->getTimestamp();
	    $minus = $timeServer - $timeClient;

	    if ($minus > 60)
	    	return false;
	    else 
	    	return true;
	}

}

?>