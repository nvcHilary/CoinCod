<?php
	ob_start();
	session_start();
	require_once '../config.php';
	require_once '../sql_function.php';
?>
<div class="buy_tokens">
	Choose your package by clicking on the button. 
	Please take note that your payment is processed using Paypal. You will also receive a notification e-mail from CoinCod on this transaction.
</div>
<div class="package_value">
	<div class="ul">
		<?php 	
		$tokens = array( 
			array( 	"Name" => "50 tokens", 
					"Discount" => "",
					"Code" => "D83KWGJ6FX9YA",
					"Price" => "RM 15.00"
			),
			array( 	"Name" => "100 tokens", 
					"Discount" => "",
					"Code" => "X24J6YZZDUN48",
					"Price" => "RM 30.00"
			),
			array( 	"Name" => "200 tokens", 
					"Discount" => "",
					"Code" => "BXDZPNG8VAQCA",
					"Price" => "RM 60.00"
			),
			array( 	"Name" => "300 tokens", 
					"Discount" => "",
					"Code" => "SDTBNLJWTY7RJ",
					"Price" => "RM 90.00"
			),
			array( 	"Name" => "500 tokens", 
					"Discount" => "(5% discount)",
					"Code" => "QBD5BC7F7S5YW",
					"Price" => "RM 142.50"
			),
			array( 	"Name" => "1000 tokens", 
					"Discount" => "(10% discount)",
					"Code" => "QRQHHTB6L7AK2",
					"Price" => "RM 270.00"
			),
			array( 	"Name" => "2500 tokens", 
					"Discount" => "(20% discount)",
					"Code" => "VGJA9EG9CZ2QJ",
					"Price" => "RM 600.00"
			),
			array( 	"Name" => "5000 tokens", 
					"Discount" => "(25% discount)",
					"Code" => "AZR4JZBCWDX2L",
					"Price" => "RM 1125.00"
			)
		);

		$i = 1;
		for ($row = 0; $row < 8; $row++) {
			if(isset($_SESSION['user_id'])) {
				echo '<form id="buyForm'.$i.'" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">';
			} else {
				echo '<form id="buyForm'.$i.'" action="'.mainPageURL().'/login" method="post">';
			}
			echo '<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="'.$tokens[$row]["Code"].'"> 
				<a href="#"><ul class="token_amount" onclick="buyForm'.$i.'.submit();">';
				if($row <4) { 
					echo '<li class="token_value">';
				} else { 
					echo '<li class="token_value_discount">'; 
				}
				echo $tokens[$row]["Name"].'<div class="discount">'.$tokens[$row]['Discount'].'</div>
				<div class="price">'.$tokens[$row]["Price"].'</div>
				</li>
			</ul></a></form>';
			$i++;
		}
		?>	
	</div>
</div>