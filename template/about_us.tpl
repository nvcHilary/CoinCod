<?php
	require_once '../config.php';
	require_once '../sql_function.php';
?>
<!--About Us-->
		<div id="mi-slider" class="mi-slider">
		<ul>
			<li>Coincod was founded in 2012 because we believe in helping our customers to challenge the status quo of the current auction method. By challenging the status quo, we try our best on lowering the barriers so that everyone can obtain their products and at the same time enjoying the best auction experience. This auction system is based on the unique worldwide concept which we ensure every user of ours will have great pleasure throughout the process. At Coincod we supply all the best branded and new high quality products. You name it, we have it. In order to fulfill our manifesto, the Coincod team will handle all our users' goods personally from buying, packaging and shipping.</li>
		</ul>
		<ul>
			<li>Need more support? Contact us at <a href="mailto:support@coincod.com">support@coincod.com</a> for all support.
				<br>
				<img class="bottom" src="../template/template_image/bottom/contacts.png" alt="contact">
			</li>
		</ul>
		<nav>
			<a href="#">Intro</a>
			<a href="#">Contact</a>
		</nav>
		</div>
<!--End of About Us-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="<?=mainPageURL()?>/libs/jquery.catslider.js"></script>
<script src="<?=mainPageURL()?>/libs/modernizr.custom.63321.js"></script>
<script>
	$(function() {
		$( '#mi-slider' ).catslider();
	});
</script>