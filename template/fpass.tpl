<?php
	ob_start();
	session_start();
	require_once '../config.php';
	require_once '../sql_function.php';
	
	if(isset($_POST["btnGet"])){
		$emails = mysql_real_escape_string($_POST['emails']);
		
		$error = NULL;
		if(empty($emails)){
			$error .= "<li>Email is required";
		}
		if(!filter_var($emails, FILTER_VALIDATE_EMAIL)){
			$error .= "<li>Invalid Email!";
		}
		
		if(!empty($error)){
			$echo_error = "<div style='text-align:left;color:#d50000;'><ul>".$error."</ul></div>";
		}else{
			if(sendpassword($emails)) {
				$echo_error = "Your password has been successfully sent to your email.";
			} else {
				$echo_error = "<div style='color:#d50000;text-align:left;'>Mailer Error (CS): Failed comment. Please try to resend again!</div>";
			}
		}	
	} 
?>
<?=$echo_error;?>
<form action="" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
	<section class="forgot">
		<table>
			<tr>
				<td>
					Please enter your email so that we can send you an email for you to reset your password:
				</td>
			</tr>
			<tr>
				<td>
					<div class="value">
						Email
					</div>
					<input type="text" id="emails" name="emails" class="text" required/>
				</td>
			</tr>
			<tr>
				<td><input type="submit" name="btnGet" class="form_button" value="RESET PASSWORD" /></td> 
			</tr>
		</table>
	</section> <!--end div forgot-->
</form>