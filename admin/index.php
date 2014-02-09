<?php
	require_once('../lib.php');
	$pass = trim(file_get_contents('editThisFileToChangePassword.txt'));
	$loginFailed = 0;
	
	//Check if logging in
	if(isset($_POST['password'])) {
		if($_POST['password'] == $pass) {
			$_SESSION['logged_in'] = md5($pass);
		} else {
			$loginFailed = 1;
		}
	}
	
	if(isset($_SESSION['logged_in']) and $_SESSION['logged_in'] == md5($pass) and isset($_POST['action'])) {
		if($_POST['action'] === 'deleteShowing') {
			$showingid = intval($_POST['showingid']);
			dbQuery("
				DELETE FROM showings
				WHERE showingid='$showingid'
			");
		}
	}
	
	if(isset($_SESSION['logged_in']) and $_SESSION['logged_in'] == md5($pass)) {
		include('addfilms.php');
	} else {
		include('login.php');
	}
		
?>