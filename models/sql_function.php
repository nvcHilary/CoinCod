<?php
function sendMail($data_mail) {
	
	$mail = new PHPMailer();
	//$mail->SetLanguage("en", "../admin/includes/js/phpmailer/language/");
		
	$data = array(
		'group'	=> 'email'
	);
	$settings = getSettings($data);
	if($settings) {
		foreach($settings as $setting) {
			if($setting['key'] == 'mail_protocol') {		$protocol = $setting['value']; 	}
			if($setting['key'] == 'mail_address_name') {	$address_name = $setting['value']; 	}
			if($setting['key'] == 'mail_address') {			$address = $setting['value']; 	}
			if($setting['key'] == 'mail_smtp_host') {		$smtp_host = $setting['value']; 	}
			if($setting['key'] == 'mail_smtp_email_name') {	$smtp_email_name = $setting['value']; 	}
			if($setting['key'] == 'mail_smtp_email') {		$smtp_email = $setting['value']; 	}
			if($setting['key'] == 'mail_smtp_password') {	$smtp_password = $setting['value']; 	}
			if($setting['key'] == 'mail_smtp_port') {		$smtp_port = $setting['value']; 	}
		}
	}
	
	if($protocol == 'SMTP') {
		
		$mail->IsSMTP();
		$mail->IsHTML(true);
		$mail->Host     		= $smtp_host;
		$mail->Port     		= $smtp_port;
		$mail->SMTPAuth 		= true;
		$mail->SMTPSecure   	= "ssl";
		$mail->Username   		= $smtp_email;
		$mail->Password   		= $smtp_password;
		$mail->From     		= $smtp_email;
		$mail->FromName   		= $smtp_email_name;
		$mail->AddAddress($data_mail['to_email'], $data_mail['to_name']);
		$mail->Subject  = $data_mail['subject'];
		$mail->Body     = $data_mail['body'];
		$mail->WordWrap = 50;
		
	} else if($protocol == 'MAIL') {
		$mail->SetFrom($address, $address_name);
		$mail->AddAddress($data_mail['to_email'], $data_mail['to_name']);
		$mail->Subject    = $data_mail['subject'];
		$mail->MsgHTML($data_mail['body']);
	}
	
	if(!$mail->Send())
		return false;
	return true;
}

function sendInMail($data_mail) {
	
	$mail = new PHPMailer();
	$mail->SetFrom($data['from_email'], $data['from_name']);
	$mail->AddAddress($data_mail['to_email'], $data_mail['to_name']);
	$mail->Subject    = $data_mail['subject'];
	$mail->MsgHTML($data_mail['body']);
		
	if(!$mail->Send())
		return false;
	return true;
}

//admin/customer_form.php + register.php + forgot_password.php + login.php + home.php + profile.php
function verifyDuplicateCustomer($data, $column) {
	$update_data = str_replace(' ', '', $data);
	$sql = mysql_query("SELECT * FROM " . e01 . " WHERE " . $column . " = '" . $update_data . "'")or die("Unable to run query:".mysql_error());
	$num = mysql_num_rows($sql);

	if ($num > 0)
		return true;
	return false;	
}

//login.php
function verifyLoginCustomer($data = array()) { 
	$sql = "SELECT id FROM " . e01 . " WHERE email = '" . $data['email'] . "' AND password = '" . $data['password'] . "' LIMIT 1";
	$query = mysql_query($sql);
	$num = mysql_num_rows($query);
	
	if ($num > 0)
		return true;
	return false;
}

//login.php + ....
function getCustomerByEmail($data = array()) {
	$records = array();
	$sql = "SELECT id, username, email, status, fcode FROM " . e01 . " WHERE email = '" . $data['email'] . "'";
	$query = mysql_query($sql);
	
	$rows = mysql_fetch_array($query);
	$records["customer_id"] = $rows["id"];
	$records["username"] = $rows["username"];
	$records['email'] = $rows["email"];
	$records["status"] = $rows["status"];
	$records["fcode"] = $rows["fcode"];

	return $records;
}

//Front End
//admin/customer_form.php
function getCountryCode() {
	$records = array();
	$sql = mysql_query("SELECT country_id, name, iso_code_2, iso_code_3 FROM ".e04) or die ("Unable to run query:".mysql_error());
	$c=0;
	while ($rows = mysql_fetch_array($sql)) {
		$records[$c]["country_id"] = $rows["country_id"];
		$records[$c]["name"] = $rows["name"];
		$records[$c]["iso_code_2"] = $rows["iso_code_2"];
		$records[$c]["iso_code_3"] = $rows["iso_code_3"];
		$c++;
	}	
	return $records;
}

//admin/customer_form.php
function getStateCode($country) {
	$records = array();
	$sql = mysql_query("SELECT zone_id, name, code FROM " . e05 . " WHERE country_id='" . $country . "'" ) or die ("Unable to run query:".mysql_error());
	$c=0;
	while ($rows = mysql_fetch_array($sql)) {
		$records[$c]["zone_id"] = $rows["zone_id"];
		$records[$c]["name"] = $rows["name"];
		$records[$c]["code"] = $rows["code"];
		$c++;
	}	
	return $records;
}

//admin/customer.php
function getTotalCustomers() {
	$sql = "SELECT COUNT(*) AS total FROM " . e01;
	$query = mysql_query($sql);
	
	$rows = mysql_fetch_array($query);
	return $rows["total"];
}

