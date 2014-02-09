<?php
	$stem =  in_array(strtolower(basename(dirname($_SERVER["SCRIPT_FILENAME"]))), array("filmsoc", "public_html", "newsite")) ? '' : '../' ;
?>

<footer>
	
	<p>Website by <a href="http://www.linkedin.com/pub/ben-tattersley/2b/914/405">Ben Tattersley</a>. Hosted at <a href="http://www.dur.ac.uk">Durham University</a></p>
	<p>&copy; The Bede Film Soc <?php echo date("Y")?></p>
	<p class="exec-login"><a rel="nofollow" href="<?php echo($stem) ?>admin/">Exec Login</a></p>
</footer>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.9.1.min.js"><\/script>')</script>
<script src="<?php echo($stem) ?>js/libs/plugins.js"></script>
<script src="<?php echo($stem) ?>js/main.js"></script>

<script>
if (document.location.hostname == "localhost") {
document.write('<script src="//'
+ location.host.split(':')[0]
+ ':35729/livereload.js"></'
+ 'script>')
}</script>