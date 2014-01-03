<?php
if(isset($logged)){
	header("location:".mainPageURL());
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

if (isset($_POST['email'])) {
	$emails = $_POST['email'];
} else {
	$emails = '';
}

if (isset($_POST['password'])) {
	$password = $_POST['password'];
} else {
	$password = '';
}

if(isset($_POST["btnLogin"])){
	$email = mysql_real_escape_string($_POST["email"]);
	$password = mysql_real_escape_string($_POST["password"]);
	
	$data_login = array(
		'email'		=> $email,
		'password'	=> md5($password)
	);
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error['email'] = $lang['error_email_valid'];
	}
	
	if(empty($error)){
		if(!verifyLoginCustomer($data_login)) {
			$error_warning = $lang['error_failed_login'];
		} else {
			$customer_data = getCustomerByEmail($data_login);
			if($customer_data['status'] == 0) {
				$error_warning = $lang['error_failed_activation'];
			} else {
				$success = $lang['success_login'];
				$_SESSION['coin_id'] = $customer_data['customer_id'];
				$_SESSION['start'] = time(); 
				$_SESSION['expire'] = $_SESSION['start'] + (1 * 60) ;
				header('Location:'.mainPageURL());
			}
		}
	} else {
		$error_email = $error['email'];
	}
}
?>
<h5><?php echo $lang['head_login']; ?></h5>
<article class="auction_container">
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<form action="login.html" enctype="multipart/form-data" name="myForm" id="myLoginForm" method="post">
		<table cellpadding="0" cellspacing="10" class="login_page">
			<tr>
				<td class="value"><?php echo $lang['text_email']; ?></td>
			</tr>
			<tr>
				<td><input type="text" name="email" value="<?php echo $emails; ?>" class="text" id="email"  placeholder="<?php echo $lang['text_email']; ?>" size="33" maxlength="50" tabindex="10" required >
				<?php if ($error_email) { ?>
					<span class="error"><?php echo $error_email; ?></span>
				<?php } ?></td>
			</tr>
			<tr>
				<td class="value"><?php echo $lang['text_password']; ?></td>
			</tr>
			<tr>
				<td><input type="password" name="password" value="<?php echo $password; ?>" class="text" id="password" placeholder="<?php echo $lang['text_password']; ?>" size="33" maxlength="50" tabindex="12" required ></td>
			</tr>
			<tr>
				<td><input name="btnLogin" type="submit" class="form_button" value="<?php echo $lang['button_login']; ?>"></td>
			</tr>
		</table> 
	</form>
</article>