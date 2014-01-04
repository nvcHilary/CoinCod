<?php
$data_setting = array(
	'group'	=> 'page'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'how_it_works') {
			$how_it_works = $setting['value'];
		}
	}
}
?>
<h5><?php echo $lang['head_how_it_works']; ?></h5>
<article class="auction_container">
	<?php echo $how_it_works; ?>
</article>