<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_LOGOUT;
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	};

	// Create new Log instance, and log the action to the database
	$log = new Log('logout_success');
	
	// Log the user out
	$user->logout();
	
?>