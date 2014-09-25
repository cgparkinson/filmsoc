<?php
	require_once('ical.php');
	
#Helper function for printing a line
	function ln($str = '') {
		echo $str."\r\n";
	}
	
	
	$meetingsDates = Array();
	
	$meetingMonths = Array(
		'jan' => 'third',
		'march' => 'third',
		'may' => 'third',
		'june' => 'third',
		'september' => 'second',
		'october' => 'third'
	);
		
	$y = idate('Y')+1;
	
	foreach($meetingMonths as $month => $thu) {
		for($i = 2013; $i <= $y; $i++) {
			array_push($meetingsDates, strtotime($thu.' thursday 6:30pm '.$month.' '.$i));
		}
	}
	
	sort($meetingsDates);


//Compose iCal object
	$ical = new ical('2352 Meetings');
	foreach($meetingsDates as $meeting) {
		$meetingName = '2352 '.date('F', $meeting).' meeting';
		$ical->addEvent($meetingName, $meeting, 'Masonic Hall, Durham');
	}
	
	
//Set header
	$ical->sendHeader();
//Output calendar
	echo $ical;
?>