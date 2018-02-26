<?php
	
	// Display errors
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	// Server time zone
	date_default_timezone_set("Europe/London");
	
	// Database constants
	defined("DB_SERVER")    	?	null	:	define("DB_SERVER", "127.0.0.1");
	defined("DB_USER")			?	null	:	define("DB_USER", "root");
	defined("DB_PASS")			?	null	:	define("DB_PASS", "");
	defined("DB_NAME")			?	null	:	define("DB_NAME", "address_book");
	
	// Database connection
	require_once("database.inc.php");
	
	// Session information and functions
	require_once("session.inc.php");
	
	// Site functions
	require_once("functions.inc.php");
	
	// Notifications, for things such as error messages and success alerts
	require_once("notification.inc.php");
	
	// Validation messages for form fields, such as string lengths too long, or required fields missing
	require_once("validation.inc.php");
	
	// Logging user activity - functions and log text
	require_once("logging.inc.php");
	

?>