//admin/customer.php
function getCustomers($data = array()) {
	$records = array();
	$sql = "SELECT a.id, a.username, a.date_added FROM " . e01 . " a LEFT JOIN " . e02 . " b ON a.id = b.customer_id ";
	
	$sort_data = array(
		'a.username',
	);	
	
	if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		$sql .= " ORDER BY " . $data['sort'];	
	} else {
		$sql .= " ORDER BY a.username";	
	}
	
	if (isset($data['order']) && ($data['order'] == 'DESC')) {
		$sql .= " DESC";
	} else {
		$sql .= " ASC";
	}
	
	if (isset($data['start']) || isset($data['limit'])) {
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}	
		
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	
	$query = mysql_query($sql);
	
	$c=0;
	while ($rows = mysql_fetch_array($query)) {
		$records[$c]["customer_id"] = $rows["id"];
		$records[$c]["username"] = $rows["username"];
		$records[$c]["date_added"] = $rows["date_added"];
		$c++;
	}

	return $records;
}

//admin/customer_form.php + header.php + bid_function.php
function getCustomerById($customer_id) {
	$records = array();
	$sql = "SELECT a.id, a.username, a.password, a.email, a.token, a.status, a.fcode, b.first_name, b.last_name, b.date_birth, b.gender, b.address1, b.address2, b.city, b.zip, b.country, b.state, b.phone 
				FROM " . e01 . " a LEFT JOIN " . e02 . " b ON a.id=b.customer_id WHERE a.id = '" . (int)$customer_id . "'";
	$query = mysql_query($sql);
	
	$rows = mysql_fetch_array($query);
	$records["username"] = $rows["username"];
	$records["password"] = $rows["password"];
	$records["email"] = $rows["email"];
	$records["token"] = $rows["token"];
	$records["status"] = $rows['status'];
	$records["fcode"] = $rows['fcode'];
	$records["fname"] = $rows['first_name'];
	$records["lname"] = $rows['last_name'];
	$records["dob"] = $rows['date_birth'];
	$records["gender"] = $rows['gender'];
	$records["add1"] = $rows['address1'];
	$records["add2"] = $rows['address2'];
	$records["city"] = $rows['city'];
	$records["zip"] = $rows['zip'];
	$records["country"] = $rows['country'];
	$records["state"] = $rows['state'];
	$records["phone"] = $rows['phone'];

	return $records;
}

//admin/customer_form.php + register.php + profile.php
function insertCustomer($data) {
	$sql = "INSERT INTO " . e01 . " (username, email, password, status, fcode, token, ip, date_added)
				VALUES('" . $data['username'] . "', '" . $data['email'] . "', '" . $data['password'] . "', '" . $data['status'] . "', '" . $data['fcode'] . "', '" . $data['token'] . "', '" . $_SERVER['REMOTE_ADDR'] . "', now())";
	$query = mysql_query($sql);
	
	$customer_id = mysql_insert_id();
	
	$sql_info = "INSERT INTO " . e02 . " (customer_id, first_name, last_name, date_birth, gender, address1, address2, city, zip, state, country, phone)
				VALUES('" . $customer_id . "', '" . $data['fname'] . "', '" . $data['lname'] . "', '" . $data['dob'] . "', '" . $data['gender'] . "', '" . $data['add1'] . "',
					'" . $data['add2'] . "', '" . $data['city'] . "', '" . $data['zip'] . "', '" . $data['state'] . "', '" . $data['country'] . "', '" . $data['phone'] . "')";
	$query_info = mysql_query($sql_info);
	
	if( ($query) && ($query_info) ) 
		return true;
	return false;
}

