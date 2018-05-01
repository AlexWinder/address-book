<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Check that the user is logged in
	require_once("../includes/authenticated.inc.php");

	// Log user logging out
	log_action("logout", "User: " . $_SESSION["user_full_name"] . " [" . $_SESSION["user_username"] . "]");
	
	// Remove all values from the $_SESSION
	$_SESSION = array();
	// Set the $_SESSION cookie value to be deleted
	setcookie(session_name(), '', time() - 2592000, '/');
	// Destroy all session data
	session_destroy();
	
	// Redirect user to the login.php page
	redirect_to("login.php");
	
	// Close the database connection
	mysqli_close($db);
?>