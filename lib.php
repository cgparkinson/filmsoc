<?php
//Get unique session names by file modified time
session_name(filemtime(__FILE__)."bedefilmsoc");
session_start();
header('X-UA-Compatible: IE=edge');
class Filmsoc {
	static function getFilms() {
		$filmsQuery = dbQuery("
			SELECT *
			FROM showings
			WHERE time >= NOW()
			ORDER BY time ASC
		");
		$films = array();
		while($f = $filmsQuery->fetch_assoc()) {
			array_push($films, $f+array(
				"date" => date('D jS M g:ia',strtotime($f['time']))
			));
		}
		$filmsQuery->close();
		return $films;
	}
}

function tokenize($arr, $str, $tokenIndicator="#") {
	$output = $str;
	foreach($arr as $key => $value) {
		$output = str_replace($tokenIndicator.$key, $value, $output);
	}
	
	return $output;
}

//Here lie our database methods

//if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == 'jambook.local') {
if (strpos($_SERVER['SERVER_NAME'],'dur') === false) {
//For local server testing only
	$dbhost = 'localhost';
	$dbusername = 'root';
	$dbpasswd = 'root';
	$database_name = 'Xchqx69_filmsoc';
} else {
//Database settings. Put in an external file with variables the same format as above
	include_once('passwords.php');
}


//Connect to the database

global $db;
$db = new mysqli($dbhost, $dbusername, $dbpasswd, $database_name);
if($db->connect_errno > 0){
    prettyDie('Unable to connect to database [' . $db->connect_error . ']');
}

//mysqli query wrapper
function dbQuery($query) {
	global $db;
	if(!$result = $db->query($query)) {
		prettyDie($db->error);
		return false;
	}
	
	return $result;
}

//Helper function for quicker execution of prepared statements
function dbPrepBind($query, $bindparams) {
	global $db;
	$stmt = $db->prepare($query);
	
	$typestring = '';
	$refs = array();
	
	foreach($bindparams as $key => $param) {
		$refs[$key] = &$bindparams[$key]; //We do this because for some reason bind_param needs references, not values
		switch(gettype($param)) {
			case 'integer':
				$typestring .= 'i';
				break;
			case 'double':
				$typestring .= 'd';
				break;
			case 'string':
				$typestring .= 's';
				break;
		}
	}
	$str = &$typestring;
	array_unshift($refs, $str);
	
	call_user_func_array(array($stmt, "bind_param"), $refs);
	$stmt->execute();
	return $stmt;
}

function esc($str) {
	global $db;
	return mysqli_real_escape_string($db, $str);
}


function prettyTimestamp($d = '') {
	if($d !== '') {
		return date('Y-m-d H:i', $d);
	} else {
		return date('Y-m-d H:i');
	}
}

function readableTimestamp($d = '') {
	if($d !== '') {
		return date('d-m-Y H:i', $d);
	} else {
		return date('d-m-Y H:i');
	}
}

function prettyDie($str) {
	if(strpos($_SERVER['REQUEST_URI'], '/api')) {
		//echo("Please forward this to your nearest code monkey: ".$str);
		echo 'Please inform your nearest code monkey';
		exit();
	}
}

?>