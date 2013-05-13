<?php
ob_start();
session_start();
require_once '../../config.php';
require_once '../../sql_function.php';

$totalUser = getTotalUser();
// protect authorize page
if(empty($_SESSION["loggedId"])){
	header("Location:".mainPageURL()."/admin/login/");
}

mysql_set_charset("utf8");

$blk_action=NULL;

if(isset($_POST["btnUpdate"])){
	$txtStatus = mysql_real_escape_string($_POST["status"]);
	$sql = updateStatus($txtStatus);
	$text1 = "Status has been updated";	
}

if(isset($sql)){
	$blk_action = "<div style='width:400px;color:#58b300;text-align:center;'>".$text1.".</div>";
}else{
	$blk_action = $echo_err;	
}
?>

<form action="" method="post" id="form" enctype="multipart/form-data">
	<div class="admcontent" align="left">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="30%"><div class="admtitle">Status Update</div></td>
				<td width="70%" align="right">
				</td>
			</tr>
		</table>
					
		<div><?=$blk_action?></div>
		
		<form action="" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
			<td>
				<select id="status" name="status" size="1">
					<option value="Well as usual running like charm.">Well as usual running like charm.</option>
					<option value="Website Maintenance! Temporary Down!">Website Maintenance! Temporary Down!</option>
				</select>
			</td>
			<input type="submit" name="btnUpdate" class="form_button" value="Update" />
		</form>
		<br/><br/>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="15%"><div class="admtitle">Current Status : </div></td>
				<td width="85%" >
					<?=getStatus()?>
				</td>
			</tr>
		</table>
	</div>
</form>