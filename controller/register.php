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

if (isset($_POST['username'])) {
	$username = $_POST['username'];
} else {
	$username = '';
}

if(isset($_SESSION['email'])) {
	$emails = $_SESSION['email'];
	unset($_SESSION['email']);
} else if (isset($_POST['emails'])) {
	$emails = $_POST['emails'];
} else {
	$emails = '';
}

if (isset($_POST['firstname'])) {
	$fname = $_POST['firstname'];
} else {
	$fname = '';
}

if (isset($_POST['lastname'])) {
	$lname = $_POST['lastname'];
} else {
	$lname = '';
}

if (isset($_POST['datepicker'])) {
	$dob = $_POST['datepicker'];
} else {
	$dob = '';
}

if (isset($_POST['gender'])) {
	$gender = $_POST['gender'];
} else {
	$gender = 'M';
}

if (isset($_POST['add1'])) {
	$add1 = $_POST['add1'];
} else {
	$add1 = '';
}

if (isset($_POST['add2'])) {
	$add2 = $_POST['add2'];
} else {
	$add2 = '';
}

if (isset($_POST['city'])) {
	$city = $_POST['city'];
} else {
	$city = '';
}

if (isset($_POST['zip'])) {
	$zip = $_POST['zip'];
} else {
	$zip = '';
}

if (isset($_POST['country'])) {
	$country = $_POST['country'];
} else {
	$country = '';
}

if (isset($_POST['state'])) {
	$state = $_POST['state'];
} else {
	$state = '';
}

if (isset($_POST['phone'])) {
	$phone = $_POST['phone'];
} else {
	$phone = '';
}

