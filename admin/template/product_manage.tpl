<?php
ob_start();
session_start();
require_once '../../config.php';
require_once '../../sql_function.php';

$totalProduct = getTotalProduct(0);
// protect authorize page
if(empty($_SESSION["loggedId"])){
	header("Location:".mainPageURL()."/admin/login/");
}

mysql_set_charset("utf8");

$no = 1;
$blk_action=NULL;
$action = $_GET["action"];
$id = base64_decode(urldecode($_GET["id"]));

if($action == "delete"){
	$q_del = deleteProduct($id);
	
	if(isset($q_del)){
		$blk_action = "<div style='width:400px;color:#58b300;text-align:center;'>Product Deleted</div>";
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

////$numrows = $row["num"];
$numrows = $totalProduct;
$maxPage = ceil($numrows/$rowsPerPage); // pages will be needed to display all 

$padding = 3;
$lo = ($pageNum - $padding < 1)? 1 : $pageNum - $padding;
$hi = ($pageNum + $padding > $maxPage)? $maxPage : $pageNum + $padding;

$nav = ""; // page navigate link

for($page = $lo; $page <= $hi; $page++){
	if($page == $pageNum){
   		$nav .= "<b>Page ".$page."</b>";
	}else{
		$nav .= " <a style='color:#000;' href='".mainPageURL()."/admin/product/?page=".$page."'>".$page."</a> ";
	}
}

if($pageNum > 1){
	$lastNum = ($rowsPerPage * $pageNum) - $rowsPerPage;
	$n = $lastNum + 1;

	$page = $pageNum - 1;
	$prev = " <a href='".mainPageURL()."/admin/product/?page=".$page."'><img src='".mainPageURL()."/admin/images/arrw_left.gif' title='previous'></a> ";
	$first =" <a href='".mainPageURL()."/admin/product/?page=1'><img src='".mainPageURL()."/admin/images/arrw_first.gif' title='first'></a> ";
}else{
	$prev  = '&nbsp;';
	$first = '&nbsp;';
}

if($pageNum < $maxPage){
	$page = $pageNum + 1;
	$next = " <a href='".mainPageURL()."/admin/product/?page=".$page."'><img src='".mainPageURL()."/admin/images/arrw_right.gif' title='next'></a> ";
	$last = " <a href='".mainPageURL()."/admin/product/?page=".$maxPage."'><img src='".mainPageURL()."/admin/images/arrw_last.gif' title='last'></a> ";
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
				<td width="30%"><div class="admtitle">Product Listing</div></td>
				<td width="70%" align="right">
					<div>
						<input type="button" onclick="location.href='product_edit.php?action=add'" value="Add New Product" style="width:140px;" />
					</div>
				</td>
			</tr>
		</table>
					
		<div><?=$blk_action?></div>
		<div style="margin:12px 0;">
			<table class="admtbcss" width="100%" border="0" cellspacing="0" cellpadding="0" >
				<tr>
					<th width="3%">No.</th>
					<th width="7%">Product Id</th>
					<th width="8%">Brand</th>
					<th width="12%">Model</th>
					<th width="20%">Market Price</th>
					<th width="10%">Auction Price</th>
					<th width="10%">Category</th>
					<th width="10%">Avalability</th>
					<th width="10%">Total Bid</th>
					<th width="10%">Start Date</th>
					<th width="10%">End Date</th>
					<th width="">Action</th>
				</tr>
				<?php
				if($totalProduct > 0) {
					$product_data = getProductListing($start, $rowsPerPage);
					if($rowsPerPage > $totalProduct) {
						$runData = $totalProduct;
					} else {
						$runData = $rowsPerPage;
					}
					for($i=0;$i<$runData;$i++) {	
						if($product_data[$i]['availability'] == 0) {
							$ava = "Not Available";
						} else if($product_data[$i]['availability'] == 1) {
							$ava = "Available";
						} else if($product_data[$i]['availability'] == 2) {
							$ava = "Coming Soon";
						}
						
						//if ($user_data[$i]['status'] == 1) {
						//	$status = "Active";
						//} else if($user_data[$i]['status'] == 0) {
						//	$status = "<div style='color:red;'>Inactive</div>";
						//}
						
						if($no == 1){
							$delete = NULL;	
						}else{
							$delete = "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href='".mainPageURL()."/admin/product/?action=delete&id=".urlencode(base64_encode($product_data[$i]["pid"]))."' class='alternate'>Delete</a>";	
						}
						
						echo "<tr>
								<td>".$no.".</td>
								<td>".$product_data[$i]["pid"]."</td>
								<td>".$product_data[$i]["brand"]."</td>
								<td>".$product_data[$i]["model"]."</td>
								<td>".$product_data[$i]["mprice"]."</td>
								<td>".$product_data[$i]["aprice"]."</td>
								<td>".$product_data[$i]["category_name"]."</td>
								<td>".$ava."</td>
								<td>".$product_data[$i]["bid"]."</td>
								<td>".$product_data[$i]["astart"]."</td>
								<td>".$product_data[$i]["aend"]."</td>					
								<td align='center'>
									<a href='".mainPageURL()."/admin/product/product_edit.php?action=edit&id=".urlencode(base64_encode($product_data[$i]["pid"]))."' class='alternate'>Edit</a>".$delete."
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