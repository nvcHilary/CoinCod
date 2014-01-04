<?php
session_start();
require_once "../models/config.php";
require_once "../models/sql_function.php";
require_once "../variables/variables.php";

$logged = $_SESSION['coin_id'];

if((isset($_POST['pid'])) && (isset($logged))) {
	$pid = $_POST['pid'];
	$customer = getCustomerById($logged);
	$oldtoken = $customer['token'];
	
	$product = getProductById($pid);
	$oldtotalbid = $product['bids'];
	$oldauctionprice = $product["aprice"];

	if($oldtotalbid <=200)                                {	$newtoken = $oldtoken - 1;	$newauctionstart=time();	$newauctionend=time()+ 18000;	}
	if($oldtotalbid > 200 && $oldtotalbid <= 400)         {	$newtoken = $oldtoken - 2;	$newauctionstart=time();	$newauctionend=time()+ 14400;	}
	if($oldtotalbid > 400 && $oldtotalbid <= 600)         {	$newtoken = $oldtoken - 3;	$newauctionstart=time();	$newauctionend=time()+ 10800;	}
	if($oldtotalbid > 600 && $oldtotalbid <= 800)         {	$newtoken = $oldtoken - 4;	$newauctionstart=time();	$newauctionend=time()+ 7200;	}
	if($oldtotalbid > 800 && $oldtotalbid <= 1000)        {	$newtoken = $oldtoken - 5;	$newauctionstart=time();	$newauctionend=time()+ 3600;	}
	if($oldtotalbid > 1000 && $oldtotalbid <= 1200)       {	$newtoken = $oldtoken - 6;	$newauctionstart=time();	$newauctionend=time()+ 2700;	}
	if($oldtotalbid > 1200 && $oldtotalbid <= 1400)       {	$newtoken = $oldtoken - 7;	$newauctionstart=time();	$newauctionend=time()+ 2400;	}
	if($oldtotalbid > 1400 && $oldtotalbid <= 1600)       {	$newtoken = $oldtoken - 8;	$newauctionstart=time();	$newauctionend=time()+ 2100;	}
	if($oldtotalbid > 1600 && $oldtotalbid <= 1800)       {	$newtoken = $oldtoken - 9;	$newauctionstart=time();	$newauctionend=time()+ 1800;	}
	if($oldtotalbid > 1800 && $oldtotalbid <= 2000)       {	$newtoken = $oldtoken - 10;	$newauctionstart=time();	$newauctionend=time()+ 1500;	}
	if($oldtotalbid > 2000 && $oldtotalbid <= 2200)       {	$newtoken = $oldtoken - 11;	$newauctionstart=time();	$newauctionend=time()+ 1200;	}
	if($oldtotalbid > 2200 && $oldtotalbid <= 2400)       {	$newtoken = $oldtoken - 12;	$newauctionstart=time();	$newauctionend=time()+ 900;		}
	if($oldtotalbid > 2400 && $oldtotalbid <= 2600)       {	$newtoken = $oldtoken - 13;	$newauctionstart=time();	$newauctionend=time()+ 600;		}
	if($oldtotalbid > 2600 && $oldtotalbid <= 2800)       {	$newtoken = $oldtoken - 14;	$newauctionstart=time();	$newauctionend=time()+ 300;		}
	if($oldtotalbid > 2800 && $oldtotalbid <= 3000)       {	$newtoken = $oldtoken - 15;	$newauctionstart=time();	$newauctionend=time()+ 240;		}
	if($oldtotalbid > 3000 && $oldtotalbid <= 3200)       {	$newtoken = $oldtoken - 16;	$newauctionstart=time();	$newauctionend=time()+ 180;		}
	if($oldtotalbid > 3200 && $oldtotalbid <= 3400)       {	$newtoken = $oldtoken - 17;	$newauctionstart=time();	$newauctionend=time()+ 120;		}
	if($oldtotalbid > 3400 && $oldtotalbid <= 3600)       {	$newtoken = $oldtoken - 18;	$newauctionstart=time();	$newauctionend=time()+ 60;		}
	if($oldtotalbid > 3600 && $oldtotalbid <= 3800)       {	$newtoken = $oldtoken - 19;	$newauctionstart=time();	$newauctionend=time()+ 45;		}
	if($oldtotalbid > 3800 && $oldtotalbid <= 4000)       {	$newtoken = $oldtoken - 20;	$newauctionstart=time();	$newauctionend=time()+ 30;		}
	if($oldtotalbid > 4000)         					  { $newtoken = $oldtoken - 25;	$newauctionstart=time();	$newauctionend=time()+ 15;		}
	
	if($newtoken >= 0) {
		$newtotalbid = $oldtotalbid + 1;
		
		$result = checkBiddingLog($pid);
		if($result) {
			$newauctionprice = $oldauctionprice + 0.01;			
		} else {
			$newauctionprice = $oldauctionprice - 0.01;
		}
		
		$data = array(
			'productId'	=> $pid,
			'customerId'	=> $logged,
			'aprice'		=> $newauctionprice,
			'modify_by'		=> "s".$logged,
			'datestart'		=> date("Y-m-d H:i:s", $newauctionstart),
			'dateend'		=> date("Y-m-d H:i:s", $newauctionend),
			'bids'			=> $newtotalbid,
			'token'			=> $newtoken
		);
		
		$updatebidcustomer = editCustomer($data);
		$updatebidproduct = editProduct($data);
		$bid_query = insertBiddingLog($data);
		
		$_SESSION['success'] = $lang['success_bidding'];
		header("location:".mainPageURL());
	} else {
		$_SESSION['error_warning'] = $lang['error_tokens']; 
		header("location:buy_tokens.html");
	}
}
?>