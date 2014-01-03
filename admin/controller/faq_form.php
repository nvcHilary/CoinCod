<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'Settings')) {
	header('Location:permission.html');
}

$faq_id = $_GET['faq_id'];
$faqs = getFAQById($faq_id);
?>
<script>
$(document).ready(function() {
	$('#form').validate({
        submitHandler: function (form) {
			editor.toggle();
			var action = $('input[name="action"]').val();
			var faq_id = $('input[name="faq_id"]').val();
			var question = $('input[name="question"]').val();	
			var description = $('#tinyeditor').val();
			
			$.ajax({
				url: 'controller/update.php',
				type: 'POST',
				data: {
					update_type: 'faq',
					action: action,
					faq_id: faq_id,
					question: question,
					description: description
				},				
				success:function (data) {
					if (data == 1) {
						$("#status").addClass("success").html("<?php echo $lang['success_insert']; ?>").fadeIn('fast').delay(1500).fadeOut('slow', function() {	document.location.href='faq.html'; });
					} else if(data == 2) {
						$("#status").addClass("success").html("<?php echo $lang['success_edit']; ?>").fadeIn('fast').delay(1500).fadeOut('slow', function() {	document.location.href='faq.html'; });
					} else {
						$("#status").addClass("warning").html("<?php echo $lang['error_query']; ?>").fadeIn('fast').delay(1500).fadeOut('slow', function() {	document.location.href='faq.html'; });
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
		<a href="faq.html"><?php echo $lang['text_faq']; ?></a>
	</div>
	<div id="status"></div>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_faq']; ?></h1>
			<div class="buttons">
				<?php if(isset($faq_id)) { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<?php } else { ?>
					<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_insert']; ?></a>
				<?php } ?>
				<a onclick="location = 'faq.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="faq_form.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_question']; ?></td>
						<td><input type="text" name="question" value="<?php echo $faqs['question']; ?>" autofocus required /></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_answer']; ?></td>
						<td><textarea id="tinyeditor" name="description" style="width: 400px; height: 200px"><?php echo $faqs['answer']; ?></textarea></td>
					</tr>
					<?php if(isset($faq_id)) { ?>
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="faq_id" value="<?php echo $faq_id; ?>" />
					<?php } else { ?>
						<input type="hidden" name="action" value="insert" />
					<?php } ?>
				</table>
			</form>
		</div>
	</div>
</div>
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