//admin/customer_form.php + profile.php
function editCustomer($data) {
	$info = getCustomerById($data['customerId']);

	$sql = "UPDATE " . e01 . " SET id='" . $data['customerId'] . "'";
	
	$implode = array();
	
	if(isset($data['username'])) {
		if ($info['username'] != $data['username']) {
			$implode[] = " username = '" . $data['username'] . "'";
			insertCustomerLog($data['customerId'], 1, $info['username'], $data['username'], $data['modify_by']);
		}
	}
	
	if(isset($data['email'])) {
		if ($info['email'] != $data['email']) {
			$implode[] = " email = '" . $data['email'] . "'";
			insertCustomerLog($data['customerId'], 2, $info['email'], $data['email'], $data['modify_by']);
		}
	}
	
	if(isset($data['password'])) {
		if ($info['password'] != $data['password']) {
			$implode[] = " password = '" . $data['password'] . "'";
			insertCustomerLog($data['customerId'], 3, $info['password'], $data['password'], $data['modify_by']);
		}
	}
	
	if(isset($data['status'])) {
		if ($info['status'] != $data['status']) {
			$implode[] = " status = '" . $data['status'] . "'";
			insertCustomerLog($data['customerId'], 5, $info['status'], $data['status'], $data['modify_by']);
		}
	}
	
	if(isset($data['token'])) {
		if ($info['token'] != $data['token']) {
			$implode[] = " token = '" . $data['token'] . "'";
			insertCustomerLog($data['customerId'], 4, $info['token'], $data['token'], $data['modify_by']);
		}
	}
	if(isset($data['fcode'])) {
		if ($info['fcode'] != $data['fcode']) {
			$implode[] = " fcode = '" . $data['fcode'] . "'";
		}
	}
	if ($implode) {
		$sql .= " , " . implode(" , ", $implode);
	}
	
	$sql .= " WHERE id='" . $data['customerId'] . "'";
	
	$query = mysql_query($sql);
	
	$sql_info = "UPDATE " . e02 . " SET customer_id='" . $data['customerId'] . "'";
	
	$implode_info = array();
	
	if(isset($data['fname'])) {
		if ($info['fname'] != $data['fname']) {
			$implode_info[] = " first_name = '" . $data['fname'] . "'";
			insertCustomerLog($data['customerId'], 6, $info['fname'], $data['fname'], $data['modify_by']);
		}
	}
	
	if(isset($data['lname'])) {
		if ($info['lname'] != $data['lname']) {
			$implode_info[] = " last_name = '" . $data['lname'] . "'";
			insertCustomerLog($data['customerId'], 7, $info['lname'], $data['lname'], $data['modify_by']);
		}
	}
	
	if(isset($data['dob'])) {
		if ($info['dob'] != $data['dob']) {
			$implode_info[] = " date_birth = '" . $data['dob'] . "'";
			insertCustomerLog($data['customerId'], 8, $info['dob'], $data['dob'], $data['modify_by']);
		}
	}
	
	if(isset($data['gender'])) {
		if ($info['gender'] != $data['gender']) {
			$implode_info[] = " gender = '" . $data['gender'] . "'";
			insertCustomerLog($data['customerId'], 9, $info['gender'], $data['gender'], $data['modify_by']);
		}
	}
	
	if(isset($data['add1'])) {
		if ($info['add1'] != $data['add1']) {
			$implode_info[] = " address1 = '" . $data['add1'] . "'";
			insertCustomerLog($data['customerId'], 10, $info['add1'], $data['add1'], $data['modify_by']);
		}
	}
	
	if(isset($data['add2'])) {
		if ($info['add2'] != $data['add2']) {
			$implode_info[] = " address2 = '" . $data['add2'] . "'";
			insertCustomerLog($data['customerId'], 11, $info['add2'], $data['add2'], $data['modify_by']);
		}
	}
	
	if(isset($data['city'])) {
		if ($info['city'] != $data['city']) {
			$implode_info[] = " city = '" . $data['city'] . "'";
			insertCustomerLog($data['customerId'], 12, $info['city'], $data['city'], $data['modify_by']);
		}
	}
	
	if(isset($data['zip'])) {
		if ($info['zip'] != $data['zip']) {
			$implode_info[] = " zip = '" . $data['zip'] . "'";
			insertCustomerLog($data['customerId'], 13, $info['zip'], $data['zip'], $data['modify_by']);
		}
	}
	
	if(isset($data['state'])) {
		if ($info['state'] != $data['state']) {
			$implode_info[] = " state = '" . $data['state'] . "'";
			insertCustomerLog($data['customerId'], 14, $info['state'], $data['state'], $data['modify_by']);
		}
	}
	
	if(isset($data['country'])) {
		if ($info['country'] != $data['country']) {
			$implode_info[] = " country = '" . $data['country'] . "'";
			insertCustomerLog($data['customerId'], 15, $info['country'], $data['country'], $data['modify_by']);
		}
	}
	
	if(isset($data['phone'])) {
		if ($info['phone'] != $data['phone']) {
			$implode_info[] = " phone = '" . $data['phone'] . "'";
			insertCustomerLog($data['customerId'], 16, $info['phone'], $data['phone'], $data['modify_by']);
		}
	}
			
	if ($implode_info) {
		$sql_info .= " , " . implode(" , ", $implode_info);
	}
	
	$sql_info .= " WHERE customer_id='" . $data['customerId'] . "'";
	
	$query_info = mysql_query($sql_info);
	
	if( ($query) && ($query_info) )
		return true;
	return false;
}

//admin/customer_form.php + profile.php
function insertCustomerLog($customer_id, $info, $old_info, $new_info, $modify_by) {
	$sql = "INSERT INTO " . e03 . " (customer_id, edit_info, old_info, new_info, modify_by)
				VALUES('" . $customer_id . "', '" . $info . "', '" . $old_info . "', '" . $new_info . "', '" . $modify_by . "')";
	$query = mysql_query($sql);
	if($sql) 
		return true;
	return false;
}

//admin/customer.php
function deleteCustomer($customer_id) {
	$sql = "DELETE FROM " . e01 . " WHERE  id = '" . $customer_id . "'";
	$sql_info = "DELETE FROM " . e02 . " WHERE  customer_id = '" . $customer_id . "'";
	
	$query = mysql_query($sql);
	$query_info = mysql_query($sql_info);
	if(($query) && ($query_info))
		return true;
	return false;
}

//admin
function hasPermission($logged_id, $key, $value) {
	$sql = "SELECT a.id, a.group_id, a.status, b.id, b.permission FROM " . e50 . " a
				LEFT JOIN " . e52 . " b ON (a.group_id = b.id) WHERE a.id = '" . (int)$logged_id . "' AND a.status = '1'";
	$user_query = mysql_query($sql);
	
	if (mysql_num_rows($user_query) > 0) {
		$info = mysql_fetch_array($user_query);
		$permissions = unserialize($info['permission']);
		if (is_array($permissions)) {
			foreach ($permissions as $x => $y) {
				$permission[$key] = $y;
			}
		}
		
		if (isset($permission[$key])) {
			return in_array($value, $permission[$key]);
		} else {
			return false;
		}
	} else {
		return false;
	}
}

//admin/user.php
function verifyDuplicateUser($data, $column) {
	$update_data = str_replace(' ', '', $data);
	$sql = mysql_query("SELECT * FROM " . e50 . " WHERE " . $column . " = '" . $update_data . "'")or die("Unable to run query:".mysql_error());
	$num = mysql_num_rows($sql);

	if ($num > 0)
		return true;
	return false;	
}

//admin/login.php
function verifyLoginAdmin($data = array()) { 
	$sql = "SELECT id FROM " . e50 . " WHERE username = '" . $data['username'] . "' AND password = '" . $data['password'] . "' AND status = '" . $data['status'] . "' LIMIT 1";
	$query = mysql_query($sql);
	$num = mysql_num_rows($query);
	
	if ($num > 0)
		return true;
	return false;
}

