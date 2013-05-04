<?php
	require '../config.php';
	require '../sql_function.php';
	session_start();
	if(isset($_POST["btnLogin"])){
		if (verifyLogin($_POST["email"],$_POST["password"])) {
			$user_data = getUserRecords($_POST["email"]);
			if($user_data['activation'] == 0) {
				$error = "Your account has not been activate. Please go email to activate it now and enjoy the forthcoming experience that CoinCod will bring to you. Thank you.";
			} else {
				$_SESSION['start'] = time(); 
				$_SESSION['expire'] = $_SESSION['start'] + (1 * 60) ;
			
				session_register('user_id'); 
				$_SESSION['user_id'] = $user_data['id'];
		
				session_register('email'); 
				$_SESSION['email'] = $_POST['email'];
				//admin location
				if($user_data['id']== 1) {
					header("location: $PREFIX/user_profile/?id=$user_data[id]");
				} else {
					header("location:$PREFIX/about_us"); 
				}
			}
		} else {
			header("location:$PREFIX/login/");
			$error = 'Looks like your account cannot be found in our server. Please try again later. If this problem persists kindly send us an email at <a href="mailto:support@coincod.com">support@coincod.com.</a> Thank you.';
		}
		session_register('error');
		$_SESSION['error'] = $error;
	}
?>
<?php echo $_SESSION['error']; $_SESSION['error']="";?>
<form action="#" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
	<table width="650" cellpadding="0" cellspacing="10">
		<tr>
			<td width="25%">
				Email
			</td>
			<td width="75%">
				<input type="text" name="email" value="" class="text" id="email"  placeholder="Email" size="33" maxlength="50" tabindex="10">
			</td>
		</tr>
		<tr>
			<td width="25%">
				Password
			</td>
			<td width="75%">
				<input type="password" name="password" value="" class="text" id="password" placeholder="Password" size="33" maxlength="50" tabindex="12">
			</td>
		</tr>
        <tr>
			<td>
				<input name="btnLogin" type="submit" class="form_button" value="Login">
			</td>
		</tr>
	</table> 
</form>