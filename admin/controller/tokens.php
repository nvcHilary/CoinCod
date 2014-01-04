<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'tokens')) {
	header('Location:permission.html');
}

if (isset($_SESSION['success'])) {
	$success = $_SESSION['success'];
	unset($_SESSION['success']);
} else {
	$success = '';
}

if (isset($_SESSION['error_warning'])) {
	$error_warning = $_SESSION['error_warning'];
	unset($_SESSION['error_warning']);
} else {
	$error_warning = '';
}

$total_token = getTotalToken();

if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 1;
}

$numrows = 20;

$data = array(
	'start' 	=> ($page - 1) * $numrows,
	'limit' 	=> $numrows
);

$tokens = getTokens($data);

$pagination = new Pagination();
$pagination->total = $total_token;
$pagination->page = $page;
$pagination->limit = $numrows;
$pagination->url = "token/page{page}.html";
$pagination = $pagination->render();

if( ($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['selected'])) ){
	foreach ($_POST['selected'] as $token_id) {
		$delete_query = deleteToken($token_id);
		if($delete_query) {
			$_SESSION['success'] = $lang['success_delete'];
			header('Location:tokens.html');
		} else {
			$error_warning = $lang['error_query'];
		}
	}
}
?>
<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="tokens.html"><?php echo $lang['text_tokens']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div id="status"></div>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_tokens']; ?></h1>
			<div class="buttons">
				<a id="add" class="button" onclick="location = 'tokens_form.html'"><?php echo $lang['button_add']; ?></a>
				<a id="delete" class="button" onclick="$('form').submit();"><?php echo $lang['button_delete']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="tokens.html" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left"><?php echo $lang['column_token_name']; ?></td>
							<td class="left"><?php echo $lang['column_token_discount']; ?></td>
							<td class="left"><?php echo $lang['column_token_code']; ?></td>
							<td class="left"><?php echo $lang['column_token_price']; ?></td>
							<td class="right"><?php echo $lang['column_action']; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($tokens) { ?>
							<?php foreach ($tokens as $token) { ?>
								<tr>
									<td style="text-align: center;">
										<?php if ($token['selected']) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $token['token_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $token['token_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left"><?php echo $token['name']; ?></td>
									<td class="left"><?php echo $token['discount']; ?></td>
									<td class="left"><?php echo $token['code']; ?></td>
									<td class="left"><?php echo $token['price']; ?></td>
									<td class="right">[ <a href="tokens_form/tokens<?php echo $token['token_id']; ?>.html"><?php echo $lang['text_edit']; ?></a> ]</td>
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