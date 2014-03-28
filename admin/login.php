<?php
	if ( basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]) ) {
		header('location: ./');
	}
?>

<!DOCTYPE html>
<!--[if lt IE 7]>	   <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>		   <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>		   <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Bede Film Society - Exec Login</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="../favicon.ico">
	<link rel="stylesheet" href="../css/main.css">
	<script src="../js/libs/modernizr-2.6.2.min.js"></script>
</head>
<body id="login">
	<!--[if lt IE 8]>
		<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	
	<div id="login-box">
		<h3>
			Login to Filmsoc
			<div class="err"><?php
				if(isset($loginFailed) and $loginFailed === 1) {
					echo("Incorrect Password");
				}
			?></div>
		</h3>
		<form action="./" method="post">
			<input type="password" name="password" placeholder="password">
			<input type="submit" name="poosh-button" value="Login">
		</form>
	</div>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/libs/jquery-1.9.1.min.js"><\/script>')</script>
	<script src="../js/libs/plugins.js"></script>
	<script src="../js/main.js"></script>
	
	<script>
	if (document.location.hostname == "localhost") {
	document.write('<script src="//'
  + location.host.split(':')[0]
  + ':35729/livereload.js"></'
  + 'script>')
  }</script>
</body>
</html>