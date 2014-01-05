<?php
$data = array();
$tokens = getTokens($data);

$title = $lang['head_buy_tokens'];
?>
<h5><?php echo $lang['head_buy_tokens']; ?></h5>
<article class="auction_container">
	<div class="buy_tokens">
		Choose your package by clicking on the button. 
		Please take note that your payment is processed using Paypal. You will also receive a notification e-mail from CoinCod on this transaction.
	</div>
	<div class="package_value">
		<div class="ul">
			<?php
			if($tokens) {
				$i = 1;
				foreach($tokens as $token) {
					if(isset($logged)) {
						echo '<form id="buyForm' . $i . '" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">';
					} else {
						echo '<form id="buyForm' . $i . '" action="login.html" method="post">';
					}
					echo '<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="' . $token["code"] . '"> 
						<a href="#"><ul class="token_amount" onclick="buyForm' . $i . '.submit();">';
						if($i <4) { 
							echo '<li class="token_value">';
						} else { 
							echo '<li class="token_value_discount">'; 
						}
					echo $token["name"] . '<div class="discount">' . $token['discount'] . '</div>
							<div class="price">' . $token["price"] . '</div>
						</li>
						</ul></a></form>';
				$i++;
				}
			}
			?>
		</div>
	</div>
	<br/>
	<img class="bottom" src="includes/images/bottom/buy_tokens.png" alt="tokens shopping">
</article>