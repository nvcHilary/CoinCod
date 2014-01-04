<?php
	include_once "../models/config.php";
	include_once "../models/sql_function.php";
	$product = getProductById($_POST['id']);	
	$endtime = strtotime($product["dateend"]); 

	$time = $endtime - time();
	if($time < 0 ) {
		$time = 0;
	} else {
		$days = date("d", $time - (8 * 60 * 60)) - 1;
		$time = date("H:i:s", $time - (8 * 60 * 60));
	}
	if($days != 0) {
		echo $days." days ";
	}
	echo $time;

?>
