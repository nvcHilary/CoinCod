<?php
$data_setting = array(
	'group'	=> 'page'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'terms') {
			$terms = $setting['value'];
		}
	}
}
?>
<h5><?php echo $lang['head_terms']; ?></h5>
<article class="auction_container">
	<?php echo $terms; ?>
	<img class="bottom" src="includes/images/bottom/term_of_service.png" alt="terms">
</article>