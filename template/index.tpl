<?php
	require $this->resource_path.'config.php';
	
	function getUserRecords($email)
{
	$sql = "SELECT * FROM user_account WHERE Email = '" . $email . "'"; 
	$res = mysql_query($sql);
	$a = mysql_fetch_array($res);
	$records['id'] = $a["user_id"];
	$records["category"] = $a["Category"];
	$records["username"] = $a["Username"];
	$records["email"] = $a["Email"];
	$records["fname"] = $a["first_name"];
	$records["lname"] = $a["last_name"];
	$records["dob"] = $a["date_birth"];
	$records["gender"] = $a["Gender"];
	$records["add1"] = $a["Address1"];
	$records["add2"] = $a["Address2"];
	$records["city"] = $a["City"];
	$records["state"] = $a["State"];
	$records["zip"] = $a["Zip"];
	$records["country"] = $a["Country"];
	$records["mobile"] = $a["Mobile"];
	$records["activation"] = $a["Activation"];
	$records["token"] = $a["Token"];

	return $records;
}	
function verifyLogin($email,$pass)
{
	$hash_pass = md5($pass);
	
	$sql = "SELECT * FROM user_account WHERE Email = '".$email."' AND hash_password = '" . $hash_pass . "'";
	$res = mysql_query($sql);
	$num = mysql_num_rows($res);

	if ($num > 0)
		return true;
	return false;	
}
function getTopBidder()
{
	$sql = "SELECT * FROM product_log WHERE auction_price=(select max(auction_price) from product_log)"; 
	$res = mysql_query($sql);
	$records = mysql_fetch_array($res);
	$highest_user=$records["Username"];
	return $highest_user;
}
function getTotalProduct()
{
	$sql = "SELECT * FROM product_list LIMIT 4"; 
	$res = mysql_query($sql);
	$records = mysql_num_rows($res);
	return $records;
}
function getProductList()
{
	$sql = "SELECT * FROM product_list"; 
	$res = mysql_query($sql);

	$c=0;
	while ($a_row = mysql_fetch_array($res)) {
		$records[$c]["pid"] = $a_row["product_id"];
		$records[$c]["brand"] = $a_row["Brand"];
		$records[$c]["model"] = $a_row["Model"];
		$records[$c]["mprice"] = $a_row["market_price"];
		$records[$c]["aprice"] = $a_row["auction_price"];
		$records[$c]["category"] = $a_row["Category"];
		$records[$c]["availablity"] = $a_row["Availability"];
		$records[$c]["description"] = $a_row["Description"];
		$records[$c]["bid"] = $a_row["total_bid"];
		$records[$c]["astart"] = $a_row["auction_start"];
		$records[$c]["aend"] = $a_row["auction_end"];
	$c++;
    }
	
	return $records;
}

if(isset($_POST["btnSubmit"])){
		if (verifyLogin($_POST["email"],$_POST["password"])) {
			$user_data = getUserRecords($_POST["email"]);
			if($user_data['activation'] == 0) {
				$error = "Your account has not been activate. Please go email to activate it now and enjoy the forthcoming experience that CoinCod will bring to you. Thank you.";
			} else {
				$_SESSION['start'] = time(); 
				$_SESSION['expire'] = $_SESSION['start'] + (1 * 60) ;
			
				session_register('user_id'); 
				$_SESSION['user_id'] = $user_data['id'];
		
				session_register('email'); 
				$_SESSION['email'] = $_POST['email'];
				//admin location
				if($user_data['id']== 1) {
					header("location: $PREFIX/user_profile/?id=$user_data[id]");
				} else {
					header("location:$PREFIX"); 
				}	
			}
		} else {
			header("location:$PREFIX/login/");
			$error = 'Looks like your account cannot be found in our server. Please try again later. If this problem persists kindly send us an email at <a href="mailto:support@coincod.com">support@coincod.com.</a> Thank you.';
		}
		session_register('error');
		$_SESSION['error'] = $error;
	}
