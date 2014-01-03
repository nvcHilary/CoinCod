<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'product_form')) {
	header('Location:permission.html');
}

$product_id = $_GET['product_id'];

if (isset($product_id) && ($_SERVER['REQUEST_METHOD'] != 'POST')) {
	$product_info = getProductById($product_id);
}

if (!empty($product_info)) {
	$brand = $product_info['brand'];
} else {
	$brand = '';
}

if (!empty($product_info)) {
	$model = $product_info['model'];
} else {
	$model = '';
}

if (!empty($product_info)) {
	$mprice = $product_info['mprice'];
} else {
	$mprice = '';
}

if (!empty($product_info)) {
	$aprice = $product_info['aprice'];
} else {
	$aprice = '';
}

if (!empty($product_info)) {
	$category = $product_info['category'];
} else {
	$category = '';
}

if (!empty($product_info)) {
	$availability = $product_info['availability'];
} else {
	$availability = 1;
}

if (!empty($product_info)) {
	$datestart = date('Y-m-d', strtotime($product_info['datestart']));
} else {
	$datestart = '';
}

if (!empty($product_info)) {
	$dateend = date('Y-m-d', strtotime($product_info['dateend']));
} else {
	$dateend = '';
}

if (!empty($product_info)) {
	$bids = $product_info['bids'];
} else {
	$bids = '';
}

if (!empty($product_info)) {
	$description = $product_info['description'];
} else {
	$description = '';
}

?>

<script>
$(document).ready(function() {
	$('#form').validate({
		rules: {
			brand: {
				required: true,
				minlength: 3,
				maxlength: 32
			},
			model: {
                required: true,
				minlength: 3,
				maxlength: 32
            },
			mprice: {
                required: true,
				number: true
            },
			aprice: {
                required: true,
				number: true
            },
			category: {
				required: true
			},
			datestart: {
				required: true,
				date: true
			},
			dateend: {
				required: true,
				date: true
			},
			bids: {
				required: true,
				number: true
			}
        },
		
        submitHandler: function (form) {
			editor.toggle();
			var action = $('input[name="action"]').val();
			var product_id = $('input[name="product_id"]').val();
			var brand = $('input[name="brand"]').val();
			var model = $('input[name="model"]').val();
			var mprice = $('input[name="mprice"]').val();	
			var aprice = $('input[name="aprice"]').val();		
			var category = $('#category').val();		
			var availability = $('#availability').val();		
			var datestart = $('input[name="datestart"]').val();		
			var dateend = $('input[name="dateend"]').val();		
			var bids = $('input[name="bids"]').val();		
			var description = $('#tinyeditor').val();
			
			$.ajax({
				url: 'controller/update.php',
				type: 'POST',
				data: {
					update_type: 'product',
					action: action,
					product_id: product_id,
					brand: brand,
					model: model,
					mprice: mprice,
					aprice: aprice,
					category: category,
					availability: availability,
					datestart: datestart,
					dateend: dateend,
					bids: bids,
					description: description
				},				
				success:function (data) {
					if (data == '1') {
						$("#status").addClass("success").html("<?php echo $lang['success_insert']; ?>").fadeIn('fast').delay(1500).fadeOut('slow', function() {	document.location.href='product.html'; });
					} else if(data == '2') {
						$("#status").addClass("success").html("<?php echo $lang['success_edit']; ?>").fadeIn('fast').delay(1500).fadeOut('slow', function() {	document.location.href='product.html'; });
					} else {
						$("#status").addClass("warning").html("<?php echo $lang['error_query']; ?>").fadeIn('fast').delay(1500).fadeOut('slow', function() {	document.location.href='product.html'; });
					}
				}
			});
        }
    });
});
</script>

