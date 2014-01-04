<?php
$product_id = $_GET['pid'];

$product = getProductById($product_id);
$product_time = strtotime($product['dateend']) - time();
$token_needed = $product["bids"];

$data_setting = array(
	'group'	=> 'social',
	'Key'	=> 'fbshare'
);
$settings = getSettings($data_setting);

foreach($settings as $setting) {
	$fbshare = $setting['value'];
}

$title = $product['brand']." ".$product['model'];;

require_once ('models/token_function.php');
?>
<script>
var poll<?php echo $product_id; ?> = function () {
	$.ajax({
		type: 'POST',
		url: "controller/timer.php",
		data : {
			id : <?php echo $product_id; ?>
		}
	}).done(function (data) {
		$("#bid_timer_<?php echo $product_id; ?>").text(data);
		setTimeout(poll<?php echo $product_id; ?>, 1000);
	});
}
poll<?php echo $product_id; ?>();
</script>

<article class="auction_container">
	<div id="product">
		<div class="name">
			<?php echo $product['brand']." ".$product['model']; ?> 
		</div>
	</div>
	
	<div id="description">
		<table width="900" border="0">
			<tr>
				<td width="412">
					<div class="product_image"> 
						<?php
						$data_image = array(
							'product_id' 	=> $product_id,
							'start'			=> 0,
							'limit'			=> 1
						);
						$images = getProductImage($data_image);
						if($images) {
							foreach($images as $image) {
								$images_show = "product/" . $product_id . "/" . $image['image'];
							}
						} else {
							$images_show = "no_image.jpg";
						}
						?>
						<input type="image" src="data/image/<?php echo $images_show; ?>" width="390" height="275" border="0" input name="link" type="submit" value="<?php echo $product['brand']." ".$product['model']; ?>" />
					</div>
				</td>
				<td width="100">&nbsp;</td>
				<td width="432" rowspan="3">
					<div class="product">
						<div class="description">
							<?php echo $product['description']; ?>
						</div>
					</div>
				</td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			
			<tr>
				<td>
					<div class="desc_bid_box">
						<div class="price">					
							<div class="current_bid_price">
								<div class="price_title">
									<?php echo $lang['text_current_price']; ?>: 
								</div> 
								RM <?php echo $product['aprice']; ?>
							</div>
		
							<div class="market_price">
								<div class="price_title">
									<?php echo $lang['text_retail_price']; ?>: 
								</div> 
								RM <?php echo $product['mprice']; ?>
							</div>
						</div>
						
						<div class="product_bid_button">
							<?php if(!isset($logged)) { ?>
								<form action="login.html" method="post">
									<div class="bid_button">
										<input name="login"  class="button" type="submit" value="<?php echo $lang['button_login']; ?>" />
									</div>
								</form>
							<?php } else { 
								if($product_time > 0) { ?>
									<form action="bid_function.html" method="post">
										<input type="hidden" name="pid" value="<?php echo $product_id; ?>" />
										<div class="bid_button">
											<input name="bid<?php echo $product_id; ?>"  class="button" type="submit" value="<?php echo $tokenneed; ?> Token to BID" />
										</div>
									</form>
								<?php } else if($product_time < 0) { ?>
									<div  class="bid_button">
										<input name="endbid<?php echo $product_id; ?>" class="button" type="submit" value="<?php echo $lang['button_bid_end']; ?>" />
									</div>
								<?php }
							} ?>
						</div>
						
						<div class="highest_bidder">
							<div id="top_highest_bidder"><?php echo $lang['text_highest_bidder']; ?> :</div>
							<?php
								$data_bidder = array(
									'productId'	=> $product_id,
									'start'		=> 0,
									'limit'		=> 5
								);
								$bidders = getHighestBidder($data_bidder);
								if($bidders) {
									foreach($bidders as $bidder) {
										echo $bidder['username']." ".$bidder['date_added']."<br/>";
									}
								}
							?>
						</div>

						<div id="bid_timer_<?php echo $product_id; ?>" class="timer" end_time="">
						</div>
					</div>
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</div>

	<div id='fb-root'></div>
	<script src='http://connect.facebook.net/en_US/all.js'></script>
	<p id="fb"><a onclick='postToFeed(); return false;'><img src="includes/images/facebooksharebutton.png"></a></p>
	<p id='msg'></p>
	
	<script>
		FB.init({appId: "<?php echo $fbshare; ?>", status: true, cookie: true});
		function postToFeed() {
			var obj = {
				method: 'feed',
				redirect_uri: '<?php echo mainPageURL(); ?>',
				link: 'http://localhost/coin/product18-brand1-model1.html',
				picture: '<?php echo mainPageURL(); ?>/data/image/<?php echo $images_show; ?>',
				name: '<?php echo $product['brand']." ".$product['model']; ?> ',
				caption: ' ',
				description: '<?php echo strip_tags($product['description']); ?>'
			};
			
			function callback(response) {
				document.getElementById('msg').innerHTML = "Post ID: " + response['post_id'];
			}
			
			FB.ui(obj, callback);
		}
	</script>
</article>