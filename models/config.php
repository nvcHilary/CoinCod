<?php
define('host', $_SERVER['HTTP_HOST']); 
define('FOLDER_ROOT', "root_folder");

function mainPageURL(){
	$pageURL = 'http';
	if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if($_SERVER["SERVER_PORT"] != "80"){
		$pageURL .= host.":".$_SERVER["SERVER_PORT"];
	}else{
		$pageURL .= host;
	}
	$pageURL .= "/";
	return $pageURL.FOLDER_ROOT;
}

$hostname = 'DB_HOSTNAME';
$dbname = 'DB_NAME';
$username = 'DB_USERNAME';
$password = 'DB_PASSWORD';

mysql_connect($hostname,$username,$password) or die ('Connection lost! Server is down!');
mysql_select_db($dbname) or die ('Database name is not available!');

//End User
define('e01', 'customer');
define('e02', 'customer_info');
define('e03', 'customer_log');
define('e04', 'country');
define('e05', 'state');
define('e06', 'category');
define('e07', 'product');
define('e08', 'product_image');
define('e09', 'product_log');
define('e10', 'settings');
define('e11', 'bidding_log');
define('e12', 'faq');
define('e13', 'graphics');
define('e14', 'tokens');

//admin table
define('e50', 'user');
define('e51', 'user_log');
define('e52', 'user_group');
?>