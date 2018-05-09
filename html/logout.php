<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('security_failed');
	};

	// Log user logging out
	log_action("logout", "User: " . $user->name . " [" . $user->details['username'] . "]");
	
	// Log the user out
	$user->logout();
	
?>