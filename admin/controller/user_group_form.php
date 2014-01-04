<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'user_group_form')) {
	header('Location:permission.html');
}

if(isset($_GET['group_id'])) {
	$group_id = $_GET['group_id'];
}

if (isset($group_id) && ($_SERVER['REQUEST_METHOD'] != 'POST')) {
	$group_info = getGroupById($group_id);
}

if (isset($_POST['name'])) {
	$name = $_POST['name'];
} elseif (!empty($group_info)) {
	$name = $group_info['name'];
} else {
	$name = '';
}

$ignore = array(
	'controller/login',
	'controller/logout',
	'controller/permission',
	'controller/getState',
	'controller/product_image',
	'controller/product_update',
	'controller/social',
	'controller/email',
	'controller/company',
	'controller/faq',
	'controller/faq_form',
	'controller/status'
);
		
$permissions = array();

$files = glob('controller/*.php');

foreach ($files as $file) {
	$data = explode('/', dirname($file));
	
	$permission_data = end($data) . '/' . basename($file, '.php');
	
	if (!in_array($permission_data, $ignore)) {
		$permissions[] = $permission_data;
	}
}

if (isset($_POST['permission']['access'])) {
	$access = $_POST['permission']['access'];
} elseif (isset($group_info['permission']['access'])) {
	$access = $group_info['permission']['access'];
} else { 
	$access = array();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$action = $_POST['action'];
	$gid = $_POST['group_id'];
	$name = $_POST['name'];
	$permission = $_POST['permission'];
	
	$error = array();
	
	if ((strlen($name) < 3) || (strlen($name) > 20)) {
		$error['name'] = $lang['error_group_name'];
	}

	if (empty($error)) {
		if($action == "insert") {
			$insert_data = array (
				'name'			=> $name,
				'permission'	=> $permission
			);
			$insert_query = insertGroup($insert_data);
			if($insert_query) {
				$_SESSION['success'] = $lang['success_insert'];
				header('Location:user_group.html');
			} else {
				$error_warning = $lang['error_query'];
			}
		} else if ($action == "update") {
			echo "y".$group_id;
			$edit_data = array (
				'groupId'		=> $gid,
				'name'			=> $name,
				'permission'	=> $permission
			);
			
			$edit_query = editGroup($edit_data);
			if($edit_query) {
				$_SESSION['success'] = $lang['success_edit'];
				header('Location:user_group.html');
			} else {
				$error_warning = $lang['error_query'];
			}
		}
	} else {
		$error_name = $error['name'];
	}
}
?>
<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="user_group.html"><?php echo $lang['text_user_group']; ?></a>
	</div>
	<?php if (isset($error_warning)) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/user.png" alt="" /> <?php echo $lang['heading_user_group']; ?></h1>
			<div class="buttons">
				<?php if(isset($group_id)) { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<?php } else { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_insert']; ?></a>
				<?php } ?>
				<a onclick="location = 'user_group.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="user_group_form.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_group_name']; ?></td>
						<td><input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $lang['entry_group_name']; ?>" autofocus required >
						<?php if (isset($error_name)) { ?>
							<span class="error"><?php echo $error_name; ?></span>
						<?php  } ?></td>
					</tr>
					<tr>
						<td><?php echo $lang['entry_access']; ?></td>
						<td>
							<div class="scrollbox">
								<div class="odd">
									<?php if (in_array("Settings", $access)) { ?>
										<input type="checkbox" name="permission[access][]" value="<?php echo $lang['text_setting']; ?>"  checked="checked"/>
									<?php } else { ?>
										<input type="checkbox" name="permission[access][]" value="<?php echo $lang['text_setting']; ?>" />
									<?php } ?>
									<?php echo $lang['text_setting']; ?>
								</div>
								<?php $class = 'odd'; ?>
								<?php foreach ($permissions as $permission) { 
									$permission_value = str_replace('controller/', '', $permission);
									$class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<?php if (in_array($permission_value, $access)) { ?>
											<input type="checkbox" name="permission[access][]" value="<?php echo $permission_value; ?>" checked="checked" />
											<?php echo $permission_value; ?>
										<?php } else { ?>
											<input type="checkbox" name="permission[access][]" value="<?php echo $permission_value; ?>" />
											<?php echo $permission_value; ?>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $lang['text_select_all']; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $lang['text_unselect_all']; ?></a></td>
						</tr>
					<?php if(isset($group_id)) { ?>
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="group_id" value="<?php echo $group_id; ?>" />
					<?php } else { ?>
						<input type="hidden" name="action" value="insert" />
					<?php } ?>
				</table>
			</form>
		</div>
	</div>
</div>