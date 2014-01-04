<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'product')) {
	header('Location:permission.html');
}

if (isset($_SESSION['error_warning'])) {
	$error_warning = $_SESSION['error_warning'];
	unset($_SESSION['error_warning']);
} else {
	$error_warning = '';
}

if (isset($_SESSION['success'])) {
	$success = $_SESSION['success'];
	unset($_SESSION['success']);
} else {
	$success = '';
}

if (isset($_GET['sort'])) {
	$sort = $_GET['sort'];
} else {
	$sort = 'brand';
}

if (isset($_GET['order'])) {
	$order = $_GET['order'];
} else {
	$order = 'ASC';
}

if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 1;
}

$numrows = 20;

$data = array(
	'sort'  => $sort,
	'order' => $order,
	'start' => ($page - 1) * $numrows,
	'limit' => $numrows
);

$total_product = getTotalProducts();

$products = getProducts($data);

$pagination = new Pagination();
$pagination->total = $total_product;
$pagination->page = $page;
$pagination->limit = $numrows;
$pagination->url = "product/page{page}.html";
$pagination = $pagination->render();

$url = '';

if ($order == 'ASC') {
	$url .= '/DESC';
} else {
	$url .= '/ASC';
}

if( ($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['selected'])) ){
	foreach ($_POST['selected'] as $product_id) {
		$delete_query = deleteProduct($product_id);
		if($delete_query) {
			$_SESSION['success'] = $lang['success_delete'];
			header('Location:product.html');
		} else {
			$_SESSION['error_warning'] = $lang['error_query'];
		}
	}
}
?>
<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="product.html"><?php echo $lang['text_product']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_product']; ?></h1>
			<div class="buttons">
				<a onclick="location = 'product_form.html'" class="button"><?php echo $lang['button_insert']; ?></a>
				<a onclick="$('form').submit();" class="button"><?php echo $lang['button_delete']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="product.html" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left">
								<?php if ($sort == 'brand') { ?>
									<a href="product/page1/brand<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_brand']; ?></a>
								<?php } else { ?>
									<a href="product/page1/brand<?php echo $url; ?>.html" ><?php echo $lang['column_brand']; ?></a>
								<?php } ?>
							</td>
							<td class="left">
								<?php if ($sort == 'model') { ?>
									<a href="product/page1/model<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_model']; ?></a>
								<?php } else { ?>
									<a href="product/page1/model<?php echo $url; ?>.html" ><?php echo $lang['column_model']; ?></a>
								<?php } ?>
							</td>
							<td class="left">
								<?php if ($sort == 'aprice') { ?>
									<a href="product/page1/aprice<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_aprice']; ?></a>
								<?php } else { ?>
									<a href="product/page1/aprice<?php echo $url; ?>.html" ><?php echo $lang['column_aprice']; ?></a>
								<?php } ?>
							</td>
							<td class="left">
								<?php if ($sort == 'availability') { ?>
									<a href="product/page1/availability<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_availability']; ?></a>
								<?php } else { ?>
									<a href="product/page1/availability<?php echo $url; ?>.html" ><?php echo $lang['column_availability']; ?></a>
								<?php } ?>
							</td>
							<td class="left">
								<?php if ($sort == 'bids') { ?>
									<a href="product/page1/bids<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_bids']; ?></a>
								<?php } else { ?>
									<a href="product/page1/bids<?php echo $url; ?>.html" ><?php echo $lang['column_bids']; ?></a>
								<?php } ?>
							</td>
							<td class="left"><?php echo $lang['column_image']; ?></td>
							<td class="right"><?php echo $lang['column_action']; ?></td>
						</tr>
					</thead>
					 <tbody>
						<?php if (isset($products)) { ?>
							<?php foreach ($products as $a => $product) { ?>
								<tr>
									<td style="text-align: center;">
										<?php if (isset($product['selected'])) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left"><?php echo $product['brand']; ?></td>
									<td class="left"><?php echo $product['model']; ?></td>
									<td class="left"><?php echo $product['aprice']; ?></td>
									<td class="left"><?php echo $product['availability']; ?></td>
									<td class="left"><?php echo $product['bids']; ?></td>
									<td class="left">
										<?php
										$data_image = array(
											'product_id' 	=> $product['product_id'],
											'start'			=> 0,
											'limit'			=> 1
										);
										$folder = 'data/image/product/' . $product['product_id'] . '/';
										$images = getProductImage($data_image);
										if($images) {
											foreach($images as $image) {
												echo '<img id="thumb' . $image['image_id'] . '" src="../' . $folder . $image['image'] .'" " width="100px" height="100px" />';
											}
										} else {
											echo '<img id="thumb" src="../data/image/no_image.jpg" " width="100px" height="100px" />';
										}
										?>
									</td>
									<td class="right">
										[ <a href="product_image/product<?php echo $product['product_id']; ?>.html"><?php echo $lang['text_upload_image']; ?></a> ]
										[ <a href="product_form/product<?php echo $product['product_id']; ?>.html"><?php echo $lang['text_edit']; ?></a> ]
									</td>
								</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="center" colspan="6"><?php echo $lang['text_no_results']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>