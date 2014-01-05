<?php
$data_setting = array(
	'group'	=> 'page'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'privacy') {
			$privacy = $setting['value'];
		}
	}
}

$title = $lang['head_privacy'];
?>
<h5><?php echo $lang['head_privacy']; ?></h5>
<article class="auction_container">
	<?php echo $privacy; ?>
	<img class="bottom" src="includes/images/bottom/privacy.png" alt="privacy">
</article>