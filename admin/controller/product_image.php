<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'product_form')) {
	header('Location:permission.html');
}

$product_id = $_GET['product_id'];

if (isset($logged) && ($_SERVER['REQUEST_METHOD'] != 'POST')) {
	$data = array(
		'product_id' 	=> $product_id
	);
	$image_info = getProductImage($data);
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$product_id = $_POST['product_id'];
	$image = $_POST['file_image'];
	
	if (empty($error)) {
		$edit_data = array (
			'product_id'	=> $product_id,
			'image'			=> $image
		);
			
		$edit_query = updateProductImage($edit_data);
		if($edit_query) {
			$_SESSION['success'] = $lang['success_edit'];
		} else {
			$_SESSION['error_warning'] = $lang['error_query'];
		}
		header('Location:product.html');
	}
}
?>

<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="product.html"><?php echo $lang['text_product']; ?></a>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_product']; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<a onclick="location = 'product.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="product_image.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_image']; ?></td>
						<td id="product_image">
							<div id="queue"></div>
							<input id="file_upload" name="file_upload" type="file" multiple="true">
							<div id="thumbnails">
								<?php 
									$row = 0;
									$folder = 'data/image/product/' . $product_id . '/';
									if($image_info) { 
										foreach($image_info as $image) {
												echo '<div class="image" id="image' . $image['image_id']. ' ">
												<input type="hidden" id="hidden' . $image['image_id'] . ' " name="file_image[]" value="' . $image['image'] . '" />
												<img id="thumb' . $image['image_id'] . '" src="../' . $folder . $image['image'] .'" " width="100px" height="100px" />
												<br/><center><a id="remove"">' . $lang['button_remove'] . '</a></center>
												</div>';
										}
									} 
								?>
							</div>
							
							<script type="text/javascript">
								<?php $timestamp = time();?>
								$(function() {
									var queue_id = 0;
									var folder = 'data/image/product/<?php echo $product_id; ?>/';
									$('#file_upload').uploadifive({
										'buttonText'	: '<?php echo $lang['button_add_image']; ?>',
										'auto'			: true,
										'multi'        : false,
										'checkScript'	: 'includes/js/uploadifive/check-exists.php',
										'formData'		: {
											'upload_dir' : folder,
											'timestamp' : '<?php echo $timestamp;?>',
											'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
										}, 
										'uploadScript'	: 'includes/js/uploadifive/uploadifive.php',
										'uploadLimit'	: 1,
										'onUploadComplete' : function(file, data) { 
											console.log(data);
											$('#thumbnails').append('<div class="image" id="image' + queue_id + '">' +
																'<input type="hidden" id="hidden' + queue_id + '" name="file_image[]" value="'+ file.name +'" />' +
																'<img id="thumb'+ queue_id+'" src="../' + folder + file.name +'" width="100px" height="100px" />'+
																'<br/><center><a id="remove"><?php echo $lang['button_remove']; ?></a></center>' +
																'</div>');
										},
										'onSelect' : function(queue) {
											queue_id = queue.count;
										}
									});
								});
							</script>
						</td>
					</tr>
				</table>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("#remove").live('click', function(){
		$(this).parent().parent().remove();
	});
});
</script>