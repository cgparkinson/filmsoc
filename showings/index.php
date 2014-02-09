<?php
	require_once('../lib.php');
	$films = Filmsoc::getFilms();
?>
<!DOCTYPE html>
<!--[if lt IE 7]>	   <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>		   <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>		   <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Bede Film Society</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="../favicon.ico">
	<link rel="stylesheet" href="../css/main.css">
	<script src="../js/libs/modernizr-2.6.2.min.js"></script>
</head>
<body id="all-showings">
	<!--[if lt IE 8]>
		<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->

	
	<header id="top">
		<nav>
			<a href="../">Home</a>
		</nav>
	</header>
	
	
	<main>
		<section>
			<h2>Next Showings</h2>
<?php
	//Display Films
	$template = file_get_contents('../templates/showings-film-listing.html');
	$numFilms = count($films);
	$d = 0;
	$i = 0;
	if($numFilms > 0) {
?>
	<div class="film-listings">
			<div class="film-row">
<?php
		while($i<$numFilms) {
			//Check to see if the next films are the same as this one, if so we group them
			$start = $i+0;
			while(($i+1 < $numFilms) and ($films[$start]['imdbid'] === $films[$i+1]['imdbid'])) {
				$i++;
				$films[$start]['date'] = $films[$start]['date'].'<br>'.$films[$i]['date'];
			}
			$d++;
			$i++;
			echo(tokenize($films[$start], $template));
		}
?>
		</div>
	</div>
<?php
	} else {
?>
	<div class="nofilms">Check back next term for details of future showings!</div>
<?php
	}
?>
		</section>
	</main>
	
<?php
	include('../footer.php');
?>
</body>
</html>