<?php
$data_setting = array(
	'group'	=> 'page'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'about_us') {
			$about_us = $setting['value'];
		} else if($setting['key'] == 'contact_us') {
			$contact_us = $setting['value'];
		}
	}
}

$title = $lang['head_about_us'];
?>
<h5><?php echo $lang['head_about_us']; ?></h5>
<article class="auction_container">
	<?php echo $about_us; ?>
</article>

<h5><?php echo $lang['head_contact_us']; ?></h5>
<article class="auction_container">
	<?php echo $contact_us; ?>
	<img class="bottom" src="includes/images/bottom/contacts.png" alt="contact us">
</article>