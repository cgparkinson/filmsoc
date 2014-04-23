<?php
	require_once('../lib.php');
?>
<!DOCTYPE html>
<!--[if lt IE 7]>	   <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>		   <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>		   <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>RAFFLE</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="../favicon.ico">
	<link rel="stylesheet" href="../css/main.css">
	<script type="text/javascript"
      src="//maps.googleapis.com/maps/api/js?sensor=false">
    </script>
    <script src="../js/libs/modernizr-2.6.2.min.js"></script>
</head>
<body id="raffle">
	<!--[if lt IE 8]>
		<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<a href="#raffle" id="fullscreenBtn">FULLSCREEN</a>
	<div class="setup">
		<div class="members">
			<span>members</span>
			<div class="data">
				<article>
					<label for="members-start">start</label>
					<input type="number" min="001" max="999" step="1" id="members-start">
				</article>
				<article class="arr">&#10146;</article>
				<article>
					<label for="members-end">end</label>
					<input type="number" min="001" max="999" step="1" id="members-end">
				</article>
			</div>
		</div>
		<div class="nonmembers">
			<div class="data">
				<article>
					<input type="number" min="001" max="999" step="1" id="nonmembers-start">
					<label for="nonmembers-start">start</label>
				</article>
				<article class="arr">&#10147;</article>
				<article>
					<input type="number" min="001" max="999" step="1" id="nonmembers-end">
					<label for="nonmembers-end">end</label>
				</article>
			</div>
			<span>non-members</span>
		</div>
	</div>
	
<?php
	include('../footer.php')
?>
</body>
</html>