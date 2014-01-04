<?php
$encry_email = $_GET['email'];
$encry_code = $_GET['code'];

$email = urldecode(base64_decode($encry_email));
$code = substr($encry_code, 0, 10);

$data_emails = array(
	'email'		=> $email
);
$customer = getCustomerByEmail($data_emails);

if ( ($customer['fcode'] != $code) || ($email == "") || ($code == "") ) {
	header('Location:'.mainPageURL().'404.html');
}

$edit_data = array (
	'customerId'	=> $customer['customer_id'],
	'status'		=> "1",
	'fcode'			=> "0"
);
			
$edit_query = editCustomer($edit_data);
if($edit_query) {
	$subject = $lang['success_activation'];
	$body = "Dear " . $customer['username'] . ", 
			<br><br/>
			Your CoinCod Account has been Activated.<br/><br/>
			<br/>
			Enjoy your awesome first auction experience with " . $lang['text_company_name'] . ".
			<br /><br /> 
			Thank You! 
			<br /><br />
			NOTE:If you did not request for this email. Kindly ignore it.
			<br /><br />
			Best Regards,
			" . $lang['text_company_name'] ." Management Team";
	$data_mail = array(
		'to_name'	=> $customer['username'],
		'to_email'	=> $customer['email'],
		'subject'	=> $subject,
		'body'		=> $body
	);
	sendMail($data_mail);
	$_SESSION['success'] = $lang['success_activation'];
	header("location:" . mainPageURL() . "login.html");
}
?>