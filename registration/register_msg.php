<?php
// Load the Savant3 class file and create an instance.
require_once '../libs/Savant3.php';

// initialize template engine
$tpl = new Savant3();

// add a series of template directories
$tpl->addPath('template', '../template');

$action = $_GET['action'];
//Set Values
$resource_path = "../";
if($action == 4) {
	$title = "Login";
} else {
	$title = "Registration";
}
$meta_description = "Welcome to CoinCod - a unique auction system built to draw everyone closer to their dream products.";

if ( $action == 1 ) {
	$content = "<div style='color:#d50000;text-align:left;'>
					Your Email Already Register. Please go to forget password! 
					If this problem persists kindly send us an email at <a href='mailto:support@coincod.com'>support@coincod.com.</a> Thank you.
				</div>";
} else if ( $action == 2) {
	$content = "Please Check Your email to get register link.Thank you.";
} else if ( $action == 3) {
	$content = "<div style='color:#d50000;text-align:left;'>
					Your Email Cannot Be Sent. Please try again later. 
					If this problem persists kindly send us an email at <a href='mailto:support@coincod.com'>support@coincod.com.</a> Thank you.
				</div>".$tpl->fetch('register.tpl');
} else if($action == 4) {
	$content = "Congratulations! Your registration is successful!<br />".$tpl->fetch('login.tpl');
}
$contentContainer = array(
    array(
        "title" => $title,
        "content" => $content,
		"bottom_image" =>''
    )
);

// Assign values to the Savant instance.
$tpl->resource_path = $resource_path;
$tpl->title = $title;
$tpl->meta_description = $meta_description;
$tpl->content_container = $contentContainer;

$tpl->setTemplate('main.tpl');
$tpl->display();
?>