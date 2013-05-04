<?php
session_start();
// Load the Savant3 class file and create an instance.
require_once 'Savant3.php';

// initialize template engine
$tpl = new Savant3();

// set search path for templates
$tpl->addPath('template', './template');

// Create a title.
$template_path = "template/";
$resource_path = "./";
$title = "CoinCod";
$meta_description = "Welcome to CoinCod - a unique auction system built to draw everyone closer to their dream products.";

$contentContainer = array(
    array(
        "title" => "",
        "content" => "",
		"bottom_image" =>""
    )
);

// Assign values to the Savant instance.
$tpl->template_path = $template_path;
$tpl->resource_path = $resource_path;
$tpl->title = $title;
$tpl->meta_description = $meta_description;
$tpl->content_container = $contentContainer;

// Display a template using the assigned values.
if(isset($_SESSION['user_id'])) {
	$tpl->header = $tpl->fetch($template_path.'header.tpl');
	$tpl->product = $tpl->fetch($template_path.'product.tpl');
} else {
	$tpl->header = "";
	$tpl->product = $tpl->fetch($template_path.'index.tpl');	
}
$tpl->footer = $tpl->fetch($template_path.'footer.tpl');
$tpl->display($template_path.'main.tpl');