<?php
$data_setting = array(
	'group'	=> 'email'
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		if($setting['key'] == 'mail_support') {		$support = $setting['value']; }
		if($setting['key'] == 'mail_security') {	$security = $setting['value']; }
	}
}

if(isset($_POST["btnSubmit"])){
	echo $emails = mysql_real_escape_string($_POST['form_email']);
	echo $names = mysql_real_escape_string($_POST['form_name']);
	echo $subject = mysql_real_escape_string($_POST['form_subject']);
	echo $comments = mysql_real_escape_string($_POST['form_comments']);
	
	$error = array();
	
	if(!filter_var($emails, FILTER_VALIDATE_EMAIL)){
		$error['email'] = $lang['error_email_valid'];
	}
	
	if(empty($names)){
		$error['name'] = $lang['error_name'];
	}
	
	if(empty($subject)){
		$error['subject'] = $lang['error_subject'];
	}
	
	if(empty($comments)){
		$error['comment'] = $lang['error_comment'];
	}
	
	if(empty($error)){
		//sent in customer service
		$subject = "Customer Feedback : ".$subject;
		$body = "Dear Support Team, 
				<br><br/>
				Subject : " . $subject . "
				<br><br/>
				Name : " . $names . "
				<br><br/>
				Email : " . $emails . "
				<br><br/>
				Comment : " . $comment . "
				<br><br/>
				Enjoy your awesome first auction experience with " . $lang['text_company_name'] . ".
				<br /><br /> 
				Thank You! 
				<br /><br />
				NOTE:If you did not request for this email. Kindly ignore it.
				<br /><br />
				Best Regards,
				" . $lang['text_company_name'] ." Management Team";
		$data_Inmail = array(
			'from_name'		=> "",
			'from_email'	=> $emails,
			'to_name'		=> "Support Team",
			'to_email'		=> $support,
			'subject'		=> $subject,
			'body'			=> $body
		);
		sendInMail($data_Inmail);
		
		//reply to customer
		$subject = $lang['text_thankyou_feedback'];
		$body = "Dear " . $names . ", 
				<br><br/>
				CoinCod will like to inform you that we have received your feedback! Here at CoinCod, we value all of our customers thus we evaluate every customers feedback seriously. 
				Therefore, it might take some time for us to process all the feedbacks we received. We will get back to you soon. Thank you for your patience.	
				<br/><br/>
				Enjoy your awesome first auction experience with Coincod.
				<br /><br /> 
				Thank You! 
				<br /><br />
				NOTE:If you did not request for this email. Kindly ignore it.
				<br /><br />
				Best Regards,
				" . $lang['text_company_name'] ." Management Team";
		$data_mail = array(
			'to_name'	=> $names,
			'to_email'	=> $emails,
			'subject'	=> $subject,
			'body'		=> $body
		);
		sendMail($data_mail);
		
		$_SESSION['success'] = $lang['success_mail_feedback'];
		header("location:login.html");
	} else {
		$error_email = $error['email'];
		$error_name = $error['name'];
		$error_subject = $error['subject'];
		$error_comment = $error['comment'];
	}
}
?>
<h5><?php echo $lang['head_feedback']; ?></h5>
<article class="auction_container">
	<div id="feedback">
	<ul>
		<!--Feedback Form-->
		<div class="column_first">
			<p>Feel free to email us at 
				<a href="mailto:<?php echo $support; ?>"><?php echo $support; ?></a>
			</p>
			
			<form accept-charset="UTF-8" action="" class="edit_user" id="edit_user_1809494" method="post">
				<div style="margin:0;padding:0;display:inline">
					<input name="_method" value="put" type="hidden">
				</div>
				
				<dl class="form">
					<dt>
						<label for="form_name"><?php echo $lang['entry_name']; ?></label>
					</dt>
					<dd>
						<input id="form_name" name="form_name" required="true" value="" type="text">
						<?php if ($error_name) { ?>
							<span class="error"><?php echo $error_name; ?></span>
						<?php } ?>
					</dd>
				</dl>

				<dl class="form">
					<dt>
						<label for="form_email"><?php echo $lang['entry_email']; ?></label>
					</dt>
					<dd>
						<input id="form_email" name="form_email" required="true" value="" type="email">
						<?php if ($error_email) { ?>
							<span class="error"><?php echo $error_email; ?></span>
						<?php } ?>
					</dd>
				</dl>

				<dl class="form">
					<dt>
						<label for="form_subject"><?php echo $lang['entry_subject']; ?></label>
					</dt>
					<dd>
						<input id="form_subject" name="form_subject" required="true" type="text">
						<?php if ($error_subject) { ?>
							<span class="error"><?php echo $error_subject; ?></span>
						<?php } ?>
					</dd>
				</dl>

				<input name="form[last_repo]" value="" type="hidden">
				<input name="form[last_repo_at]" value="" type="hidden">

				<dl class="form">
					<dt>
						<label for="form_comments"><?php echo $lang['entry_comment']; ?></label>
					</dt>
					<dd>
						<textarea id="form_comments" name="form_comments" placeholder="Please write your feedback here. We will reply your feedback as soon as possible." required="true"></textarea>
						<?php if ($error_comment) { ?>
							<span class="error"><?php echo $error_comment; ?></span>
						<?php } ?>
					</dd>
				</dl>

				<div class="form-actions">
					<button name="btnSubmit" type="submit" class="form_button">
						<span><?php echo $lang['button_submit']; ?></span>
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
			<a href="mailto:<?php echo $security; ?>"><?php echo $security; ?></a>
			If you have any special request <strong>just shoot CoinCod an email</strong>, we are always available.<br/>
			<img alt="fisheart" class="fisheart" src="includes/images/bottom/fisheart.png">
		</section>
	</ul>
</div>
</article>