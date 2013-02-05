<?php
	require '../config.php';
	
	function sendpassword($emails) {
		$password_check = mysql_query("SELECT * FROM user_account WHERE Email='$emails'");
		$passwords_check = mysql_num_rows($password_check);
		$row = mysql_fetch_array($password_check);
		$recover_password = $row["Password"];
		$recover_name = $row["Username"];
		if($passwords_check == 1) {
			$to = "$emails";
			$toname = $recover_name;
			$from = "hychan_89@hotmail.com";
			$fromname = "Customer Service";
			$subject = "Forgot Password";
			$messages = '<html>
							<body>
								This is your basic account information registered with CoinCod. 
								<br/><br/>
								E-mail Address: ' . $emails . ' <br />
								Password: ' . $recover_password . ' 
								<br/>
								Enjoy your awesome first auction experience with Coincod.
								<br /><br /> 
								Thank You! 
								<br /><br />
								NOTE:If you did not request for this email. Kindly ignore it.
								<br /><br />
								Best Regards,
								CoinCod Management Team
							</body>
					</html>';
				// end of message
			$headers = "From: $fromname <$from>\r\n";
			$headers .= "Content-type: text/html\r\n";
			$headers .= "Bcc: wilson@nexvend.com";
			$to = "$toname <$to>";

			$sentmail = mail($to,$subject,$messages,$headers);
			if($sentmail) {
				$result .= "Your password has been successfully sent to your email.";
			} else {
				$result .= "Sorry. The password failed to be sent. Please retry.";
			}
		} else {
			$result = "Sorry, this email cannot be found";
		}
		return $result;
	}
	
	if(isset($_POST["btnGet"])){
		$result = sendpassword($_POST['emails']);	
?>
	<div style="color:red;">
		<?php echo $result;?>
	</div>
<?php } ?>
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
					<input type="text" id="emails" name="emails" class="text"/>
					<script type="text/javascript">
						var emails = new LiveValidation('emails');
						emails.add(Validate.Presence)
						emails.add(Validate.Email )
					</script>
				</td>
			</tr>
			<tr>
				<td><input type="submit" name="btnGet" class="form_button" value="RESET PASSWORD" /></td> 
			</tr>
		</table>
	</section> <!--end div forgot-->
</form>