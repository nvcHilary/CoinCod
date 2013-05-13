<?php
session_start();
require_once "../config.php";
require_once "../sql_function.php";

if((isset($_POST['pid'])) && (isset($_POST['uid'])))
{
	$pid=$_POST['pid'];
	$uid=$_POST['uid'];
	$email = $_SESSION['email'];
	$user_data = getMemberRecords($email);
	$oldtoken = $user_data['token'];
	$product_data = getProductList($pid, 0, 0);
	$oldtotalbid = $user_data['bid'];
	$oldauctionprice=$get["aprice"];

	if($oldtotalbid <=200)                                {	$newtoken = $oldtoken - 1;      }
	if($oldtotalbid > 200 && $oldtotalbid <= 400)         {	$newtoken = $oldtoken - 2;		}
	if($oldtotalbid > 400 && $oldtotalbid <= 600)         {	$newtoken = $oldtoken - 3;		}
	if($oldtotalbid > 600 && $oldtotalbid <= 800)         {	$newtoken = $oldtoken - 4;		}
	if($oldtotalbid > 800 && $oldtotalbid <= 1000)        {	$newtoken = $oldtoken - 5;		}
	if($oldtotalbid > 1000 && $oldtotalbid <= 1200)       {	$newtoken = $oldtoken - 6;		}
	if($oldtotalbid > 1200 && $oldtotalbid <= 1400)       {	$newtoken = $oldtoken - 7;		}
	if($oldtotalbid > 1400 && $oldtotalbid <= 1600)       {	$newtoken = $oldtoken - 8;		}
	if($oldtotalbid > 1600 && $oldtotalbid <= 1800)       {	$newtoken = $oldtoken - 9;		}
	if($oldtotalbid > 1800 && $oldtotalbid <= 2000)       {	$newtoken = $oldtoken - 10;		}
	if($oldtotalbid > 2000 && $oldtotalbid <= 2200)       {	$newtoken = $oldtoken - 11;		}
	if($oldtotalbid > 2200 && $oldtotalbid <= 2400)       {	$newtoken = $oldtoken - 12;		}
	if($oldtotalbid > 2400 && $oldtotalbid <= 2600)       {	$newtoken = $oldtoken - 13;		}
	if($oldtotalbid > 2600 && $oldtotalbid <= 2800)       {	$newtoken = $oldtoken - 14;		}
	if($oldtotalbid > 2800 && $oldtotalbid <= 3000)       {	$newtoken = $oldtoken - 15;		}
	if($oldtotalbid > 3000 && $oldtotalbid <= 3200)       {	$newtoken = $oldtoken - 16;		}
	if($oldtotalbid > 3200 && $oldtotalbid <= 3400)       {	$newtoken = $oldtoken - 17;		}
	if($oldtotalbid > 3400 && $oldtotalbid <= 3600)       {	$newtoken = $oldtoken - 18;		}
	if($oldtotalbid > 3600 && $oldtotalbid <= 3800)       {	$newtoken = $oldtoken - 19;		}
	if($oldtotalbid > 3800 && $oldtotalbid <= 4000)       {	$newtoken = $oldtoken - 20;		}
	if($oldtotalbid > 4000)         					  { $newtoken = $oldtoken - 25;		}
	
	if($newtoken >= 0) {
		$newauctionstart=time();
		$newauctionend=time()+ 15;
		$newtotalbid = $oldtotalbid + 1;
		$newauctionprice = $oldauctionprice + 0.01;
		
		$result = bid($uid, $newtoken, $pid, $newauctionprice, $newauctionstart, $newauctionend, $newtotalbid);

		header("location:".mainPageURL());
	} else {
		header("location:".mainPageURL()."/buy_tokens");
	}
}
?>