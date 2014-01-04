<?php
if(isset($logged)){
	header("location:".mainPageURL());
}

$encry_email = $_GET['email'];
$encry_code = $_GET['code'];

$email = urldecode(base64_decode($encry_email));
$code = substr($encry_code, 0, 10);

$data_emails = array(
	'email'		=> $email
);
$customer = getCustomerByEmail($data_emails);

if ( ($customer['fcode'] != $code) || ($email == "") || ($code == "") ) {
	header('Location:'.mainPageURL().'404.html');
}

if (isset($_POST['password'])) {
	$password = $_POST['password'];
} else {
	$password = '';
}

if (isset($_POST['confirm'])) {
	$confirm = $_POST['confirm'];
} else {
	$confirm = '';
}

if(isset($_POST["btnReset"])){
	$password = mysql_real_escape_string($_POST["password"]);
	$confirm = mysql_real_escape_string($_POST["confirm"]);
	
	$error = array();
	if ((strlen($password) < 4) || (strlen($password) > 20)) {
		$error['password'] = $lang['error_password_valid'];
	}
		
	if ($password != $confirm) {
		$error['confirm'] = $lang['error_confirm'];
	}
	
	if(empty($error)){
		$edit_data = array (
			'customerId'	=> $customer['customer_id'],
			'password'		=> md5($password),
			'fcode'			=> "0"
		);
			
		$edit_query = editCustomer($edit_data);
		if($edit_query) {
			$subject = $lang['success_reset_password'];
			$body = "Dear " . $customer['username'] . ", 
					<br><br/>
					Your Password has been updated.<br/><br/>
					<br/>
					Enjoy your awesome first auction experience with " . $lang['text_company_name'] . ".
					<br /><br /> 
					Thank You! 
					<br /><br />
					NOTE:If you did not request for this email. Kindly ignore it.
					<br /><br />
					Best Regards,
					" . $lang['text_company_name'] ." Management Team";
			$data_mail = array(
				'to_name'	=> $customer['username'],
				'to_email'	=> $customer['email'],
				'subject'	=> $subject,
				'body'		=> $body
			);
			sendMail($data_mail);
			$_SESSION['success'] = $lang['success_reset_password'];
			header("location:" . mainPageURL() . "login.html");
		}
	} else {
		$error_password = $error['password'];
		$error_confirm = $error['confirm'];
	}
}
?>
<h5><?php echo $lang['head_reset_password']; ?></h5>
<article class="auction_container">
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<form action="resetpassword/<?php echo $encry_email; ?>/<?php echo $encry_code; ?>.html" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
		<section class="forgot">
			<table>
				<tr>
					<td>Please enter your New Password:</td>
				</tr>
				<tr>
					<td>
						<div class="value"><?php echo $lang['text_password']; ?></div>
						<input type="password" id="password" name="password" class="text" value="<?php echo $password; ?>" required/>
						<?php if ($error_password) { ?>
							<span class="error"><?php echo $error_password; ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>
						<div class="value"><?php echo $lang['text_confirm']; ?></div>
						<input type="password" id="confirm" name="confirm" class="text" value="<?php echo $confirm; ?>" required/>
						<?php if ($error_confirm) { ?>
							<span class="error"><?php echo $error_confirm; ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><input type="submit" name="btnReset" class="form_button" value="<?php echo $lang['button_reset_password']; ?>" /></td> 
				</tr>
			</table>
		</section> <!--end div forgot-->
	</form>
</article>
	