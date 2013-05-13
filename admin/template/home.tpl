<?php
ob_start();
session_start();
require_once '../config.php';
require_once '../sql_function.php';
// protect authorize page
if(empty($_SESSION["loggedId"])){
	header("Location:".mainPageURL()."/admin/login/");
}
?>
