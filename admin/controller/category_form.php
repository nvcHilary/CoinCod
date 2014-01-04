<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'category_form')) {
	header('Location:permission.html');
}

if(isset($_GET['category_id'])) {
	$category_id = $_GET['category_id'];
}
	
if (isset($category_id) && ($_SERVER['REQUEST_METHOD'] != 'POST')) {
	$category_info = getCategoryById($category_id);
}

if (isset($_POST['name'])) {
	$name = $_POST['name'];
} elseif (!empty($category_info)) {
	$name = $category_info['category_name'];
} else {
	$name = '';
}

//$categories = getCategoriesList(0);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$action = $_POST['action'];
	$name = $_POST['name'];
	$category_id = $_POST['category_id'];
	
	$error = array();
	
	if ((strlen($name) < 3) || (strlen($name) > 32)) {
		$error['name'] = $lang['error_category_name'];
	}

	if (empty($error)) {
		if($action == "insert") {
			$insert_data = array (
				'name'		=> $name
			);
			$insert_query = insertCategory($insert_data);
			if($insert_query) {
				$_SESSION['success'] = $lang['success_insert'];
			} else {
				$error_warning = $lang['error_query'];
			}
		} else if ($action == "update") {
			$edit_data = array (
				'categoryId'	=> $category_id,
				'name'			=> $name
			);
			
			$edit_query = editCategory($edit_data);
			if($edit_query) {
				$_SESSION['success'] = $lang['success_edit'];
			} else {
				$_SESSION['error_warning'] = $lang['error_query'];
			}
		}
		header('Location:category.html');
	} else {
		$error_name = $error['name'];
	}
}
?>

<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="category.html"><?php echo $lang['text_category']; ?></a>
	</div>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_category']; ?></h1>
			<div class="buttons">
				<?php if(isset($category_id)) { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<?php } else { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_insert']; ?></a>
				<?php } ?>
				<a onclick="location = 'category.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="category_form.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_category_name']; ?></td>
						<td><input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $lang['entry_category_name']; ?>" autofocus required>
						<?php if (isset($error_name)) { ?>
							<span class="error"><?php echo $error_name; ?></span>
						<?php } ?></td>
					</tr>
					<?php if(isset($category_id)) { ?>
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
					<?php } else { ?>
						<input type="hidden" name="action" value="insert" />
					<?php } ?>
				</table>
			</form>
		</div>
	</div>
</div>