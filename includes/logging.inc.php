<?php
	// This will contain all functions and messages which are required to insert information about user activity into the database

	// Array of log messages
	
	// Username, full name, ip address, agent, url visited
	
	// action for everything
	
	/*
		login
		logout
		view index (address book)
		view contact
		add contact
		update contact
		delete contact
		attempt to delete self
		view users
		add user
		update user name
		update full name
		update user password
		delete user
		view logs
	*/
	
	/*
		Logs table
			id
			datetime
			action - $action
			url - $site_url . $_SERVER["REQUEST_URI"]
			user - $user
			ip - $_SERVER["REMOTE_ADDR"]
			user agent - $_SERVER["HTTP_USER_AGENT"]
	*/
	
	$logging = array (
		"database" => array (
			"failure" => "There was an error making changes to the database.",
		),
		"page" => array (
			"not_exist" => "User attempted to access a page which doesn't exist.",
		),
	);
	
	function log_validation_failures($validation_array) {
		$failures = "Errors: ";
		// Format errors into one single string so that they can be sent to the database
		foreach($validation_array as $validation) {
			$failures .= $validation . " ";
		};
		return $failures;
	};
	
	function log_action($log_action, $additional_message=null) {
		// Require database variable so that logs can be added to the database
		global $db;
		
		// Require notification variable if log fails
		global $notification;
		
		// Page name is required for every entry, so can be called from the global scope
		global $page_name;
		
		// Generate current mysql datetime value
		$date_time = current_mysql_datetime();
		
		switch($log_action) {
			case "auto_logout":
				$action = "Automatic Logout";
				if(!empty($additional_message) && !is_null($additional_message)) {
					$action .= " - " . mysql_prep($additional_message);
				};
				break;
			case "not_found":
				$action = "Page Doesn\'t Exist - " . $page_name;
				if(!empty($additional_message) && !is_null($additional_message)) {
					$action .= " - " . mysql_prep($additional_message);
				};
				break;
			case "add_success":
				$action = "Entry Added - " . $page_name;
				if(!empty($additional_message) && !is_null($additional_message)) {
					$action .= " - " . mysql_prep($additional_message);
				};
				break;
			case "add_failed":
				$action = "Add Failed - " . $page_name;
				if(!empty($additional_message) && !is_null($additional_message)) {
					$action .= " - " . mysql_prep($additional_message);
				};
				break;
			case "update_success":
				$action = "Entry Updated - " . $page_name;
				if(!empty($additional_message) && !is_null($additional_message)) {
					$action .= " - " . mysql_prep($additional_message);
				};
				break;
			case "update_failed":
				$action = "Update Failed - " . $page_name;
				if(!empty($additional_message) && !is_null($additional_message)) {
					$action .= " - " . mysql_prep($additional_message);
				};
				break;
		};
		
		// Obtain the URL of the page that the user is accessing
		$url = mysql_prep($_SERVER["REQUEST_URI"]);
		
		// If the user is not logged in then there is nothing to enter into the user field
		if(!isset($_SESSION["user_full_name"]) || !isset($_SESSION["user_username"])) {
			$user = "Unknown";
		} else {
			$user = mysql_prep($_SESSION["user_full_name"]) . " [" . mysql_prep($_SESSION["user_username"]) . "]";
		};
		
		// Obtain the IP address of the user
		$ip_address = mysql_prep($_SERVER["REMOTE_ADDR"]);
		// Obtain the user agent of the user
		$user_agent = mysql_prep($_SERVER["HTTP_USER_AGENT"]);
		
		// Create SQL query to input into the database
		$sql = "INSERT INTO logs (";
		$sql .= "datetime, action, url, user, ip, user_agent";
		$sql .= ") VALUES (";
		$sql .= "'{$date_time}', '{$action}', '{$url}', '{$user}', '{$ip_address}', '{$user_agent}'";
		$sql .= ")";
		
		// Run the database query
		$result = mysqli_query($db, $sql);
		
		// Test whether the query was successful
		if($result){
			// Log successfully added to the database, do nothing
			return true;
		} else {
			//$_SESSION["message"] = construct_message($notification["log"]["add"]["failure"], "danger");
			return false;
		};
		
	};
	
	
?>