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
$proID = base64_decode(urldecode($_GET["id"]));

if((isset($_POST["btnAdd"])) || (isset($_POST["btnEdit"]))){
	$proID = mysql_real_escape_string($_POST["proID"]);
	$proBrand = mysql_real_escape_string($_POST["proBrand"]);
	$proModel = mysql_real_escape_string($_POST["proModel"]);
	$proMPrice = mysql_real_escape_string($_POST["proMPrice"]);
	$proAPrice = mysql_real_escape_string($_POST["proAPrice"]);
	$proCategory = mysql_real_escape_string($_POST["proCategory"]);
	$proAva = mysql_real_escape_string($_POST["proAva"]);
	$proDesc = mysql_real_escape_string($_POST['proDesc']);
	$proStart = mysql_real_escape_string($_POST['picker_start']);
	$proEnd = mysql_real_escape_string($_POST['picker_end']);
	$proBids = mysql_real_escape_string($_POST['proBids']);
	$proImage = mysql_real_escape_string($_POST["proImage"]);
	
	$error = NULL;
	if(empty($proBrand)){
		$error .= '<li>Brand is required';
	}
	if(empty($proModel)){
		$error .= '<li>Model is required';
	}
	if(empty($proMPrice)){
		$error .= '<li>Market Price is required';
	}
	if(empty($proAPrice)){
		$error .= '<li>Auction Price is required';
	}
	if(empty($proCategory)){
		$error .= '<li>Category is required';
	}
	if(empty($proDesc)){
		$error .= '<li>Description is required';
	}
	if(empty($proStart)){
		$error .= '<li>Start Date is required';
	}
	if(empty($proEnd)){
		$error .= '<li>End Date is required';
	}
		
	if(!empty($error)){
		$echo_err = "<div style='width:245px;color:#d50000;text-align:left;'><ul>".$error."</ul></div>";
	}else{
		if(isset($_POST["btnAdd"])){
			$sql = registerProduct($proID, $proBrand, $proModel, $proMPrice, $proAPrice, $proCategory, $proAva, $proDesc, $proStart, $proEnd, $proBids);
			$text1 = "Product Added";		
		}else 
		if(isset($_POST["btnEdit"])){
			$sql = updateProduct($proID, $proBrand, $proModel, $proMPrice, $proAPrice, $proCategory, $proAva, $proDesc, $proStart, $proEnd, $proBids);
			$text1 = "Product Information has been updated";	
		}
	}
	
	if(isset($sql)){
		$blk_action = "<div style='width:400px;color:#58b300;text-align:center;'>".$text1.".</div>";
	}else{
		$blk_action = $echo_err;	
	}
}

