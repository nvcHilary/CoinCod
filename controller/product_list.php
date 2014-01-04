<section class="site_body">
<?php
	$products = getProducts($data_product);
	$token_needed = "";
	if($products) {
		foreach($products as $product) {
			$auctiontime = strtotime($product['dateend']) - time();
			
			$token_needed = $product["bids"];
			
			include('models/token_function.php');
?>
			<script>
			var poll<?php echo $product['product_id']; ?> = function () {
				$.ajax({
					type: 'POST',
					url: "controller/timer.php",
					data : {
						id : <?php echo $product['product_id']; ?>
					}
				}).done(function (data) {
					$("#bid_timer_<?php echo $product['product_id']; ?>").text(data);
					setTimeout(poll<?php echo $product['product_id']; ?>, 1000);
				});
			}
			poll<?php echo $product['product_id']; ?>();
			</script>
			<ul id="auction_list_ul" class="auction_box">
				<li>
					<div class="title">
						<form id="myForm" name="postlink" action="product<?php echo $product['product_id']."-".$product['brand']."-".$product['model']; ?>.html" method="post">
							<input type="hidden" name="pid" value='<?php echo $product['product_id']; ?>' />
							<input name="link"  class="button_title" type="submit" value="<?php echo $product['brand']." ".$product['model']; ?>" />	
						</form>
					</div>
					
					<div class="image">
						<form id="myForm" name="postlink" action="product<?php echo $product['product_id']."-".$product['brand']."-".$product['model']; ?>.html" method="post">
							<input type="hidden" name="pid" value='<?php echo $product['product_id']; ?>' />
							<?php
								$data_image = array(
									'product_id' 	=> $product['product_id'],
									'start'			=> 0,
									'limit'			=> 1
								);
								$images = getProductImage($data_image);
								if($images) {
									foreach($images as $image) {
										$images_show = "product/" . $product['product_id'] . "/" . $image['image'];
									}
								} else {
									$images_show = "no_image.jpg";
								}
								?>
							<input type="image" src="data/image/<?php echo $images_show; ?>" width="210" height="150" input name="link" type="submit" value="<?php echo $product['brand']." ".$product['model']; ?>" />				  	
						</form>
					</div>
					
					<div class="bid_current">
						<?php echo $lang['text_current_aprice']; ?>
						<div class="bid_current_color">
							RM <?php echo $product['aprice']; ?>
						</div>
					</div>
					
					<div class="bid_box">
						<div id="bid_timer_<?php echo $product['product_id']; ?>" class="bid_timer" end_time="">
							<?php echo $auctiontime; ?>
						</div>
						
						<?php if(!isset($logged)) { ?>
							<form action="login.html" method="post">
								<div class="bid_button">
									<input name="login"  class="button" type="submit" value="<?php echo $lang['button_login']; ?>" />
								</div>
							</form>
						<?php } else { 
							if($auctiontime > 0) { ?>
								<form action="bid_function.html" method="post">
									<input type="hidden" name="pid" value="<?php echo $product['product_id']; ?>" />
									<div class="bid_button">
										<input name="bid<?php echo $product['product_id']; ?>"  class="button" type="submit" value="<?php echo $tokenneed; ?> Token to BID" />
									</div>
								</form>
							<?php } else if($auctiontime < 0) { ?>
								<div  class="bid_button">
									<input name="endbid<?php echo $product['product_id']; ?>" class="button" type="submit" value="<?php echo $lang['button_bid_end']; ?>" />
								</div>
							<?php }
						} ?>
						
						<div id="read_more">						
							<form id="myForm" name="postlink" action="product<?php echo $product['product_id']."-".$product['brand']."-".$product['model']; ?>.html" method="post">
								<input type="hidden" name="pid" value='<?php echo $product['product_id']; ?>' />
								<input name="link"  class="read_more" type="submit" value="<?php echo $lang['text_read_more']; ?>" />	
							</form>
						</div>
						
						<span class="bid_spot">
							<?php echo $lang['text_highest_bidder']; ?> : 
							<?php
								$data_bidder = array(
									'productId'	=> $product['product_id'],
									'start'		=> 0,
									'limit'		=> 1
								);
								$bidders = getHighestBidder($data_bidder);
								if($bidders) {
									foreach($bidders as $bidder) {
										echo $bidder['username'];
									}
								}
							?>
							<!--This is the spot for you to put the bid function<div>Bid<div>-->
						</span>
					</div>
				</li>
			</ul>
<?php
		}
	} else { 
		echo $lang['text_no_results'];
	} ?>
</section>