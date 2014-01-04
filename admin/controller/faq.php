<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'Settings')) {
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

if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 1;
}

$numrows = 20;

$data = array(
	'start' => ($page - 1) * $numrows,
	'limit' => $numrows
);

$total_faq = getTotalFAQ();

$faqs = getFAQs($data);

$pagination = new Pagination();
$pagination->total = $total_faq;
$pagination->page = $page;
$pagination->limit = $numrows;
$pagination->url = "faq/page{page}.html";
$pagination = $pagination->render();

if( ($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['selected'])) ){
	foreach ($_POST['selected'] as $faq_id) {
		$delete_query = deleteFAQ($faq_id);
		if($delete_query) {
			$_SESSION['success'] = $lang['success_delete'];
			header('Location:faq.html');
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
		<a href="faq.html"><?php echo $lang['text_faq']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/user.png" alt="" /> <?php echo $lang['heading_faq']; ?></h1>
			<div class="buttons">
				<a onclick="location = 'faq_form.html'" class="button"><?php echo $lang['button_insert']; ?></a>
				<a onclick="$('form').submit();" class="button"><?php echo $lang['button_delete']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="faq.html" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left"><?php echo $lang['column_question']; ?></td>
							<td class="right"><?php echo $lang['column_action']; ?></td>
						</tr>
					</thead>
					 <tbody>
						<?php if (isset($faqs)) { ?>
							<?php foreach ($faqs as $a => $faq) { ?>
								<tr>
									<td style="text-align: center;">
										<?php if (isset($faq['selected'])) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $faq['faq_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $faq['faq_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left"><?php echo $faq['question']; ?></td>
									<td class="right">
											[ <a href="faq_form/faq<?php echo $faq['faq_id']; ?>.html"><?php echo $lang['text_edit']; ?></a> ]
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