//admin/login.php
function getUserByUsername($data = array()) {
	$records = array();
	$sql = "SELECT id FROM " . e50 . " WHERE username = '" . $data['username'] . "'";
	$query = mysql_query($sql);
	
	$rows = mysql_fetch_array($query);
	$records["user_id"] = $rows["id"];

	return $records;
}

//admin/user_group.php
function getTotalGroups() {
	$sql = "SELECT COUNT(*) AS total FROM " . e52;
	$query = mysql_query($sql);
	
	$rows = mysql_fetch_array($query);
	return $rows["total"];
}

//admin/user_group.php
function getGroups($data = array()) {
	$records = array();
	$sql = "SELECT id, name, permission FROM " . e52;
	
	$sort_data = array(
		'name',
	);	
	
	if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		$sql .= " ORDER BY " . $data['sort'];	
	} else {
		$sql .= " ORDER BY name";	
	}
	
	if (isset($data['order']) && ($data['order'] == 'DESC')) {
		$sql .= " DESC";
	} else {
		$sql .= " ASC";
	}
	
	if (isset($data['start']) || isset($data['limit'])) {
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}	
		
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	
	$query = mysql_query($sql);
	
	$c=0;
	while ($rows = mysql_fetch_array($query)) {
		$records[$c]["user_group_id"] = $rows["id"];
		$records[$c]["name"] = $rows["name"];
		$c++;
	}

	return $records;
}

//admin/user_group_form.php
function getGroupById($group_id) {
	$records = array();
	$sql = "SELECT id, name, permission FROM " . e52 . " WHERE id = '" . (int)$group_id . "'";
	$query = mysql_query($sql);
	
	$rows = mysql_fetch_array($query);
	$records["user_group_id"] = $rows["id"];
	$records["name"] = $rows["name"];
	$records["permission"] = unserialize($rows['permission']);

	return $records;
}

//admin/user_group_form.php
function insertGroup($data) {
	$sql = "INSERT INTO " . e52 . " (name, permission)
				VALUES('" . $data['name'] . "', '" . (isset($data['permission']) ? serialize($data['permission']) : '') . "')";
	$query = mysql_query($sql);
	if($query)
		return true;
	return false;
}

//admin/user_group.php
function deleteGroup($group_id) {
	$sql = "DELETE FROM " . e52 . " WHERE  id = '" . $group_id . "'";
	
	$query = mysql_query($sql);
	if($query)
		return true;
	return false;
}

//admin/user_group_form.php
function editGroup($data) {
	$group_info = getGroupById($data['groupId']);

	$sql = "UPDATE " . e52 ;
	
	$implode = array();
	
	if ($group_info['name'] != $data['name']) {
		$implode[] = " name = '" . $data['name'] . "'";
	}
	
	$implode[] = " permission = '" . serialize($data['permission']) . "'";
		
	if ($implode) {
		$sql .= " SET " . implode(" , ", $implode);
	}
	
	$sql .= " WHERE id='" . $data['groupId'] . "'";
	
	$query = mysql_query($sql);
	if($query)
		return true;
	return false;
}

//admin/user.php
function getTotalUsers() {
	$sql = "SELECT COUNT(*) AS total FROM " . e50;
	$query = mysql_query($sql);
	
	$rows = mysql_fetch_array($query);
	return $rows["total"];
}

//admin/user.php
function getUsers($data = array()) {
	$records = array();
	$sql = "SELECT a.id AS ID, a.username, a.status, a.group_id, a.date_added, b.id, b.name FROM " . e50 . " a LEFT JOIN " . e52 . " b ON a.group_id = b.id ";
	
	$sort_data = array(
		'a.username',
	);	
	
	if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		$sql .= " ORDER BY " . $data['sort'];	
	} else {
		$sql .= " ORDER BY a.username";	
	}
	
	if (isset($data['order']) && ($data['order'] == 'DESC')) {
		$sql .= " DESC";
	} else {
		$sql .= " ASC";
	}
	
	if (isset($data['start']) || isset($data['limit'])) {
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}	
		
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	
	$query = mysql_query($sql);
	
	$c=0;
	while ($rows = mysql_fetch_array($query)) {
		$records[$c]["user_id"] = $rows["ID"];
		$records[$c]["username"] = $rows["username"];
		$records[$c]["status"] = $rows["status"];
		$records[$c]["date_added"] = $rows["date_added"];
		$records[$c]["group_name"] = $rows["name"];
		$c++;
	}

	return $records;
}

//admin/user_form.php
function getUserById($user_id) {
	$records = array();
	$sql = "SELECT id, username, password, email, group_id, status FROM " . e50 . " WHERE id = '" . (int)$user_id . "'";
	$query = mysql_query($sql);
	
	$rows = mysql_fetch_array($query);
	$records["username"] = $rows["username"];
	$records["password"] = $rows["password"];
	$records["email"] = $rows["email"];
	$records["group_id"] = $rows['group_id'];
	$records["status"] = $rows['status'];

	return $records;
}

//admin/user_form.php
function insertUser($data) {
	$sql = "INSERT INTO " . e50 . " (group_id, username, password, email, ip, status, creator_id, date_added)
				VALUES('" . $data['user_group'] . "', '" . $data['username'] . "', '" . $data['password'] . "', '" . $data['email'] . "', '" . $_SERVER['REMOTE_ADDR'] . "', '" . $data['status'] . "', '" . $data['creator'] . "', now())";
	$query = mysql_query($sql);
	if($query)
		return true;
	return false;
}

