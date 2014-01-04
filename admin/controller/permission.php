<?php
if(!isset($logged)) {
	header('Location:login.html');
}
?>
<div id="content">
	<div class="breadcrumb">
		<a href="home.html"><?php echo $lang['text_home']; ?></a>
		&nbsp; > &nbsp; 
		<a href="permission.html"><?php echo $lang['text_permission']; ?></a>
	</div>
	<div class="box">
		<div class="heading">
			<h1><img src="includes/images/error.png" alt="" /> <?php echo $lang['heading_permission']; ?></h1>
		</div>
		<div class="content">
			<div style="border: 1px solid #DDDDDD; background: #F7F7F7; text-align: center; padding: 15px;"><?php echo $lang['error_permission']; ?></div>
		</div>
	</div>
</div>