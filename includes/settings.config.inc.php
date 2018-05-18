<?php
	
	// Check if a custom settings file has been created with all the relevant constants
	if(!file_exists("../includes/settings.local.inc.php")) {
		// Output to screen that the file is missing and go no further
		echo 'A config file could not be found. Please create a file inside the includes/ directory called settings.local.inc.php';
		die();
	};
	
	// Require the localsetting.inc.php
	require_once('settings.local.inc.php');
	
	// Check that the required settings for the system to function have been defined
	// Initialise an $errors array to store any errors
	$errors = array();
	
	// Check that constants are defined
	if(!defined('DB_SERVER')) 	{ $errors[] = "DB_SERVER is not defined. Please add the following as a new line to your includes/settings.local.inc.php file: <b>define('DB_SERVER', 'YOUR DATABASE IP/HOSTNAME');</b>"; };
	if(!defined('DB_USER')) 	{ $errors[] = "DB_USER is not defined. Please add the following as a new line to your includes/settings.local.inc.php file: <b>define('DB_USER', 'YOUR DATABASE USERNAME');</b>"; };
	if(!defined('DB_PASS')) 	{ $errors[] = "DB_PASS is not defined. Please add the following as a new line to your includes/settings.local.inc.php file: <b>define('DB_PASS', 'YOUR DATABASE USER PASSWORD');</b>"; };
	if(!defined('DB_NAME')) 	{ $errors[] = "DB_NAME is not defined. Please add the following as a new line to your includes/settings.local.inc.php file: <b>define('DB_NAME', 'YOUR DATABASE NAME');</b>"; };
	
	// Output the errors to screen if any are present
	if(!empty($errors)) {
		echo '<p>There appear to be some issues with your configuration. Please review the following errors:</p>';
		echo '<ul>';
		foreach($errors as $error) {
			echo '- '. $error . '<br>';
		};
		echo '</ul>';
		echo '<p>Please remember to include all of the example line but replacing the key information with that which relates to your system. Also ensure that the settings.local.inc.php file starts with the first line with <b>' . htmlspecialchars("<?php ") . '</b>. Without this then any setting you set won\'t work!</p>';
		die();
	}; // Close if(!empty($errors))
	
	// Set the database driver to MySQL
	define("DB_TYPE", "mysql");

	// Set page names
	defined("PAGENAME_INDEX")						?	null	:	define("PAGENAME_INDEX", "Address Book");
	defined("PAGENAME_LOGIN")						?	null	:	define("PAGENAME_LOGIN", "Log In");
	defined("PAGENAME_LOGOUT")						?	null	:	define("PAGENAME_LOGOUT", "Log Out");
	defined("PAGENAME_USERS")						?	null	:	define("PAGENAME_USERS", "Users");
	defined("PAGENAME_USERSADD")					?	null	:	define("PAGENAME_USERSADD", "Add User");
	defined("PAGENAME_USERSDELETE")					?	null	:	define("PAGENAME_USERSDELETE", "Delete User");
	defined("PAGENAME_USERSUPDATE")					?	null	:	define("PAGENAME_USERSUPDATE", "Update User");
	defined("PAGENAME_LOGS")						?	null	:	define("PAGENAME_LOGS", "Logs");
	defined("PAGENAME_CONTACTS")					?	null	:	define("PAGENAME_CONTACTS", "Contacts");
	defined("PAGENAME_CONTACTSADD")					?	null	:	define("PAGENAME_CONTACTSADD", "Add Contact");
	defined("PAGENAME_CONTACTSDELETE")				?	null	:	define("PAGENAME_CONTACTSDELETE", "Delete Contact");
	defined("PAGENAME_CONTACTSUPDATE")				?	null	:	define("PAGENAME_CONTACTSUPDATE", "Update Contact");
	defined("PAGENAME_CONTACTSVIEW")				?	null	:	define("PAGENAME_CONTACTSVIEW", "View Contact");
	
	// Set page links
	defined("PAGELINK_INDEX")						?	null	:	define("PAGELINK_INDEX", "index.php");
	defined("PAGELINK_LOGIN")						?	null	:	define("PAGELINK_LOGIN", "login.php");
	defined("PAGELINK_LOGOUT")						?	null	:	define("PAGELINK_LOGOUT", "logout.php");
	defined("PAGELINK_USERS")						?	null	:	define("PAGELINK_USERS", "users.php");
	defined("PAGELINK_LOGS")						?	null	:	define("PAGELINK_LOGS", "logs.php");

	// Server time zone
	date_default_timezone_set("Europe/London");
	
	// Autoload classes so that they are called as and when they are required
	spl_autoload_register(function($class_name) { 
		$class_name = strtolower($class_name);
		include('class.' . $class_name . '.inc.php');
	});
	
	// Begin running the Session as items in constructor are required for the system to function correctly
	$session = new Session();
	
	// Begin a new User instance as will automatically check details of the user if they are logged in etc
	$user = new User();
	
	// Database connection
	require_once("database.inc.php");
	
	// Site functions
	require_once("functions.inc.php");
	
	// Notifications, for things such as error messages and success alerts
	require_once("alerts.notification.inc.php");
	
	// Validation messages for form fields, such as string lengths too long, or required fields missing
	require_once("alerts.validation.inc.php");
	
	// Logging user activity - functions and log text
	require_once("logging.inc.php");

?>