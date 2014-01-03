<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

include_once "../../../../models/config.php";

// Set the uplaod directory
$uploadDir = $_SERVER['DOCUMENT_ROOT'].'/'.FOLDER_ROOT.$_POST['upload_dir'];

// Set the allowed file extensions
$fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // Allowed file extensions

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	$targetFile = $uploadDir . $_FILES['Filedata']['name'];
	
	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
		// Save the file
		move_uploaded_file($tempFile, $targetFile);
		echo 1;
	} else {
		// The file type wasn't allowed
		echo 'Invalid file type.';
	}
}
?>