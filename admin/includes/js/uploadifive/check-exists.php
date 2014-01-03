<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/
include_once "../../../../models/config.php";

// Define a destination
$targetFolder = $_SERVER['DOCUMENT_ROOT'].'/'.FOLDER_ROOT.$_POST['upload_dir'];

if (!file_exists($targetFolder)) {
	mkdir($targetFolder, 0777);
	echo 0;
} else {
	if (file_exists($targetFolder . '/' . $_POST['filename'])) {
		echo 1;
	} else {
		echo 0;
	}
}
?>