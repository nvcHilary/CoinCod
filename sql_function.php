<?php
function codeClean($var) {
	$res = mysql_real_escape_string((get_magic_quotes_gpc())? stripslashes($var): $var);
	return $res;
}

function verifyLogin($email,$pass, $auth) {
	if($auth == 0) {
		$rule_auth = "";
	} else {
		$rule_auth = " AND Category='".$auth."'";
	}
	$hash_pass = md5($pass);
	$sql = mysql_query("SELECT * FROM member WHERE Email = '".$email."' AND hash_password = '".$hash_pass."' ".$rule_auth." ")or die ("Unable to run query:".mysql_error());
	$num = mysql_num_rows($sql);

	if ($num > 0)
		return true;
	return false;	
}

function getMemberRecords($email) {
	$sql = mysql_query("SELECT * FROM member 
						INNER JOIN member_info ON member.user_id=member_info.user_id
						WHERE Email = '".$email."'") or die ("Unable to run query:".mysql_error());
	$rows = mysql_fetch_array($sql);
	$records['id'] = $rows["user_id"];
	$records["category"] = $rows["Category"];
	$records["username"] = $rows["Username"];
	$records["email"] = $rows["Email"];
	$records["status"] = $rows["Status"];
	$records["token"] = $rows["Token"];
	$records["fname"] = $rows["first_name"];
	$records["lname"] = $rows["last_name"];
	$records["dob"] = $rows["date_birth"];
	$records["gender"] = $rows["Gender"];
	$records["add1"] = $rows["Address1"];
	$records["add2"] = $rows["Address2"];
	$records["city"] = $rows["City"];
	$records["state"] = $rows["State"];
	$records["zip"] = $rows["Zip"];
	$records["country"] = $rows["Country"];
	$records["mobile"] = $rows["Mobile"];
	
	return $records;
}

function getTotalProduct($search) {
	if($search == "0" ) { $rule_search = ""; } 
	else { $rule_search = " WHERE Brand LIKE '%".$search."%' or Model LIKE '%".$search."%'"; }
	
	$sql = mysql_query("SELECT * FROM product_list ".$rule_search)or die ("Unable to run query:".mysql_error());
	$records = mysql_num_rows($sql);
	return $records;
}

function getProductList($id, $search, $limit) {
	if($id == 0 ) { $rule_id = ""; } 
	else { $rule_id = " WHERE product_id='".$id."'"; }
	
	if($search == "0" ) { $rule_search = ""; } 
	else { $rule_search = " WHERE Brand LIKE '%".$search."%' or Model LIKE '%".$search."%'"; }
	
	if($limit == 0 ) { $rule = ""; } 
	else { $rule = "LIMIT ".$limit; }
	
	$sql = mysql_query("SELECT * FROM product_list 
						INNER JOIN product_category ON product_list.Category = product_category.category_id
						".$rule_id.$rule_search.$rule)or die ("Unable to run query:".mysql_error());

	$c=0;
	while ($rows = mysql_fetch_array($sql)) {
		$records[$c]["pid"] = $rows["product_id"];
		$records[$c]["brand"] = $rows["Brand"];
		$records[$c]["model"] = $rows["Model"];
		$records[$c]["mprice"] = $rows["market_price"];
		$records[$c]["aprice"] = $rows["auction_price"];
		$records[$c]["category"] = $rows["Category"];
		$records[$c]["availablity"] = $rows["Availability"];
		$records[$c]["description"] = $rows["Description"];
		$records[$c]["bid"] = $rows["total_bid"];
		$records[$c]["astart"] = $rows["auction_start"];
		$records[$c]["aend"] = $rows["auction_end"];
		$c++;
    }
	return $records;
}

function getBidderList($limit) {
	if($limit == 0 ) { $rule = ""; } 
	else { $rule = "LIMIT ".$limit; }
	$sql = mysql_query("SELECT * FROM product_log p
						INNER JOIN member m ON p.user_id=m.user_id 
						ORDER BY log_date DESC ".$rule ) or die("Unable to run query:".mysql_error()); 
	
	$c=0;
	while ($a_row = mysql_fetch_array($sql)) {
		$records[$c]['username'] = $a_row["Username"];
		$records[$c]['time'] = $a_row["log_date"];
		$c++;
	}	
	return $records;
}

function get_sitestatus() {
	$getstatus=mysql_query("SELECT * FROM status");
	$get=mysql_fetch_array($getstatus);
	$status=$get["site_status"];
	return $status;
}

function getStateList() {
	$sql = mysql_query("SELECT * FROM state ")or die ("Unable to run query:".mysql_error());

	$c=0;
	while ($rows = mysql_fetch_array($sql)) {
		$records[$c]["sid"] = $rows["state_id"];
		$records[$c]["state"] = $rows["state_name"];
		$c++;
    }
	return $records;
}

function getTotalState() {
	$sql = mysql_query("SELECT * FROM state ")or die ("Unable to run query:".mysql_error());
	$result = mysql_num_rows($sql);
	return $result;
}

function generatePassword() {
	$length = 8;
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }

    return $result;
}

function checkExist($email) {
	$password_check = mysql_query("SELECT * FROM member WHERE Email='".$email."'");
	$passwords_check = mysql_num_rows($password_check);
	
	if($passwords_check == 1) {
		return 1;
	} else {
		return 0;
	}
}

function sendfeedback ($email, $name, $subject, $comment) {
	$to = "contact@nexvend.com";
	$from = "$email";
	$subject = "Feedback : ".$subject;
	$message = '<html>
				<body>
				'.$comment.'
				</body>
				</html>';
	$headers = "From: $from\r\n";
	$headers .= "Content-type: text/html\r\n";
	$headers .= "Bcc: wilson@nexvend.com";
	$to = "$to";
	$mail = mail($to, $subject, $message, $headers);

	$toreply = "$email";
	$fromreply = "contact@nexvend.com";
	$subjectreply = "Thank You Feedback";
	$messagereply ='<html>
						<body>
							Dear '.$name.',
							<br/><br/>
							CoinCod will like to inform you that we have received your feedback! Here at CoinCod, we value all of our customers thus we evaluate every customers feedback seriously. Therefore, it might take some time for us to process all the feedbacks we received. We will get back to you soon. Thank you for your patience.
							<br/><br/>
							Best Regards,<br/>
							CoinCod Management Team
						</body>
					</html>';
	$headerreply = "From: $fromreply\r\n";
	$headerreply .= "Content-type: text/html\r\n";
	$headerreply .= "Bcc: wilson@nexvend.com";
	$mail = mail($toreply, $subjectreply, $messagereply, $headerreply);

	if($mail != 1){
		$result = "<div style='color:#d50000;text-align:left;'>Mailer Error (CS): Failed comment. Please try to resend again!</div>";
	}else{
		$result = "Thank you for comment";
	}
	return $result;
}

function updateUser($id, $email, $user, $category, $status, $token, $first_name, $last_name, $dob, $gender, $add1, $add2, $city, $state, $zip, $mobile) {
		$id = codeClean($id);
	 $email = codeClean($email);
	  $user = codeClean($user);
  $category = codeClean($category);
	$status = codeClean($status);
	 $token = codeClean($token);
	 $fname = codeClean($first_name);
	 $lname = codeClean($last_name);
	   $dob = codeClean($dob);
    $gender = codeClean($gender);
	  $add1 = codeClean($add1);
      $add2 = codeClean($add2);
	  $city = codeClean($city);
	 $state = codeClean($state);
	   $zip = codeClean($zip);
	$mobile = codeClean($mobile);
	
	$sql_main = mysql_query("UPDATE member SET Category='$category', Status='$status', Token='$token' WHERE user_id='$id'") or die ("Unable to run query:".mysql_error());	
	
	$sql = "UPDATE member_info SET first_name='$fname', last_name='$lname', date_birth='$dob', Gender='$gender', Address1='$add1', Address2='$add2', City='$city', Zip='$zip', State='$state', Mobile='$mobile' WHERE user_id='$id'";		
	$res = mysql_query($sql)or die ("Unable to run query:".mysql_error());	
	
	if(($res == 1) && ($sql_main == 1)) {
		$to = "$email";
		$toname = "$user";
		$from = "contact@nexvend.com";
		$fromname ="Customer Service";
		$subject = "CoinCod Profile Update";
		$message = '<html>
					<body>
					Dear ' . $user . ',
					<br /><br />
					Your personal information was updated. We hope you have an enjoyable auction experience with us.
					<br /><br /> 
					Thank You!  
					<br /><br /> 
					Best Regards,
					<br/>
					CoinCod Management Team
					</body>
					</html>';
		$headers = "From: $fromname <$from>\r\n";
		$headers .= "Content-type: text/html\r\n";
		$headers .= "Bcc: wilson@nexvend.com";
		$to = "$toname <$to>";
		$sending = mail($to, $subject, $message, $headers);
		
		if($sending != 1) {
			$result = "<div style='color:red;'>Email for updated personal information failed to be sent</div>";
		}else{
			$result = "Your personal information was updated. We hope you have an enjoyable auction experience with us.";
		}
	} else {
		$result = "<div style='color:red;'>Personal Information failed to be updated</div>";
	}
	return $result;
}

function sendpassword($emails) {
	
	$new_pass = generatePassword();
	$hash_new_pass = md5($new_pass);

	if(checkExist($emails) == 1) {
		$update_pass = mysql_query("UPDATE member SET hash_password='".$hash_new_pass."' WHERE Email='".$emails."'")or die ("Unable to run query:".mysql_error());
		if($update_pass == 1) {
			$to = "$emails";
			$toname = "$recover_name";
			$from = "support@nexvend.com";
			$fromname = "Customer Service";
			$subject = "Forgot Password";
			$messages = '<html>
							<body>
								This is your basic account information registered with CoinCod. 
								<br/><br/>
								E-mail Address: ' . $emails . ' <br />
								Password: ' . $new_pass . ' 
								<br/>
								Enjoy your awesome first auction experience with Coincod.
								<br /><br /> 
								Thank You! 
								<br /><br />
								NOTE:If you did not request for this email. Kindly ignore it.
								<br /><br />
								Best Regards,
								CoinCod Management Team
							</body>
						</html>';
			// end of message
			$headers = "From: $fromname <$from>\r\n";
			$headers .= "Content-type: text/html\r\n";
			$headers .= "Bcc: wilson@nexvend.com";
			$to = "$toname <$to>";
			$sentmail = mail($to,$subject,$messages,$headers);

			if($sentmail) {
				$result = "Your password has been successfully sent to your email.";
			} else {
				$result = "<div style='color:red;'>Sorry. The password failed to be sent. Please retry.</div>";
			}
		} else {
			$result = "<div style='color:red;'>Sorry. The password failed to be sent. Please retry.</div>";
		}
	} else {
		$result = "<div style='color:red;'>Sorry, this email cannot be found</div>";
	}
	return $result;
}

function sendRegLink($emails) {
	$to = "$emails";
	$toname = "$emails";
	$from = "support@nexvend.com";
	$fromname = "Customer Service";
	$subject = "Register Link";
	$messages = '<html>
					<body>
						Please Click link below to continue for register process. 
						<br/><br/>
						<a href="'.$_SERVER['SERVER_NAME'].'/registration/index.php?email='.urlencode(base64_encode($emails)).'">Register</a>
						<br/><br/>
						Enjoy your awesome first auction experience with Coincod.
						<br /><br /> 
						Thank You! 
						<br /><br />
						NOTE:If you did not request for this email. Kindly ignore it.
						<br /><br />
						Best Regards,
						CoinCod Management Team
					</body>
				</html>';
	// end of message
	$headers = "From: $fromname <$from>\r\n";
	$headers .= "Content-type: text/html\r\n";
	$headers .= "Bcc: wilson@nexvend.com";
	$to = "$toname <$to>";
	$sentmail = mail($to,$subject,$messages,$headers);

	if($sentmail) {
		$result = "Your Register Link has been successfully sent to your email.";
	} else {
		$result = "<div style='color:red;'>Sorry. The register link failed to be sent to your email. Please retry.</div>";
	}
	return $result;
}

function registeruser($category, $emails, $username, $passwords, $re_password, $status, $token, $firstname, $lastname, $dob, $gender, $add1, $add2, $city, $state, $zip, $country, $mobile) {
	$username_check = mysql_query("SELECT * FROM member WHERE Username='".$username."' LIMIT 1")or die ("Unable to run query:".mysql_error());
	$username_checks = mysql_num_rows($username_check);
	
	if ($username_checks > 0) {
		$error .= "<div style='color:red;'>your username is taken.Please use another username</div>";
	} else if(checkExist($emails) == 1) {
		$error .= "<div style='color:#d50000;text-align:left;'>
					Your Email Already Register. Please go to forget password! 
					If this problem persists kindly send us an email at <a href='mailto:support@coincod.com'>support@coincod.com.</a> Thank you.
				</div>";
	}else {
		$hash_password = md5($passwords); //MD5 encryption.
		$sql = mysql_query("INSERT INTO member (Category,Username,Email,hash_password,Status, Token, date_register) 
							VALUES('".$category."','".$username."','".$emails."','".$hash_password."', '".$status."', '".$token."', DATE_ADD(NOW(),INTERVAL 8 HOUR))") or die ("Unable to run query:".mysql_error()); 		
		if($sql == 1) {
			$sql_getid = mysql_query("SELECT * FROM member WHERE Email='".$emails."'");
			while($sql_get = mysql_fetch_array($sql_getid)) {
				$id = $sql_get['user_id'];
			}
			$sql_info = mysql_query("INSERT INTO member_info (user_id,first_name,last_name,date_birth,Gender,Address1,Address2,City,State,Zip,Country,Mobile,date_update) 
													VALUES('".$id."','".$firstname."','".$lastname."','".$dob."','".$gender."','".$add1."','".$add2."','".$city."','".$state."','".$zip."','".$country."','".$mobile."',now())") or die ("Unable to run query:".mysql_error()); 
		
			if($sql_info == 1) {
				$to = "$emails";
				$toname = "$username";
				$from = "support@nexvend.com";
				$fromname = "Customer Service";
				$subject = "Complete your registration";
				$message = '<html>
							<body>
							<article>
							Dear ' . $username . ',
							<br /><br />
							Congratulations for the successful registeration with CoinCod.  
							<br /><br />
							Your login information are as follows: 
							<br /><br />
							E-mail Address: ' . $emails . ' <br />
							Password: ' . $passwords . ' 
							<br /><br /> 
							Thanks You!  
							<br /><br /> 
							Best Regards,
							CoinCod Management Team
							</article>
							</body>
							</html>';
				// end of message
				$headers = "From: $fromname <$from>\r\n";
				$headers .= "Content-type: text/html\r\n";
				$headers .= "Bcc: wilson@nexvend.com";
				$to = "$toname <$to>";
				// Finally send the activation email to the member
				$sentactive = mail($to, $subject, $message, $headers);
				if($sentactive) {
					$error = header("location:".mainPageURL()."/registration/register_msg.php?action=4");
				} else {
					$error = "<div style='color:red;'>Registration Email failed to be sent</div>";
				}
			} else {
				$sql_del = mysql_query("DELETE FROM member WHERE user_id = '".$id."'")or die ("Unable to run query:".mysql_error());
				$error = "<div style='color:red;'>Unable to Register! Please Try Again!</div>";
			}
		} else {
			$error = "<div style='color:red;'>Unable to Register! Please Try Again!</div>";
		}
	}
	return $error;
}

////-----------------------------------admin sql --------------------------------------//
function getTotalUser() {
	$sql = mysql_query("SELECT * FROM member")or die("Unable to run query:".mysql_error());  
	$totaluser = mysql_num_rows($sql);
	return $totaluser;
}

function getUserListing($start, $limit) {
	$sql = mysql_query("SELECT * FROM member 
						INNER JOIN member_info ON member.user_id = member_info.user_id
						LIMIT ".$start.", ".$limit)or die("Unable to run query:".mysql_error()); 

	$c=0;
	while ($rows = mysql_fetch_array($sql)) {
		$records[$c]["id"] = $rows["user_id"];
		$records[$c]["category"] = $rows["Category"];
		$records[$c]["username"] = $rows["Username"];
		$records[$c]["email"] = $rows["Email"];
		$records[$c]["status"] = $rows["Status"];
		$records[$c]["token"] = $rows["Token"];
		$records[$c]["date"] = $rows["date_register"];
	$c++;
    }
	return $records;
}

function deleteUser($id) {
	$sql = mysql_query("DELETE FROM member WHERE user_id = '".$id."'") or die(mysql_error()); 
	$sql_info = mysql_query("DELETE FROM member_info WHERE user_id = '".$id."'") or die(mysql_error());
	return $result;
}

function getProductListing($start, $limit) {
	$sql = mysql_query("SELECT * FROM product_list 
						INNER JOIN product_category ON product_list.Category = product_category.category_id
						LIMIT ".$start.", ".$limit)or die ("Unable to run query:".mysql_error());

	$c=0;
	while ($rows = mysql_fetch_array($sql)) {
		$records[$c]["pid"] = $rows["product_id"];
		$records[$c]["brand"] = $rows["Brand"];
		$records[$c]["model"] = $rows["Model"];
		$records[$c]["mprice"] = $rows["market_price"];
		$records[$c]["aprice"] = $rows["auction_price"];
		$records[$c]["category_name"] = $rows["category_name"];
		$records[$c]["availability"] = $rows["Availability"];
		$records[$c]["description"] = $rows["Description"];
		$records[$c]["bid"] = $rows["total_bid"];
		$records[$c]["astart"] = $rows["auction_start"];
		$records[$c]["aend"] = $rows["auction_end"];
		$c++;
    }
	return $records;
}

function getCategoryList() {
	$sql = mysql_query("SELECT * FROM product_category ")or die ("Unable to run query:".mysql_error());

	$c=0;
	while ($rows = mysql_fetch_array($sql)) {
		$records[$c]["cid"] = $rows["category_id"];
		$records[$c]["cname"] = $rows["category_name"];
		$c++;
    }
	return $records;
}

function getTotalCategory() {
	$sql = mysql_query("SELECT * FROM product_category ")or die ("Unable to run query:".mysql_error());
	$result = mysql_num_rows($sql);
	return $result;
}

function updateProduct($id, $brand, $model, $mprice, $aprice, $category, $ava, $desc, $start, $end, $bids) {
		$id = codeClean($id);
	 $brand = codeClean($brand);
	 $model = codeClean($model);
	$mprice = codeClean($mprice);
	$aprice = codeClean($aprice);
  $category = codeClean($category);
	   $ava = codeClean($ava);
	  $desc = codeClean($desc);
	 $start = codeClean($start);
	   $end = codeClean($end);
	  $bids = codeClean($bids);
	
	$sql = "UPDATE product_list SET Brand='".$brand."', Model='".$model."', market_price='".$mprice."', auction_price='".$aprice."', Category='".$category."', Availability='".$ava."', Description='".$desc."', auction_start='".$start."', auction_end='".$end."', total_bid='".$bids."' WHERE product_id='".$id."'";		
	$res = mysql_query($sql)or die ("Unable to run query:".mysql_error());	
	
	return $result;
}

function registerProduct($id, $brand, $model, $mprice, $aprice, $category, $ava, $desc, $start, $end, $bids) {
		$id = codeClean($id);
	 $brand = codeClean($brand);
	 $model = codeClean($model);
	$mprice = codeClean($mprice);
	$aprice = codeClean($aprice);
  $category = codeClean($category);
	   $ava = codeClean($ava);
	  $desc = codeClean($desc);
	 $start = codeClean($start);
	   $end = codeClean($end);
	  $bids = codeClean($bids);
	
	$sql = "INSERT INTO product_list (Brand, Model, market_price, auction_price, Category, Availability, Description, auction_start, auction_end, total_bid ) 
							VALUES('".$brand."', '".$model."', '".$mprice."', '".$aprice."', '".$category."', '".$ava."', '".$desc."', '".$start."', '".$end."', '".$bids."')";	
	$res = mysql_query($sql)or die ("Unable to run query:".mysql_error());	
	
	return $result;
}

function deleteProduct($id) {
	$sql = mysql_query("DELETE FROM product_list WHERE product_id = '".$id."'") or die(mysql_error()); 
	return $result;
}

function getStatus() {
	$sql = "SELECT * FROM status";		
	$res = mysql_query($sql)or die ("Unable to run query:".mysql_error());	
	$data = mysql_fetch_array($res);
	$result = $data['site_status'];
	return $result;
}

function updateStatus($status) {
	$status = codeClean($status);
	
	$sql = "UPDATE status SET site_status='".$status."'";		
	$res = mysql_query($sql)or die ("Unable to run query:".mysql_error());	
	
	return $result;
}

//---------------function------------------------//
function bid($uid, $token, $pid, $aprice, $astart, $aend, $bid) {
	$updateToken = mysql_query("UPDATE member SET Token='".$token."' WHERE user_id='".$uid."'")or die ("Unable to run query:".mysql_error());	
	if($updateToken) {
		$updateProduct = mysql_query("UPDATE product_list SET auction_price='".$aprice."', auction_start='".$astart."', auction_end='".$aend."', total_bid='".$bid."' WHERE product_id='".$pid."'") or die ("Unable to run query:".mysql_error());	
		if($updateProduct) {
			$insertLog = mysql_query("INSERT INTO product_log (product_id,user_id,auction_price,log_date)VALUES('".$pid."','".$uid."','".$newauctionprice."',now())")or die ("Unable to run query:".mysql_error());	
			$result = "update done";
		} else {
			$result = "Failed to bid";
		}
	} else {
		$result = "Failed to bid";
	} 
	return $result;
}

?>