//admin/user_form.php
function editUser($data) {
	$user_info = getUserById($data['userId']);

	$sql = "UPDATE " . e50 ;
	
	$implode = array();
	
	if(isset($data['username'])) {
		if ($user_info['username'] != $data['username']) {
			$implode[] = " username = '" . $data['username'] . "'";
			insertUserLog($data['userId'], 1, $user_info['username'], $data['username']);
		}
	}
	
	if(isset($data['email'])) {
		if ($user_info['email'] != $data['email']) {
			$implode[] = " email = '" . $data['email'] . "'";
			insertUserLog($data['userId'], 2, $user_info['email'], $data['email']);
		}
	}
	
	if(isset($data['password'])) {
		if ($user_info['password'] != $data['password']) {
			$implode[] = " password = '" . $data['password'] . "'";
			insertUserLog($data['userId'], 3, $user_info['password'], $data['password']);
		}
	}
	
	if(isset($data['group_id'])) {
		if ($user_info['group_id'] != $data['user_group']) {
			$implode[] = " group_id = '" . $data['user_group'] . "'";
			insertUserLog($data['userId'], 4, $user_info['group_id'], $data['user_group']);
		}
	}
	
	if(isset($data['status'])) {
		if ($user_info['status'] != $data['status']) {
			$implode[] = " status = '" . $data['status'] . "'";
			insertUserLog($data['userId'], 5, $user_info['status'], $data['status']);
		}
	}
			
	if ($implode) {
		$sql .= " SET " . implode(" , ", $implode);
	}
	
	$sql .= " WHERE id='" . $data['userId'] . "'";
	
	$query = mysql_query($sql);
	if($query)
		return true;
	return false;
}

//admin/user_form.php
function insertUserLog($user_id, $info, $old_info, $new_info) {
	$sql = "INSERT INTO " . e51 . " (user_id, edit_info, old_info, new_info, modify_by)
				VALUES('" . $user_id . "', '" . $info . "', '" . $old_info . "', '" . $new_info . "', '" . $_SESSION['coin_user_id'] . "')";
	$query = mysql_query($sql);
	if($sql) {
		return true;
	} else {
		return false;
	}
}

//admin/user.php
function deleteUser($user_id) {
	$sql = "DELETE FROM " . e50 . " WHERE  id = '" . $user_id . "'";
	
	$query = mysql_query($sql);
	if($query)
		return true;
	return false;
}

//admin/category.php
function getTotalCategory() {
	$query = mysql_query("SELECT COUNT(id) AS total FROM " . e06);
	
	$rows = mysql_fetch_array($query);
	return $rows["total"];
}

//admin/category.php
function getCategory($data) {
	$records = array();
	$sql = "SELECT id, name FROM " . e06 . " ORDER BY id ASC ";
	
	if (isset($data['start']) || isset($data['limit'])) {
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}	
		
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	
	$query = mysql_query($sql);
	
	$c=0;
	while($rows = mysql_fetch_array($query)) {
		$records[$c]["category_id"] = $rows["id"];
		$records[$c]["category_name"] = $rows["name"];
		$c++;
	}

	return $records;
}

//admin/category_form.php
function getCategoryById($category_id) {
	$records = array();
	$query = mysql_query("SELECT id, name FROM " . e06 . " a WHERE a.id='" . $category_id . "'");
	$rows = mysql_fetch_array($query);

	$records["category_id"] = $rows['id'];
	$records["category_name"] = $rows["name"];
	
	return $records;
}

//admin/category_form.php
function insertCategory($data) {
	$sql = mysql_query("INSERT INTO " . e06 . " (name)
				VALUES('" . $data['name'] . "')");
					
	if($sql)
		return true;
	return false;
}

function deleteCategory($category_id) {
	$sql = mysql_query("DELETE FROM " . e06 . " WHERE  id = '" . $category_id. "' ");
	
	if($sql)
		return true;
	return false;
}

//admin/category_form.php
function editCategory($data) {
	$sql = "UPDATE " . e06 . " SET name='" . $data['name'] . "' WHERE id='" . $data['categoryId'] . "'" ;
	
	$query = mysql_query($sql);
	
	if($query)
		return true;
	return false;
}

//admin/product_update.php
function insertProduct($data) {	
	$sql = "INSERT INTO " . e07 . " (brand, model, market_price, auction_price, category_id, availability, time_start, time_end,
							description, total_bid, date_added)
				VALUES('" . $data['brand'] . "', '" .$data['model']. "', '" . $data['mprice'] . "', '" . $data['aprice'] . "', '" . $data['category'] . "',
						'" . $data['availability'] . "', '" . $data['datestart'] . "', '" . $data['dateend'] . "', '" . $data['description'] . "', '" . $data['bids'] . "', now())";	
						
	$query = mysql_query($sql);	

	if($query)
		return true;
	return false;
}

//admin/product.php
function getTotalProducts() {
	$query = mysql_query("SELECT COUNT(id) AS total FROM " . e07);
	
	$rows = mysql_fetch_array($query);
	return $rows["total"];
}

//admin/product.php + home.php
function getProducts($data) {
	$records = array();
	$sql = "SELECT id, brand, model, auction_price, availability, total_bid, time_end FROM " . e07 ;
	
	$sort_data = array(
		'id',
	);	
	
	if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		$sql .= " ORDER BY " . $data['sort'];	
	} else {
		$sql .= " ORDER BY id";	
	}
	
	if (isset($data['order']) && ($data['order'] == 'DESC')) {
		$sql .= " DESC";
	} else {
		$sql .= " ASC";
	}
	
	if (isset($data['start']) || isset($data['limit'])) {
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}	
		
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	
	$query = mysql_query($sql);
	
	$c=0;
	while($rows = mysql_fetch_array($query)) {
		$records[$c]["product_id"] = $rows["id"];
		$records[$c]["brand"] = $rows["brand"];
		$records[$c]["model"] = $rows["model"];
		$records[$c]["aprice"] = $rows["auction_price"];
		$records[$c]["availability"] = $rows["availability"];
		$records[$c]["bids"] = $rows["total_bid"];
		$records[$c]["dateend"] = $rows["time_end"];
		$c++;
	}

	return $records;
}

