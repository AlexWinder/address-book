<?php

	function redirect_to($new_location) {
		// Redirect user to a specified location
		header("Location: " . $new_location);
		exit;
	};
	
	function full_name($first_name, $middle_name = null, $last_name){
		// If the person has a middle name
		if($middle_name != null ) {
			// Create their name with a middle name
			return $first_name . " " . $middle_name . " " . $last_name;
		} else {
			// Don't include the middle name
			return $first_name . " " . $last_name;
		};
	};
	
	function page_name() {
		// Bring in $page_name and $subpage_name if set
		global $page_name;
		global $subpage_name;
		
		// If both $subpage_name and $page_name are set, return both
		if(isset($page_name) && isset($subpage_name)) {
			return $subpage_name . " - " . $page_name;
		} else { // Otherwise just return the $page_name
			return $page_name;
		};
	}

	function cosmetic_date_from_mysqldate($mysql_date) {
		// Set correct time zone
		date_default_timezone_set("Europe/London");
		
		// Convert MySQL date to a UNIX time stamp
		$unix_date = strtotime($mysql_date);
		
		// Format date into correct string, example: Saturday 1st May 1993
		$cosmetic_date = date('jS F Y', $unix_date);
		return $cosmetic_date;
	};
	
	function current_mysql_datetime() {
		// Set correct time zone
		date_default_timezone_set("Europe/London");
		
		$mysql_datetime = date('Y-m-d H:i:s', time());
		return $mysql_datetime;
	}
	
	function generate_key($key_length) {
        $idkey = NULL;
        $salt = "abcdefghjkmnpqrstuvxyzABCDEFGHIJKLMNOPQRSTUVXYZ0123456789";
        srand((double)microtime()*1000000);
        $i = 0;
        while ($i <= $key_length)
        {
            $num = rand() % strlen($salt);
            $tmp = substr($salt, $num, 1);
            $idkey = $idkey . $tmp;
            $i++;
        }
        return $idkey;
    };
	
	function attempt_login($username, $password) {
		// Check if the username exists in the database
		$user = find_user_by_username($username);
		if ($user) {
			// User exists, now check password
			if (password_check($password, $user["hashed_password"])) {
				// Password matches that of the one stored in the database
				return $user;
			} else {
				// Password does not match
				return false;
			};
			
		} else {
			// User does not exist in the database
			return false;
		};
	};
	
	function password_check($password, $existing_hash) {
		// Existing hash contains format and salt at start
		$hash = crypt($password, $existing_hash);
		if ($hash === $existing_hash) {
			return true;
		} else {
			return false;
		};
	};
	
	function password_encrypt($password) {
		// Tells PHP to use Blowfish with a "cost" of 10
		$hash_format = "$2y$10$";
		// Blowfish salts should be 22-characters or more
		$salt_length = 22;
		$salt = generate_salt($salt_length);
		$format_and_salt = $hash_format . $salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;
	};
	
	function generate_salt($length) {
		// Not 100% unique, not 100% random, but good enough for a salt
		// MD5 returns 32 characters
		$unique_random_string = md5(uniqid(mt_rand(), true));
	  
		// Valid characters for a salt are [a-zA-Z0-9./]
		$base64_string = base64_encode($unique_random_string);
	  
		// But not '+' which is valid in base64 encoding
		$modified_base64_string = str_replace('+', '.', $base64_string);
	  
		// Truncate string to the correct length
		$salt = substr($modified_base64_string, 0, $length);
	  
		return $salt;
	};
	
	// MySQL Functions
	function confirm_query($result_set, $last_query){
		if(!$result_set) { 
			die("Database Query Failed. Last Query: " . $last_query);
		}
	};
	
	function mysql_prep($string) {
		global $db;
		
		$strip_slashes = stripslashes($string);
		$escaped_string = mysqli_real_escape_string($db, $strip_slashes);
		return $escaped_string;
	};
	
	// Find from database
	
	// Users
	function find_all_users() {
		global $db;
		
		$sql = "SELECT * ";
		$sql .= "FROM users";
		
		$result_set = mysqli_query($db, $sql);
		confirm_query($result_set, $sql);
		return $result_set;
	};
	
	function find_user_by_id($id) {
		// Find a user based on their user_id in the database
		global $db;
		
		$sql = "SELECT * "; 
		$sql .= "FROM users "; 
		$sql .= "WHERE BINARY user_id = '{$id}' "; 
		$sql .= "LIMIT 1"; 
		
		$result_set = mysqli_query($db, $sql); 
		confirm_query($result_set, $sql); 
		
		if($user = mysqli_fetch_assoc($result_set)){
			return $user;
		} else {
			return null; 
		};
	};
	
	function find_user_by_username($username) {
		// Find a user based on their username in the database
		global $db;
		
		$sql = "SELECT * "; 
		$sql .= "FROM users "; 
		$sql .= "WHERE BINARY username = '{$username}' ";
		$sql .= "LIMIT 1";
		
		$result_set = mysqli_query($db, $sql); 
		confirm_query($result_set, $sql); 
		
		if($user = mysqli_fetch_assoc($result_set)){
			return $user;
		} else {
			return null; 
		};
	};
	// End of users
	
	// Logs
	function find_columns_from_logs(){
		global $db;
	
		$sql = "SELECT datetime, action, user, ip ";
		$sql .= "FROM logs";
		
		$result_set = mysqli_query($db, $sql);
		confirm_query($result_set, $sql);
		return $result_set;
	};
	// End of logs


?>