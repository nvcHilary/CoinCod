<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$db_hostname = $_POST['db_hostname'];
	$db_username = $_POST['db_username'];
	$db_password = $_POST['db_password'];
	$db_name = $_POST['db_name'];
	$folder = $_POST['folder'];
	
	if (empty($db_hostname)) {
		$error['db_hostname'] = "Host Name cannot be empty";
	}
	
	if (empty($db_username)) {
		$error['db_username'] = "Database Username cannot be empty";
	}
	
	if (empty($db_name)) {
		$error['db_name'] = "Database Name cannot be empty";
	}
	
	if(empty($folder)) {
		$folder = "";
	}
	
	if (empty($error)) {
		$filecontent = file_get_contents('../models/config.php');

		$filecontent = str_replace("DB_HOSTNAME", $db_hostname, $filecontent);
		$filecontent = str_replace("DB_NAME", $db_name, $filecontent);
		$filecontent = str_replace("DB_USERNAME", $db_username, $filecontent);
		$filecontent = str_replace("DB_PASSWORD", $db_password, $filecontent);
		$filecontent = str_replace("root_folder", $folder."/", $filecontent);
		file_put_contents("../models/config.php", $filecontent);
		
		$filecontent_access = file_get_contents('../.htaccess');
		$filecontent_access = str_replace("RewriteBase /", "RewriteBase /" . $folder . "/", $filecontent_access);;
		file_put_contents("../.htaccess", $filecontent_access);
		
		//$dbconn = mysql_connect($db_hostname, $db_username, $db_password);
		//mysql_select_db($db_name, $dbconn);

		//$file = '../coin.sql';

		//if($fp = file_get_contents($file)) {
		//	$var_array = explode(';',$fp);
		//	foreach($var_array as $value) {
		//		mysql_query($value.';',$dbconn);
		//	}
		//} 
		echo "<h1>Successfully Install</h1>";
	} else {
		$error_db_hostname = $error['db_hostname'];
		$error_db_username = $error['db_username'];
		$error_db_name = $error['db_name'];
	}
}
?>
<html>
<head>
	<style>
	.error {
		margin-top: 3px;
		color: #FF0000;
		display: block;
		font-size: 12px;
		font-weight: normal;
	}
	</style>
</head>
<body>
	<h1>Installation CoinCod Page</h1>
	<?php 
	$permission_config =  substr(sprintf('%o', fileperms('../models/config.php')), -4);
	$permission_access =  substr(sprintf('%o', fileperms('../.htaccess')), -4);
	?>
	<?php if($permission_config > 0666) { ?>
		<?php if($permission_config > 0666) { ?>
			<form action="index.php" method="post" enctype="multipart/form-data" id="form">
				<table>
					<tr>
						<td>Host Name : </td>
						<td><input type="text" name="db_hostname" value="<?php echo $db_hostname; ?>"  placeholder="Database HostName" />
						<?php if ($error_db_hostname) { ?>
							<span class="error"><?php echo $error_db_hostname; ?></span>
						<?php } ?></td>
					</tr>
			
					<tr>
						<td>Databse Username : </td>
						<td><input type="text" name="db_username" value="<?php echo $db_username; ?>"  placeholder="Database Username" />
						<?php if ($error_db_username) { ?>
							<span class="error"><?php echo $error_db_username; ?></span>
						<?php } ?></td>
					</tr>
			
					<tr>
						<td>Database Password : </td>
						<td><input type="password" name="db_password" value="<?php echo $db_password; ?>"  placeholder="Database Password" /></td>
					</tr>
			
					<tr>
						<td>Database Name : </td>
						<td><input type="text" name="db_name" value="<?php echo $db_name; ?>"  placeholder="Database Name" />
						<?php if ($error_db_name) { ?>
							<span class="error"><?php echo $error_db_name; ?></span>
						<?php } ?></td>
					</tr>
			
					<tr>
						<td>FOLDER in htdocs : </td>
						<td><input type="text" name="folder" value="<?php echo $folder; ?>" placeholder="Folder" /></td>
					</tr>
			
					<tr>
						<td><input type="submit" value="Activate CoinCod"></td>
					</tr>
				</table>
			</form>
		<?php } else { ?>
			.htaccess File permission is not allow updating. <br/>
			Current permission : <?php echo $permission_access; ?>
		<?php } ?>
	<?php } else { ?>
		Config File permission is not allow updating. <br/>
		Current permission : <?php echo $permission_config; ?>
	<?php } ?>
</body>
</html>