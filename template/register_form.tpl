<?php
	ob_start();
	require_once '../config.php';
	require_once '../sql_function.php';
	
	if(!isset($_GET['email'])) {
//		header("location:".mainPageURL());	
	}
	
	$email_reg = base64_decode(urldecode($_GET["email"]));
	
	$echo_error=NULL;
	if(isset($_POST["btnRegister"])){
		$emails = $email_reg;
		$username = mysql_real_escape_string($_POST['username']);
		$passwords = mysql_real_escape_string($_POST['passwords']);
		$re_password = mysql_real_escape_string($_POST['re_password']);
		$firstname = mysql_real_escape_string($_POST['firstname']);
		$lastname = mysql_real_escape_string($_POST['lastname']);
		$dob = mysql_real_escape_string($_POST['datepicker']);
		$gender = mysql_real_escape_string($_POST['gender']);
		$add1 = mysql_real_escape_string($_POST['add1']);
		$add2 = mysql_real_escape_string($_POST['add2']);
		$city = mysql_real_escape_string($_POST['city']);
		$state = mysql_real_escape_string($_POST['state']);
		$zip = mysql_real_escape_string($_POST['zip']);
		$country = "Malaysia";
		$mobile = mysql_real_escape_string($_POST['mobile']);	
		
		$error = NULL;
		if(empty($username)){
			$error .= "<li>Username is required";
		}
		if((empty($password)) || (empty($re_password))){
			$error .= "<li>Password is required";
		}
		if($passwords != $re_password){
			$error .= "<li>Password not match";
		}
		if(empty($firstname)){
			$error .= "<li>First Name is required";
		}
		if(empty($lastname)){
			$error .= "<li>Last Name is required";
		}
		if(empty($gender)){
			$error .= "<li>Gender is required";
		}
		if((empty($add1)) || (empty($add2))){
			$error .= "<li>Address is required";
		}
		if(empty($city)){
			$error .= "<li>City is required";
		}
		if(empty($state)){
			$error .= "<li>State is required";
		}
		if(empty($zip)){
			$error .= "<li>Zip is required";
		}
		if(empty($mobile)){
			$error .= "<li>Mobile Number is required";
		}
	
		if(!empty($error)){
			$echo_error = "<div style='text-align:left;color:#d50000;'><ul>".$error."</ul></div>";
		}else{
			$result = registeruser('2', $emails, $username, $passwords, $re_password, '1', '10', $firstname, $lastname, $dob, $gender, $add1, $add2, $city, $state, $zip, $country, $mobile);
			if($result) {
				header("location:".mainPageURL()."/registration/register_msg.php?action=4");
			} else {
				$echo_error = "<div style='color:#d50000;text-align:left;'>Mailer Error (CS): Failed register. Please try to resend again!</div>";
			}
		}
	}
?>

<?=$echo_error;?>
<div class="registration">
	<article class="smallfont">
		All Fields are required to be filled.
	</article>
	<form action="" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
		<fieldset>
			<legend>ID and Password</legend>
			<table>
				<tr>
					<td>
						Username
					</td>
					<td>
						<input type="text" name="username" value="" class="text" id="username" size="33" maxlength="50" tabindex="10" required >
					</td>
				</tr>
					
				 <tr>
					<td>
						Email
					</td>
					<td>
						<input type="text" name="emails" value="<?=$email_reg?>" class="text" id="emails" size="33" maxlength="50" tabindex="10" disabled=disabled>
					</td>
				</tr>

				<tr>
					<td>
						Password
					</td>
					<td>
						<input type="password" name="passwords" value="" class="text" id="passwords" size="33" maxlength="50" tabindex="12" required>
					</td>
				</tr>

				<tr>
					<td>
						Confirm password
					</td>
					<td>
						<input type="password" name="re_password" value="" class="text" id="re_password" size="33" maxlength="50" tabindex="13" required>
					</td>
				</tr>										
			</table>
		</fieldset>
					
		<fieldset>
			<legend>Personal Information</legend>
			 <table>
				<tr>
					<td>
						First Name
					</td>
					<td>
						<input type="text" name="firstname" value="" class="text" id="firstname" size="33" maxlength="50" tabindex="1" required>
					</td>
				</tr>

				<tr>
					<td>
						Last Name
					</td>
					<td>
						<input type="text" name="lastname" value="" class="text" id="lastname" size="33" maxlength="50" tabindex="2" required>
					</td>
				</tr>

				<tr>
					<td>
						Date birth
					</td>
					<td>
						<input type="text" id="datepicker" name="datepicker" value="" required/>
					</td>
				</tr>

				<tr>
					<td>
						Gender
					</td>
					<td>
						<div class="gender">
							<select id="gender" name="gender" size="1" tabindex="3" required>
								<option value="">--</option>
								<option value="M">Male</option>
								<option value="F">Female</option>
							</select>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						Address 1
					</td>
					<td>
						<input type="text" name="add1" value="" class="text" id="add1" size="33" maxlength="100" tabindex="5" required>
					</td>
				</tr>

				<tr>
					<td>
						Address 2
					</td>
					<td>
						<input type="text" name="add2" value="" class="text" id="add2" size="33" maxlength="100" tabindex="5" required>
					</td>
				</tr>

				<tr>
					<td>
						City
					</td>
					<td>
						<input type="text" name="city" value="" class="text" id="city" size="33" maxlength="100" tabindex="6" required>
					</td>
				</tr>

				<tr>
					<td>
						State
					</td>
					<td>
						<div class="state">
							<select id="state" name="state" size="1" tabindex="7">
								<?php
								$state_list = getStateList();
								$totalState = getTotalState();
								for($i=0;$i<$totalState;$i++) {
									echo '<option value="'.$state_list[$i]["sid"].'">'.$state_list[$i]["state"].'</option>';
								}
								?>
							</select>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						Zip
					</td>
					<td>
						<input type="text" name="zip" value="" class="text" id="zip" size="33" maxlength="100" tabindex="8" required>
					</td>
				</tr>

				<tr>
					<td>
						Country
					</td>
					<td>
						Malaysia
					</td>
				</tr>

				<tr>
					<td>
						Mobile <smallfont>(+60XXXXXXXX)</smallfont>
					</td>
					<td>
						<input type="text" name="mobile" value="" class="text" id="mobile" size="33" maxlength="100" tabindex="9" required>
					</td>
				</tr>
			</table>
			<input type="submit" name="btnRegister" class="form_button" value="Submit" />                    
		</fieldset>
	</form>
</div> <!--end div registration-->