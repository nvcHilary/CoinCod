<?php
	ob_start();
	session_start();
	require_once $this->resource_path.'config.php';
	require_once $this->resource_path.'sql_function.php';
	
	$echo_error=NULL;
	if(isset($_POST["btnSubmit"])){
		$email = mysql_real_escape_string($_POST["email_in"]);
		$password = mysql_real_escape_string($_POST["password_in"]);
		
		if (verifyLogin($email,$password)) {
			$user_data = getMemberRecords($email);
			if($user_data['status'] == 0) {
				$echo_error = "<div style='color:#d50000;text-align:left;'>
								Your account has not been activate. Please go email to activate it now and enjoy the forthcoming experience that CoinCod will bring to you. Thank you.
							</div>";
				$_SESSION['error'] = $echo_error;
				header("location:".mainPageURL()."/login/");
			} else {
				$_SESSION['start'] = time(); 
				$_SESSION['expire'] = $_SESSION['start'] + (1 * 60) ;

				$_SESSION['user_id'] = $user_data['id'];
				$_SESSION['email'] = $email;
				header("location:".mainPageURL());
			}
		} else {
			$echo_error = "<div style='color:#d50000;text-align:left;'>
							Looks like your account cannot be found in our server. Please try again later. 
							If this problem persists kindly send us an email at <a href='mailto:support@coincod.com'>support@coincod.com.</a> Thank you.
						</div>";
			$_SESSION['error'] = $echo_error;
			header("location:".mainPageURL()."/login/");
		}
		
	}
	
	if(isset($_POST["btnReg"])){
		$email = mysql_real_escape_string($_POST["email_reg"]);
		if (checkExist($email) == 1) {
			header("location:".mainPageURL()."/registration/register_msg.php?action=1");
		} else {
			if (sendRegLink($email)) {
				header("location:".mainPageURL()."/registration/register_msg.php?action=2");	
			} else {
				header("location:".mainPageURL()."/registration/register_msg.php?action=3");
			}
		}
	}
?>
<section id="content_container">
	<section id="before_login">
		<header>
			<aside>
				<form action="" enctype="multipart/form-data" name="login" id="myForm" method="post">
					Registered User? Come on, in...
					<input type="email" name="email_in" id="email_in" placeholder="User ID" required/>
					<input type="password" name="password_in" id="password_in" placeholder="Password" required/>
					<input type="submit" name="btnSubmit" value="Log In" />
					<div class="login_label_field"><a href="<?=mainPageURL()?>/forgot_password">Forgot Password?</a><div>
				</form>
				
				<form action="" enctype="multipart/form-data" name="login" id="myForm" method="post">
					New here? Then join us...
					<input type="email" name="email_reg" id="email_reg" placeholder="Email ID" required/>
					<input type="submit" name="btnReg" value="Sign Up" />
				</form>
			</aside>
			<div id="title">CoinCod</div>
			<div id="punchline">
				The Awesome Punchline goes here...
			</div>
		</header>
		<img src="<?=mainPageURL()?>/template/template_image/shadow-line.png" alt="shadow-line">
	</section>

	<section class="site_body">
		<?php
		$bidder = getBidderList(1);
		for($i=0;$i<1;$i++) {
			$highest_bidder=$bidder[$i]['username'];
		}
		$totalproduct = 4;
		$product = getProductList(0,0,4);
		for($i=0;$i<$totalproduct;$i++) {		
			$pro_id=$product[$i]["pid"]; 
			$pro_brand = $product[$i]["brand"];
			$pro_model = $product[$i]["model"];
			$pro_aprice = $product[$i]["aprice"];
			$pro_aend = $product[$i]["aend"];	
			$auctiontime = $pro_aend - time();
				
		?>
			<script>
				var poll<?php echo $pro_id; ?> = function () {
					$.ajax({
						type: 'POST',
						url: "<?php echo mainPageURL() . '/timer.php'; ?>",
						data : {
							id : <?php echo $pro_id; ?>
						}
					}).done(function (data) {
						$("#bid_timer_<?php echo $pro_id; ?>").text(data);
						setTimeout(poll<?php echo $pro_id; ?>, 1000);
					});
				}
				poll<?php echo $pro_id; ?>();
			</script>
			<ul id="auction_list_ul" class="auction_box">
				<li>
					<div class="title">
						<form id="myForm" name="postlink" action="<?=mainPageURL()?>/product/product_description.php" method="post">
							<input type="hidden" name="pid" value='<?=$pro_id?>' />
							<input name="link"  class="button_title" type="submit" value="<?=$pro_brand.$pro_model?>" />	
						</form>
					</div>
						
					<div class="image">
						<form id="myForm" name="postlink" action="<?=mainPageURL()?>/product/product_description.php" method="post">
							<input type="hidden" name="pid" value='<?=$pro_id?>' />
							<input type="image" src="<?=mainPageURL()?>/product/product_image/<?=$pro_id?>.jpg" width="210" height="150" input name="link" type="submit" value="<?=$pro_brand.$pro_model?>" />				  	
						</form>
					</div>
						
					<div class="bid_current">
						Current Auction Price:
						<div class="bid_current_color">
							RM <?=$pro_aprice?>
						</div>
					</div>
						
					<div class="bid_box">
						<div id="bid_timer_<?=$pro_id?>" class="bid_timer" end_time="">
							<?=$auctiontime?>
						</div>
						<!--<div id="bid_price_1" class="bid_price">'.$Marketprice.'</div>-->
						<form action="<?=mainPageURL()?>/login" method="post">
							<div class="bid_button">
								<input name="login"  class="button" type="submit" value="LOGIN" />
							</div>
						</form>

						<div id="read_more">						
							<form id="myForm" name="postlink" action="<?=mainPageURL()?>/product/product_description.php" method="post">
								<input type="hidden" name="pid" value='<?=$pro_id?>' />
								<input name="link"  class="read_more" type="submit" value="Read More..." />	
							</form>
						</div>
							
						<span class="bid_spot">
							Highest Bidder: <?=$highest_bidder?>
							<!--This is the spot for you to put the bid function<div>Bid<div>-->
						</span>
					</div>
				</li>
			</ul>
		<?php } ?>
	</section>
</section>
<?php ob_end_flush();?>