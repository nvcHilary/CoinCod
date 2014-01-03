<?php
$data_setting = array(
	'group'	=> 'social'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'fbaccount') {		$fbaccount = $setting['value']; }
		if($setting['key'] == 'twitteraccount') {	$twitteraccount = $setting['value']; }
		if($setting['key'] == 'googleaccount') {	$googleaccount = $setting['value']; }
		if($setting['key'] == 'pinterestaccount') {	$pinterestaccount = $setting['value']; }
	}
}
?>
<footer id="footer_wrap">
	<section class="upper_footer_wrap">
		<div class="container" align="left">
			<nav class="ul">
				<ul class="footer_nav">
					<h4><?php echo $lang['text_company_name']; ?></h4>
					<li><a href="about_us.html"><?php echo $lang['text_about_us']; ?></a></li>
					<li><a href="how_it_works.html"><?php echo $lang['text_how_it_works']; ?></a></li>
					<li><a href="faq.html"><?php echo $lang['text_faq']; ?></a></li>
					<li><a href="careers.html"><?php echo $lang['text_careers']; ?></a></li>
					<li><a href="feedback.html"><?php echo $lang['text_feedback']; ?></a></li>
				</ul>
				
				<ul class="footer_nav">
					<h4><?php echo $lang['text_other_features']; ?></h4>
					<li><a href="location.html"><?php echo $lang['text_location']; ?></a></li>
					<li><a href="graphics.html"><?php echo $lang['text_graphics']; ?></a></li>
					<li><a href="site_status.html"><?php echo $lang['text_site_status']; ?></a></li>
				</ul>
				
				<ul class="footer_nav">
					<h4><?php echo $lang['text_social_networks']; ?></h4>
					<?php if($fbaccount != "") { ?><li><a href="<?php echo $fbaccount; ?>" target="_blank"><?php echo $lang['text_like_facebook']; ?></a></li><?php } ?>
					<?php if($twitteraccount != "") { ?><li><a href="<?php echo $twitteraccount; ?>" target="_blank"><?php echo $lang['text_follow_twitter']; ?></a></li><?php } ?>
					<?php if($googleaccount != "") { ?><li><a href="<?php echo $googleaccount; ?>" target="_blank"><?php echo $lang['text_plus_google+']; ?></a></li><?php } ?>
					<?php if($pinterestaccount != "") { ?><li><a href="<?php echo $pinterestaccount; ?>" target="_blank"><?php echo $lang['text_follow_pinterest']; ?></a></li><?php } ?>
					<li><a href="../auction_blog"><?php echo $lang['text_subscribe_blog']; ?></a></li>
				</ul>
				
				<ul class="footer_nav">
					<h4><?php echo $lang['text_legal']; ?></h4>
					<li><a href="terms.html"><?php echo $lang['text_terms_of_Service']; ?></a></li>
					<li><a href="privacy.html"><?php echo $lang['text_privacy']; ?></a></li>
					<li><a href="security.html"><?php echo $lang['text_security']; ?></a></li>
				</ul>
			</nav>  <!--end of nav ul-->
		</div>  <!--end of container-->
	</section>  <!--end of section upper_footer_wrap-->
	
	<section id="lower_footer_wrap">
		<div class="container">
			<div class="logo_image">
				<a href=""><img src="includes/images/logo2.png" width="80px" border="0"></a>
			</div>  <!--end div logo_image-->	
			<div class="payment_image">
				<p/><a href="#"><img src="includes/images/payment.png" width="700" border="0"></a>
			</div>  <!--end div payment_image-->
			<div class="copyright">
				<p>Copyright &copy; <?php echo date('Y') ?> <?php echo $lang['text_company_name'] ?>. All rights reserved.</p>
			</div><!--end of copyright-->
		</div> <!--end of container-->	 
	</section><!--end  of section lower_footer_wrap-->         
	
	<!-- back2top blockcode -->
	<section id="back-top">
		<a href="#top">Back To Top</a>
	</section><!-- end of back2top -->	
</footer><!--footer_wrap--> 

<!-- ======================================= Lazy Loads ================================= -->
        
<!-- back2top script-->   
<script >
	$(function(){
		// hide #back-top first
		$("#back-top").hide();
		// fade in #back-top
		$(function () {
			$(window).scroll(function () {
				if ($(this).scrollTop() > 100) {
					$('#back-top').fadeIn();
				} else {
					$('#back-top').fadeOut();
				}
			});
			// scroll body to 0px on click
			$('#back-top a').click(function () {
				$('body,html').animate({
					scrollTop: 0
				}, 800);
				return false;
			});
		});
	});
</script><!-- end of back2top script -->

<!-- Accordion script -->
<script>
    $(function() {
        $("#menu").accordion({
            collapsible: true, 
            active: false,
			heightStyle: "content" 
        });
    });
</script>