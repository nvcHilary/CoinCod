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

$data_setting = array(
	'group'	=> 'page'
);
$settings = getSettings($data_setting);

?>
<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="information.html"><?php echo $lang['text_information']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/user.png" alt="" /> <?php echo $lang['heading_information']; ?></h1>
		</div>
		<div class="content">
			<form action="information.html" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td class="left"><?php echo $lang['column_numbering']; ?></td>
							<td class="left"><?php echo $lang['column_page_name']; ?></td>
							<td class="right"><?php echo $lang['column_action']; ?></td>
						</tr>
					</thead>
					 <tbody>
						<?php $no = 1; ?>
						<?php if ($settings) { ?>
							<?php foreach ($settings as $a => $setting) { ?>
								<tr>
									<td class="left"><?php echo $no; ?></td>
									<td class="left"><?php echo $setting['key']; ?></td>
									<td class="right">
											[ <a href="information_form/information<?php echo $setting['information_id']; ?>.html"><?php echo $lang['text_edit']; ?></a> ]
									</td>
								</tr>
							<?php 
								$no++;
							} ?>
						<?php } else { ?>
							<tr>
								<td class="center" colspan="6"><?php echo $lang['text_no_results']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>