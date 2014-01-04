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
	'group'	=> 'config'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'status') {	$status = $setting['value']; }
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$status = $_POST['status'];
	
	$error = array();

	if (empty($error)) {
		$data = array (
			array(
				group	=> "config",
				Key 	=> "status",
				Value 	=> $status
			)
		);
		$query = updateSettings($data);
		if($query) {
			$_SESSION['success'] = $lang['success_edit'];
		} else {
			$_SESSION['error_warning'] = $lang['error_query'];
		}
		header('Location:status.html');
	} else {
	}
}
?>

<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="status.html"><?php echo $lang['text_status']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_status']; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<a onclick="location = 'home.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="status.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_website_status']; ?></td>
						<td><input type="text" name="status" value="<?php echo $status; ?>" placeholder="<?php echo $lang['entry_website_status']; ?>" autofocus required></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>