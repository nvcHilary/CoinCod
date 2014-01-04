<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'user_group')) {
	header('Location:' . mainPageURL() . 'admin/permission.html');
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
	$sort = 'name';
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

$total_group = getTotalGroups();

$user_groups = getGroups($data);

$pagination = new Pagination();
$pagination->total = $total_group;
$pagination->page = $page;
$pagination->limit = $numrows;
$pagination->url = "user_group/page{page}.html";
$pagination = $pagination->render();

$url = '';

if ($order == 'ASC') {
	$url .= '/DESC';
} else {
	$url .= '/ASC';
}

if( ($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['selected'])) ){
	foreach ($_POST['selected'] as $group_id) {
		$delete_query = deleteGroup($group_id);
		if($delete_query) {
			$_SESSION['success'] = $lang['success_delete'];
			header('Location:user_group.html');
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
		<a href="user_group.html"><?php echo $lang['text_user_group']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/user-group.png" alt="" /> <?php echo $lang['heading_user_group']; ?></h1>
			<div class="buttons">
				<a onclick="location = 'user_group_form.html'" class="button"><?php echo $lang['button_insert']; ?></a>
				<a onclick="$('form').submit();" class="button"><?php echo $lang['button_delete']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="user_group.html" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left">
								<?php if ($sort == 'name') { ?>
									<a href="user_group/page1/name<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_group_name']; ?></a>
								<?php } else { ?>
									<a href="user_group/page1/name<?php echo $url; ?>.html"><?php echo $lang['column_group_name']; ?></a>
								<?php } ?></td>
							<td class="right"><?php echo $lang['column_action']; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if (isset($user_groups)) { ?>
							<?php foreach ($user_groups as $user_group) { ?>
								<tr>
									<td style="text-align: center;">
										<?php if (isset($user_group['selected'])) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $user_group['user_group_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $user_group['user_group_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left"><?php echo $user_group['name']; ?></td>
									<td class="right">
										[ <a href="user_group_form/group<?php echo $user_group['user_group_id']; ?>.html"><?php echo $lang['text_edit']; ?></a> ]
									</td>
								</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="center" colspan="3"><?php echo $lang['text_no_results']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>