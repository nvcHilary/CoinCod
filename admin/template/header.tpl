<?php
	ob_start();
	session_start();
	require_once $this->resource_path.'config.php';
	require_once $this->resource_path.'sql_function.php';
?>
<div id="header_outer" align="center">
	<div id="header_inner">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="40%" align="center">
                    <div style="height:158px;width:300px;padding:20px 0 0 0;"><img src="<?=mainPageURL()?>/template/template_image/logo2.png" height="100px"></div>
                </td>
                <td width="60%" valign="top">
                    <div align="right" style="padding:18px 100px 0 0;color:#999;">
						<?php
						if(isset($_SESSION["loggedId"])){ // if login true
							echo "<h3 style='font-size:16px;color:#fff;'
									>Hola~ ".$_SESSION['logged']."&nbsp;&nbsp;&bull;&nbsp;&nbsp;
									<a href='".mainPageURL()."/admin/logout/' class='alternate'>Logout</a>
								</h3>";
						}
						?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<div align="center" style="padding-bottom:0px;">
	<?php 
	if(isset($_SESSION["loggedId"])){ // if login true
		include_once $this->template('menu.tpl') ;
	}
	?>
</div>