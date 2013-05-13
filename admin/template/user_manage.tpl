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

$no = 1;
$blk_action=NULL;
$action = $_GET["action"];
$email = base64_decode(urldecode($_GET["id"]));

if((isset($_POST["btnAdd"])) || (isset($_POST["btnEdit"]))){
	$txtID = mysql_real_escape_string($_POST["txtID"]);
	$txtCategory = mysql_real_escape_string($_POST["txtCategory"]);
	$txtUsername = mysql_real_escape_string($_POST["txtUsername"]);
	$txtPassword = mysql_real_escape_string($_POST["txtPassword"]);
	$txtRepassword = mysql_real_escape_string($_POST["txtRepassword"]);
	$txtEmail = mysql_real_escape_string($_POST["txtEmail"]);
	$txtStatus = mysql_real_escape_string($_POST["txtStatus"]);
	$txtToken = mysql_real_escape_string($_POST["txtToken"]);
	$txtFName = mysql_real_escape_string($_POST["txtFName"]);
	$txtLName = mysql_real_escape_string($_POST["txtLName"]);
	$txtDOB = mysql_real_escape_string($_POST["datepicker"]);
	$txtGender = mysql_real_escape_string($_POST["txtGender"]);
	$txtAdd1 = mysql_real_escape_string($_POST["txtAdd1"]);
	$txtAdd2 = mysql_real_escape_string($_POST["txtAdd2"]);
	$txtCity = mysql_real_escape_string($_POST["txtCity"]);
	$txtState = mysql_real_escape_string($_POST["txtState"]);
	$txtZip = mysql_real_escape_string($_POST["txtZip"]);
	$txtCountry = mysql_real_escape_string($_POST["txtCountry"]);
	$txtMobile = mysql_real_escape_string($_POST["txtMobile"]);
	
	$error = NULL;
	if(empty($txtCategory)){
		$error .= '<li>Category is required';
	}
	if(empty($txtUsername)){
		$error .= '<li>Username is required';
	}
	if($action = "add") {
		if((empty($txtPassword)) || (empty($txtRepassword))){
			$error .= '<li>Password is required';
		}
		if($txtPassword != $txtRepassword) {
			$error .= '<li>Password Not Match!';
		}
	}
	if(empty($txtEmail)){
		$error .= '<li>Email is required';
	}
	if(empty($txtToken)){
		$error .= '<li>Token is required';
	}
	if(empty($txtFName)){
		$error .= '<li>First Name is required';
	}
	if(empty($txtLName)){
		$error .= '<li>Last Name is required';
	}
	if(empty($txtDOB)){
		$error .= '<li>DOB is required';
	}
	if(empty($txtGender)){
		$error .= '<li><u>Gender</u> is required';
	}
	if((empty($txtAdd1)) || (empty($txtAdd2))){
		$error .= '<li>Address is required';
	}
	if(empty($txtCity)){
		$error .= '<li>City is required';
	}
	if(empty($txtState)){
		$error .= '<li>State is required';
	}
	if(empty($txtZip)){
		$error .= '<li>Zip is required';
	}
	if(empty($txtCountry)){
		$error .= '<li>Country is required';
	}
	if(empty($txtMobile)){
		$error .= '<li>Mobile is required';
	}
		
	if(!empty($error)){
		$echo_err = "<div style='width:245px;color:#d50000;text-align:left;'><ul>".$error."</ul></div>";
	}else{
		if(isset($_POST["btnAdd"])){
			$sql = registeruser($txtCategory, $txtEmail, $txtUsername, $txtPassword, $txtRepassword, $txtStatus, $txtToken, $txtFName, $txtLName, $txtDOB, $txtGender, $txtAdd1, $txtAdd2, $txtCity, $txtState, $txtZip, $txtCountry, $txtMobile);
			$text1 = $sql;		
		}else 
		if(isset($_POST["btnEdit"])){
			$sql = updateUser($txtID, $txtEmail, $txtUsername, $txtCategory, $txtStatus, $txtToken, $txtFName, $txtLName, $txtDOB, $txtGender, $txtAdd1, $txtAdd2, $txtCity, $txtState, $txtZip, $txtMobile);
			$text1 = "Member Information has been updated";	
		}
	}
	
	if(isset($sql)){
		$blk_action = "<div style='width:400px;color:#58b300;text-align:center;'>".$text1.".</div>";
	}else{
		$blk_action = $echo_err;	
	}
}

