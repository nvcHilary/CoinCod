<?php
if(isset($_SESSION['coin_id'])) {
	$logged = $_SESSION['coin_id'];
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

if(isset($_POST["btnSubmit"])){
	$email = mysql_real_escape_string($_POST["email_in"]);
	$password = mysql_real_escape_string($_POST["password_in"]);
	
	$data_login = array(
		'email'		=> $email,
		'password'	=> md5($password)
	);
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error['email'] = $lang['error_email_valid'];
	}
	
	if(empty($error)){
		if(!verifyLoginCustomer($data_login)) {
			$_SESSION['error_warning'] = $lang['error_failed_login'];
			header('Location:login.html');
		} else {
			$customer_data = getCustomerByEmail($data_login);
			if($customer_data['status'] == 0) {
				$_SESSION['error_warning'] = $lang['error_failed_activation'];
				header('Location:login.html');
			} else {
				$success = $lang['success_login'];
				$_SESSION['coin_id'] = $customer_data['customer_id'];
				$_SESSION['start'] = time(); 
				$_SESSION['expire'] = $_SESSION['start'] + (1 * 60) ;
				header('Location:'.mainPageURL());
			}
		}
	} else {
		$_SESSION['error_warning'] = $error['email'];
		header('Location:login.html');
	}
}

if(isset($_POST["btnReg"])){
	$email = mysql_real_escape_string($_POST["email_reg"]);
	
	if(verifyDuplicateCustomer($email, "email")) {
		$_SESSION['error_warning'] = $lang['error_email_duplicate'];
	} else {
		$_SESSION['email'] = $email; 
	}
	header("location:register.html");
}

$title = $lang['head_home'];
?>
<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if(isset($logged)) {
	$data_product = array(	
	);
	include_once "product_list.php";
} else { ?>
	<section id="before_login">
		<header>
			<aside>
				<form action="./" enctype="multipart/form-data" name="login" id="myForm" method="post">
					Registered User? Come on, in...
					<input type="email" name="email_in" id="email_in" placeholder="<?php echo $lang['text_user_ID']; ?>" required/>
					<input type="password" name="password_in" id="password_in" placeholder="<?php echo $lang['text_password']; ?>" required/>
					<input type="submit" name="btnSubmit" value="Log In" />
					<div class="login_label_field"><a href="forgot_password.html"><?php echo $lang['text_forgot_password']; ?> ?</a><div>
				</form>
				
				<form action="./" enctype="multipart/form-data" name="login" id="myForm" method="post">
					New here? Then join us...
					<input type="email" name="email_reg" id="email_reg" placeholder="<?php echo $lang['text_email']; ?>" required/>
					<input type="submit" name="btnReg" value="Sign Up" />
				</form>
			</aside>
			<div id="title"><?php echo $lang['text_company_name']; ?></div>
			<div id="punchline">
				The Awesome Punchline goes here...
			</div>
		</header>
		<img src="includes/images/shadow-line.png" alt="shadow-line">
	</section>
	
	<?php
	$data_product = array(
		'start'	=> 0,
		'limit'	=> 4	
	);
	include_once "product_list.php";
} ?>