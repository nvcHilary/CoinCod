<?php
//function declare at header.php
//$customer = getCustomerById($logged);
$email = $customer['email'];
                                                                                                                                                                                                                                 
if (isset($_POST['firstname'])) {
	$fname = $_POST['firstname'];
} else if ($customer['fname']) {
	$fname = $customer['fname'];
} else {
	$fname = '';
}

if (isset($_POST['lastname'])) {
	$lname = $_POST['lastname'];
} else if ($customer['lname']) {
	$lname = $customer['lname'];
} else {
	$lname = '';
}

if (isset($_POST['datepicker'])) {
	$dob = $_POST['datepicker'];
} else if ($customer['dob']) {
	$dob = $customer['dob'];
} else {
	$dob = '';
}

if (isset($_POST['gender'])) {
	$gender = $_POST['gender'];
} else if ($customer['gender']) {
	$gender = $customer['gender'];
} else {
	$gender = 'M';
}

if (isset($_POST['add1'])) {
	$add1 = $_POST['add1'];
} else if ($customer['add1']) {
	$add1 = $customer['add1'];
} else {
	$add1 = '';
}

if (isset($_POST['add2'])) {
	$add2 = $_POST['add2'];
} else if ($customer['add2']) {
	$add2 = $customer['add2'];
} else {
	$add2 = '';
}

if (isset($_POST['city'])) {
	$city = $_POST['city'];
} else if ($customer['city']) {
	$city = $customer['city'];
} else {
	$city = '';
}

if (isset($_POST['zip'])) {
	$zip = $_POST['zip'];
} else if ($customer['zip']) {
	$zip = $customer['zip'];
} else {
	$zip = '';
}

if (isset($_POST['country'])) {
	$country = $_POST['country'];
} else if ($customer['country']) {
	$country = $customer['country'];
} else {
	$country = '';
}

if (isset($_POST['state'])) {
	$state = $_POST['state'];
} else if ($customer['state']) {
	$state = $customer['state'];
} else {
	$state = '';
}

if (isset($_POST['phone'])) {
	$phone = $_POST['phone'];
} else if ($customer['phone']) {
	$phone = $customer['phone'];
} else {
	$phone = '';
}

if(isset($_POST["btnSave"])){
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
	
//	if(verifyDuplicateCustomer($username, "username")) {
//		$error['username'] = $lang['error_username_duplicate'];
//	} else {
//		if ((strlen($username) < 3) || (strlen($username) > 20)) {
//			$error['username'] = $lang['error_username_valid'];
//		}
//	}
	
//	if(verifyDuplicateCustomer($emails, "email")) {
//		$error['email'] = $lang['error_email_duplicate'];
//	} else {
//		if(!filter_var($emails, FILTER_VALIDATE_EMAIL)) {
//			$error['email'] = $lang['error_email_valid'];
//		}
//	}
	
//	if ($password || (!isset($customer_id))) {
//		if ((strlen($password) < 4) || (strlen($password) > 20)) {
//			$error['password'] = $lang['error_password_valid'];
//		}
		
//		if ($password != $confirm) {
//			$error['confirm'] = $lang['error_confirm'];
//		}
//	}

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
	
	if(empty($phone)){
		$error['phone'] = $lang['error_phone_empty'];
	}
	
	if(empty($error)){
		$edit_data = array (
			'customerId'	=> $logged,
			'fname'			=> $fname,
			'lname'			=> $lname,
			'dob'			=> $dob,
			'gender'		=> $gender,
			'add1'			=> $add1,
			'add2'			=> $add2,
			'city'			=> $city,
			'zip'			=> $zip,
			'country'		=> $country,
			'state'			=> $state,
			'phone'			=> preg_replace("/[^0-9]+/", "", $phone),
			'modify_by'		=> "c".$logged
		);
			
		$edit_query = editCustomer($edit_data);
		if($edit_query) {
			$success = $lang['success_edit'];
		} else {
			$error_warning = $lang['error_query'];
		}
	} else {
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

<h5><?php echo $lang['head_user_profile']; ?></h5>
<article class="auction_container">
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="registration">
		<article class="smallfont">
			All Fields are required to be filled.
		</article>
		<form action="" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
			<fieldset>
				<legend><?php echo $lang['text_user_ID']." ".$lang['text_and']." ".$lang['text_password']; ?></legend>
				<table>
					<input type="hidden" name="userid" id="userid" value="<?php echo $logged; ?>"/>
					<tr>
						<td><?php echo $lang['text_user_ID']; ?></td>
						<td><?php echo $logged; ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_username']; ?></td>
						<td><?php echo $username; ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_email']; ?></td>
						<td><?php echo $email; ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_token']; ?></td>
						<td><?php echo $token; ?></td>
					</tr>
				</table>
			</fieldset>
		
			<fieldset>
				<legend><?php echo $lang['text_information']; ?></legend>
				<table>
					<tr>
						<td><?php echo $lang['text_fname']; ?></td>
						<td><input type="text" name="firstname" value="<?php echo $fname; ?>" class="text" id="firstname" size="33" maxlength="50" tabindex="1" >
						<?php if ($error_fname) { ?>
							<span class="error"><?php echo $error_fname; ?></span>
						<?php } ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_lname']; ?></td>
						<td><input type="text" name="lastname" value="<?php echo $lname; ?>" class="text" id="lastname" size="33" maxlength="50" tabindex="2" >
						<?php if ($error_lname) { ?>
							<span class="error"><?php echo $error_lname; ?></span>
						<?php } ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_dob']; ?></td>
						<td><input type="text" id="datepicker" name="datepicker" value="<?php echo $dob; ?>" />
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
						<td><input type="text" name="add1" value="<?php echo $add1; ?>" class="text" id="add1" size="33" maxlength="100" tabindex="5" >
						<?php if ($error_add1) { ?>
							<span class="error"><?php echo $error_add1; ?></span>
						<?php } ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_add2']; ?></td>
						<td><input type="text" name="add2" value="<?php echo $add2; ?>" class="text" id="add2" size="33" maxlength="100" tabindex="5" ></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_city']; ?></td>
						<td><input type="text" name="city" value="<?php echo $city; ?>" class="text" id="city" size="33" maxlength="100" tabindex="6" >
						<?php if ($error_city) { ?>
							<span class="error"><?php echo $error_city; ?></span>
						<?php } ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_zip']; ?></td>
						<td><input type="text" name="zip" value="<?php echo $zip; ?>" class="text" id="zip" size="33" maxlength="100" tabindex="8" >
						<?php if ($error_zip) { ?>
							<span class="error"><?php echo $error_zip; ?></span>
						<?php } ?></td>
					</tr>
				
					<tr>
						<td><?php echo $lang['text_country']; ?></td>
						<td>
							<select name="country" id="country" onchange="updatecountry(this)" autofocus="autofocus" autocorrect="off" autocomplete="off" >
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
						
					<tr>
						<td><?php echo $lang['text_state']; ?></td>
						<td><select name="state" id="state" ></select>
						<?php if ($error_state) { ?>
							<span class="error"><?php echo $error_state; ?></span>
						<?php  } ?></td>
					</tr>
					
					<tr>
						<td><?php echo $lang['text_phone']; ?></td>
						<td><input type="text" name="phone" value="<?php echo $phone; ?>" placeholder="<?php echo $lang['text_phone']; ?>" autofocus>
						<?php if ($error_phone) { ?>
							<span class="error"><?php echo $error_phone; ?></span>
						<?php } ?></td>
					</tr>
				</table>
				<input type="submit" name="btnSave" class="form_button" value="<?php echo $lang['button_save']; ?>" /> 
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