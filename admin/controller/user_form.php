<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'user_form')) {
	header('Location:permission.html');
}

$user_id = $_GET['user_id'];

if (isset($user_id) && ($_SERVER['REQUEST_METHOD'] != 'POST')) {
	$user_info = getUserById($user_id);
}

if (isset($_POST['username'])) {
	$username = $_POST['username'];
} elseif (!empty($user_info)) {
	$username = $user_info['username'];
} else {
	$username = '';
}

if (isset($_POST['email'])) {
	$email = $_POST['email'];
} elseif (!empty($user_info)) {
	$email = $user_info['email'];
} else {
	$email = '';
}

if (isset($_POST['password'])) {
	$password = $_POST['password'];
} else {
	$password = '';
}

if (isset($_POST['confirm'])) {
	$confirm = $_POST['confirm'];
} else {
	$confirm = '';
}

if (isset($_POST['user_group_id'])) {
	$user_group_id = $_POST['user_group_id'];
} elseif (!empty($user_info)) {
	$user_group_id = $user_info['group_id'];
} else {
	$user_group_id = '';
}

$user_groups = getGroups();

if (isset($_POST['status'])) {
	$status = $_POST['status'];
} elseif (!empty($user_info)) {
	$status = $user_info['status'];
} else {
	$status = 0;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$action = $_POST['action'];
	$user_id = $_POST['user_id'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirm = $_POST['confirm'];
	$user_group = $_POST['user_group_id'];
	$status = $_POST['status'];
	
	$error = array();
	
	if($action == "insert") {
		if(verifyDuplicateUser($username, "username")) {
			$error['username'] = $lang['error_username_duplicate'];
		} else {
			if ((strlen($username) < 3) || (strlen($username) > 20)) {
				$error['username'] = $lang['error_username_valid'];
			}
		}
		
		if(verifyDuplicateUser($email, "email")) {
			$error['email'] = $lang['error_email_duplicate'];
		} else {
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error['email'] = $lang['error_email_valid'];
			}
		}
	}
	
	if( ($action == "insert") || (($action == "update") && ($password != NULL)) ){
		if ($password || (!isset($user_id))) {
			if ((strlen($password) < 3) || (strlen($password) > 20)) {
				$error['password'] = $lang['error_password_valid'];
			}
	
			if ($password != $confirm) {
				$error['confirm'] = $lang['error_confirm'];
			}
		}
	}
		
	if(empty($user_group)) {
		$error['user_group'] = $lang['error_user_group'];
	}

	if (empty($error)) {
		echo $action;
		if($action == "insert") {
			$insert_data = array (
				'username'		=> $username,
				'email'			=> $email,
				'password'		=> md5($password),
				'user_group'	=> $user_group,
				'status'		=> $status,
				'creator'		=> $logged
			);
			$insert_query = insertUser($insert_data);
			if($insert_query) {
				$_SESSION['success'] = $lang['success_insert'];
			} else {
				$_SESSION['$error_warning'] = $lang['error_query'];
			}
		} else if ($action == "update") {
			$edit_data = array (
				'userId'		=> $user_id,
				'username'		=> $username,
				'email'			=> $email,
				'password'		=> md5($password),
				'user_group'	=> $user_group,
				'status'		=> $status
			);
			
			$edit_query = editUser($edit_data);
			if($edit_query) {
				$_SESSION['success'] = $lang['success_edit'];
			} else {
				$_SESSION['error_warning'] = $lang['error_query'];
			}
		}
		header('Location:user.html');
	} else {
		$error_username = $error['username'];
		$error_email = $error['email'];
		$error_password = $error['password'];
		$error_confirm = $error['confirm'];
		$error_user_group = $error['user_group'];
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
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/user.png" alt="" /> <?php echo $lang['heading_user']; ?></h1>
			<div class="buttons">
				<?php if(isset($user_id)) { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<?php } else { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_insert']; ?></a>
				<?php } ?>
				<a onclick="location = 'user.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="user_form.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_username']; ?></td>
						<td><input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $lang['entry_username']; ?>" size="50" autofocus required>
						<?php if ($error_username) { ?>
							<span class="error"><?php echo $error_username; ?></span>
						<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_email']; ?></td>
						<td><input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $lang['entry_email']; ?>" size="50" autofocus required>
						<?php if ($error_email) { ?>
							<span class="error"><?php echo $error_email; ?></span>
						<?php } ?></td>
					</tr>
					<tr>
						<td><?php if(!isset($user_id)) { ?><span class="required">*</span> <?php } ?> <?php echo $lang['entry_password']; ?></td>
						<td><input type="password" name="password" value="<?php echo $password; ?>"  placeholder="<?php echo $lang['entry_password']; ?>" size="50" autofocus />
						<?php if ($error_password) { ?>
							<span class="error"><?php echo $error_password; ?></span>
						<?php  } ?></td>
					</tr>
					<tr>
						<td><?php if(!isset($user_id)) { ?><span class="required">*</span> <?php } ?> <?php echo $lang['entry_confirm']; ?></td>
						<td><input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $lang['entry_confirm']; ?>" size="50" autofocus />
						<?php if ($error_confirm) { ?>
							<span class="error"><?php echo $error_confirm; ?></span>
						<?php  } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_group']; ?></td>
						<td><select name="user_group_id">
							<option value=""><?php echo $lang['text_select']; ?></option>
							<?php foreach ($user_groups as $user_group) { ?>
								<?php if ($user_group['user_group_id'] == $user_group_id) { ?>
									<option value="<?php echo $user_group['user_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
						<?php if ($error_user_group) { ?>
							<span class="error"><?php echo $error_user_group; ?></span>
						<?php  } ?></td>
					</tr>
					<tr>
						<td><?php echo $lang['entry_status']; ?></td>
						<td><select name="status">
						<?php if ($status == 1) { ?>
							<option value="0"><?php echo $lang['text_disabled']; ?></option>
							<option value="1" selected="selected"><?php echo $lang['text_enabled']; ?></option>
						<?php } else { ?>
							<option value="0" selected="selected"><?php echo $lang['text_disabled']; ?></option>
							<option value="1"><?php echo $lang['text_enabled']; ?></option>
						<?php } ?>
					</select></td>
					</tr>
					<?php if(isset($user_id)) { ?>
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
					<?php } else { ?>
						<input type="hidden" name="action" value="insert" />
					<?php } ?>
				</table>
			</form>
		</div>
	</div>
</div>