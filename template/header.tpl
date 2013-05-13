<?php
	ob_start();
	session_start();
	require_once $this->resource_path.'config.php';
	require_once $this->resource_path.'sql_function.php';
?>
<header id="header">
	<div id="header_content">
		<div class="container">
<!--			<div class="login_head">
<!--				<?php ///include_once $this->template('login.tpl') ?>
<!--			</div> <!--end div login_head-->
						
			<div id="logo">
				<a href="<?=mainPageURL()?>"></a>
			</div>  <!--end div logo-->
						
			<nav class="menu">
				<table cellspacing="0">
					<tbody>
						<tr>
							<td>
								<ul class="top_nav logged_out">
									<li>
										<a href="<?=mainPageURL()?>">Home</a>
									</li>
									<li>
										<a href="<?=mainPageURL()?>/how_it_works">How It Works</a>
									</li>
									<li>
										<a href="<?=mainPageURL()?>/buy_tokens">Buy Tokens</a>
									</li>
									<!--<li>
										<a href="header/winners">Winners</a>			
									</li>-->					
								</ul>
							</td>
						</tr>
					</tbody>
				</table> 		
			</nav>  <!--end div menu-->
		
			<?php 
			if (isset($_SESSION['user_id']))  {
				$now = time(); 
				if($now > $_SESSION['expire']) {
					session_destroy();
					echo "<script language='javascript'>
							window.location=".mainPageURL().";
							alert('Timeout!!!Please login to continue browsing the site');
						</script>";
				} else { 

					//		<input type="text" class="search_input" name="search" placeholder="Enter Search..." value="'.htmlspecialchars($searchTerms).'" />

					echo '<div id="search"> 
						<form action="'.mainPageURL().'" enctype="multipart/form-data" name="searchForm" id="myForm" method="GET">
							<input type="text" class="search_input" name="search" placeholder="Enter Search..." value="" />
						</form>
					</div>';
								
					$_SESSION['start_reset'] = time();
					$_SESSION['expire'] = $_SESSION['start_reset'] + (30 * 60) ;
					$email = $_SESSION['email'];
					$user_data = getMemberRecords($email);
					$userid = $user_data['id'];
					$username = $user_data['username'];
					$token = $user_data['token'];
					$gravatar_image = $user_data['email'];
					$gravatar_default = mainPageURL()."/template/template_image/favicon.ico";
					$size = 40;
					$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $gravatar_image ) ) ) . "?d=" . urlencode( $gravatar_default ) . "&s=" . $size;
				
					echo '<div id="after_log_in">
							<div class="user_name">
								<img src="'.$grav_url.'" alt="" />
								<a href="'.mainPageURL().'/user_profile/?id=' . $userid . '">' . $username . '</a>
							</div>
				
							<div class="token_left">
								Token Left: '.$token.'
							</div>
					
							<div id="logout">
								<a href="'.mainPageURL().'/logout"><img src="'.mainPageURL().'/template/template_image/header/logout.png" border="0" width="30%" title="Log Out"></a>
							</div>
						</div>';			
////$user=mysql_query("SELECT * FROM user_account WHERE user_id='$userid'");
////$get=mysql_fetch_array($user);
////$token=$get["Token"];
////$gravatar_image=$get["Email"];
////					
												

				}
			} else { 
				echo '<a href="'.mainPageURL().'/login"><button class="form_button" name="btnSubmit" type="submit">Sign In</button></a>';
			}	
			?>
		</div> <!--end div container-->
	</div> <!--end div header_content-->
</header> <!--end header-->