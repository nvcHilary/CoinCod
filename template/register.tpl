<?php
	ob_start();
	session_start();
	require_once '../config.php';
	require_once '../sql_function.php';
		
	if(isset($_POST["btnReg"])){
		$email = mysql_real_escape_string($_POST["email_in"]);
		if (checkExist($email) == 1) {
			header("location:".mainPageURL()."/registration/register_msg.php?action=1");
		} else {
			if (sendRegLink($email)) {
				header("location:".mainPageURL()."/registration/register_msg.php?action=2");	
			} else {
				header("location:".mainPageURL()."/registration/register_msg.php?action=3");
			}
		}
	}
?>
<form action="" enctype="multipart/form-data" name="login" id="myForm" method="post">
	Register : 
	<input type="email" name="email_reg" id="email_reg" placeholder="Email ID" required/>
	<input type="submit" name="btnReg" value="Sign Up" />
</form>