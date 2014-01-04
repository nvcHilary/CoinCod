<?php
$data_setting = array(
	'group'	=> 'config'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'company_name') {		$Cname = $setting['value']; 	}
		if($setting['key'] == 'company_address') {	$Caddress = $setting['value']; 	}
		if($setting['key'] == 'company_phone') {	$Cphone = $setting['value']; 	}
		if($setting['key'] == 'company_fax') {		$Cfax = $setting['value']; 	}
		if($setting['key'] == 'company_email') {	$Cemail = $setting['value']; 	}
		if($setting['key'] == 'company_maps') {		$Cmaps = $setting['value']; 	}
	}
}
?>
<h5><?php echo $lang['head_location']; ?></h5>
<article class="auction_container">
	<!--Google Map Display-->
	<?php echo $Cmaps; ?>
	<!--Display the Company address-->
	<address>
		<ul>
			<li>Address: </li>
			<li><?php echo $Cname; ?> </li>
			<li><?php echo $Caddress; ?></li> 
			<?php if($Cphone != "") { ?><li><?php echo $Cphone; ?></li><?php } ?>
			<?php if($Cfax != "") { ?><li><?php echo $Cfax; ?></li><?php } ?>
			<?php if($Cemail != "") { ?><li><?php echo $Cemail; ?></li><?php } ?>
		</ul>
	</address>
	<br/>
	<div class="location">
		<img class="bottom" src="includes/images/bottom/location.png" alt="pointer">
	<div>
</article>