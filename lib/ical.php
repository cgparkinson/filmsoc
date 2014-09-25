<?php
	class ical {
		
		public $title;
		public $events;
		public $header = "Content-Type: text/calendar";
		
		public function __construct($title) {
			$this->title 	= $title;
			$this->events	= Array();
		}
		
		public function addEvent($name, $description, $time, $duration, $place) {
			$event = new icalEvent($name, $description, $time, $duration, $place);
			array_push($this->events, $event);
		}
		
		public function sendHeader() {
			header($this->header);
		}
		
		public function __toString() {
			$content  = "BEGIN:VCALENDAR\r\n";
			$content .= "VERSION:2.0\r\n";
			$content .= "PRODID:"      .     $this->title . "\r\n";
			$content .= "METHOD:PUBLISH\r\n";
			$content .= "X-WR-CALDESC:".     $this->title . "\r\n";
			$content .= "X-WR-CALNAME:".     $this->title . "\r\n";
			$content .= "URL:http://community.dur.ac.uk/hildbede.filmsociety/calendar" . "\r\n";
			
			
			foreach($this->events as $event) {
				$content .= $event;
			}
			
			
			$content  .= "BEGIN:VTIMEZONE"						    . "\r\n";
			$content  .= "TZID:Europe/London"					    . "\r\n";
			$content  .= "BEGIN:DAYLIGHT"						    . "\r\n";
			$content  .= "TZOFFSETFROM:+0000"					    . "\r\n";
			$content  .= "TZOFFSETTO:+0100"						    . "\r\n";
			$content  .= "DTSTART:19810329T010000"				    . "\r\n";
			$content  .= "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU"   . "\r\n";
			$content  .= "TZNAME:BST"							    . "\r\n";
			$content  .= "END:DAYLIGHT"							    . "\r\n";
			$content  .= "BEGIN:STANDARD"						    . "\r\n";
			$content  .= "TZOFFSETFROM:+0100"					    . "\r\n";
			$content  .= "TZOFFSETTO:+0000"						    . "\r\n";
			$content  .= "DTSTART:19961027T020000"				    . "\r\n";
			$content  .= "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU"  . "\r\n";
			$content  .= "TZNAME:GMT"							    . "\r\n";
			$content  .= "END:STANDARD"							    . "\r\n";
			$content  .= "END:VTIMEZONE"						    . "\r\n";
			
			$content  .= "END:VCALENDAR";
			
			return $content;
		}
		
	}
	
	class icalEvent {
		public $name;
		public $description;
		public $time;
		public $duration;
		public $place;
		
		public function __construct($name, $description, $time, $duration, $place) {
			$this->name 	   = $name;
			$this->description = $description;
			$this->time 	   = $time;
			$this->duration    = $duration;
			$this->place 	   = $place;
			
		}
		
		public function __toString() {
			$fmt = 'Ymd\THis';
			$duration = $this->duration;
			$dtF = new DateTime("@0");
		    $dtT = new DateTime("@$duration");
		    $formattedDuration =  $dtF->diff($dtT)->format('PT%hH%iM%sS');
			
			$content  = "BEGIN:VEVENT\r\n";
			
			$content .= "UID:"     	   .     date($fmt ,$this->time)      . "\r\n";
			$content .= "DTSTART:" 	   .     date($fmt ,$this->time)      . "\r\n";
			$content .= "DURATION:"	   .     $formattedDuration           . "\r\n";
			$content .= "SUMMARY:" 	   .     $this->name                  . "\r\n";
			$content .= "DESCRIPTION:" .     $this->description           . "\r\n";
			$content .= "LOCATION:"    .     $this->place                 . "\r\n";
			
			$content .= "END:VEVENT\r\n";
			
			return $content;
		}
	}	
?>