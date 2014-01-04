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
	'group'	=> 'email'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'mail_protocol') {		$protocol = $setting['value']; }
		if($setting['key'] == 'mail_address_name') {	$address_name = $setting['value']; }
		if($setting['key'] == 'mail_address') {			$address = $setting['value']; }
		if($setting['key'] == 'mail_smtp_host') {		$smtp_host = $setting['value']; }
		if($setting['key'] == 'mail_smtp_email_name') {	$smtp_email_name = $setting['value']; }
		if($setting['key'] == 'mail_smtp_email') {		$smtp_email = $setting['value']; }
		if($setting['key'] == 'mail_smtp_password') {	$smtp_password = $setting['value']; }
		if($setting['key'] == 'mail_smtp_port') {		$smtp_port = $setting['value']; }
		if($setting['key'] == 'mail_support') {			$mail_support = $setting['value']; }
		if($setting['key'] == 'mail_security') {		$mail_security = $setting['value']; }
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$mail_protocol = $_POST['mail_protocol'];
	$mail_address_name = $_POST['mail_address_name'];
	$mail_address = $_POST['mail_address'];
	$mail_smtp_host = $_POST['mail_smtp_host'];
	$mail_smtp_email_name = $_POST['mail_smtp_email_name'];
	$mail_smtp_email = $_POST['mail_smtp_email'];
	$mail_smtp_password = $_POST['mail_smtp_password'];
	$mail_smtp_port = $_POST['mail_smtp_port'];
	$mail_support = $_POST['mail_support'];
	$mail_security = $_POST['mail_security'];
	
	$error = array();

	if (empty($error)) {
		$data = array (
			array(
				group	=> "email",
				Key 	=> "mail_protocol",
				Value 	=> $mail_protocol
			),
			array(
				group	=> "email",
				Key 	=> "mail_address_name",
				Value 	=> $mail_address_name
			),
			array(
				group	=> "email",
				Key 	=> "mail_address",
				Value 	=> $mail_address
			),
			array(
				group	=> "email",
				Key 	=> "mail_smtp_host",
				Value 	=> $mail_smtp_host
			),
			array(
				group	=> "email",
				Key 	=> "mail_smtp_email_name",
				Value 	=> $mail_smtp_email_name
			),
			array(
				group	=> "email",
				Key 	=> "mail_smtp_email",
				Value 	=> $mail_smtp_email
			),
			array(
				group	=> "email",
				Key 	=> "mail_smtp_password",
				Value 	=> $mail_smtp_password
			),
			array(
				group	=> "email",
				Key 	=> "mail_smtp_port",
				Value 	=> $mail_smtp_port
			),
			array(
				group	=> "email",
				Key 	=> "mail_support",
				Value 	=> $mail_support
			),
			array(
				group	=> "email",
				Key 	=> "mail_security",
				Value 	=> $mail_security
			)
		);
		$query = updateSettings($data);
		if($query) {
			$_SESSION['success'] = $lang['success_edit'];
		} else {
			$_SESSION['error_warning'] = $lang['error_query'];
		}
		header('Location:email.html');
	} else {
	}
}
?>

<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="email.html"><?php echo $lang['text_email_setup']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_email_setup']; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<a onclick="location = 'home.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="email.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_email_protocol']; ?></td>
						<td>
							<select name="mail_protocol">
								<?php if ($protocol == 'MAIL') { ?>
									<option value="MAIL" selected="selected"><?php echo $lang['text_mail']; ?></option>
									<option value="SMTP"><?php echo $lang['text_smtp']; ?></option>
								<?php } else { ?>
									<option value="MAIL"><?php echo $lang['text_mail']; ?></option>
									<option value="SMTP" selected="selected"><?php echo $lang['text_smtp']; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_email_addressname']; ?></td>
						<td><input type="text" name="mail_address_name" value="<?php echo $address_name; ?>" placeholder="<?php echo $lang['entry_email_addressname']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_email_address']; ?></td>
						<td><input type="text" name="mail_address" value="<?php echo $address; ?>" placeholder="<?php echo $lang['entry_email_address']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_email_smtphost']; ?></td>
						<td><input type="text" name="mail_smtp_host" value="<?php echo $smtp_host; ?>" placeholder="<?php echo $lang['entry_email_smtphost']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_email_smtpemailname']; ?></td>
						<td><input type="text" name="mail_smtp_email_name" value="<?php echo $smtp_email_name; ?>" placeholder="<?php echo $lang['entry_email_smtpemailname']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_email_smtpemail']; ?></td>
						<td><input type="text" name="mail_smtp_email" value="<?php echo $smtp_email; ?>" placeholder="<?php echo $lang['entry_email_smtpemail']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_email_smtppassword']; ?></td>
						<td><input type="text" name="mail_smtp_password" value="<?php echo $smtp_password; ?>" placeholder="<?php echo $lang['entry_email_smtppassword']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_email_smtpport']; ?></td>
						<td><input type="text" name="mail_smtp_port" value="<?php echo $smtp_port; ?>" placeholder="<?php echo $lang['entry_email_smtpport']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_email_support']; ?></td>
						<td><input type="text" name="mail_support" value="<?php echo $mail_support; ?>" placeholder="<?php echo $lang['entry_email_support']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_email_security']; ?></td>
						<td><input type="text" name="mail_security" value="<?php echo $mail_security; ?>" placeholder="<?php echo $lang['entry_email_security']; ?>" autofocus required></td>
					</tr>
					
				</table>
			</form>
		</div>
	</div>
</div>