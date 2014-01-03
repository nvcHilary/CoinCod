<?php
if(!isset($logged)) {
	header('Location:login.html');
}

if (!hasPermission($logged, 'access', 'Settings')) {
	header('Location:permission.html');
}

$information_id = $_GET['information_id'];
$key = getSettingsById($information_id);

$data_setting = array(
	'group'	=> 'page',
	'Key'	=> $key['key']
);
$settings = getSettings($data_setting);

if($settings) {
	foreach($settings as $setting) {
		$information = $setting['value'];
	}
}
?>
<script>
$(document).ready(function() {
	$('#form').validate({
		submitHandler: function (form) {
			editor.toggle();
			var description = $('#tinyeditor').val();

			$.ajax({
				url: 'controller/update.php',
				type: 'POST',
				data: {
					update_type: 'settings',
					info_id: '<?php echo $key['key']; ?>',
					description: description
				},				
				success:function (data) {
					if (data == '1') {
						$("#status").addClass("success").html("<?php echo $lang['success_edit']; ?>").fadeIn('fast').delay(1500).fadeOut('slow', function() {	document.location.href='information.html'; });
					} else {
						$("#status").addClass("warning").html("<?php echo $lang['error_query']; ?>").fadeIn('fast').delay(1500).fadeOut('slow', function() {	document.location.href='information.html'; });
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
		<a href="information.html"><?php echo $lang['text_information']; ?></a>
	</div>
	<div id="status"></div>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/category.png" alt="" /> <?php echo $lang['heading_information']; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><?php echo $lang['button_save']; ?></a>
				<a onclick="location = 'information.html'" class="button"><?php echo $lang['button_cancel']; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="information_form.html" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $lang['entry_page_description']; ?></td>
						<td><textarea id="tinyeditor" name="description" style="width: 400px; height: 200px"><?php echo $information; ?></textarea></td>
					</tr>
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