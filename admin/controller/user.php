<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'user')) {
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

$total_user = getTotalUsers();

$users = getUsers($data);

$pagination = new Pagination();
$pagination->total = $total_user;
$pagination->page = $page;
$pagination->limit = $numrows;
$pagination->url = "user/page{page}.html";
$pagination = $pagination->render();

$url = '';

if ($order == 'ASC') {
	$url .= '/DESC';
} else {
	$url .= '/ASC';
}

if( ($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['selected'])) ){
	foreach ($_POST['selected'] as $user_id) {
		$delete_query = deleteUser($user_id);
		if($delete_query) {
			$_SESSION['success'] = $lang['success_delete'];
			header('Location:user.html');
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
		<a href="user.html"><?php echo $lang['text_user']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/user.png" alt="" /> <?php echo $lang['heading_user']; ?></h1>
			<div class="buttons">
				<a onclick="location = 'user_form.html'" class="button"><?php echo $lang['button_insert']; ?></a>
				<a onclick="$('form').submit();" class="button"><?php echo $lang['button_delete']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="user.html" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left">
								<?php if ($sort == 'a.username') { ?>
									<a href="user/page1/a.username<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_username']; ?></a>
								<?php } else { ?>
									<a href="user/page1/a.username<?php echo $url; ?>.html" ><?php echo $lang['column_username']; ?></a>
								<?php } ?>
							</td>
							<td class="left">
								<?php if ($sort == 'a.status') { ?>
									<a href="user/page1/a.status<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_status']; ?></a>
								<?php } else { ?>
									<a href="user/page1/a.status<?php echo $url; ?>.html" ><?php echo $lang['column_status']; ?></a>
								<?php } ?>
							</td>
							<td class="left">
								<?php if ($sort == 'a.user_group_id') { ?>
									<a href="user/page1/a.user_group_id<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_group']; ?></a>
								<?php } else { ?>
									<a href="user/page1/a.user_group_id<?php echo $url; ?>.html" ><?php echo $lang['column_group']; ?></a>
								<?php } ?>
							</td>
							<td class="left">
								<?php if ($sort == 'a.date_added') { ?>
									<a href="user/page1/a.date_added<?php echo $url; ?>.html" class="<?php echo strtolower($order); ?>"><?php echo $lang['column_date_added']; ?></a>
								<?php } else { ?>
									<a href="user/page1/a.date_added<?php echo $url; ?>.html" ><?php echo $lang['column_date_added']; ?></a>
								<?php } ?>
							</td>
							<td class="right"><?php echo $lang['column_action']; ?></td>
						</tr>
					</thead>
					 <tbody>
						<?php if ($users) { ?>
							<?php foreach ($users as $a => $user) { ?>
								<tr>
									<td style="text-align: center;">
										<?php if ($user['selected']) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left"><?php echo $user['username']; ?></td>
									<td class="left">
										<?php if($user['status'] == 1) {
											echo $lang['text_enabled'];
										} else {
											echo $lang['text_disabled']; 
										} ?>
									</td>
									<td class="left"><?php echo $user['group_name']; ?></td>
									<td class="left"><?php echo $user['date_added']; ?></td>
									<td class="right">
											[ <a href="user_form/user<?php echo $user['user_id']; ?>.html"><?php echo $lang['text_edit']; ?></a> ]
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