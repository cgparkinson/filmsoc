<?php
//Get the films
	require_once('../lib.php');
	require_once('../lib/ical.php');
	
	function no_agm($film) {
		return $film['imdbid'] != 'agm';
	}
	
	$films = Filmsoc::getAllFilms();
	
	$films = array_filter($films, "no_agm");
	$films = array_values($films);
	
	$calendar = new ical("Bede Film Soc Showings");
	
	foreach($films as $film) {
		$name        = $film['name'];
		$description = $film['plot'].' Starring '.$film['actors'];
		$time        = strtotime($film['time']);
		$duration    = $film['runtime'];
		$place       = 'Caedmon Hall, College of St Hild and St Bede';
		$calendar->addEvent($name, $description, $time, $duration, $place);
	}
	
	header('Content-disposition: attachment;filename=BedeFilmSocShowings.ics');

	$calendar->sendHeader();
	echo $calendar;

?>