if($action == "add"){
	$blk_action .= "<fieldset>
						<legend><b>Edit Information</b></legend>
						<fieldset style='margin:8px 0 0 0;padding:8px;background-color:#f6f7f9;border:1px solid #e9e9e9;'>
							<legend><b>ID and Password</b></legend>
							<div align='left' style='width:600px;'>
								<table width='100%' border='0' cellspacing='4' cellpadding='0'>
									<tr>
										<td width='45%' align='right'>Category :</td>
										<td width='55%'>
											<select name='txtCategory' style='width:152px;' required>
												<option value='0' selected>--</option>
												<option value='1' >Admin</option>
												<option value='2' >Member</option>
											</select>
										</td>
									</tr>
									<tr>
										<td width='45%' align='right'>Username :</td>
										<td width='55%'><input name='txtUsername' type='text' value='' style='width:200px;' required/></td>
									</tr>
									<tr>
										<td width='45%' align='right'>Password :</td>
										<td width='55%'><input name='txtPassword' type='password' value='' style='width:200px;' required/></td>
									</tr>
									<tr>
										<td width='45%' align='right'>Repeat Password :</td>
										<td width='55%'><input name='txtRepassword' type='password' value='' style='width:200px;' required/></td>
									</tr>
									<tr>
										<td width='45%' align='right'>Email :</td>
										<td width='55%'><input name='txtEmail' type='text' value='' style='width:200px;' required/></td>
									</tr>
									<tr>
										<td width='45%' align='right'>Status :</td>
										<td width='55%'>
											<select name='txtStatus' style='width:152px;' required>
												<option selected>--</option>
												<option value='0' >Inactive</option>
												<option value='1' >Active</option>
											</select>
										</td>
									</tr>
									<tr>
										<td width='45%' align='right'>Token :</td>
										<td width='55%'><input name='txtToken' type='text' value='' style='width:200px;' required/></td>
									</tr>
								</table>
							</div>
						</fieldset>
				
						<fieldset style='margin:8px 0 0 0;padding:8px;background-color:#f6f7f9;border:1px solid #e9e9e9;'>
							<legend><b>Personal Information</b></legend>
								<div align='left' style='width:600px;'>
									<table width='100%' border='0' cellspacing='4' cellpadding='0'>
										<tr>
											<td width='45%' align='right'>First Name :</td>
											<td width='55%'><input name='txtFName' type='text' value='' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>Last Name :</td>
											<td width='55%'><input name='txtLName' type='text' value='' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>DOB :</td>
											<td width='55%'><input type='text' id='datepicker' name='datepicker' value='' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>Gender :</td>
											<td width='55%'>
												<select name='txtGender' style='width:152px;' required>
													<option selected>--</option>
													<option value='M' >Male</option>
													<option value='F' >Female</option>
												</select>
											</td>
										</tr>
										<tr>
											<td width='45%' align='right'>Address 1 :</td>
											<td width='55%'><input name='txtAdd1' type='text' value='' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>Address 2 :</td>
											<td width='55%'><input name='txtAdd2' type='text' value='' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>City :</td>
											<td width='55%'><input name='txtCity' type='text' value='' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>State :</td>
											<td width='55%'>
												<select name='txtState' style='width:152px;' required>
													<option selected>--</option>";
													$state_list = getStateList();
													$totalState = getTotalState();
													for($i=0;$i<$totalState;$i++) {
														$blk_action .= '<option value="'.$state_list[$i]["sid"].'">'.$state_list[$i]["state"].'</option>';
													}
	$blk_action .= "							</select>
											</td>
										</tr>
										<tr>
											<td width='45%' align='right'>Zip :</td>
											<td width='55%'><input name='txtZip' type='text' value='' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>Country :</td>
											<td width='55%'><input name='txtCountry' type='text' value='' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>Mobile :</td>
											<td width='55%'><input name='txtMobile' type='text' value='' style='width:200px;' required/></td>
										</tr>
									</table>
								</div>
							</fieldset>
							<div align='center'><input name='btnAdd' type='submit' value='Save' style='width:80px;' required/></div>
						</fieldset>";
}

