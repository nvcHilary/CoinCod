<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'customer_form')) {
	header('Location:permission.html');
}

if(isset($_GET['customer_id'])) { 
	$customer_id = $_GET['customer_id'];
}
	
if (isset($customer_id) && ($_SERVER['REQUEST_METHOD'] != 'POST')) {
	$customer_info = getCustomerById($customer_id);
}

if (isset($_POST['username'])) {
	$username = $_POST['username'];
} elseif (!empty($customer_info)) {
	$username = $customer_info['username'];
} else {
	$username = '';
}

if (isset($_POST['email'])) {
	$email = $_POST['email'];
} elseif (!empty($customer_info)) {
	$email = $customer_info['email'];
} else {
	$email = '';
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

if (isset($_POST['token'])) {
	$token = $_POST['token'];
} elseif (!empty($customer_info)) {
	$token = $customer_info['token'];
} else {
	$token = '';
}

if (isset($_POST['status'])) {
	$status = $_POST['status'];
} elseif (!empty($customer_info)) {
	$status = $customer_info['status'];
} else {
	$status = 0;
}

if (isset($_POST['fname'])) {
	$fname = $_POST['fname'];
} elseif (!empty($customer_info)) {
	$fname = $customer_info['fname'];
} else {
	$fname = '';
}

if (isset($_POST['lname'])) {
	$lname = $_POST['lname'];
} elseif (!empty($customer_info)) {
	$lname = $customer_info['lname'];
} else {
	$lname = '';
}

if (isset($_POST['datepicker'])) {
	$dob = $_POST['datepicker'];
} elseif (!empty($customer_info)) {
	$dob = $customer_info['dob'];
} else {
	$dob = '';
}

if (isset($_POST['gender'])) {
	$gender = $_POST['gender'];
} elseif (!empty($customer_info)) {
	$gender = $customer_info['gender'];
} else {
	$gender = 'M';
}

if (isset($_POST['add1'])) {
	$add1 = $_POST['add1'];
} elseif (!empty($customer_info)) {
	$add1 = $customer_info['add1'];
} else {
	$add1 = '';
}

if (isset($_POST['add2'])) {
	$add2 = $_POST['add2'];
} elseif (!empty($customer_info)) {
	$add2 = $customer_info['add2'];
} else {
	$add2 = '';
}

if (isset($_POST['city'])) {
	$city = $_POST['city'];
} elseif (!empty($customer_info)) {
	$city = $customer_info['city'];
} else {
	$city = '';
}

if (isset($_POST['zip'])) {
	$zip = $_POST['zip'];
} elseif (!empty($customer_info)) {
	$zip = $customer_info['zip'];
} else {
	$zip = '';
}

if (isset($_POST['country'])) {
	$country = $_POST['country'];
} elseif (!empty($customer_info)) {
	$country = $customer_info['country'];
} else {
	$country = '';
}

if (isset($_POST['state'])) {
	$state = $_POST['state'];
} elseif (!empty($customer_info)) {
	$state = $customer_info['state'];
} else {
	$state = '';
}

if (isset($_POST['phone'])) {
	$phone = $_POST['phone'];
} elseif (!empty($customer_info)) {
	$phone = $customer_info['phone'];
} else {
	$phone = '';
}

$path = "customer_form.html";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$action = $_POST['action'];
	$cid = $_POST['customer_id'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirm = $_POST['confirm'];
	$token = $_POST['token'];
	$status = $_POST['status'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$dob = $_POST['datepicker'];
	$gender = $_POST['gender'];
	$add1 = $_POST['add1'];
	$add2 = $_POST['add2'];
	$city = $_POST['city'];
	$zip = $_POST['zip'];
	$country = $_POST['country'];
	$state = $_POST['state'];
	$phone = $_POST['phone'];
	
	$error = array();
	
	if($action == "insert") {
		if(verifyDuplicateCustomer($username, "username")) {
			$error['username'] = $lang['error_username_duplicate'];
		} else {
			if ((strlen($username) < 3) || (strlen($username) > 20)) {
				$error['username'] = $lang['error_username_valid'];
			}
		}
	
		if(verifyDuplicateCustomer($email, "email")) {
			$error['email'] = $lang['error_email_duplicate'];
		} else {
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error['email'] = $lang['error_email_valid'];
			}
		}
	}
	
	if( ($action == "insert") || (($action == "update") && ($password != NULL)) ){
		if ($password || (!isset($customer_id))) {
			if ((strlen($password) < 4) || (strlen($password) > 20)) {
				$error['password'] = $lang['error_password_valid'];
			}
		
			if ($password != $confirm) {
				$error['confirm'] = $lang['error_confirm'];
			}
		}
	}
	
	if (empty($fname)) {
		$error['fname'] = $lang['error_fname_empty'];
	}
	
	if (empty($lname)) {
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
	
	//if(empty($state)){
	//	$error['state'] = $lang['error_state_empty'];
	//}
	
	if(empty($phone)){
		$error['phone'] = $lang['error_phone_empty'];
	}

	if (empty($error)) {
		if($action == "insert") {
			$insert_data = array (
				'username'	=> $username,
				'email'		=> $email,
				'password'	=> md5($password),
				'token'		=> $token,
				'status'	=> $status,
				'fname'		=> $fname,
				'lname'		=> $lname,
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
				$_SESSION['success'] = $lang['success_insert'];
			} else {
				$_SESSION['error_warning'] = $lang['error_query'];
			}
		} else if ($action == "update") {
			$edit_data = array (
				'customerId'	=> $cid,
				'username'		=> $username,
				'email'			=> $email,
				'password'		=> md5($password),
				'token'			=> $token,
				'status'		=> $status,
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
				'modify_by'		=> "a".$logged
				
			);
			
			echo $edit_query = editCustomer($edit_data);
			if($edit_query) {
				$_SESSION['success'] = $lang['success_edit'];
			} else {
				$_SESSION['error_warning'] = $lang['error_query'];
			}
		}
		header('Location:customer.html');
	} else {
		$error_warning = $lang['error_not_completed'];
		$error_username = $error['username'];
		$error_email = $error['email'];
		$error_password = $error['password'];
		$error_confirm = $error['confirm'];
		$error_token = $error['token'];
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

<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="customer.html"><?php echo $lang['text_customer']; ?></a>
	</div>
	<?php if (isset($error_warning)) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/user.png" alt="" /> <?php echo $lang['heading_customer']; ?></h1>
			<div class="buttons">
				<?php if(isset($customer_id)) { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<?php } else { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_insert']; ?></a>
				<?php } ?>
				<a onclick="location = 'customer.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs">
				<a href="#tab-general"><?php echo $lang['tab_general']; ?></a>
				<a href="#tab-information"><?php echo $lang['tab_information']; ?></a>
			</div>
			<form action="<?php echo $path; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $lang['entry_username']; ?></td>
							<td><input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $lang['entry_username']; ?>" autofocus required>
							<?php if (isset($error_username)) { ?>
								<span class="error"><?php echo $error_username; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $lang['entry_email']; ?></td>
							<td><input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $lang['entry_email']; ?>" autofocus required>
							<?php if (isset($error_email)) { ?>
								<span class="error"><?php echo $error_email; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php if(!isset($customer_id)) { ?><span class="required">*</span> <?php } ?> <?php echo $lang['entry_password']; ?></td>
							<td><input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $lang['entry_password']; ?>" autofocus required>
							<?php if (isset($error_password)) { ?>
								<span class="error"><?php echo $error_password; ?></span>
							<?php  } ?></td>
						</tr>
						<tr>
							<td><?php if(!isset($customer_id)) { ?><span class="required">*</span> <?php } ?> <?php echo $lang['entry_confirm']; ?></td>
							<td><input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $lang['entry_confirm']; ?>" autofocus required>
							<?php if (isset($error_confirm)) { ?>
								<span class="error"><?php echo $error_confirm; ?></span>
							<?php  } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $lang['entry_token']; ?></td>
							<td><input type="text" name="token" value="<?php echo $token; ?>" placeholder="<?php echo $lang['entry_token']; ?>" autofocus required>
							<?php if (isset($error_token)) { ?>
								<span class="error"><?php echo $error_toekn; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $lang['entry_status']; ?></td>
							<td><select name="status">
							<?php if ($status == 1) { ?>
								<option value="0"><?php echo $lang['text_disabled']; ?></option>
								<option value="1" selected="selected"><?php echo $lang['text_enabled']; ?></option>
							<?php } else { ?>
								<option value="0" selected="selected"><?php echo $lang['text_disabled']; ?></option>
								<option value="1"><?php echo $lang['text_enabled']; ?></option>
							<?php } ?>
							</select></td>
						</tr>
					</table>
				</div>
				<div id="tab-information">
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $lang['entry_fname']; ?></td>
							<td><input type="text" name="fname" value="<?php echo $fname; ?>" placeholder="<?php echo $lang['entry_fname']; ?>" autofocus required>
							<?php if (isset($error_fname)) { ?>
								<span class="error"><?php echo $error_fname; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $lang['entry_lname']; ?></td>
							<td><input type="text" name="lname" value="<?php echo $lname; ?>" placeholder="<?php echo $lang['entry_lname']; ?>" autofocus required>
							<?php if (isset($error_lname)) { ?>
								<span class="error"><?php echo $error_lname; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $lang['entry_dob']; ?></td>
							<td><input type="text" id="datepicker" name="datepicker" value="<?php echo $dob; ?>" placeholder="<?php echo $lang['entry_dob']; ?>" required/>
							<?php if (isset($error_dob)) { ?>
								<span class="error"><?php echo $error_dob; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $lang['entry_gender']; ?></td>
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
							<td><span class="required">*</span> <?php echo $lang['entry_add1']; ?></td>
							<td><input type="text" name="add1" value="<?php echo $add1; ?>" placeholder="<?php echo $lang['entry_add1']; ?>" autofocus required>
							<?php if (isset($error_add1)) { ?>
								<span class="error"><?php echo $error_add1; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $lang['entry_add2']; ?></td>
							<td><input type="text" name="add2" value="<?php echo $add2; ?>" placeholder="<?php echo $lang['entry_add2']; ?>" autofocus required></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $lang['entry_city']; ?></td>
							<td><input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $lang['entry_city']; ?>" autofocus required>
							<?php if (isset($error_city)) { ?>
								<span class="error"><?php echo $error_city; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $lang['entry_zip']; ?></td>
							<td><input type="text" name="zip" value="<?php echo $zip; ?>" placeholder="<?php echo $lang['entry_zip']; ?>" autofocus required>
							<?php if (isset($error_zip)) { ?>
								<span class="error"><?php echo $error_zip; ?></span>
							<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $lang['entry_country']; ?></td>
							<td>
								<select name="country" id="country" onchange="updatecountry(this)" autofocus="autofocus" autocorrect="off" autocomplete="off" required >
								<option value="" selected="selected"><?php echo $lang['text_select']." ".$lang['entry_country']; ?></option>
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
							<?php if (isset($error_country)) { ?>
								<span class="error"><?php echo $error_country; ?></span>
							<?php  } ?></td>
						</tr>
						
						<tr>
							<td><span class="required">*</span> <?php echo $lang['entry_state']; ?></td>
							<td><select name="state" id="state" ></select>
							<?php if (isset($error_state)) { ?>
								<span class="error"><?php echo $error_state; ?></span>
							<?php  } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $lang['entry_phone']; ?></td>
							<td><input type="text" name="phone" value="<?php echo $phone; ?>" placeholder="<?php echo $lang['entry_phone']; ?>" autofocus required>
							<?php if (isset($error_phone)) { ?>
								<span class="error"><?php echo $error_phone; ?></span>
							<?php } ?></td>
						</tr>
					</table>
				</div>
				<?php if(isset($customer_id)) { ?>
					<input type="hidden" name="action" value="update" />
					<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
				<?php } else { ?>
					<input type="hidden" name="action" value="insert" />
				<?php } ?>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#tabs a').tabs(); 
</script> 

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
		url: 'controller/getState.php',
		type: 'post',
		dataType : 'json',
		data: 'country_id='+ element.value ,		
		success: function(json) {		
			html = '<option value=""><?php echo $lang['text_select']." ".$lang['entry_state']; ?></option>';
						
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '">' + json['zone'][i]['name'] + '</option>';
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