if($action == "add"){
	$blk_action .= "<script type='text/javascript'>document.getElementById('proGetID').disabled=true;</script>";
	$blk_action .= "<div class='smallfont'>All Fields are required to be filled.</div>
					<fieldset style='margin:8px 0 0 0;padding:8px;background-color:#f6f7f9;border:1px solid #e9e9e9;'>
						<legend><b>Personal Information</b></legend>
						<div align='left' style='width:800px;'>
							<table width='100%' border='0' cellspacing='4' cellpadding='0'>
								<tr>
									<td width='45%' align='right'>Product ID :</td>
									<td width='55%'>
										<input type='hidden' name='proID' value='' style='width:200px;'/>
										<input type='text' value='' style='width:200px;' disabled=disabled/>
									</td>
								</tr>
								<tr>
									<td width='45%' align='right'>Brand :</td>
									<td width='55%'><input name='proBrand' type='text' value='' style='width:200px;' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Model :</td>
									<td width='55%'><input name='proModel' type='text' value='' style='width:200px;' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Market Price :</td>
									<td width='55%'><input name='proMPrice' type='text' value='' style='width:200px;' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Auction Price :</td>
									<td width='55%'><input name='proAPrice' type='text' value='' style='width:200px;' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Category :</td>
									<td width='55%'>
										<select name='proCategory' style='width:152px;' required>";
										$category_list = getCategoryList();
										$totalCategory = getTotalCategory();
										for($i=0;$i<$totalCategory;$i++) {
											$blk_action .= '<option value="'.$category_list[$i]["cid"].'" >'.$category_list[$i]["cname"].'</option>';
										}
	$blk_action .= "					</select>
									</td>
								</tr>
								<tr>
									<td width='45%' align='right'>Availability :</td>
									<td width='55%'>
										<select id='proAva' name='availablity' size='1' tabindex='3'>
											<option value='0' >Not Available</option>
											<option value='1' >Available</option>
											<option value='2' >Coming Soon</option>
										</select>
									</td>
								</tr>
								<tr>
									<td width='45%' align='right'>Description :</td>
									<td width='55%'>
										<textarea id='tinyeditor' name='proDesc' rows='15' cols='80' style='width: 80%'></textarea>
										<script>
										var editor = new TINY.editor.edit('editor', {
											id: 'tinyeditor',
											width: 584,
											height: 175,
											cssclass: 'tinyeditor',
											controlclass: 'tinyeditor-control',
											rowclass: 'tinyeditor-header',
											dividerclass: 'tinyeditor-divider',
											controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'subscript', 'superscript', '|',
												'orderedlist', 'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign',
												'centeralign', 'rightalign', 'blockjustify', '|', 'unformat', '|', 'undo', 'redo', 'n',
												'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink', '|', 'print'],
											footer: true,
											fonts: ['Verdana','Arial','Georgia','Trebuchet MS'],
											xhtml: true,
											cssfile: 'custom.css',
											bodyid: 'editor',
											footerclass: 'tinyeditor-footer',
											toggle: {text: 'wysiwyg', activetext: 'source', cssclass: 'toggle'},
											resize: {cssclass: 'resize'}});
										</script>
									</td>
								</tr>
								<tr>
									<td width='45%' align='right'>Start Date :</td>
									<td width='55%'><input type='text' id='datepicker' name='picker_start' value='' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>End Date :</td>
									<td width='55%'><input type='text' id='datepicker2' name='picker_end' value='' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Total Bids :</td>
									<td width='55%'><input name='proBids' type='text' value='' style='width:200px;' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Product Image :</td>
									<td width='55%'><input type='file' name='proImage' id='proImage'/></td>
								</tr>
							</table>
						</div>
						<div align='center'><input name='btnAdd' type='submit' value='Add' style='width:80px;' /></div>
					</fieldset>";
}