?>
<link href="<?php $this ->eprint($this->template_path)?>style.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 9]>
<link href="<?php $this ->eprint($this->template_path)?>style_ie.css" rel="stylesheet" type="text/css"  />
<![endif]-->
<link rel="shortcut icon" href="template/template_image/favicon.ico" />
<div id="wrapper">
	<section id="content_container">
		<section id="before_login">
			<header>
				<aside>
					<form action="" enctype="multipart/form-data" name="login" id="myForm" method="post">
						Registered User? Come on, in...
						<input type="text" placeholder="User ID" />
						<input type="password" placeholder="Password" />
						<input type="submit" name="btnSubmit" value="Log In" />
						<div class="login_label_field"><a href="<?php echo $this->resource_path; ?>forgot_password">Forgot Password?</a><div>
					</form>
					<form name="login">
						New here? Then join us...
						<input type="email" placeholder="Email ID" />
						<input type="submit" value="Sign Up" />
					</form>
				</aside>
				<div id="title">CoinCod</div>
				<div id="punchline">
					The Awesome Punchline goes here...
				</div>
			</header>
			<img src="<?php echo $PREFIX; ?>/template/template_image/shadow-line.png" alt="shadow-line">
		</section>

		<section class="site_body">
		<?php
			$highest_bidder = getTopBidder();
			$totalproduct = getTotalProduct();
			if($totalproduct > 0) {
				$product = getProductList();
				for($i=0;$i<$totalproduct;$i++) {		
					$pro_id=$product[$i]["pid"]; 
					$pro_brand = $product[$i]["brand"];
					$pro_model = $product[$i]["model"];
					$pro_mprice = $product[$i]["mprice"];
					$pro_aprice = $product[$i]["aprice"];
			//		$pro_category =$product[$i]["category"]; 
			//		$pro_availability = $product[$i]["availability"];
			//		$pro_description = $product[$i]["description"];
					$pro_bid = $product[$i]["bid"];
			//		$pro_astart = $product[$i]["astart"];
				
					$pro_aend = $product[$i]["aend"];	
					$auctiontime = $pro_aend - time();
				
					if($pro_bid <= 200)									{	$tokenneed = 1;		}
					if(($pro_bid <= 400)	&&	($pro_bid > 200))		{	$tokenneed = 2;		}
					if(($pro_bid <= 600)	&&	($pro_bid > 400))		{	$tokenneed = 3;		}
					if(($pro_bid <= 800)	&&	($pro_bid > 600))		{	$tokenneed = 4;		}
					if(($pro_bid <= 1000)	&&	($pro_bid > 800))		{	$tokenneed = 5;		}
					if(($pro_bid <= 1200)	&&	($pro_bid > 1000))		{	$tokenneed = 6;		}
					if(($pro_bid <= 1400)	&&	($pro_bid > 1200))		{	$tokenneed = 7;		}
					if(($pro_bid <= 1600)	&&	($pro_bid > 1400))		{	$tokenneed = 8;		}
					if(($pro_bid <= 1800)	&&	($pro_bid > 1600))		{	$tokenneed = 9;		}
					if(($pro_bid <= 2000)	&&	($pro_bid > 1800))		{	$tokenneed = 10;	}
					if(($pro_bid <= 2200)	&&	($pro_bid > 2000))		{	$tokenneed = 11;	}
					if(($pro_bid <= 2400)	&&	($pro_bid > 2200))		{	$tokenneed = 12;	}
					if(($pro_bid <= 2600)	&&	($pro_bid > 2400))		{	$tokenneed = 13;	}
					if(($pro_bid <= 2800)	&&	($pro_bid > 2600))		{	$tokenneed = 14;	}
					if(($pro_bid <= 3000)	&&	($pro_bid > 2800))		{	$tokenneed = 15;	}
					if(($pro_bid <= 3200)	&&	($pro_bid > 3000))		{	$tokenneed = 16;	}
					if(($pro_bid <= 3400)	&&	($pro_bid > 3200))		{	$tokenneed = 17;	}
					if(($pro_bid <= 3600)	&&	($pro_bid > 3400))		{	$tokenneed = 18;	}
					if(($pro_bid <= 3800)	&&	($pro_bid > 3600))		{	$tokenneed = 19;	}
					if(($pro_bid <= 4000)	&&	($pro_bid > 3800))		{	$tokenneed = 20;	}
					if($pro_bid > 4000)									{	$tokenneed = 25;	}
		?>
					<script>
						Timer[<?=$pro_id?>] = <?=$auctiontime?>;
					</script>
					<ul id="auction_list_ul" class="auction_box">
						<li>
							<div class="title">
								<form id="myForm" name="postlink" action="<?=$PREFIX?>/product/product_description.php" method="post">
									<input type="hidden" name="pid" value='<?=$pro_id?>' />
									<input name="link"  class="button_title" type="submit" value="<?=$pro_brand.$pro_model?>" />	
								</form>
							</div>
						
							<div class="image">
								<form id="myForm" name="postlink" action="<?=$PREFIX?>/product/product_description.php" method="post">
									<input type="hidden" name="pid" value='<?=$pro_id?>' />
									<input type="image" src="<?=$PREFIX?>/product/product_image/<?=$pro_id?>.jpg" width="210" height="150" input name="link" type="submit" value="<?=$pro_brand.$pro_model?>" />				  	
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
								<?php if(!isset($_SESSION['user_id'])) { ?>
									<form action="<?=$PREFIX?>/login" method="post">
										<div class="bid_button">
											<input name="login"  class="button" type="submit" value="LOGIN" />
										</div>
									</form>
								<?php } else { 
									$userid = $_SESSION['user_id'];
									if($userid == 1 && $auctiontime >0) { ?>
										<form action="<?=$PREFIX?>/admin_site/product_info.php" method="post">
											<input type="hidden" name="uid" value='<?=$userid?>' />
											<input type="hidden" name="pid" value='<?=$pro_id?>' />
											<div class="bid_button">
												<input name="bid<?=$pro_id?>"  class="button" type="submit" value="Edit"/>
											</div>
										</form>
								<?php } else if($userid == 1 && $auctiontime <0) { ?>
									<form action="<?=$PREFIX?>/admin_site/product_info.php" method="post">
										<input type="hidden" name="uid" value='<?=$userid?>' />
										<input type="hidden" name="pid" value='<?=$pro_id?>' />
										<div class="bid_button">
											<input name="bid<?=$pro_id?>"  class="button" type="submit" value="Edit"/>
											<input name="endbid<?=$pro_id?>" class="button" type="submit" value="End Bid" />
										</div>
									</form>
								<?php } else if($userid > 0 && $auctiontime >0) { ?>
									<form action="<?=$PREFIX?>/Function/updatetimer.php" method="post">
										<input type="hidden" name="uid" value='<?=$userid?>' />
										<input type="hidden" name="pid" value='<?$pro_id?>' />
										<div class="bid_button">
											<input name="bid<?=$pro_id?>"  class="button" type="submit" value="<?=$tokenneed?> Token to BID" />
										</div>
									</form>
								<?php } else if($userid > 0 && $auctiontime <0) { ?>
									<div class="bid_button">
										<input name="endbid<?=$pro_id?>" class="button" type="submit" value="Bid Ended" />
									</div>
								<?php }
							} ?>
							
								<div id="read_more">						
									<form id="myForm" name="postlink" action="<?=$PREFIX?>/product/product_description.php" method="post">
										<input type="hidden" name="pid" value='<?=$pro_id?>' />
										<input name="link"  class="read_more" type="submit" value="Read More..." />	
									</form>
								</div>
							
								<span class="bid_spot">
									Highest Bidder<?=$highest_bidder?> 
									<!--This is the spot for you to put the bid function<div>Bid<div>-->
								</span>
								<span id="auto_bidder">
									Auto Bidder</br>
								</span>	
							</div>
						</li>
					</ul>
				<?php
				}
			} else {
				echo "no data";
			}
			?>
		</section>
	</section>
</div>