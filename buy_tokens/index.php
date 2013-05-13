<?php
// Load the Savant3 class file and create an instance.
require_once '../libs/Savant3.php';

// initialize template engine
$tpl = new Savant3();

// add a series of template directories
$tpl->addPath('template', '../template');

//Set Values
$resource_path = "../";
$title = "Buy Tokens";
$meta_description = "Welcome to CoinCod - a unique auction system built to draw everyone closer to their dream products.";

//Display and Create the buy tokens
$contentContainer = array(
    array(
        "title" => $title,
        "content" => $tpl->fetch('buy_token.tpl'),
		"bottom_image" =>'<img class="bottom" src="../template/template_image/bottom/buy_tokens.png" alt="tokens shopping">'
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