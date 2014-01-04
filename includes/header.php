<?php
$logged = $_SESSION['coin_id'];
?>
<header id="header">
	<div id="header_content">
		<div class="container">
			<div id="logo">
				<a href="<?php echo mainPageURL(); ?>"></a>
			</div>  <!--end div logo-->
			
			<nav class="menu">
				<table cellspacing="0">
					<tbody>
						<tr>
							<td>
								<ul class="top_nav logged_out">
									<li>
										<a href="<?php echo mainPageURL(); ?>"><?php echo $lang['text_home']; ?></a>
									</li>
									<li>
										<a href="how_it_works.html"><?php echo $lang['text_how_it_works']; ?></a>
									</li>
									<?php if (isset($logged)) { ?>
									<li>
										<a href="buy_tokens.html"><?php echo $lang['text_buy_tokens']; ?></a>
									</li>
									<?php } ?>
									<!--<li>
										<a href="winners.html"><?php ///echo $lang['text_winners']; ?></a>			
									</li>-->					
								</ul>
							</td>
						</tr>
					</tbody>
				</table> 		
			</nav>  <!--end div menu-->
			
			<?php 
			if (isset($logged))  {
				$now = time(); 
				if($now > $_SESSION['expire']) {
					session_destroy();
					echo "<script language='javascript'>
							window.location=".mainPageURL().";
							alert('Timeout!!!Please login to continue browsing the site');
						</script>";
				} else { 
//					echo '<div id="search"> 
//						<form action="'.mainPageURL().'" enctype="multipart/form-data" name="searchForm" id="myForm" method="GET">
//							<input type="text" class="search_input" name="search" placeholder="Enter Search..." value="" />
//						</form>
//					</div>';
								
					$_SESSION['start_reset'] = time();
					$_SESSION['expire'] = $_SESSION['start_reset'] + (30 * 60) ;
					$customer = getCustomerById($logged);
					$username = $customer['username'];
					$token = $customer['token'];
					$gravatar_image = $customer['email'];
					//$gravatar_default = "";
					$size = 40;
					$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $gravatar_image ) ) ) . "?d=" . urlencode( $gravatar_default ) . "&s=" . $size;
				
					echo '<div id="after_log_in">
							<div class="user_name">
								<img src="'.$grav_url.'" alt="" />
								<a href="profile.html">' . $username . '</a>
							</div>
				
							<div class="token_left">
								Token Left: '.$token.'
							</div>
					
							<div id="logout">
								<a href="logout.html"><img src="includes/images/header/logout.png" border="0" width="30%" title="' . $lang['text_logout'] . '"></a>
							</div>
						</div>';			
				}
			} else { 
				echo '<a href="login.html"><button class="form_button" type="submit">' . $lang['text_log_in'] . '</button></a>';
			}	
			?>
			
		</div> <!--end div container-->
	</div> <!--end div header_content-->
</header> <!--end header-->