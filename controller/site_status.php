<?php
$data_setting = array(
	'group'	=> 'config'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'status') {
			$status = $setting['value'];
		}
	}
}
?>
<h5><?php echo $lang['head_site_status']; ?></h5>
<article class="auction_container">
	<div class="status_box">
	<div class="status_left">
		<div class="left">How is CoinCod doing today?
			<?php echo date("d-F-Y"); ?>
		</div>
	</div>
	<div class="status_right">
		<div class="right">
			<?php echo $status; ?>
		</div>
	</div>
</div>
	<br>
	<img class="bottom" src="includes/images/bottom/site_status.png" alt="communicating">
</article>
