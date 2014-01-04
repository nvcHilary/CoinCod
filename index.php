<?php
ob_start();
session_start();
include_once ('models/config.php');
//include_once ('models/define.php');
include_once ('models/sql_function.php');
include_once ('variables/variables.php');
require("admin/includes/js/phpmailer/class.phpmailer.php");

if(isset($_GET['opt'])) {
	$opt = $_GET['opt'];
}
	
$files = glob('install/*.php');

foreach ($files as $file) {
	$data = explode('/', dirname($file));
	$permission_data = end($data) . '/' . basename($file, '.php');
	if(isset($permission_data)) {
		echo "Please Remove Install Folder For Security Purpose!";
	}
}
?>
<html>
<head>
	<base href="<?php echo mainPageURL(); ?>">
	<meta charset="utf-8" />
	<meta name="description" content="<?php echo $meta_description; ?>" />
	
	<!-- Le Styles -->
	<link href="includes/css/style.css" rel="stylesheet" type="text/css" />
	<!--[if lte IE 9]>
	<link href="includes/css/style_ie.css" rel="stylesheet" type="text/css"  />
	<![endif]-->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css">
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
	
	<!-- Le Javascript -->
	<script src="http://code.jquery.com/jquery-1.9.0.js"></script>
	<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
	
	<!-- favicon -->
	<link rel="shortcut icon" href="includes/images/favicon.ico" />
</head>

<body>
	<div id="wrapper">
		<?php 
		if( (!isset($_SESSION['coin_id'])) && (!isset($opt)) ) {
		} else {
			//header template
			include_once ('includes/header.php');
		} 
		?>
		<!--content-->
		<section id="content_container">
			<?php 
				if(!isset($opt)) {
					$opt = "home";
				}
				include_once ('controller/'.$opt.'.php'); 
			?>
		</section>
	</div>
	
	<!--footer-->
	<?php include_once ('includes/footer.php'); ?>
	<title><?php echo $title; ?></title>
</body>
</html>
<?php ob_end_flush();?>