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
	<link type="text/css" href="<?=mainPageURL()?>/admin/css/style.css" media="screen" rel="stylesheet" />
	<link type="text/css" href="<?=mainPageURL()?>/admin/css/menu.css" media="screen" rel="stylesheet" />	
</head>

<body style="background:#1B0A2A left -150px; color:#383838;">
<center>
<div class="main">
	<div>
        <!-- header -->
        <div style="text-align:left;"><?php include_once $this->template('header.tpl') ?></div>
        
        <!-- content -->
        <div>
            <div class="content">
				<?=$this->content_container?>
            </div>
        </div>
        
        <!-- footer -->
        <div><?php include_once $this->template('footer.tpl') ?></div>
    </div>
</div>
</center>
</body>
</html>