if(isset($_POST["btnRegister"])){
	$username = mysql_real_escape_string($_POST['username']);
	$emails = $emails;
	$password = mysql_real_escape_string($_POST['passwords']);
	$confirm = mysql_real_escape_string($_POST['re_password']);
	$firstname = mysql_real_escape_string($_POST['firstname']);
	$lastname = mysql_real_escape_string($_POST['lastname']);
	$dob = mysql_real_escape_string($_POST['datepicker']);
	$gender = mysql_real_escape_string($_POST['gender']);
	$add1 = mysql_real_escape_string($_POST['add1']);
	$add2 = mysql_real_escape_string($_POST['add2']);
	$city = mysql_real_escape_string($_POST['city']);
	$zip = mysql_real_escape_string($_POST['zip']);
	$country = mysql_real_escape_string($_POST['country']);
	$state = mysql_real_escape_string($_POST['state']);
	$phone = mysql_real_escape_string($_POST['phone']);
	
	$error = array();
	
	if(verifyDuplicateCustomer($username, "username")) {
		$error['username'] = $lang['error_username_duplicate'];
	} else {
		if ((strlen($username) < 3) || (strlen($username) > 20)) {
			$error['username'] = $lang['error_username_valid'];
		}
	}
	
	if(verifyDuplicateCustomer($emails, "email")) {
		$error['email'] = $lang['error_email_duplicate'];
	} else {
		if(!filter_var($emails, FILTER_VALIDATE_EMAIL)) {
			$error['email'] = $lang['error_email_valid'];
		}
	}
	
	if ($password || (!isset($customer_id))) {
		if ((strlen($password) < 4) || (strlen($password) > 20)) {
			$error['password'] = $lang['error_password_valid'];
		}
		
		if ($password != $confirm) {
			$error['confirm'] = $lang['error_confirm'];
		}
	}
	
	if (empty($firstname)) {
		$error['fname'] = $lang['error_fname_empty'];
	}
	
	if (empty($lastname)) {
		$error['lname'] = $lang['error_lname_empty'];
	}
	
	if(empty($dob)){
		$error['dob'] = $lang['error_dob'];
	}
	
	if(empty($add1)){
		$error['add1'] = $lang['error_add1_empty'];
	}
	
	if(empty($city)){
		$error['city'] = $lang['error_city_empty'];
	}
	
	if(empty($zip)){
		$error['zip'] = $lang['error_zip_empty'];
	}
	
	if(empty($country)){
		$error['country'] = $lang['error_country_empty'];
	}
	
	if(empty($state)){
		$error['state'] = $lang['error_state_empty'];
	}
	
	if(empty($phone)){
		$error['phone'] = $lang['error_phone_empty'];
	}
	
	if(empty($error)){
		$code = sha1(uniqid(mt_rand(), true));
		$insert_data = array (
			'username'	=> $username,
			'email'		=> $emails,
			'password'	=> md5($password),
			'token'		=> 10,
			'status'	=> 0,
			'fcode'		=> $code,
			'fname'		=> $firstname,
			'lname'		=> $lastname,
			'dob'		=> $dob,
			'gender'	=> $gender,
			'add1'		=> $add1,
			'add2'		=> $add2,
			'city'		=> $city,
			'zip'		=> $zip,
			'country'	=> $country,
			'state'		=> $state,
			'phone'		=> preg_replace("/[^0-9]+/", "", $phone)
		);
		$insert_query = insertCustomer($insert_data);
		if($insert_query) {
			$subject = $lang['text_register_activation'];
			$body = "Dear " . $username . ", 
					<br><br/>
					Please Click link below to continue for register process. 
					<br/><br/>
					<a href='" . mainPageURL() . "activation/" . urlencode(base64_encode($emails)) . "/".$code.".html'>Activate</a>
					<br/><br/>
					Enjoy your awesome first auction experience with Coincod.
					<br /><br /> 
					Thank You! 
					<br /><br />
					NOTE:If you did not request for this email. Kindly ignore it.
					<br /><br />
					Best Regards,
					CoinCod Management Team";
			$data_mail = array(
				'to_name'	=> $username,
				'to_email'	=> $emails,
				'subject'	=> $subject,
				'body'		=> $body
			);
			sendMail($data_mail);
			$_SESSION['success'] = $lang['success_register'];
			header("location:login.html");
		} else {
			$_SESSION['error_warning'] = $lang['error_query'];
			header("location:register.html");
		}
	} else {
		$error_username = $error['username'];
		$error_email = $error['email'];
		$error_password = $error['password'];
		$error_confirm = $error['confirm'];
		$error_fname = $error['fname'];
		$error_lname = $error['lname'];
		$error_dob = $error['dob'];
		$error_add1 = $error['add1'];
		$error_city = $error['city'];
		$error_zip = $error['zip'];
		$error_country = $error['country'];
		$error_state = $error['state'];
		$error_phone = $error['phone'];
	}
}
?>
<h5><?php echo $lang['head_register']; ?></h5>
<article class="auction_container">
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php //if ($success) { ?>
		<!--<div class="success"><?php //echo $success; ?></div>-->
	<?php //} ?>
	<div class="registration">
		<article class="smallfont">
			All Fields are required to be filled.
		</article>
		<form action="" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
			<fieldset>
				<legend><?php echo $lang['text_user_ID']." ".$lang['text_and']." ".$lang['text_password']; ?></legend>
				<table>
					<tr>
						<td><?php echo $lang['text_username']; ?></td>
						<td><input type="text" name="username" value="<?php echo $username; ?>" class="text" id="username" size="33" maxlength="50" tabindex="10" required >
						<?php if ($error_username) { ?>
							<span class="error"><?php echo $error_username; ?></span>
						<?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $lang['text_email']; ?></td>
						<td><input type="text" name="emails" value="<?php echo $emails; ?>" class="text" id="emails" size="33" maxlength="50" tabindex="10" >
						<?php if ($error_email) { ?>
							<span class="error"><?php echo $error_email; ?></span>
						<?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $lang['text_password']; ?></td>
						<td><input type="password" name="passwords" value="" class="text" id="passwords" size="33" maxlength="50" tabindex="12" required>
						<?php if ($error_password) { ?>
							<span class="error"><?php echo $error_password; ?></span>
						<?php } ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_confirm']; ?></td>
						<td><input type="password" name="re_password" value="" class="text" id="re_password" size="33" maxlength="50" tabindex="13" required>
						<?php if ($error_confirm) { ?>
							<span class="error"><?php echo $error_confirm; ?></span>
						<?php } ?></td>
				</tr>
				</table>
			</fieldset>
		
			<fieldset>
				<legend><?php echo $lang['text_information']; ?></legend>
				<table>
					<tr>
						<td><?php echo $lang['text_fname']; ?></td>
						<td><input type="text" name="firstname" value="<?php echo $fname; ?>" class="text" id="firstname" size="33" maxlength="50" tabindex="1" required>
						<?php if ($error_fname) { ?>
							<span class="error"><?php echo $error_fname; ?></span>
						<?php } ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_lname']; ?></td>
						<td><input type="text" name="lastname" value="<?php echo $lname; ?>" class="text" id="lastname" size="33" maxlength="50" tabindex="2" required>
						<?php if ($error_lname) { ?>
							<span class="error"><?php echo $error_lname; ?></span>
						<?php } ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_dob']; ?></td>
						<td><input type="text" id="datepicker" name="datepicker" value="<?php echo $dob; ?>" required/>
						<?php if ($error_dob) { ?>
							<span class="error"><?php echo $error_dob; ?></span>
						<?php } ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_gender']; ?></td>
						<td><select name="gender">
							<?php if ($gender == "F") { ?>
								<option value="M"><?php echo $lang['text_male']; ?></option>
								<option value="F" selected="selected"><?php echo $lang['text_female']; ?></option>
							<?php } else if($gender == "M") { ?>
								<option value="M" selected="selected"><?php echo $lang['text_male']; ?></option>
								<option value="F"><?php echo $lang['text_female']; ?></option>
							<?php } ?>
						</select></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_add1']; ?></td>
						<td><input type="text" name="add1" value="<?php echo $add1; ?>" class="text" id="add1" size="33" maxlength="100" tabindex="5" required>
						<?php if ($error_add1) { ?>
							<span class="error"><?php echo $error_add1; ?></span>
						<?php } ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_add2']; ?></td>
						<td><input type="text" name="add2" value="<?php echo $add2; ?>" class="text" id="add2" size="33" maxlength="100" tabindex="5" required></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_city']; ?></td>
						<td><input type="text" name="city" value="<?php echo $city; ?>" class="text" id="city" size="33" maxlength="100" tabindex="6" required>
						<?php if ($error_city) { ?>
							<span class="error"><?php echo $error_city; ?></span>
						<?php } ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_zip']; ?></td>
						<td><input type="text" name="zip" value="<?php echo $zip; ?>" class="text" id="zip" size="33" maxlength="100" tabindex="8" required>
						<?php if ($error_zip) { ?>
							<span class="error"><?php echo $error_zip; ?></span>
						<?php } ?></td>
					</tr>
				
					<tr>
						<td><?php echo $lang['text_country']; ?></td>
						<td>
							<select name="country" id="country" onchange="updatecountry(this)" autofocus="autofocus" autocorrect="off" autocomplete="off" required >
							<option value="" selected="selected"><?php echo $lang['text_select']." ".$lang['text_country']; ?></option>
							<?php 
								$status = getCountryCode();
								foreach ($status as $value) {
									if($country == $value['country_id']) {
										echo '<option value="'.$value['country_id'].'" data-alternative-spellings="'.$value['iso_code_2'].'" selected="selected">'.$value['name'].'</option>';
									} else {
										echo '<option value="'.$value['country_id'].'" data-alternative-spellings="'.$value['iso_code_2'].'">'.$value['name'].'</option>';
									}
								}
							?>
							</select>
						<?php if ($error_country) { ?>
							<span class="error"><?php echo $error_country; ?></span>
						<?php  } ?></td>
					</tr>
						<?php echo "x".$state; ?>
					<tr>
						<td><?php echo $lang['text_state']; ?></td>
						<td><select name="state" id="state" required ></select>
						<?php if ($error_state) { ?>
							<span class="error"><?php echo $error_state; ?></span>
						<?php  } ?></td>
					</tr>
					<tr>
						<td><?php echo $lang['text_phone']; ?></td>
						<td><input type="text" name="phone" value="<?php echo $phone; ?>" placeholder="<?php echo $lang['text_phone']; ?>" autofocus >
						<?php if ($error_phone) { ?>
							<span class="error"><?php echo $error_phone; ?></span>
						<?php } ?></td>
					</tr>
				</table>
				<input type="submit" name="btnRegister" class="form_button" value="Submit" />                    
			</fieldset>
		</form>
	</div> <!--end div registration-->
	<div class="location">
		<img src="includes/images/bottom/location.png" alt="pointer">
	</div>
</article>
<script type="text/javascript">
	$(document).ready(function() {
		$("#datepicker").datepicker({ 
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true ,
			defaultDate: '2000-01-01'
		});
	});
</script>
<script type="text/javascript">
function updatecountry(element) {
	$.ajax({
		url: 'admin/controller/getState.php',
		type: 'post',
		dataType : 'json',
		data: 'country_id='+ element.value ,		
		success: function(json) {		
			html = '<option value=""><?php echo $lang['text_select']." ".$lang['text_state']; ?></option>';
						
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					if(json['zone'][i]['zone_id'] == '<?php echo $state; ?>') {
						html += '<option value="' + json['zone'][i]['zone_id'] + '" selected="selected" >' + json['zone'][i]['name'] + '</option>';
					} else {
						html += '<option value="' + json['zone'][i]['zone_id'] + '">' + json['zone'][i]['name'] + '</option>';
					}
				}
			} else {
				html += '<option value="0"><?php echo $lang['text_none']; ?></option>';
			}
			
			$('select[name=\'state\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
$('select[name$=\'country\']').trigger('change');
</script> 