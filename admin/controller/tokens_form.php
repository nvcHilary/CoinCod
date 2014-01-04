<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'tokens_form')) {
	header('Location:permission.html');
}

if(isset($_GET['tokens_id'])) {
	$token_id = $_GET['tokens_id'];
}
	
if (isset($token_id) && ($_SERVER['REQUEST_METHOD'] != 'POST')) {
	$token_info = getTokensById($token_id);
}

if (isset($_POST['name'])) {
	$name = $_POST['name'];
} elseif (!empty($token_info)) {
	$name = $token_info['name'];
} else {
	$name = '';
}

if (isset($_POST['discount'])) {
	$discount = $_POST['discount'];
} elseif (!empty($token_info)) {
	$discount = $token_info['discount'];
} else {
	$discount = '';
}

if (isset($_POST['code'])) {
	$code = $_POST['code'];
} elseif (!empty($token_info)) {
	$code = $token_info['code'];
} else {
	$code = '';
}

if (isset($_POST['price'])) {
	$price = $_POST['price'];
} elseif (!empty($token_info)) {
	$price = $token_info['price'];
} else {
	$price = '';
}

//$categories = getCategoriesList(0);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$action = $_POST['action'];
	$name = $_POST['name'];
	$discount = $_POST['discount'];
	$code = $_POST['code'];
	$price = $_POST['price'];
	$token_id = $_POST['token_id'];
	
	$error = array();
	
	if (empty($error)) {
		if($action == "insert") {
			$insert_data = array (
				'name'		=> $name,
				'discount'	=> $discount,
				'code'		=> $code,
				'price'		=> $price
			);
			$insert_query = insertTokens($insert_data);
			if($insert_query) {
				$_SESSION['success'] = $lang['success_insert'];
			} else {
				$error_warning = $lang['error_query'];
			}
		} else if ($action == "update") {
			$edit_data = array (
				'tokenId'	=> $token_id,
				'name'		=> $name,
				'discount'	=> $discount,
				'code'		=> $code,
				'price'		=> $price
			);
			
			$edit_query = editTokens($edit_data);
			if($edit_query) {
				$_SESSION['success'] = $lang['success_edit'];
			} else {
				$_SESSION['error_warning'] = $lang['error_query'];
			}
		}
		header('Location:tokens.html');
	} else {
	}
}
?>

<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="tokens.html"><?php echo $lang['text_tokens']; ?></a>
	</div>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_tokens']; ?></h1>
			<div class="buttons">
				<?php if(isset($token_id)) { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<?php } else { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_insert']; ?></a>
				<?php } ?>
				<a onclick="location = 'tokens.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="tokens_form.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_tokens_name']; ?></td>
						<td><input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $lang['entry_tokens_name']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_tokens_discount']; ?></td>
						<td><input type="text" name="discount" value="<?php echo $discount; ?>" placeholder="<?php echo $lang['entry_tokens_discount']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_tokens_code']; ?></td>
						<td><input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $lang['entry_tokens_code']; ?>" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_tokens_price']; ?></td>
						<td><input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $lang['entry_tokens_price']; ?>" autofocus required></td>
					</tr>
					
					<?php if(isset($token_id)) { ?>
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="token_id" value="<?php echo $token_id; ?>" />
					<?php } else { ?>
						<input type="hidden" name="action" value="insert" />
					<?php } ?>
				</table>
			</form>
		</div>
	</div>
</div>