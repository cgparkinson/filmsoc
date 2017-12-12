<?php

require_once('../../lib.php');

function getStatus($status) {
	$httpStatus = Array(
	    100 => 'Continue',
	    101 => 'Switching Protocols',
	    200 => 'OK',
	    201 => 'Created',
	    202 => 'Accepted',
	    203 => 'Non-Authoritative Information',
	    204 => 'No Content',
	    205 => 'Reset Content',
	    206 => 'Partial Content',
	    300 => 'Multiple Choices',
	    301 => 'Moved Permanently',
	    302 => 'Found',
	    303 => 'See Other',
	    304 => 'Not Modified',
	    305 => 'Use Proxy',
	    306 => '(Unused)',
	    307 => 'Temporary Redirect',
	    400 => 'Bad Request',
	    401 => 'Unauthorized',
	    402 => 'Payment Required',
	    403 => 'Forbidden',
	    404 => 'Not Found',
	    405 => 'Method Not Allowed',
	    406 => 'Not Acceptable',
	    407 => 'Proxy Authentication Required',
	    408 => 'Request Timeout',
	    409 => 'Conflict',
	    410 => 'Gone',
	    411 => 'Length Required',
	    412 => 'Precondition Failed',
	    413 => 'Request Entity Too Large',
	    414 => 'Request-URI Too Long',
	    415 => 'Unsupported Media Type',
	    416 => 'Requested Range Not Satisfiable',
	    417 => 'Expectation Failed',
	    500 => 'Internal Server Error',
	    501 => 'Not Implemented',
	    502 => 'Bad Gateway',
	    503 => 'Service Unavailable',
	    504 => 'Gateway Timeout',
	    505 => 'HTTP Version Not Supported'
	);
	return $httpStatus[$status];
}

function successHeader() {
	$status_header = 'HTTP/1.1 200 ' . getStatus(200);
	// set the status
	header($status_header);
	//Set the content type
	$content_type = 'application/json';
	header('Content-type: ' . $content_type);
}

function sendSuccess($res) {
	successHeader();
	echo json_encode($res);
}

function api_die($str, $code) {
	$status = (isset($code))?$code:500;
	$status_header = 'HTTP/1.1 ' . $status . ' ' . getStatus($status);
	// set the status
	header($status_header);
	//Set the content type
	$content_type = 'application/json';
	header('Content-type: ' . $content_type);
	
	$message = array(
		'error' => $str
	);
	
	echo json_encode($message);
	exit();
}


//Get the input. It should be a well formatted JSON string
$api = $_POST['api'];

$pass = trim(file_get_contents('../editThisFileToChangePassword.txt'));
if(!isset($_SESSION['logged_in']) or $_SESSION['logged_in'] !== md5($pass)) {
	api_die('Bad credentials', 401);
}

if($api === 'getFilmData') {
	if(!isset($_POST['imdbid'])) {
		api_die('No IMDB ID specified', 400);
	}
	$url = 'http://www.omdbapi.com/?apikey=f33947dc&i='.$_POST['imdbid'];
	$filmJSON = file_get_contents($url);
	$filmData = json_decode($filmJSON, true);
	$remotePosterURL = $filmData['Poster'];
	$fileinfo = pathinfo($remotePosterURL);
	$posterName = strtolower(str_replace(' ', '', $filmData['Title'])).$filmData['Year'].$_POST['imdbid'];
	// CGP: ADD HERE the preg_replace. syntax:
	$posterName = preg_replace('/[^ \w]+/', '', $posterName).'.'.$fileinfo['extension'];
	
	if (!file_exists('../../posters/')) {
   		mkdir('../../posters/', 0777, true);
	}
	
	file_put_contents('../../posters/'.$posterName, file_get_contents($remotePosterURL));
	
	$res = array(
		"poster" => $posterName,
		"omdbResult" => $filmData
	);
	
} else if($api === 'addNewShowing') {
	if(!isset($_POST['imdbid'])) {
		api_die('No IMDB ID specified', 400);
	} else if(!isset($_POST['time'])) {
		api_die('No time specified', 400);
	} else if(!preg_match('/[0-9]{4}-[0-1][0-9]-[0-3][0-9] [0-2][0-9]:[0-5][0-9]/', $_POST['time'])) {
		api_die('Time is in incorrect format', 400);
	}
	
	$url = 'http://www.omdbapi.com/?apikey=f33947dc&i='.$_POST['imdbid'];
	$filmJSON = file_get_contents($url);
	$filmData = json_decode($filmJSON, true);
	$remotePosterURL = $filmData['Poster'];
	$fileinfo = pathinfo($remotePosterURL);
	$posterName = strtolower(str_replace(' ', '', $filmData['Title'])).$filmData['Year'].$_POST['imdbid'];
	// CGP: ADD HERE the preg_replace. syntax:
        $posterName = preg_replace('/[^ \w]+/', '', $posterName).'.'.$fileinfo['extension'];
	if (!file_exists('../../posters/')) {
   		mkdir('../../posters/', 0777, true);
	}
	file_put_contents('../../posters/'.$posterName, file_get_contents($remotePosterURL));
	
	$runtime = $filmData['Runtime'];
	$runtime = floor(strtotime($runtime)-time());
	// CGP: so structure is 
	// name: name of the film (string)
	// time: inputted time (string?)
	// imdbid: inputted IMDB ID (string?)
	// plot: string
	// year: string
	// actors: string
	// poster: filename of the poster
	// runtime: a *time* (but seems unnecessary? what does it do)
	$addNewShowingQuery = dbPrepBind("
		INSERT INTO showings
		(name, time, imdbid, plot, year, actors, poster, runtime)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?)
	", array($filmData['Title'], $_POST['time'], $_POST['imdbid'], $filmData['Plot'], $filmData['Year'], $filmData['Actors'], $posterName, $runtime));
	
	$res = array(
		"message" => "adding film was successful",
		"poster" => $posterName,
		"time" => $_POST['time'],
		"omdbResult" => $filmData
	);
} else {
	api_die('Invalid API Endpoint', 400);
}

sendSuccess($res);


?>
