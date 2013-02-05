<?php
function getUserRecords($email)
{
	$sql = "SELECT * FROM user_account WHERE Email = '" . $email . "'"; 
	$res = mysql_query($sql);
	$a = mysql_fetch_array($res);
	$records['id'] = $a["user_id"];
	$records["category"] = $a["Category"];
	$records["username"] = $a["Username"];
	$records["email"] = $a["Email"];
	$records["fname"] = $a["first_name"];
	$records["lname"] = $a["last_name"];
	$records["dob"] = $a["date_birth"];
	$records["gender"] = $a["Gender"];
	$records["add1"] = $a["Address1"];
	$records["add2"] = $a["Address2"];
	$records["city"] = $a["City"];
	$records["state"] = $a["State"];
	$records["zip"] = $a["Zip"];
	$records["country"] = $a["Country"];
	$records["mobile"] = $a["Mobile"];
	$records["activation"] = $a["Activation"];
	$records["token"] = $a["Token"];

	return $records;
}	
function verifyLogin($email,$pass)
{
	$hash_pass = md5($pass);
	
	$sql = "SELECT * FROM user_account WHERE Email = '".$email."' AND hash_password = '" . $hash_pass . "'";
	$res = mysql_query($sql);
	$num = mysql_num_rows($res);

	if ($num > 0)
		return true;
	return false;	
}
?>

<?php
session_start();
if ((isset($_SESSION['user_id'])) && (isset($_SESSION['email']))) {
	$now = time(); 
	if($now > $_SESSION['expire']) {
		session_destroy();
		echo "<script language='javascript'>";
		echo "window.location=$PREFIX";
		echo "alert('Timeout!!!Please login to continue browsing the site');";
		echo "</script>";
	} else { 
		$_SESSION['start_reset'] = time();
		$_SESSION['expire'] = $_SESSION['start_reset'] + (30 * 60) ;

		$userid = $_SESSION['user_id'];
		$email = $_SESSION['email'];

		$user_data = getUserRecords($email);		
		$token=$user_data["token"];
		$gravatar_image=$user_data["email"];
		$default = "$this->template_path.template_image/favicon.ico";
		$size = 40;

		$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $gravatar_image ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
											
		echo '
		<div id="after_log_in">
			<div class="user_name">
				<img src="'.$grav_url.'" alt="" />
					<a href="'.$this->resource_path.'user_profile/?id=' . $userid . '">' . $user_data['username'] . '</a>
			</div>
			
			<div id="logout">
				<a href="'.$this->resource_path.'logout">Log Out</a>
			</div>
			
			<div class="token_left">
				<img src="'.$this->template_path.'template_image/header/token_left.png" border="0" width="30%"> '.$token.'
			</div>
		</div>';
	}
} else {
	if(isset($_POST["btnSubmit"])){
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
					header("location:$PREFIX"); 
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

<form action="" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
	<table cellspacing="0">
		<tbody>
			<tr>
				<td>
					<input type="text" class="inputtext" name="email" id="email" placeholder="Email" tabindex="1" required="true"/>
				</td>
				<td>
					<input type="password" class="inputtext" name="password" id="password" placeholder="Password" tabindex="2" required="true">
				</td>
				<td>
					<button class="form_button" name="btnSubmit" type="submit">LOGIN</button>
				</td>
			</tr>
			<tr>
				<td class="login_label_field">
					<a href="<?php echo $this->resource_path; ?>forgot_password">Forgot Password?</a>
				</td>
				<td class="login_label_field">
					<a href="<?php echo $this->resource_path; ?>registration">Sign Up</a>
				</td>
			</tr>                   
		</tbody>
	</table>
</form>	
<?php } ?>