<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="product.html"><?php echo $lang['text_product']; ?></a>
	</div>
	<div id="status"></div>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_product']; ?></h1>
			<div class="buttons">
				<?php if(isset($product_id)) { ?>
					<a onclick="$('#form').submit();" id="submit" class="button"><?php echo $lang['button_save']; ?></a>
				<?php } else { ?>
					<a onclick="$('#form').submit();" id="submit" class="button"><?php echo $lang['button_insert']; ?></a>
				<?php } ?>
				<a onclick="location = 'product.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		
		<div class="content">
			<form action="product_form.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_brand']; ?></td>
						<td><input type="text" name="brand" value="<?php echo $brand; ?>" placeholder="<?php echo $lang['entry_brand']; ?>" size="50" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_model']; ?></td>
						<td><input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $lang['entry_model']; ?>" size="50" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_mprice']; ?></td>
						<td><input type="text" name="mprice" value="<?php echo $mprice; ?>" placeholder="<?php echo $lang['entry_mprice']; ?>" size="50" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_aprice']; ?></td>
						<td><input type="text" name="aprice" value="<?php echo $aprice; ?>" placeholder="<?php echo $lang['entry_aprice']; ?>" size="50" autofocus required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_category']; ?></td>
						<td>
							<select name="category" id="category" autofocus="autofocus" autocorrect="off" autocomplete="off" required >
								<option value="" selected="selected"><?php echo $lang['text_select']." ".$lang['entry_category']; ?></option>
								<?php 
									$categories = getCategory();
									foreach ($categories as $value) {
										if($category == $value['category_id']) {
											echo '<option value="'.$value['category_id'].'" selected="selected">'.$value['category_name'].'</option>';
										} else {
											echo '<option value="'.$value['category_id'].'" >'.$value['category_name'].'</option>';
										}
									}
								?>
							</select>
						</td>
					</tr>
						
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_availibility']; ?></td>
						<td><select id="availability">
						<?php if ($availability == 0) { ?>
							<option value="0" selected="selected"><?php echo $lang['text_not_available']; ?></option>
							<option value="1"><?php echo $lang['text_available']; ?></option>
							<option value="2"><?php echo $lang['text_coming_soon']; ?></option>
						<?php } else if ($availability == 1) { ?>
							<option value="0"><?php echo $lang['text_not_available']; ?></option>
							<option value="1" selected="selected"><?php echo $lang['text_available']; ?></option>
							<option value="2"><?php echo $lang['text_coming_soon']; ?></option>
						<?php } else { ?>
							<option value="0"><?php echo $lang['text_not_available']; ?></option>
							<option value="1"><?php echo $lang['text_available']; ?></option>
							<option value="2" selected="selected"><?php echo $lang['text_coming_soon']; ?></option>
						<?php } ?>
						</select></td>
					</tr>
					
					<?php 
					$disabled = "";
					if( (isset($product_id)) && (strtotime($datestart) < time()) )  { 
						$disabled = 'disabled="disabled"';
					} else ?>
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_start_date']; ?></td>
						<td><input type="text" id="datestart" name="datestart" value="<?php echo $datestart; ?>" placeholder="<?php echo $lang['entry_start_date']; ?>" <?php echo $disabled; ?> required/></td>
					</tr>
						
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_end_date']; ?></td>
						<td><input type="text" id="dateend" name="dateend" value="<?php echo $dateend; ?>" placeholder="<?php echo $lang['entry_end_date']; ?>" <?php echo $disabled; ?> required/></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_bids']; ?></td>
						<td><input type="text" name="bids" value="<?php echo $bids; ?>" placeholder="<?php echo $lang['entry_bids']; ?>" size="50" required></td>
					</tr>
					
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_description']; ?></td>
						<td><textarea id="tinyeditor" name="description" style="width: 400px; height: 200px"><?php echo $description; ?></textarea></td>
					</tr>
					
					<?php if(isset($product_id)) { ?>
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
					<?php } else { ?>
						<input type="hidden" name="action" value="insert" />
					<?php } ?>
				</table>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#datestart").datepicker({ 
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true ,
			defaultDate: '2014-01-01'
		});
		$("#dateend").datepicker({ 
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true ,
			defaultDate: '2014-01-01'
		});
	});
</script>
<script>
var editor = new TINY.editor.edit('editor', {
	id: 'tinyeditor',
	width: 584,
	height: 175,
	cssclass: 'tinyeditor',
	controlclass: 'tinyeditor-control',
	rowclass: 'tinyeditor-header',
	dividerclass: 'tinyeditor-divider',
	controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'subscript', 'superscript', '|',
		'orderedlist', 'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign',
		'centeralign', 'rightalign', 'blockjustify', '|', 'unformat', '|', 'undo', 'redo', 'n',
		'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink', '|', 'print'],
	footer: true,
	fonts: ['Verdana','Arial','Georgia','Trebuchet MS'],
	xhtml: true,
	cssfile: 'custom.css',
	bodyid: 'editor',
	footerclass: 'tinyeditor-footer',
	toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
	resize: {cssclass: 'resize'}
});
</script>