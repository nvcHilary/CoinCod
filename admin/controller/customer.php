<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'customer')) {
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
	$sort = 'username';
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

$total_customer = getTotalCustomers();

$customers = getCustomers($data);

$pagination = new Pagination();
$pagination->total = $total_customer;
$pagination->page = $page;
$pagination->limit = $numrows;
$pagination->url = "customer/page{page}.html";
$pagination = $pagination->render();

$url = '';

if ($order == 'ASC') {
	$url .= '/DESC';
} else {
	$url .= '/ASC';
}

if( ($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['selected'])) ){
	foreach ($_POST['selected'] as $customer_id) {
		$delete_query = deleteCustomer($customer_id);
		if($delete_query) {
			$_SESSION['success'] = $lang['success_delete'];
			header('Location:customer.html');
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
		<a href="customer.html"><?php echo $lang['text_customer']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/user.png" alt="" /> <?php echo $lang['heading_customer']; ?></h1>
			<div class="buttons">
				<a onclick="location = 'customer_form.html'" class="button"><?php echo $lang['button_insert']; ?></a>
				<a onclick="$('form').submit();" class="button"><?php echo $lang['button_delete']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="customer.html" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left">
								<?php if ($sort == 'username') { ?>
									<a href="customer/page1/username<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_username']; ?></a>
								<?php } else { ?>
									<a href="customer/page1/username<?php echo $url; ?>.html" ><?php echo $lang['column_username']; ?></a>
								<?php } ?>
							</td>
							<td class="left">
								<?php if ($sort == 'date_added') { ?>
									<a href="customer/page1/date_added<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_date_added']; ?></a>
								<?php } else { ?>
									<a href="customer/page1/date_added<?php echo $url; ?>.html" ><?php echo $lang['column_date_added']; ?></a>
								<?php } ?>
							</td>
							<td class="right"><?php echo $lang['column_action']; ?></td>
						</tr>
					</thead>
					 <tbody>
						<?php if ($customers) { ?>
							<?php foreach ($customers as $a => $customer) { ?>
								<tr>
									<td style="text-align: center;">
										<?php if ($customer['selected']) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left"><?php echo $customer['username']; ?></td>
									<td class="left"><?php echo $customer['date_added']; ?></td>
									<td class="right">
											[ <a href="customer_form/customer<?php echo $customer['customer_id']; ?>.html"><?php echo $lang['text_edit']; ?></a> ]
									</td>
								</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="center" colspan="5"><?php echo $lang['text_no_results']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>