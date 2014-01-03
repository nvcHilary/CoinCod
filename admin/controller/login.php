<?php
if(isset($logged)) {
	header('Location:home.html');
}

if (isset($_SESSION['success'])) {
	$success = $_SESSION['success'];
	unset($_SESSION['success']);
} else {
	$success = '';
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

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username = mysql_real_escape_string($_POST["username"]);
	$password = mysql_real_escape_string($_POST["password"]);
	
	$data_login = array(
		'username'	=> $username,
		'password'	=> md5($password),
		'status'	=> 1
	);
	
	$error = array();
	$error_warning = NULL;
	
	if(empty($username)) {
		$error[] = $lang['error_username_empty'];
	}
	
	if(empty($password)) {
		$error[] = $lang['error_password_empty'];
	}
	
	if(!verifyLoginAdmin($data_login)) {
		$error[] = $lang['error_failed_login'];
	}
	
	if (empty($error)) {
		$success = $lang['success_login'];
		$user_data = getUserByUsername($data_login);
		$_SESSION['coin_user_id'] = $user_data['user_id'];
		header('Location:home.html');
	} else {
		foreach ($error as $key => $values) {
			$error_warning = $values . '<br/>';
		}
	}
}
?>
<div id="content">
	<div class="box" style="width: 400px; min-height: 300px; margin-top: 40px; margin-left: auto; margin-right: auto;">
		<div class="heading">
			<h1><img src="includes/images/lockscreen.png" alt="" /> <?php echo $lang['text_login']; ?></h1>
		</div>
		<div class="content" style="min-height: 150px; overflow: hidden;">
			<?php if ($success) { ?>
				<div class="success"><?php echo $success; ?></div>
			<?php } ?>
			<?php if ($error_warning) { ?>
				<div class="warning"><?php echo $error_warning; ?></div>
			<?php } ?>
			<form action="" method="post" enctype="multipart/form-data" id="formLogin">
				<table style="width: 100%;">
					<tr>
						<td style="text-align: center;" rowspan="4"><img src="includes/images/login.png" alt="Login" /></td>
					</tr>
					<tr>
						<td>
							<?php echo $lang['text_username']; ?><br />
							<input type="text" name="username" value="<?php echo $username; ?>" style="margin-top: 4px;" required /><br /><br />
							<?php echo $lang['text_password']; ?><br />
							<input type="password" name="password" value="<?php echo $password; ?>" style="margin-top: 4px;" required /><br />
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td style="text-align: right;"><input type="submit" class="button" value="<?php echo $lang['text_login']; ?>" /></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>