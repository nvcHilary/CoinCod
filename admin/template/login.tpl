<?php
session_start();
require_once '../../config.php';
require_once '../../sql_function.php';
// get out here if user cookie valid
if(isset($_SESSION["loggedId"])){
	echo $redirectInAdmin;
}

$echo_error=NULL;
if(isset($_POST["btnLogin"])){
	$email = mysql_real_escape_string(strtolower($_POST["txtEmail"]));
	$password = mysql_real_escape_string(strtolower($_POST["txtPassword"]));
	
	$error = NULL;
	if(empty($email)){
		$error .= "<li>Email Address is required";
	}
	if(empty($password)){
		$error .= "<li>Password is required";
	}
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error .= "<li>Invalid Email!";
	}

	if(!empty($error)){
		$echo_err = "<div style='width:145px;color:#d50000;text-align:left;'><ul>".$error."</ul></div>";
	}else{
		if (verifyLogin($email,$password,1)) {
			$user_data = getMemberRecords($email);

			$_SESSION['loggedId'] = $user_data['id'];
			$_SESSION['logged'] = $user_data['username'];
			
////			$qLog = mysql_query("UPDATE ".$tb05." SET auth_logdate=DATE_ADD(NOW(),INTERVAL 8 HOUR) WHERE auth_id='".$authId."'", $conn_auth) or die(mysql_error());
	
			echo "<meta http-equiv='Refresh' content='0; URL=".mainPageURL()."/admin/'>"; //avoid to refresh twice to load cookie
////		}
		} else {
			$echo_error = "<div style='color:#d50000;text-align:left;'>
							Looks like your account cannot be found in our server. Please try again later. 
							If this problem persists kindly send us an email at <a href='mailto:support@coincod.com'>support@coincod.com.</a> Thank you.
						</div>";
		}
	}
}
?>

<form method="post" action="" name="form" enctype="multipart/form-data">
	<div align="center">
		<!--<div style="width:340px;padding:30px;background-color:#FFF;">-->
		<div style="width:340px;padding:30px;background-image: url('<?=mainPageURL()?>/admin/images/Login_bg.png');background-size:430px 250px;">
			<div style="color:#FFF;"><h2>Login</h2>
			<?=$echo_error?></div>
			
			<div>
				<table width="100%" border="0" cellspacing="0" cellpadding="3">
					<tr>
						<td width="36%" align="right"><div style="font-size:13px;color:#FFF;">Email</div></td>
						<td width="64%">
							<input type="text" name="txtEmail" style="padding:3px;width:180px;" required onfocus="this.style.backgroundColor='#fde6ff'" onblur="this.style.backgroundColor=''" />
						</td>
					</tr>
					<tr>
						<td align="right"><div style="font-size:13px;color:#FFF;">Password</div></td>
						<td>
							<input type="password" maxlength="35" name="txtPassword" style="padding:3px;width:180px;" required onfocus="this.style.backgroundColor='#fde6ff'" onblur="this.style.backgroundColor=''" />
						</td>
					</tr>
				</table>
			</div>
    
			<div align="center" style="margin-top:10px;">
				<input name="btnLogin" type="submit" class="form_button" value="Login">
			</div>
		</div>
	</div>
</form>
