<?php
	ob_start();
	session_start();
	require_once '../config.php';
	require_once '../sql_function.php';
	
	$id = $_SESSION['user_id'];
	$email = $_SESSION['email'];
	$user_data = getMemberRecords($email);		
	$username = $user_data["username"];
	$token = $user_data["token"];
	$status = $user_data["status"];
	$category = $user_data["category"];
	$fname = $user_data["fname"];
	$lname = $user_data["lname"];
	$dob = $user_data["dob"];
	$gender = $user_data["gender"];
	$add1 = $user_data["add1"];
	$add2 = $user_data["add2"];
	$city = $user_data["city"];
	$state = $user_data["state"];
	$zip = $user_data["zip"];
	$country = $user_data["country"];
	$mobile = $user_data["mobile"];


$echo_error=NULL;	
if(isset($_POST["btnSave"])){
	$id = mysql_real_escape_string($_POST['userid']);
	$email = mysql_real_escape_string($_POST['emails']);
	$username = mysql_real_escape_string($_POST['username']);
	$token = mysql_real_escape_string($token);
	$status = mysql_real_escape_string($status);
	$category = mysql_real_escape_string($category);
	$fname = mysql_real_escape_string($_POST['firstname']);
	$lname = mysql_real_escape_string($_POST['lastname']);
	$dob = mysql_real_escape_string($_POST['datepicker']);
	$gender = mysql_real_escape_string($_POST['gender']);
	$add1 = mysql_real_escape_string($_POST['add1']);
	$add2 = mysql_real_escape_string($_POST['add2']);
	$city = mysql_real_escape_string($_POST['city']);
	$state = mysql_real_escape_string($_POST['state']);
	$zip = mysql_real_escape_string($_POST['zip']);
	$mobile = mysql_real_escape_string($_POST['mobile']);
	
	$error = NULL;
	if(empty($fname)){
		$error .= "<li>First Name is required";
	}
	if(empty($lname)){
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
		$update = updateUser($id, $email, $username, '2', '1', $token, $fname, $lname, $dob, $gender, $add1, $add2, $city, $state, $zip, $mobile);
		if($update) {
			$echo_error = ($update);
		} else {
			$echo_error = "<div style='color:#d50000;text-align:left;'>Mailer Error (CS): Failed updated. Please try to resend again!</div>";
		}
	}
}
?>

<?=$echo_error;?>
<form action="" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
	<fieldset width="700">
		<legend><font size="5"><div class="strong">ID and Password</div></legend>
		<table width="650" cellpadding="0" cellspacing="10">
			<tr>
				<td width="20%">
					User ID
				</td>
				<td width="80%">
					<?=$id?>
					<input type="hidden" name="userid" id="userid" value="<?=$id?>"/>
				</td>
			</tr>
				
			<tr>
				<td width="20%">
					Username
				</td>
				<td width="80%">
					<?=$username?>
					<input type="hidden" name="username" value="<?=$username?>">
				</td>
			</tr>
			
			<tr>
				<td width="25%">
					Email
				</td>
				<td width="75%">
                   	<?=$email?>
					<input type="hidden" name="emails" value="<?=$email?>" id="emails">
				</td>
			</tr>
			
			<tr>
				<td width="25%">
					Token
				</td>
				<td width="75%">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>
								<?=$token?>
								<input type="hidden" name="token" value="<?=$token?>" id="token">
							</td>
						</tr>
					</table>
				</td>
			</tr>								
		</table>
	</fieldset>

	<fieldset>
		<legend><font size="5"><div class="strong">Personal Information</div></legend>
		<table width="640" cellpadding="0" cellspacing="10">
			<tr>
				<td width="20%">
					First Name
		    	</td>
				<td width="80%">
					<input type="text" name="firstname" value="<?=$fname?>" class="text" id="firstname" size="33" maxlength="50" tabindex="1" required>
				</td>
			</tr>
			<tr>
				<td width="25%">
					Last Name
		    	</td>
				<td width="75%">
					<input type="text" name="lastname" value="<?=$lname?>" class="text" id="lastname" size="33" maxlength="50" tabindex="2" required>
				</td>
			</tr>
			<tr>
				<td width="25%">
					Date birth
				</td>
				<td width="75%">
					<input type="text" id="datepicker" name="datepicker" value="<?=$dob?>" required/>
				</td>
			</tr>
			<tr>
				<td width="25%">
					Gender
				</td>
				<td width="75%">
					<select id="gender" name="gender" size="1" tabindex="3">
						<option value="M" <?=(($gender == 'M') ? 'selected' : "")?> >Male</option>
						<option value="F" <?=(($gender == 'F') ? 'selected' : "")?> >Female</option>
					</select>				
				</td>
			</tr>
			<tr>
				<td width="25%">
					Address 1
				</td>
				<td width="75%">
					<input type="text" name="add1" value="<?=$add1?>" class="text" id="add1" size="33" maxlength="100" tabindex="5" required>
				</td>
			</tr>
			<tr>
				<td width="25%">
					Address 2
				</td>
				<td width="75%">
					<input type="text" name="add2" value="<?=$add2?>" class="text" id="add2" size="33" maxlength="100" tabindex="5" required>
				</td>
			</tr>
			<tr>
				<td width="25%">
					City
				</td>
				<td width="75%">
					<input type="text" name="city" value="<?=$city?>" class="text" id="city" size="33" maxlength="100" tabindex="6" required />                            
				</td>
			</tr>
			<tr>
				<td width="25%">
					State
				</td>
				<td width="75%">
					<select id="state" name="state" size="1" tabindex="7">
						<?php
						$state_list = getStateList();
						$totalState = getTotalState();
						for($i=0;$i<$totalState;$i++) {
							$select = ($state == $state_list[$i]["sid"]) ? 'selected' : '';
							echo '<option value="'.$state_list[$i]["sid"].'" '.$select.'>'.$state_list[$i]["state"].'</option>';
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td width="25%">
					Zip
				</td>
				<td width="75%">
					<input type="text" name="zip" value="<?=$zip?>" class="text" id="zip" size="33" maxlength="100" tabindex="8" required>
				</td>
			</tr>
			<tr>
				<td width="25%">
					Country
				</td>
				<td width="75%">
					Malaysia
				</td>
			</tr>
			<tr>
				<td width="25%">
					Mobile <smallfont>(+60XXXXXXXX)</smallfont>
				</td>
				<td width="75%">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>
								<input type="text" name="mobile" value="<?=$mobile?>" class="text" id="mobile" size="33" maxlength="100" tabindex="9" required>
							</td>
						</tr>
					</table>
				</td>
			</tr>
        </table>
		<input type="submit" name="btnSave" class="form_button" value="Save" />                    
	</fieldset>
</form>