//admin/product_form.php + bid_function.php
function getProductById($product_id) {
	$records = array();
	$query = mysql_query("SELECT id, brand, model, market_price, auction_price, category_id, availability, time_start, time_end, description, total_bid FROM " . e07 . " a WHERE a.id='" . $product_id . "'");
	$rows = mysql_fetch_array($query);

	$records["brand"] = $rows["brand"];
	$records["model"] = $rows["model"];
	$records["mprice"] = $rows["market_price"];
	$records["aprice"] = $rows["auction_price"];
	$records["category"] = $rows["category_id"];
	$records["availability"] = $rows["availability"];
	$records["datestart"] = $rows["time_start"];
	$records["dateend"] = $rows["time_end"];
	$records["description"] = $rows["description"];
	$records["bids"] = $rows["total_bid"];
	
	return $records;
}

//admin/product.php + admin.product_image.php + home.php
function getProductImage($data) {
	$records = array();
	$sql = "SELECT id, product_id, image FROM " . e08 . " a WHERE a.product_id='" . $data['product_id'] . "'";
	
	if (isset($data['start']) || isset($data['limit'])) {
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}	
		
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	
	$query = mysql_query($sql);

	$c=0;
	while($rows = mysql_fetch_array($query)) {
		$records[$c]["image_id"] = $rows["id"];
		$records[$c]["image"] = $rows["image"];
		$c++;
	}
	
	return $records;
}

