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

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-55938347-1', 'auto');
  ga('send', 'pageview');

</script>