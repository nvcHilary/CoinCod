<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'Settings')) {
	header('Location:permission.html');
}

if (isset($_SESSION['error_warning'])) {
	$error_warning = $_SESSION['error_warning'];
	unset($_SESSION['error_warning']);
} else {
	$error_warning = '';
}

if (isset($_SESSION['success'])) {
	$success = $_SESSION['success'];
	unset($_SESSION['success']);
} else {
	$success = '';
}

$data_setting = array(
	'group'	=> 'social'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'fbshare') {				$fbshare = $setting['value']; }
		if($setting['key'] == 'fbaccount') {			$fbaccount = $setting['value']; }
		if($setting['key'] == 'twitteraccount') {		$twitteraccount = $setting['value']; }
		if($setting['key'] == 'googleaccount') {		$googleaccount = $setting['value']; }
		if($setting['key'] == 'pinterestaccount') {		$pinterestaccount = $setting['value']; }
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$fbshare = $_POST['fbshare'];
	$fbaccount = $_POST['fbaccount'];
	$twitteraccount = $_POST['twitteraccount'];
	$googleaccount = $_POST['googleaccount'];
	$pinterestaccount = $_POST['pinterestaccount'];
	
	$error = array();

	if (empty($error)) {
		$data = array (
			array(
				group	=> "social",
				Key 	=> "fbshare",
				Value 	=> $fbshare
			),
			array(
				group	=> "social",
				Key 	=> "fbaccount",
				Value 	=> $fbaccount
			),
			array(
				group	=> "social",
				Key 	=> "twitteraccount",
				Value 	=> $twitteraccount
			),
			array(
				group	=> "social",
				Key 	=> "googleaccount",
				Value 	=> $googleaccount
			),
			array(
				group	=> "social",
				Key 	=> "pinterestaccount",
				Value 	=> $pinterestaccount
			)
		);
		echo $query = updateSettings($data);
		if($query) {
			$_SESSION['success'] = $lang['success_edit'];
		} else {
			$_SESSION['error_warning'] = $lang['error_query'];
		}
		header('Location:social.html');
	} else {
	}
}
?>

<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="social.html"><?php echo $lang['text_social_network']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_social_network']; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<a onclick="location = 'home.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="social.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_fb_share_api']; ?></td>
						<td><input type="text" name="fbshare" value="<?php echo $fbshare; ?>" placeholder="<?php echo $lang['entry_fb_share_api']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_fb_account']; ?></td>
						<td><input type="text" name="fbaccount" value="<?php echo $fbaccount; ?>" placeholder="<?php echo $lang['entry_fb_account']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_twitter_account']; ?></td>
						<td><input type="text" name="twitteraccount" value="<?php echo $twitteraccount; ?>" placeholder="<?php echo $lang['entry_twitter_account']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_google_account']; ?></td>
						<td><input type="text" name="googleaccount" value="<?php echo $googleaccount; ?>" placeholder="<?php echo $lang['entry_google_account']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_pinterest_account']; ?></td>
						<td><input type="text" name="pinterestaccount" value="<?php echo $pinterestaccount; ?>" placeholder="<?php echo $lang['entry_pinterest_account']; ?>" autofocus required></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>