if($action == "edit"){
	$edit_rows = getMemberRecords($email);	
	 
	$blk_action .= "<fieldset>
						<legend><b>Edit Information</b></legend>
						<fieldset style='margin:8px 0 0 0;padding:8px;background-color:#f6f7f9;border:1px solid #e9e9e9;'>
							<legend><b>ID and Password</b></legend>
							<div align='left' style='width:600px;'>
								<table width='100%' border='0' cellspacing='4' cellpadding='0'>
									<tr>
										<td width='45%' align='right'>User ID :</td>
										<td width='55%'>
											<input type='text' value='".$edit_rows['id']."' style='width:200px;' disabled=disabled/>
											<input name='txtID' type='hidden' value='".$edit_rows['id']."' style='width:200px;' />
										</td>
									</tr>
									<tr>
										<td width='45%' align='right'>Category :</td>
										<td width='55%'>
											<select name='txtCategory' style='width:152px;'>
												<option value='1' ". (($edit_rows['category'] == '1') ? 'selected' : "") ." >Admin</option>
												<option value='2' ". (($edit_rows['category'] == '2') ? 'selected' : "") ." >Member</option>
											</select>
										</td>
									</tr>
									<tr>
										<td width='45%' align='right'>Username :</td>
										<td width='55%'>
											<input type='text' value='".$edit_rows['username']."' style='width:200px;' disabled=disabled/>
											<input name='txtUsername' type='hidden' value='".$edit_rows['username']."' style='width:200px;' />
										</td>
									</tr>
									<tr>
										<td width='45%' align='right'>Email :</td>
										<td width='55%'>
											<input type='text' value='".$edit_rows['email']."' style='width:200px;' disabled=disabled/>
											<input name='txtEmail' type='hidden' value='".$edit_rows['email']."' style='width:200px;' />
										</td>
									</tr>
									<tr>
										<td width='45%' align='right'>Status :</td>
										<td width='55%'>
											<select name='txtStatus' style='width:152px;'>
												<option value='0' ". (($edit_rows['status'] == '0') ? 'selected' : "") ." >Inactive</option>
												<option value='1' ". (($edit_rows['status'] == '1') ? 'selected' : "") ." >Active</option>
											</select>
										</td>
									</tr>
									<tr>
										<td width='45%' align='right'>Token :</td>
										<td width='55%'><input name='txtToken' type='text' value='".$edit_rows['token']."' style='width:200px;' required/></td>
									</tr>
								</table>
							</div>
						</fieldset>
				
						<fieldset style='margin:8px 0 0 0;padding:8px;background-color:#f6f7f9;border:1px solid #e9e9e9;'>
							<legend><b>Personal Information</b></legend>
								<div align='left' style='width:600px;'>
									<table width='100%' border='0' cellspacing='4' cellpadding='0'>
										<tr>
											<td width='45%' align='right'>First Name :</td>
											<td width='55%'><input name='txtFName' type='text' value='".$edit_rows['fname']."' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>Last Name :</td>
											<td width='55%'><input name='txtLName' type='text' value='".$edit_rows['lname']."' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>DOB :</td>
											<td width='55%'><input type='text' id='datepicker' name='datepicker' value='".$edit_rows['dob']."' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>Gender :</td>
											<td width='55%'>
												<select name='txtGender' style='width:152px;'>
													<option value='M' ". (($edit_rows['gender'] == 'M') ? 'selected' : "") ." >Male</option>
													<option value='F' ". (($edit_rows['gender'] == 'F') ? 'selected' : "") ." >Female</option>
												</select>
											</td>
										</tr>
										<tr>
											<td width='45%' align='right'>Address 1 :</td>
											<td width='55%'><input name='txtAdd1' type='text' value='".$edit_rows['add1']."' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>Address 2 :</td>
											<td width='55%'><input name='txtAdd2' type='text' value='".$edit_rows['add2']."' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>City :</td>
											<td width='55%'><input name='txtCity' type='text' value='".$edit_rows['city']."' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>State :</td>
											<td width='55%'>
												<select name='txtState' style='width:152px;' required>";
													$state_list = getStateList();
													$totalState = getTotalState();
													for($i=0;$i<$totalState;$i++) {
														$select = ($edit_rows['state'] == $state_list[$i]["sid"]) ? 'selected' : '';
														$blk_action .= '<option value="'.$state_list[$i]["sid"].'" '.$select.'>'.$state_list[$i]["state"].'</option>';
													}
	$blk_action .= "							</select>
											</td>
										</tr>
										<tr>
											<td width='45%' align='right'>Zip :</td>
											<td width='55%'><input name='txtZip' type='text' value='".$edit_rows['zip']."' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>Country :</td>
											<td width='55%'><input name='txtCountry' type='text' value='".$edit_rows['country']."' style='width:200px;' required/></td>
										</tr>
										<tr>
											<td width='45%' align='right'>Mobile :</td>
											<td width='55%'><input name='txtMobile' type='text' value='".$edit_rows['mobile']."' style='width:200px;' required /></td>
										</tr>
									</table>
								</div>
							</fieldset>
							<div align='center'><input name='btnEdit' type='submit' value='Save' style='width:80px;' /></div>
						</fieldset>";
}

