<?php
$data_setting = array(
	'group'	=> 'page'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'security') {
			$security = $setting['value'];
		}
	}
}

$title = $lang['head_security'];
?>
<h5><?php echo $lang['head_security']; ?></h5>
<article class="auction_container">
	<?php echo $security; ?>
	<img class="bottom" src="includes/images/bottom/security.png" alt="security">
</article>