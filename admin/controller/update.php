<?php
session_start();
include_once ('../../models/config.php');
include_once ('../../models/define.php');
include_once ('../../models/sql_function.php');

$update_type = mysql_real_escape_string($_POST['update_type']);

if($update_type == 'product') {
	$product_id = mysql_real_escape_string($_POST['product_id']);
	$action = mysql_real_escape_string($_POST['action']);
	$brand = mysql_real_escape_string($_POST['brand']);
	$model = mysql_real_escape_string($_POST['model']);
	$mprice = mysql_real_escape_string($_POST['mprice']);
	$aprice = mysql_real_escape_string($_POST['aprice']);
	$category = mysql_real_escape_string($_POST['category']);
	$availability = mysql_real_escape_string($_POST['availability']);
	$datestart = mysql_real_escape_string($_POST['datestart']);
	$dateend = mysql_real_escape_string($_POST['dateend']);
	$bids = mysql_real_escape_string($_POST['bids']);
	$description = mysql_real_escape_string($_POST['description']);

	$product_data = array (
		'productId'		=> $product_id,
		'brand'			=> $brand,
		'model'			=> $model,
		'mprice'		=> $mprice,
		'aprice'		=> $aprice,
		'category'		=> $category,
		'availability'	=> $availability,
		'datestart'		=> $datestart,
		'dateend'		=> $dateend,
		'bids'			=> $bids,
		'description'	=> $description,
		'modify_by'		=> $logged
	);
		
	if($action == "insert") {
		$insert_query = insertProduct($product_data);
		if($insert_query) {
			echo 1;
		} else {
			echo 0;
		}
	} else if ($action == "update") {
		$edit_query = editProduct($product_data);
		if($edit_query) {
			echo 2;
		} else {
			echo 0;
		}
	}
} else if($update_type == 'settings') {
	$description = mysql_real_escape_string($_POST['description']);
	$info_id = mysql_real_escape_string($_POST['info_id']);
	
	$data = array (
		array(
			group	=> "page",
			Key 	=> $info_id,
			Value 	=> $description
		)
	);
	$query = updateSettings($data);
	if($query) {
		echo 1;
	} else {
		echo 0;
	}
} else if($update_type == 'faq') {
	$faq_id = mysql_real_escape_string($_POST['faq_id']);
	$action = mysql_real_escape_string($_POST['action']);
	$question = mysql_real_escape_string($_POST['question']);
	$description = mysql_real_escape_string($_POST['description']);

	$faq_data = array (
		'faqId'		=> $faq_id,
		'question'	=> $question,
		'description'	=> $description
	);
		
	if($action == "insert") {
		$insert_query = insertFAQ($faq_data);
		if($insert_query) {
			echo 1;
		} else {
			echo 0;
		}
	} else if ($action == "update") {
		$edit_query = editFAQ($faq_data);
		if($edit_query) {
			echo 2;
		} else {
			echo 0;
		}
	}
}
?>