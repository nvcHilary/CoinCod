<?php
	ob_start();
	session_start();
	require_once $this->resource_path.'config.php';
	require_once $this->resource_path.'sql_function.php';
?>
<html>
<head>
	<title><?=$this->title?></title>
	<meta charset="utf-8" />
    <meta name="description" content="<?=$this->meta_description?>" />
	
	<!-- Le Styles -->
	<link href="<?=mainPageURL()?>/template/style.css" rel="stylesheet" type="text/css" />
	<!--[if lte IE 9]>
	<link href="<?=mainPageURL()?>/template/style_ie.css" rel="stylesheet" type="text/css"  />
	<![endif]-->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css">
<!--	<link rel="stylesheet" href="<?php ///echo $PREFIX; ?>/validation/css/validation.css" type="text/css" />-->
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>

	
	<!-- Le Javascript -->
	<script src="http://code.jquery.com/jquery-1.9.0.js"></script>
    <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<!--    <script type="text/javascript" src="<?php ///echo $PREFIX; ?>/validation/livevalidation_standalone.compressed.js"></script>

	<!-- favicon -->
	<link rel="shortcut icon" href="<?=mainPageURL()?>/template/template_image/favicon.ico" />
	
	<!-- Countdown Script -->    
	<script type="text/javascript">  
 		window.setTimeout("Tick()", 1000);  
		var Timer = {};
  
		function Tick() {
    		UpdateTimer();
    		window.setTimeout("Tick()", 1000);
		}

		function UpdateTimer() {
			var eta;
			for ( var i in Timer ) {
				eta = Timer[i];
				//eta = "00:" + LeadingZero(Timer[i]);
				document.getElementById("bid_timer_" + i).innerHTML = ( (eta <= 0) ? 0 : eta );
				Timer[i] -= 1;
			}
		}

		function LeadingZero(Time) {  
			return (Time < 10) ? "0" + Time : + Time;  
		}
	</script>
	
	<!--DatePicker Script-->
	<script>
	$(document).ready(function() {
		$("#datepicker").datepicker({ 
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true ,
			defaultDate: '1990-01-01'
		});
	});
	</script>
	
</head>

<body>
	<div id="wrapper">	
		<?php
		if((curPageURL() == mainPageURL()."/") && (!isset($_SESSION['user_id']))) {
			include_once $this->template('index.tpl');
		} else { ?>
			<!--header template-->
			<?php include_once $this->template('header.tpl') ?>
	
			<!--content-->
			<section id="content_container">	
				<?php 
				//if (isset($_GET['search'])) { //load out in product template
				//	include_once $this->template('search.tpl');
				//} else 
				if ((curPageURL() == mainPageURL()."/") || (isset($_GET['search']))){ //load out in product template
					include_once $this->template('product.tpl');
				 
//				} else if (curPageURL() == mainPageURL()."/product/product_description.php") { //load out in product_description template
//					include_once $this->template('product_description.tpl');
//				} else if ($_SERVER["PHP_SELF"] == $PATH."/search/index.php") { //load out in search template
//					echo $this->search;
				} else { 
					foreach ($this->content_container as $key => $val): 
						echo '<h5>'.$val['title'].'</h5>';
						echo '<article class="auction_container">';
						echo $val['content'];
						echo '<br>';
						echo $val['bottom_image'];
						echo '</article>';
					endforeach;
				}
				?>
			</section>
		<?php } ?>
	</div>
	
	<!--footer-->
	<?php include_once $this->template('footer.tpl') ?>
</body>
</html>