<?php
	session_destroy();
	unset( $_SESSION );
	header("location:".mainPageURL()."admin/"); 
?>