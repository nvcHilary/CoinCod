<?php
ob_start();
session_start();

include_once ('../models/config.php');
include_once ('../models/sql_function.php');
include_once ('variables/variables.php');
require("includes/js/phpmailer/class.phpmailer.php");

$opt = $_GET['opt'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<base href="<?php echo mainPageURL(); ?>admin/">
	<link rel="stylesheet" href="includes/css/stylesheet.css" />
	<script type="text/javascript" src="includes/js/jquery-1.7.1.min.js"></script>
	
	<!--menu-->
	<script type="text/javascript" src="includes/js/superfish.js"></script>
	<script type="text/javascript">
		jQuery(function(){
			jQuery('ul.left').superfish();
			jQuery('ul.right').superfish();
		});
	</script>
	
	<!--datepicker-->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	
	<!--tabs-->
	<script type="text/javascript" src="includes/js/tabs.js"></script>
	
	<!--pagination-->
	<?php include_once ("includes/js/pagination.php"); ?>
	
	<!--tinyeditor-->
	<link rel="stylesheet" href="includes/js/tinyeditor/tinyeditor.css">
	<script src="includes/js/tinyeditor/tiny.editor.packed.js"></script>
	
	<!--validate-->
	<script type="text/javascript" src="includes/js/jquery.validate.js"></script>
	
	<!--uploadifive-->
	<script src="includes/js/uploadifive/jquery.uploadifive.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="includes/js/uploadifive/uploadifive.css">
	
</head>
<body>
	<?php include_once ("includes/header.php"); ?>
	<?php
		if(!isset($opt)) {
			header('Location:login.html');
		} else {
			include("controller/".$opt.".php");
		}
		?>
	<?php include_once ("includes/footer.php"); ?>
</body>
</html>
<?php ob_end_flush(); ?>