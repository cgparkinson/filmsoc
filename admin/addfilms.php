<?php
	if ( basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]) ) {
		header('location: ./');
	}

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
	<title>Bede Film Society - Manage Films</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="../favicon.ico">
	<link rel="stylesheet" href="../css/main.css">
	<script src="../js/libs/modernizr-2.6.2.min.js"></script>
</head>
<body id="addfilms">
	<!--[if lt IE 8]>
		<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	
	<header id="top">
		<nav>
			<a href="../">Home</a>
			<a href="pr/">PR Tools</a>
		</nav>
	</header>
	
	<main>
		<h2>Upcoming Showings</h2>
		
		<div class="new-showing">
			<article>
				<div id="new-film-poster"></div>
				<div id="film-info">
					<div class="slidepanel-wrapper">
						<div id="choosefilm" class="slidepanel">
							<label for="new-listing-film"><h3>Search for a film</h3></label>
							<input type="text" id="new-listing-film" placeholder="Enter a film title or imdb id (ttXXXXXXX)...">
							<ul id="film-suggestions">
								<li class="hide"></li>
								<li class="hide"></li>
								<li class="hide"></li>
							</ul>
						</div>
						<div id="choosetime" class="slidepanel">
							<label for="new-listing-time"><h3>What time will #Title be showing?</h3></label>
							<input type="text" id="new-listing-time" placeholder="Enter a film time..." value="<?php echo(readableTimestamp(strtotime("next saturday 8pm")))?>">
							<input type="submit" id="new-showing-submit-button" value="Create New Showing">
						</div>
						<div id="showingadded" class="slidepanel">
							<h3>Success! New showing for #Title scheduled for #Time</h3>
						</div>
					</div>
				</div>
			</article>
		</div>
		<div id="existing-showings">
<?php
	$template = file_get_contents('../templates/addfilms-listing.html');
	foreach($films as $film) {
		echo(tokenize($film, $template));
	}
?>
		</div>
	</main>
	
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