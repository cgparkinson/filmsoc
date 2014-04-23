<?php
	require_once('lib.php');
	
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
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" href="css/main.css">
	<script src="js/libs/modernizr-2.6.2.min.js"></script>
	<script type="text/javascript"
      src="//maps.googleapis.com/maps/api/js?sensor=false">
    </script>
</head>
<body id="home">
	<!--[if lt IE 8]>
		<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->

	
	<header id="top">
		<nav>
			<a href="#showings">Showings</a>
			<a href="#admission">Admission</a>
			<a href="#membership">Membership</a>
			<a href="#about">About Us</a>
		</nav>
	</header>
	
	
	<div id="welcome">
		<div class="hero-wrapper">
			<h3>Welcome To</h3>
			<div class="tape">
				<h1>
					<span>B</span><span>e</span><span>d</span><span>e</span><br>
					<span>F</span><span>i</span><span>l</span><span>m</span><br>
					<span>S</span><span>o</span><span>c</span>
				</h1>
			</div>
			<p>Durham's best and oldest student cinema</p>
		</div>
		
		<div class="social-buttons">
			<a class="twitter" href="https://twitter.com/BedeFilmSoc"></a>
			<a class="facebook" href="https://www.facebook.com/thebedefilmsoc"></a>
		</div>
<?php
	if(count($films) > 0) {
		$firstFilm = $films[0];
?>
		<div class="next-showing">
			<!--
<div class="poster"
			style="background-image: url('<?php echo($firstFilm['poster']) ?>')"></div>
-->
			<img src="posters/<?php echo($firstFilm['poster']) ?>" alt="">
			<p>
				<em>Next Showing</em>
				<span><?php echo($firstFilm['name']) ?></span>
				<span><?php echo($firstFilm['date']) ?></span>
			</p>

		</div>
<?php
	}
?>
	</div>
	<section id="showings">
		<h2>Next Showings <a href="showings/">See this term's full listings</a></h2>
		
			
<?php	
	//Display Films
	$template = file_get_contents('templates/film-listing.html');
	$numFilms = count($films);
	$count = min(4,$numFilms);
	$d = 0;
	$i = 0;
?>
		
		
<?php
	if($count > 0) {
?>
	<div class="film-listings">
			<div class="film-row">
<?php
		while($d<$count and $i<$numFilms) {
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
	<main>
	
		
		<section id="admission">
			<h2>Admission</h2>
			
			<div class="twocol">
				<div id="priceinfo">
					<span>
						<div class="price">£1</div>
						<p>members</p>
					</span>
					<span>
						<div class="price">£3</div>
						<p>non-members</p>
					</span>
				</div>
				<p>Films are just £1 for members or £3 otherwise. We have weekly showings on Saturdays and Sundays, occasionally accompanied by a for one night only midweek Wednesday showing. We announce all our showings on the Facebook page so like us to keep in the loop. All our members also receive an email with showing times.</p>
			</div>
			
		</section>
		
		<section id="membership">
			<h2>Membership</h2>
			<p>Making good choices at University is important, and buying a membership to Bede Film Soc is definitely one of the best you'll make. It' just £15 per year and gets you in for just £1 &mdash; a third of the usual price! It's like getting 7 films for free!</p>
			<div class="twocol">
				<p>If you feel like saving even more money, you can buy life membership, which gives you all of the benefits of the regular membership, but lasts for the entire length of your degree!</p>
				<div id="membershipinfo">
					<span>
						<div class="price">£30</div>
						<p>for life</p>
					</span>
					<span>
						<div class="price">£15</div>
						<p>per year</p>
					</span>
				</div>
			</div>

			<p class="clear">Anybody can have the chance of joining the Bede Film Soc Exec and directly influencing the running of the society. You are not required to be a member of Hild Bede college, members and non members are welcome at AGM or EGMs. <a href="exec/">You can see our past and present exec here.</a></p>
		</section>
		
		<section id="about">
			<h2>About Us</h2>
			<p>Bede Film Society is not only one of Hild Bede College’s oldest societies, but also the University’s. Since 1962 we’ve been the place in Durham to watch movies. Evolving from a group of friends in a darkened room, we now bring students and staff from all over Durham the opportunity to see the latest movies in the best surroundings at the lowest possible price.</p>
		</section>
			<div id="map-canvas"></div>
		<section id="final">
<p><a href="https://www.google.co.uk/maps/preview/place/Bede+Film+Society/@54.777147,-1.565535,17z/data=!3m1!4b1!4m2!3m1!1s0x0:0xa9f917f2a83d5e24?hl=en">Situated in the spacious Caedmon Hall</a> in the beautiful grounds of Hild Bede, films are shown in stunning quality by our incredible new Digital Cinema Projector on the biggest screen in Durham and with Dolby 5.1 Surround Sound, you really feel like a part of the film. As Durham’s only traditional student cinema and now in our <?php echo(ordinalSuffix(intval(date("Y"))-1962)) ?> year, Bede Film Soc brings you the latest major releases each week, and with a wide range of sweets at our amazing value pick ‘n’ mix and drinks of all manner in The Vernon Arms just next door, Bede Film Soc offers the premier student cinema experience in Durham. <!-- <a href="history/">You can read more about our exciting history here.</a> --></p>

<p class="enjoy">Enjoy the film!</p>
		</section>
	</main>
	
<?php
	include('footer.php')
?>
</body>
</html>