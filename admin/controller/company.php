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
		if($setting['key'] == 'company_name') {		$Cname = $setting['value']; }
		if($setting['key'] == 'company_address') {	$Caddress = $setting['value']; }
		if($setting['key'] == 'company_phone') {	$Cphone = $setting['value']; }
		if($setting['key'] == 'company_fax') {		$Cfax = $setting['value']; }
		if($setting['key'] == 'company_email') {	$Cemail = $setting['value']; }
		if($setting['key'] == 'company_maps') {		$Cmaps = $setting['value']; }
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$company_name = $_POST['company_name'];
	$company_address = $_POST['company_address'];
	$company_phone = $_POST['company_phone'];
	$company_fax = $_POST['company_fax'];
	$company_email = $_POST['company_email'];
	$company_maps = $_POST['company_maps'];
	
	$error = array();

	if (empty($error)) {
		$data = array (
			array(
				group	=> "config",
				Key 	=> "company_name",
				Value 	=> $company_name
			),
			array(
				group	=> "config",
				Key 	=> "company_address",
				Value 	=> $company_address
			),
			array(
				group	=> "config",
				Key 	=> "company_phone",
				Value 	=> $company_phone
			),
			array(
				group	=> "config",
				Key 	=> "company_fax",
				Value 	=> $company_fax
			),
			array(
				group	=> "config",
				Key 	=> "company_email",
				Value 	=> $company_email
			),
			array(
				group	=> "config",
				Key 	=> "company_maps",
				Value 	=> $company_maps
			)
		);
		echo $query = updateSettings($data);
		if($query) {
			$_SESSION['success'] = $lang['success_edit'];
		} else {
			$_SESSION['error_warning'] = $lang['error_query'];
		}
		header('Location:company.html');
	} else {
	}
}
?>

<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="company.html"><?php echo $lang['text_company']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_company']; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<a onclick="location = 'home.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="company.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_company_name']; ?></td>
						<td><input type="text" name="company_name" value="<?php echo $Cname; ?>" placeholder="<?php echo $lang['entry_company_name']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_company_address']; ?></td>
						<td><textarea name="company_address" placeholder="<?php echo $lang['entry_company_address']; ?>" rows="5" cols="100px" autofocus required><?php echo $Caddress; ?></textarea></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_company_phone']; ?></td>
						<td><input type="text" name="company_phone" value="<?php echo $Cphone; ?>" placeholder="<?php echo $lang['entry_company_phone']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_company_fax']; ?></td>
						<td><input type="text" name="company_fax" value="<?php echo $Cfax; ?>" placeholder="<?php echo $lang['entry_company_fax']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_company_email']; ?></td>
						<td><input type="text" name="company_email" value="<?php echo $Cemail; ?>" placeholder="<?php echo $lang['entry_company_email']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_company_maps']; ?></td>
						<td><textarea name="company_maps" placeholder="<?php echo $lang['entry_company_maps']; ?>" rows="5" cols="100px" autofocus required><?php echo $Cmaps; ?></textarea></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>