if($action == "del"){
	$q_del = deleteUser($email);
	
	if(isset($q_del)){
		$blk_action = "<div style='width:400px;color:#58b300;text-align:center;'>User Deleted</div>";
	} else {
		$blk_action = "<div style='color:red;'>Failed to delete user</div>";
	}
}

/* Paginate Start */
$rowsPerPage = 10;
$pageNum = 1;
																		
if(isset($_GET['page'])){
	$pageNum = $_GET['page'];
}

$start = ($pageNum - 1) * $rowsPerPage;

// count total rows in database by condition
////$qPg = "SELECT COUNT(branch_id) AS num FROM ".$tb03;
////$rPg = mysql_query($qPg) or die(mysql_error());
////$row = mysql_fetch_array($rPg, MYSQL_ASSOC);

////$numrows = $row["num"];
$numrows = $totalUser;
$maxPage = ceil($numrows/$rowsPerPage); // pages will be needed to display all 

$padding = 3;
$lo = ($pageNum - $padding < 1)? 1 : $pageNum - $padding;
$hi = ($pageNum + $padding > $maxPage)? $maxPage : $pageNum + $padding;

$nav = ""; // page navigate link

for($page = $lo; $page <= $hi; $page++){
	if($page == $pageNum){
   		$nav .= "<b>Page ".$page."</b>";
	}else{
		$nav .= " <a style='color:#000;' href='".mainPageURL()."/admin/user_manage/?page=".$page."'>".$page."</a> ";
	}
}

if($pageNum > 1){
	$lastNum = ($rowsPerPage * $pageNum) - $rowsPerPage;
	$n = $lastNum + 1;

	$page = $pageNum - 1;
	$prev = " <a href='".mainPageURL()."/admin/user_manage/?page=".$page."'><img src='".mainPageURL()."/admin/images/arrw_left.gif' title='previous'></a> ";
	$first =" <a href='".mainPageURL()."/admin/user_manage/?page=1'><img src='".mainPageURL()."/admin/images/arrw_first.gif' title='first'></a> ";
}else{
	$prev  = '&nbsp;';
	$first = '&nbsp;';
}

