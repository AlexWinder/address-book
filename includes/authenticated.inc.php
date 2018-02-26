<?php
	
	// Check that the user is logged in to the system and is authenticated correctly
	// To be required on every page which the user has to be logged in to access
	
	/*
		$_SESSION values set which are used to verify the user
		$_SESSION["user_user_id"]
		$_SESSION["user_full_name"]
		$_SESSION["user_username"]
		$_SESSION["remote_addr"]
		$_SESSION["http_user_agent"]
	*/
	
	// Obtain user details from the database 
	
	if(isset($_SESSION["user_user_id"])) {
		$logged_in_user_id = mysql_prep($_SESSION["user_user_id"]);
		$logged_in_user = find_user_by_id($logged_in_user_id);
		
		// If there is a user
		if($logged_in_user) {
			// Check that the values in the session match the same values in the database
			if($_SESSION["user_full_name"] != $logged_in_user["full_name"]) {
				// Session full name does not match that of the one stored in the database
				// Log action
				log_action("auto_logout", "Full name collected at login (" . $_SESSION["user_full_name"] . ") does not match the full name stored in the database (" . $logged_in_user["full_name"] . ").");
				// Set all values to null and redirect
				$_SESSION["user_user_id"] = null;
				$_SESSION["user_full_name"] = null;
				$_SESSION["user_username"] = null;
				$_SESSION["remote_addr"] = null;
				$_SESSION["http_user_agent"] = null;
				$_SESSION["message"] = construct_message($notification["authenticate"]["details_changed"], "danger");
				redirect_to("login.php");
			};
			if($_SESSION["user_username"] != $logged_in_user["username"]) {
				// Session username does not match that of the one stored in the database
				// Log action
				log_action("auto_logout", "Username collected at login (" . $_SESSION["user_username"] . ") does not match the username stored in the database (" . $logged_in_user["username"] . ").");
				// Set all values to null and redirect
				$_SESSION["user_user_id"] = null;
				$_SESSION["user_full_name"] = null;
				$_SESSION["user_username"] = null;
				$_SESSION["remote_addr"] = null;
				$_SESSION["http_user_agent"] = null;
				$_SESSION["message"] = construct_message($notification["authenticate"]["details_changed"], "danger");
				redirect_to("login.php");
			};
			
			// Check that the values set at login match that of the currently accessing user
			if($_SESSION["remote_addr"] != $_SERVER["REMOTE_ADDR"]) {
				// Session ip address does not match that of the one currently being used
				// Log action
				log_action("auto_logout", "IP address collected at login (" . $_SESSION["remote_addr"] . ") does not match the current IP address of the user (" . $_SERVER["REMOTE_ADDR"] . ").");
				// Set all values to null and redirect
				$_SESSION["user_user_id"] = null;
				$_SESSION["user_full_name"] = null;
				$_SESSION["user_username"] = null;
				$_SESSION["remote_addr"] = null;
				$_SESSION["http_user_agent"] = null;
				$_SESSION["message"] = construct_message($notification["authenticate"]["ip_changed"], "danger");
				redirect_to("login.php");
			};
			if($_SESSION["http_user_agent"] != $_SERVER["HTTP_USER_AGENT"]) {
				// Session user agent does not match that of the one currently being used
				// Log action
				log_action("auto_logout", "User agent collected at login (" . $_SESSION["http_user_agent"] . ") does not match the current user agent of the user (" . $_SERVER["HTTP_USER_AGENT"] . ").");
				// Set all values to null and redirect
				$_SESSION["user_user_id"] = null;
				$_SESSION["user_full_name"] = null;
				$_SESSION["user_username"] = null;
				$_SESSION["remote_addr"] = null;
				$_SESSION["http_user_agent"] = null;
				$_SESSION["message"] = construct_message($notification["authenticate"]["agent_changed"], "danger");
				redirect_to("login.php");
			};
			
		} else {
			// User doesn't appear to exist in the database any more
			// Log action
			log_action("auto_logout", "User doesn't appear to exist in the database anymore.");
			// Set all values to null and redirect
			$_SESSION["user_user_id"] = null;
			$_SESSION["user_full_name"] = null;
			$_SESSION["user_username"] = null;
			$_SESSION["remote_addr"] = null;
			$_SESSION["http_user_agent"] = null;
			$_SESSION["message"] = construct_message($notification["authenticate"]["not_found"], "danger");
			redirect_to("login.php");
		};
		
	} else {
		// No user ID present in the session - likely caused by timeout
		// Log action
		log_action("auto_logout", "User wasn't able to provide a user ID. Likely caused by system timeout.");
		// Set all values to null and redirect
		$_SESSION["user_user_id"] = null;
		$_SESSION["user_full_name"] = null;
		$_SESSION["user_username"] = null;
		$_SESSION["remote_addr"] = null;
		$_SESSION["http_user_agent"] = null;
		$_SESSION["message"] = construct_message($notification["authenticate"]["timeout"], "danger");
		redirect_to("login.php");
	};

?>