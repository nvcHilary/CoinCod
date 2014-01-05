<?php
$data = array();
$faqs = getFAQs($data);

$title = $lang['head_faq'];
?>
<h5><?php echo $lang['head_faq']; ?></h5>
<article class="auction_container">
	 <article id="menu">
		<?php if($faqs) { ?>
			<?php foreach($faqs as $faq) { ?>
				<h3><?php echo $faq['question']; ?></h3>
				<p><?php echo $faq['answer']; ?></p>
			<?php } ?>
		<?php } ?>
	 </article> <!--end of faq-->
	<img class="bottom" src="includes/images/bottom/faq.png" alt="faqs">
</article>