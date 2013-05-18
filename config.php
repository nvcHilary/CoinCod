<?php 
//database setting

$hostname = 'localhost';
$dbname = 'coincod';
$username = 'root';
$password = 'password';

mysql_connect($hostname,$username,$password) or die ('Connection lost! Server is down!');
mysql_select_db($dbname) or die ('Database name is not available!');

////////////////////////////////////////////////////////////////////////////
define('host', $_SERVER['HTTP_HOST']); 
define('self', $_SERVER['PHP_SELF']);
define('company', 'NEXVEND SDN BHD');

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
 	return $pageURL;
}

function curPageURL(){
	$pageURL = 'http';
	if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
		$pageURL .= "s";
	}
 		$pageURL .= "://";
 	if($_SERVER["SERVER_PORT"] != "80"){
  		$pageURL .= host.":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 	}else{
  		$pageURL .= host.$_SERVER["REQUEST_URI"];
 	}
 	return $pageURL;
}
?>