//admin/product_image.php
function updateProductImage($data) {
	$sql = mysql_query("DELETE FROM " . e08 . " WHERE product_id = '" . $data['product_id'] . "'")or die("Unable to run query:".mysql_error());
	
	if(isset($data['image'])) {
		foreach ($data['image'] as $image) {
			$sql_insert = mysql_query("INSERT INTO " . e08 . " (product_id, image)
					VALUES('" . $data['product_id'] . "', '" . $image . "')");
		}
	} else {
		$sql_insert = true;
	}
	
	if(($sql) && ($sql_insert)) {
		return true;
	} else {
		return false;
	}
}

//admin/product.php
function deleteProduct($product_id) {
	$sql = mysql_query("DELETE FROM " . e07 . " WHERE  id = '" . $product_id. "' ");
	
	if($sql)
		return true;
	return false;
}

//admin/product_update.php + bid_function.php
function editProduct($data) {
	$product_info = getProductById($data['productId']);

	$sql = "UPDATE " . e07 ;
	
	$implode = array();
	
	if(isset($data['brand'])) {
		if ($product_info['brand'] != $data['brand']) {
			$implode[] = " brand = '" . $data['brand'] . "'";
			insertProductLog($data['productId'], 1, $product_info['brand'], $data['brand'], $data['modify_by']);
		}
	}
	
	if(isset($data['model'])) {
		if ($product_info['model'] != $data['model']) {
			$implode[] = " model = '" . $data['model'] . "'";
			insertProductLog($data['productId'], 2, $product_info['model'], $data['model'], $data['modify_by']);
		}
	}
	
	if(isset($data['mprice'])) {
		if ($product_info['mprice'] != $data['mprice']) {
			$implode[] = " market_price = '" . $data['mprice'] . "'";
			insertProductLog($data['productId'], 3, $product_info['mprice'], $data['mprice'], $data['modify_by']);
		}
	}
	
	if(isset($data['aprice'])) {
		if ($product_info['aprice'] != $data['aprice']) {
			$implode[] = " auction_price = '" . $data['aprice'] . "'";
			insertProductLog($data['productId'], 4, $product_info['aprice'], $data['aprice'], $data['modify_by']);
		}
	}
	
	if(isset($data['category'])) {
		if ($product_info['category'] != $data['category']) {
			$implode[] = " category_id = '" . $data['category'] . "'";
			insertProductLog($data['productId'], 5, $product_info['category'], $data['category'], $data['modify_by']);
		}
	}
	
	if(isset($data['availability'])) {
		if ($product_info['availability'] != $data['availability']) {
			$implode[] = " availability = '" . $data['availability'] . "'";
			insertProductLog($data['productId'], 6, $product_info['availability'], $data['availability'], $data['modify_by']);
		}
	}
	
	if(isset($data['datestart'])) {
		if ($product_info['datestart'] != $data['datestart']) {
			$implode[] = " time_start = '" . $data['datestart'] . "'";
			insertProductLog($data['productId'], 7, $product_info['datestart'], $data['datestart'], $data['modify_by']);
		}
	}
	
	if(isset($data['dateend'])) {
		if ($product_info['dateend'] != $data['dateend']) {
			$implode[] = " time_end = '" . $data['dateend'] . "'";
			insertProductLog($data['productId'], 8, $product_info['dateend'], $data['dateend'], $data['modify_by']);
		}
	}
	
	if(isset($data['description'])) {
		if ($product_info['description'] != $data['description']) {
			$implode[] = " description = '" . $data['description'] . "'";
			insertProductLog($data['productId'], 9, $product_info['description'], $data['description'], $data['modify_by']);
		}
	}
	
	if(isset($data['bids'])) {
		if ($product_info['bids'] != $data['bids']) {
			$implode[] = " total_bid = '" . $data['bids'] . "'";
			insertProductLog($data['productId'], 10, $product_info['bids'], $data['bids'], $data['modify_by']);
		}
	}
			
	if ($implode) {
		$sql .= " SET " . implode(" , ", $implode);
	}
	
	$sql .= " WHERE id='" . $data['productId'] . "'";
	
	$query = mysql_query($sql);
	if($query)
		return true;
	return false;
}

//admin/product_update.php
function insertProductLog($product_id, $info, $old_info, $new_info, $modify_by) {
	$sql = "INSERT INTO " . e09 . " (product_id, edit_info, old_info, new_info, modify_by)
				VALUES('" . $product_id . "', '" . $info . "', '" . $old_info . "', '" . $new_info . "', '" . $modify_by . "')";
	$query = mysql_query($sql);
	if($query)
		return true;
	return false;
}

//admin/social.php + information.php + information_form.php + sql_function.php + security + careers + privacy + terms
function getSettings($data) {
	$records = array();
	$sql = "SELECT id, keyword, value FROM " . e10 . " WHERE grouping = '" . $data['group'] . "'";
	
	if(isset($data['Key'])) {
		$sql .= " AND keyword = '" . $data['Key'] . "'";
	}
	$query = mysql_query($sql);

	$c=0;
	while($rows = mysql_fetch_array($query)) {
		$records[$c]["information_id"] = $rows["id"];
		$records[$c]["key"] = $rows["keyword"];
		$records[$c]["value"] = $rows["value"];
		$c++;
	}
	
	return $records;
}

//admin/information_form.php
function getSettingsById($information_id) {
	$records = array();
	$sql = "SELECT id, keyword FROM " . e10 . " WHERE id = '" . $information_id . "'";
	
	$query = mysql_query($sql);
	$rows = mysql_fetch_array($query);
	$records["key"] = $rows["keyword"];
	
	return $records;
}

//admin/social.php
function insertSettings($data) {
	$sql = "INSERT INTO " . e10 . " (grouping, keyword, value) VALUES ('" . $data['group'] . "', '" . $data['Key'] . "', '" . $data['Value'] . "')";
	$query = mysql_query($sql);
	if($query)
		return true;
	return false;
}

//admin/social.php
function editSettings($data) {
	$sql = "UPDATE " . e10 . " SET value = '" . $data['Value'] . "' WHERE keyword='" . $data['Key'] . "'" ;
	
	$query = mysql_query($sql);
	
	if($query)
		return true;
	return false;
}

//admin/social.php + update.php
function updateSettings($datas) {
	foreach($datas as $data) {
		$data_setting = array(
			'group'	=> $data['group'],
			'Key'	=> $data['Key'],
			'Value'	=> $data['Value']
		);
		$settings = getSettings($data_setting);
				
		if(count($settings) > 0) {
			$query = editSettings($data_setting);
		} else {
			$query = insertSettings($data_setting);
		}
	}
	
	if($query)
		return true;
	return false;
}

//bid_function.php
function checkBiddingLog($product_id) {
	$sql = "SELECT COUNT(id) AS total FROM " . e11 . " WHERE product_id = '" . $product_id . "' AND auction_price = '0.00'"  ;
	$query = mysql_query($sql);
	$rows = mysql_fetch_array($query);

	if($rows['total'] > 1) 
		return true;
	return false;
}

//bid_function.php
function insertBiddingLog($data) {
	$sql = "INSERT INTO " . e11 . " (product_id, customer_id, auction_price) VALUES ('" . $data['productId'] . "', '" . $data['customerId'] . "', '" . $data['aprice'] . "')";
	$query = mysql_query($sql);
	if($query)
		return true;
	return false;
}

//product.php + product_list.php
function getHighestBidder($data) {
	$records = array();
	$sql = "SELECT a.product_id, a.customer_id, a.date_added, b.username FROM " . e11 . " a LEFT JOIN " . e01 . " b ON a.customer_id = b.id WHERE product_id='" . $data['productId'] . "' ORDER BY a.date_added DESC";
	
	if (isset($data['start']) || isset($data['limit'])) {
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}	
		
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	
	$query = mysql_query($sql);

	$c=0;
	while($rows = mysql_fetch_array($query)) {
		$records[$c]["username"] = $rows["username"];
		$records[$c]["date_added"] = $rows["date_added"];
		$c++;
	}
	
	return $records;
}

//admin/faq.php
function getTotalFAQ() {
	$query = mysql_query("SELECT COUNT(id) AS total FROM " . e12);
	
	$rows = mysql_fetch_array($query);
	return $rows["total"];
}

//admin/faq.php + controller/faq.php
function getFAQs($data) {
	$records = array();
	$sql = "SELECT id, question, answer FROM " . e12 . " ORDER BY id ASC ";
	
	if (isset($data['start']) || isset($data['limit'])) {
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}	
		
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	
	$query = mysql_query($sql);
	
	$c=0;
	while($rows = mysql_fetch_array($query)) {
		$records[$c]["faq_id"] = $rows["id"];
		$records[$c]["question"] = $rows["question"];
		$records[$c]["answer"] = $rows["answer"];
		$c++;
	}

	return $records;
}

//admin/faq_form.php
function getFAQById($faq_id) {
	$records = array();
	$query = mysql_query("SELECT id, question, answer FROM " . e12 . " a WHERE a.id='" . $faq_id . "'");
	$rows = mysql_fetch_array($query);

	$records["faq_id"] = $rows['id'];
	$records["question"] = $rows["question"];
	$records["answer"] = $rows["answer"];
	
	return $records;
}

//admin/faq_form.php
function insertFAQ($data) {
	$sql = mysql_query("INSERT INTO " . e12 . " (question, answer)
				VALUES('" . $data['question'] . "', '" . $data['description'] . "')");
					
	if($sql)
		return true;
	return false;
}

//admin/faq.php
function deleteFAQ($faq_id) {
	$sql = mysql_query("DELETE FROM " . e12 . " WHERE  id = '" . $faq_id. "' ");
	
	if($sql)
		return true;
	return false;
}

//admin/faq_form.php
function editFAQ($data) {
	$sql = "UPDATE " . e12 . " SET question='" . $data['question'] . "', answer='" . $data['description'] . "' WHERE id='" . $data['faqId'] . "'" ;
	
	$query = mysql_query($sql);
	
	if($query)
		return true;
	return false;
}

//admin/graphics_form.php
function insertGraphics($data) {
	if(isset($data['image'])) {
		foreach ($data['image'] as $image) {
			$sql = "INSERT INTO " . e13 . " (name, image) VALUES('" . $data['name'] . "', '" . $image . "')";
		}
	} else {
		$sql_insert = "INSERT INTO " . e13 . " (name) VALUES('" . $data['name'] . "')";
	}
	$query = mysql_query($sql);
	if($sql) 
		return true;
	return false;
}

//admin/graphics.php
function getTotalGraphics() {
	$query = mysql_query("SELECT COUNT(id) AS total FROM " . e13);
	
	$rows = mysql_fetch_array($query);
	return $rows["total"];
}

//admin/graphics.php
function getGraphics($data) {
	$records = array();
	$sql = "SELECT id, name, image FROM " . e13 . " ORDER BY id ASC ";
	
	if (isset($data['start']) || isset($data['limit'])) {
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}	
		
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	
	$query = mysql_query($sql);
	
	$c=0;
	while($rows = mysql_fetch_array($query)) {
		$records[$c]["graphics_id"] = $rows["id"];
		$records[$c]["name"] = $rows["name"];
		$records[$c]["image"] = $rows["image"];
		$c++;
	}

	return $records;
}

//admin/graphics.php
function deleteGraphics($graphics_id) {
	$sql = mysql_query("DELETE FROM " . e13 . " WHERE  id = '" . $graphics_id. "' ");
	
	if($sql)
		return true;
	return false;
}

//admin/graphics_form.php
function getGraphicsById($graphics_id) {
	$records = array();
	$query = mysql_query("SELECT id, name, image FROM " . e13 . " a WHERE a.id='" . $graphics_id . "'");
	$rows = mysql_fetch_array($query);

	$records["graphics_id"] = $rows['id'];
	$records["name"] = $rows["name"];
	$records["image"] = $rows["image"];
	
	return $records;
}

//admin/graphics_form.php
function editGraphics($data) {
	if(isset($data['image'])) {
		foreach ($data['image'] as $image) {
			$sql = "UPDATE " . e13 . " SET name='" . $data['name'] . "', image='" . $image . "' WHERE id='" . $data['graphicsId'] . "'" ;
		}
	} else {
		$sql = "UPDATE " . e13 . " SET name='" . $data['name'] . "' WHERE id='" . $data['graphicsId'] . "'" ;
	}

	$query = mysql_query($sql);
	
	if($query)
		return true;
	return false;
}

//admin/tokens_form.php
function insertTokens($data) {
	$sql = "INSERT INTO " . e14 . " (name, discount, paypal_code, price) VALUES('" . $data['name'] . "', '" . $data['discount'] . "', '" . $data['code'] . "', '" . $data['price'] . "')";
	$query = mysql_query($sql);
	if($sql) 
		return true;
	return false;
}

//admin/tokens.php
function getTotalToken() {
	$query = mysql_query("SELECT COUNT(id) AS total FROM " . e14);
	
	$rows = mysql_fetch_array($query);
	return $rows["total"];
}

//admin/tokens.php
function getTokens($data) {
	$records = array();
	$sql = "SELECT id, name, discount, paypal_code, price FROM " . e14 . " ORDER BY id ASC ";
	
	if (isset($data['start']) || isset($data['limit'])) {
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}	
		
		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	
	$query = mysql_query($sql);
	
	$c=0;
	while($rows = mysql_fetch_array($query)) {
		$records[$c]["token_id"] = $rows["id"];
		$records[$c]["name"] = $rows["name"];
		$records[$c]["discount"] = $rows["discount"];
		$records[$c]["code"] = $rows["paypal_code"];
		$records[$c]["price"] = $rows["price"];
		$c++;
	}

	return $records;
}

//admin/tokens.php
function deleteToken($tokens_id) {
	$sql = mysql_query("DELETE FROM " . e14 . " WHERE  id = '" . $tokens_id. "' ");
	
	if($sql)
		return true;
	return false;
}

//admin/tokens_form.php
function getTokensById($tokens_id) {
	$records = array();
	$query = mysql_query("SELECT id, name, discount, paypal_code, price FROM " . e14 . " a WHERE a.id='" . $tokens_id . "'");
	$rows = mysql_fetch_array($query);

	$records["tokens_id"] = $rows['id'];
	$records["name"] = $rows["name"];
	$records["discount"] = $rows["dicsount"];
	$records["code"] = $rows["paypal_code"];
	$records["price"] = $rows["price"];
	
	return $records;
}

//admin/tokens_form.php
function editTokens($data) {
	$sql = "UPDATE " . e14 . " SET name='" . $data['name'] . "', discount='" . $data['discount'] . "', paypal_code='" . $data['code'] . "', price='" . $data['price'] . "' WHERE id='" . $data['tokenId'] . "'" ;

	$query = mysql_query($sql);
	
	if($query)
		return true;
	return false;
}
?>