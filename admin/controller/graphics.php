<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'graphics')) {
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

$total_graphics = getTotalGraphics();

$graphics = getGraphics($data);

$pagination = new Pagination();
$pagination->total = $total_graphics;
$pagination->page = $page;
$pagination->limit = $numrows;
$pagination->url = "graphics/page{page}.html";
$pagination = $pagination->render();

if( ($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['selected'])) ){
	foreach ($_POST['selected'] as $graphics_id) {
		$delete_query = deleteGraphics($graphics_id);
		if($delete_query) {
			$_SESSION['success'] = $lang['success_delete'];
			header('Location:graphics.html');
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
		<a href="graphics.html"><?php echo $lang['text_graphics']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_graphics']; ?></h1>
			<div class="buttons">
				<a onclick="location = 'graphics_form.html'" class="button"><?php echo $lang['button_insert']; ?></a>
				<a onclick="$('form').submit();" class="button"><?php echo $lang['button_delete']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="graphics.html" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left"><?php echo $lang['column_name']; ?></td>
							<td class="left"><?php echo $lang['column_image']; ?></td>
							<td class="right"><?php echo $lang['column_action']; ?></td>
						</tr>
					</thead>
					 <tbody>
						<?php if (isset($graphics)) { ?>
							<?php foreach ($graphics as $a => $graphic) { ?>
								<tr>
									<td style="text-align: center;">
										<?php if (isset($graphic['selected'])) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $graphic['graphics_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $graphic['graphics_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left"><?php echo $graphic['name']; ?></td>
									<td class="left">
										<?php
										$data_image = array(
											'graphics_id' 	=> $graphic['graphics_id'],
											'start'			=> 0,
											'limit'			=> 1
										);
										$folder = 'data/image/graphics/';
										if($graphic['image'] != '') {
											echo '<img id="thumb' . $graphic['graphics_id'] . '" src="../' . $folder . $graphic['image'] .'" " width="100px" height="100px" />';
										} else {
											echo '<img id="thumb" src="../data/image/no_image.jpg" " width="100px" height="100px" />';
										}
										?>
									</td>
									<td class="right">
										[ <a href="graphics_form/graphics<?php echo $graphic['graphics_id']; ?>.html"><?php echo $lang['text_edit']; ?></a> ]
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