if($action == "edit"){
	$edit_rows = getProductList($proID, 0, 1);	
	$blk_action .= "<div class='smallfont'>All Fields are required to be filled.</div>
					<fieldset style='margin:8px 0 0 0;padding:8px;background-color:#f6f7f9;border:1px solid #e9e9e9;'>
						<legend><b>Personal Information</b></legend>
						<div align='left' style='width:800px;'>
							<table width='100%' border='0' cellspacing='4' cellpadding='0'>
								<tr>
									<td width='45%' align='right'>Product ID :</td>
									<td width='55%'>
										<input type='hidden' name='proID' value='".$edit_rows[0]['pid']."' style='width:200px;'/>
										<input type='text' value='".$edit_rows[0]['pid']."' style='width:200px;' disabled=disabled/>
									</td>
								</tr>
								<tr>
									<td width='45%' align='right'>Brand :</td>
									<td width='55%'><input name='proBrand' type='text' value='".$edit_rows[0]['brand']."' style='width:200px;' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Model :</td>
									<td width='55%'><input name='proModel' type='text' value='".$edit_rows[0]['model']."' style='width:200px;' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Market Price :</td>
									<td width='55%'><input name='proMPrice' type='text' value='".$edit_rows[0]['mprice']."' style='width:200px;' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Auction Price :</td>
									<td width='55%'><input name='proAPrice' type='text' value='".$edit_rows[0]['aprice']."' style='width:200px;' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Category :</td>
									<td width='55%'>
										<select name='proCategory' style='width:152px;' required>";
										$category_list = getCategoryList();
										$totalCategory = getTotalCategory();
										for($i=0;$i<$totalCategory;$i++) {
											$select = ($edit_rows[0]['category'] == $category_list[$i]["cid"]) ? 'selected' : '';
											$blk_action .= '<option value="'.$category_list[$i]["cid"].'" '.$select.'>'.$category_list[$i]["cname"].'</option>';
										}
	$blk_action .= "					</select>
									</td>
								</tr>
								<tr>
									<td width='45%' align='right'>Availability :</td>
									<td width='55%'>
										<select id='proAva' name='proAva' size='1' tabindex='3'>
											<option value='0' ". (($edit_rows[0]['category'] == '0') ? 'selected' : "") ." >Not Available</option>
											<option value='1' ". (($edit_rows[0]['category'] == '1') ? 'selected' : "") ." >Available</option>
											<option value='2' ". (($edit_rows[0]['category'] == '2') ? 'selected' : "") ." >Coming Soon</option>
										</select>
									</td>
								</tr>
								<tr>
									<td width='45%' align='right'>Description :</td>
									<td width='55%'>
										<textarea id='tinyeditor' name='proDesc' rows='15' cols='80' style='width: 80%' >".$edit_rows[0]['description']."</textarea>
										<script>
										var editor = new TINY.editor.edit('editor', {
											id: 'tinyeditor',
											width: 584,
											height: 175,
											cssclass: 'tinyeditor',
											controlclass: 'tinyeditor-control',
											rowclass: 'tinyeditor-header',
											dividerclass: 'tinyeditor-divider',
											controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'subscript', 'superscript', '|',
												'orderedlist', 'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign',
												'centeralign', 'rightalign', 'blockjustify', '|', 'unformat', '|', 'undo', 'redo', 'n',
												'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink', '|', 'print'],
											footer: true,
											fonts: ['Verdana','Arial','Georgia','Trebuchet MS'],
											xhtml: true,
											cssfile: 'custom.css',
											bodyid: 'editor',
											footerclass: 'tinyeditor-footer',
											toggle: {text: 'wysiwyg', activetext: 'source', cssclass: 'toggle'},
											resize: {cssclass: 'resize'}});
										</script>
									</td>
								</tr>
								<tr>
									<td width='45%' align='right'>Start Date :</td>
									<td width='55%'><input type='text' id='datepicker' name='picker_start' value='".$edit_rows[0]['astart']."' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>End Date :</td>
									<td width='55%'><input type='text' id='datepicker2' name='picker_end' value='".$edit_rows[0]['aend']."' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Total Bids :</td>
									<td width='55%'><input name='proBids' type='text' value='".$edit_rows[0]['bid']."' style='width:200px;' required/></td>
								</tr>
								<tr>
									<td width='45%' align='right'>Product Image :</td>
									<td width='55%'><input type='file' name='proImage' id='proImage'/></td>
								</tr>
							</table>
						</div>
						<div align='center'><input name='btnEdit' type='submit' value='Save' style='width:80px;' /></div>
					</fieldset>";
}

if(isset($_POST['btnGET'])) {
	$proID = mysql_real_escape_string($_POST["proGetID"]);
	header("location:".mainPageURL()."/admin/product/product_edit.php?action=edit&id='".urlencode(base64_encode($proID))."'");
}
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
		$("#datepicker2").datepicker({ 
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true ,
			defaultDate: '1990-01-01'
		});
	});
	</script>
	
	<!-- TinyEditor -->
	<script type="text/javascript" src="<?=mainPageURL()?>/libs/tinyeditor/tiny.editor.packed.js"></script>
	<link rel="stylesheet" href="<?=mainPageURL()?>/libs/tinyeditor/tinyeditor.css" type="text/css" />	
	
	<?=$echo_error;?>
	<div class="admcontent" align="left">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="30%"><div class="admtitle">User Editing</div></td>
				<td width="70%" align="right">
					<div>
						<input type="button" onclick="location.href='?action=add'" value="Add New Product" style="width:140px;" />
					</div>
				</td>
			</tr>
		</table>
	<form action="" method="post" id="form" enctype="multipart/form-data">				
		<div style="margin:12px 0;">
			Product ID : 
			<input type="text" name="proGetID" id="proGetID" value=""/>
			<input type="submit" name="btnGET" value="Edit Product" style="width:140px;" />
		</div>
		<div><?=$blk_action?></div>
	</div>
</form>