if($pageNum < $maxPage){
	$page = $pageNum + 1;
	$next = " <a href='".mainPageURL()."/admin/user_manage/?page=".$page."'><img src='".mainPageURL()."/admin/images/arrw_right.gif' title='next'></a> ";
	$last = " <a href='".mainPageURL()."/admin/user_manage/?page=".$maxPage."'><img src='".mainPageURL()."/admin/images/arrw_last.gif' title='last'></a> ";
}else{
	$next = '&nbsp;';
	$last = '&nbsp;';
}

$paginate = $first.$prev.$nav.$next.$last;
/* Paginate End */

?>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<!--DatePicker Script-->
	<script>
	$(document).ready(function() {
		$("#datepicker").datepicker({ 
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true ,
			defaultDate: '1990-01-01'
		});
	});
	</script>
<form action="" method="post" id="form" enctype="multipart/form-data">
	<div class="admcontent" align="left">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="30%"><div class="admtitle">User Listing</div></td>
				<td width="70%" align="right">
					<div>
						<input type="button" onclick="location.href='?action=add'" value="Add New User" style="width:140px;" />
					</div>
				</td>
			</tr>
		</table>
					
		<div><?=$blk_action?></div>
		<div style="margin:12px 0;">
			<table class="admtbcss" width="100%" border="0" cellspacing="0" cellpadding="0" >
				<tr>
					<th width="3%">No.</th>
					<th width="7%">User Id</th>
					<th width="8%">Auth</th>
					<th width="12%">Username</th>
					<th width="20%">Email</th>
					<th width="10%">Status</th>
					<th width="10%">Token</th>
					<th width="">Date Register</th>
					<th width="">Action</th>
				</tr>
				<?php
				if($totalUser > 0) {
					$user_data = getUserListing($start,$rowsPerPage);
					if($rowsPerPage > $totalUser) {
						$runData = $totalUser;
					} else {
						$runData = $rowsPerPage;
					}
					for($i=0;$i<$runData;$i++) {	
						if($user_data[$i]['category'] == 1) {
							$auth = "Admin";
						} else if($user_data[$i]['category'] == 2) {
							$auth = "Member";
						}
						
						if ($user_data[$i]['status'] == 1) {
							$status = "Active";
						} else if($user_data[$i]['status'] == 0) {
							$status = "<div style='color:red;'>Inactive</div>";
						}
						
						if($no == 1){
							$delete = NULL;	
						}else{
							$delete = "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href='?opt=user_manage&action=del&id=".urlencode(base64_encode($user_data[$i]["id"]))."' class='alternate'>Delete</a>";	
						}
						
						echo "<tr>
								<td>".$no.".</td>
								<td>".$user_data[$i]["id"]."</td>
								<td>".$auth."</td>
								<td>".$user_data[$i]["username"]."</td>
								<td>".$user_data[$i]["email"]."</td>
								<td>".$status."</td>
								<td>".$user_data[$i]["token"]." token(s)</td>
								<td>".$user_data[$i]["date"]."</td>								
								<td align='center'>
									<a href='".mainPageURL()."/admin/user_manage/?action=edit&id=".urlencode(base64_encode($user_data[$i]["email"]))."' class='alternate'>Edit</a>".$delete."
								</td>
						</tr>";
						$no += 1;
					}
				} else {
					echo "no data found";
				}
				?>
			</table>
		</div>
		<div align="right">
			Total&nbsp;<b style="color:#5b0680;"><?=$numrows?></b> user(s) found&nbsp;&nbsp;&nbsp;&nbsp;&diams;&nbsp;&nbsp;
			<?=$paginate?>
        </div>
	</div>
</form>
<?php
////}else{
////	echo "<div class='admcontent'>You <b>DO NOT HAVE PERMISSION</b> to access current page, contact <a style='color:blue;' href='mailto:mis@newaykb.com.my?Subject=Neway Internal Website Error'>MIS</a> if any inquiry.</div>";
////}
?>
