<?php
session_start();
include_once ('../../models/config.php');
include_once ('../../models/define.php');
include_once ('../../models/sql_function.php');

$country_id = $_POST['country_id'];
$state_info = getStateCode($country_id);

$json = array(
	'zone'	=> $state_info
);

echo json_encode($json)
?>