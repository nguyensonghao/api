<?php 

class DateController {

	public function checkInvalidDate ($timeClient) {
	    $timeServer = $date->getTimestamp();
	    $minus = $timeServer - $timeClient;

	    if ($minus > 60) {
	    	Log::info('Quá thời gian rồi');
	    	return false;
	    }
	    else {
	    	Log::info('Không quá thời gian');
	    	return true;
	    }
	}

}

?>