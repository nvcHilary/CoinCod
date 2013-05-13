<?php
	ob_start();
	session_start();
	require_once '../config.php';
	require_once '../sql_function.php';
	
$echo_error=NULL;
if(isset($_POST["btnSubmit"])){
	$emails = mysql_real_escape_string($_POST['form_email']);
	$names = mysql_real_escape_string($_POST['form_name']);
	$subject = mysql_real_escape_string($_POST['form_subject']);
	$comments = mysql_real_escape_string($_POST['form_comments']);
	
	$error = NULL;
	if(empty($names)){
		$error .= "<li>Name is required";
	}
	if(empty($subject)){
		$error .= "<li>Subject is required";
	}
	if(empty($emails)){
		$error .= "<li>Email is required";
	}
	if(!filter_var($emails, FILTER_VALIDATE_EMAIL)){
		$error .= "<li>Invalid Email!";
	}
	if(empty($comments)){
		$error .= "<li>Comments is required";
	}
	
	if(!empty($error)){
		$echo_error = "<div style='text-align:left;color:#d50000;'><ul>".$error."</ul></div>";
	}else{
		if(sendfeedback($emails, $names, $subject, $comments)) {
			$echo_error = (sendfeedback($emails, $names, $subject, $comments));
		} else {
			$echo_error = "<div style='color:#d50000;text-align:left;'>Mailer Error (CS): Failed comment. Please try to resend again!</div>";
		}
	}
}
?>
<?=$echo_error;?>
<div id="feedback">
	<ul>
		<!--Feedback Form-->
		<div class="column_first">
			<p>Feel free to email us at 
				<a href="mailto:support@coincod.com">support@coincod.com</a>
			</p>
			
			<form accept-charset="UTF-8" action="" class="edit_user" id="edit_user_1809494" method="post">
				<div style="margin:0;padding:0;display:inline">
					<input name="_method" value="put" type="hidden">
				</div>
				
				<dl class="form">
					<dt>
						<label for="form_name">Name</label>
					</dt>
					<dd>
						<input id="form_name" name="form_name" required="true" value="" type="text">
					</dd>
				</dl>

				<dl class="form">
					<dt>
						<label for="form_email">Email</label>
					</dt>
					<dd>
						<input id="form_email" name="form_email" required="true" value="" type="text">
					</dd>
				</dl>

				<dl class="form">
					<dt>
						<label for="form_subject">Subject</label>
					</dt>
					<dd>
						<input id="form_subject" name="form_subject" required="true" type="text">
					</dd>
				</dl>

				<input name="form[last_repo]" value="" type="hidden">
				<input name="form[last_repo_at]" value="" type="hidden">

				<dl class="form">
					<dt>
						<label for="form_comments">Questions / Comments</label>
					</dt>
					<dd>
						<textarea id="form_comments" name="form_comments" placeholder="Please write your feedback here. We will reply your feedback as soon as possible." required="true"></textarea>
					</dd>
				</dl>

				<div class="form-actions">
					<button name="btnSubmit" type="submit" class="form_button">
						<span>Submit</span>
					</button>
				</div>
			</form>
		</div>
	</ul>

	<ul>
		<!--Feedback Description-->
		<section class="column_last">
			<h3>Why is your feedback crucial to CoinCod?</h3>
			<ul class="checklist">
				<li>We will able to challenge the status quo together by having a mutual understanding of each other.</li>
				<li>Pushing boundaries to create more upcoming innovative features</li>
				<li>Users like you will be able to have full control of your favorites.</li>
			</ul>
	  
			<h3>Reporting a security vulnerability?</h3>
			Please send to our email at
			<a href="mailto:security@coincod.com">security@coincod.com</a>
			If you have any special request <div class="strong">just shoot CoinCod an email</div>, we are always available.
			<img alt="fisheart" class="fisheart" src="../template/template_image/fisheart.png">
		</section>
	</ul>
</div>