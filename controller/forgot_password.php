<?php
if(isset($logged)){
	header("location:".mainPageURL());
}

if (isset($_POST['emails'])) {
	$emails = $_POST['emails'];
} else {
	$emails = '';
}

if(isset($_POST["btnGet"])){
	$emails = mysql_real_escape_string($_POST['emails']);
	
	if(verifyDuplicateCustomer($emails, "email")) {
		$code = sha1(uniqid(mt_rand(), true));
		$data_emails = array(
			'email'		=> $emails
		);
		$customer = getCustomerByEmail($data_emails);
		$edit_data = array (
			'customerId'	=> $customer['customer_id'],
			'fcode'			=> $code
		);
			
		$edit_query = editCustomer($edit_data);
		if($edit_query) {
			$subject = $lang['text_password_reset'];
			$body = "Dear " . $customer['username'] . ", 
					<br><br/>
					Please <a href='" . mainPageURL() . "resetpassword/" . base64_encode(urlencode($emails)) . "/" . $code . ".html'>Click Here</a> to reset your password.<br/><br/>
					If the above link does not work, you can paste the following address into your browser:<br/><br/>
					" . mainPageURL() . "resetpassword/" . base64_encode(urlencode($emails)) . "/" . $code . ".html<br/><br/>
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
				'to_email'	=> $emails,
				'subject'	=> $subject,
				'body'		=> $body
			);
			sendMail($data_mail);
			$_SESSION['success'] = $lang['success_mail_password'];
			header("location:login.html");
		} else {
			$error_warning = $lang['error_query'];
		}		
	} else {
		$error_warning = $lang['error_email_not_exist'];
	}
}

$title = $lang['head_forgot_password'];
?>
<h5><?php echo $lang['head_forgot_password']; ?></h5>
<article class="auction_container">
	<?php if (isset($error_warning)) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<form action="forgot_password.html" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
		<section class="forgot">
			<table>
				<tr>
					<td>Please enter your email so that we can send you an email for you to reset your password:</td>
				</tr>
				<tr>
					<td>
						<div class="value"><?php echo $lang['text_email']; ?></div>
						<input type="text" id="emails" name="emails" class="text" value="<?php echo $emails; ?>" required/>
					</td>
				</tr>
				<tr>
					<td><input type="submit" name="btnGet" class="form_button" value="<?php echo $lang['button_reset_password']; ?>" /></td> 
				</tr>
			</table>
		</section> <!--end div forgot-->
	</form>
</article>