<?php
	
	// Check if a custom settings file has been created with all the relevant constants
	if(!file_exists("../includes/settings.local.inc.php")) {
		// Output to screen that the file is missing and go no further
		echo 'A config file could not be found. Please create a file inside the includes/ directory called "settings.local.inc.php".';
		echo '<br>';
		echo 'For an example file simply create a copy of the "EXAMPLE.settings.local.inc.php" and rename it to "settings.local.inc.php", you can then input the details relating to your set up.';
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
	if(!defined('SITE_URL')) 	{ $errors[] = "SITE_URL is not defined. Please add the following as a new line to your includes/settings.local.inc.php file: <b>define('SITE_URL', 'YOUR SITE URL');</b>"; };
	if(!defined('TIMEZONE')) 	{ $errors[] = "TIMEZONE is not defined. Please add the following as a new line to your includes/settings.local.inc.php file: <b>define('TIMEZONE', 'YOUR TIMEZONE');</b>"; };

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
	defined("PAGENAME_SETTINGS")					?	null	:	define("PAGENAME_SETTINGS", "Settings");
	defined("PAGENAME_API")							?	null	:	define("PAGENAME_API", "API");
	defined("PAGENAME_APIADD")						?	null	:	define("PAGENAME_APIADD", "Add API Token");
	defined("PAGENAME_APIDELETE")					?	null	:	define("PAGENAME_APIDELETE", "Delete API Token");
	defined("PAGENAME_APIUPDATE")					?	null	:	define("PAGENAME_APIUPDATE", "Update API Token");
	
	// Set page links
	defined("PAGELINK_INDEX")						?	null	:	define("PAGELINK_INDEX", "index.php");
	defined("PAGELINK_LOGIN")						?	null	:	define("PAGELINK_LOGIN", "login.php");
	defined("PAGELINK_LOGOUT")						?	null	:	define("PAGELINK_LOGOUT", "logout.php");
	defined("PAGELINK_USERS")						?	null	:	define("PAGELINK_USERS", "users.php");
	defined("PAGELINK_USERSDELETE")					?	null	:	define("PAGELINK_USERSDELETE", "delete-user.php");
	defined("PAGELINK_USERSUPDATE")					?	null	:	define("PAGELINK_USERSUPDATE", "update-user.php");
	defined("PAGELINK_LOGS")						?	null	:	define("PAGELINK_LOGS", "logs.php");
	defined("PAGELINK_CONTACTSADD")					?	null	:	define("PAGELINK_CONTACTSADD", "add-contact.php");
	defined("PAGELINK_CONTACTSDELETE")				?	null	:	define("PAGELINK_CONTACTSDELETE", "delete-contact.php");
	defined("PAGELINK_CONTACTSUPDATE")				?	null	:	define("PAGELINK_CONTACTSUPDATE", "update-contact.php");
	defined("PAGELINK_CONTACTSVIEW")				?	null	:	define("PAGELINK_CONTACTSVIEW", "view-contact.php");
	defined("PAGELINK_SETTINGS")					?	null	:	define("PAGELINK_SETTINGS", "settings.php");
	defined("PAGELINK_API")							?	null	:	define("PAGELINK_API", "api.php");
	defined("PAGELINK_APIADD")						?	null	:	define("PAGELINK_APIADD", "add-api.php");
	defined("PAGELINK_APIDELETE")					?	null	:	define("PAGELINK_APIDELETE", "delete-api.php");
	defined("PAGELINK_APIUPDATE")					?	null	:	define("PAGELINK_APIUPDATE", "update-api.php");
	
	// Server time zone
 	date_default_timezone_set(TIMEZONE);

	// // // // // // // // // //
	// Set table header names  //
	// // // // // // // // // //
	if (!isset($_SERVER['TABLE_CONTACT_NAME'])){
		putenv('TABLE_CONTACT_NAME=' . "Name");
	}

	if (!isset($_SERVER['TABLE_CONTACT_ADDRESS_1'])){
		putenv('TABLE_CONTACT_ADDRESS_1=' . "Address Line 1");
	}

	if (!isset($_SERVER['TABLE_CONTACT_ADDRESS_2'])){
		putenv('TABLE_CONTACT_ADDRESS_2=' . "Address Line 2");
	}

	if (!isset($_SERVER['TABLE_CONTACT_TOWN'])){
		putenv('TABLE_CONTACT_TOWN=' . "Town");
	}

	if (!isset($_SERVER['TABLE_CONTACT_POSTAL_CODE'])){
		putenv('TABLE_CONTACT_POSTAL_CODE=' . "Postal Code");
	}

	if (!isset($_SERVER['TABLE_CONTACT_COUNTY'])){
		putenv('TABLE_CONTACT_COUNTY=' . "County");
	}

	if (!isset($_SERVER['TABLE_CONTACT_MOBILE_NUMBER'])){
		putenv('TABLE_CONTACT_MOBILE_NUMBER=' . "Mobile Number");
	}

	if (!isset($_SERVER['TABLE_CONTACT_HOME_NUMBER'])){
		putenv('TABLE_CONTACT_HOME_NUMBER=' . "Home Number");
	}

	if (!isset($_SERVER['TABLE_CONTACT_EMAIL'])){
		putenv('TABLE_CONTACT_EMAIL=' . "Email");
	}	

	if (!isset($_SERVER['TABLE_CONTACT_DATE_OF_BIRTH'])){
		putenv('TABLE_CONTACT_DATE_OF_BIRTH=' . "Date of Birth");
	}
	
	// Autoload classes so that they are called as and when they are required
	spl_autoload_register(function($class_name) { 
		$class_name = strtolower($class_name);
		include('class.' . $class_name . '.inc.php');
	});
	
	// Begin running the Session as items in constructor are required for the system to function correctly
	$session = new Session();
	
	// Begin a new User instance as will automatically check details of the user if they are logged in etc
	$user = new User();
	
	// Site functions
	require_once("functions.inc.php");
	
	// Notifications, for things such as error messages and success alerts
	require_once("alerts.notification.inc.php");
	
	// Validation messages for form fields, such as string lengths too long, or required fields missing
	require_once("alerts.validation.inc.php");

?>