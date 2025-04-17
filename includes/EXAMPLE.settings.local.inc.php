<?php
	/**
	 * The IP/hostname of the MySQL/MariaDB server.
	 * If you are using a Docker environment then this should be the name of your Docker MySQL/MariaDB container.
	 * Otherwise this should be the address of your database server, typically this is 127.0.0.1.
	 */
	defined('DB_SERVER')    	?	null	:	define('DB_SERVER', 'mysql'); // Docker example
	// defined('DB_SERVER')    	?	null	:	define('DB_SERVER', '127.0.0.1'); // Standalone database example

	/**
	 * The username of the account which has access to the database on the MySQL/MariaDB server.
	 */
	defined('DB_USER')			?	null	:	define('DB_USER', 'root');

	/**
	 * The password (if any) associated with the DB_USER account.
	 * If you are using Docker then a password will likely be set during setup. 
	 * You should consult the logs for the 'mysql' container which will list the password generated.
	 * mysql_1    | 2022-07-16 22:04:23+00:00 [Note] [Entrypoint]: GENERATED ROOT PASSWORD: CT5qDK3cyvh38v8Z+oqIG07YuBQhvkOO
	 */
	defined('DB_PASS')			?	null	:	define('DB_PASS', '');

	/**
	 * The database where the data will be stored.
	 * If you have used the default installation with the sql.sql file and didn't change any settings then this will be 'address_book'.
	 * Please note that the user set for DB_USER will need to have permission to this database.
	 */
	defined('DB_NAME')			?	null	:	define('DB_NAME', 'address_book');

	/**
	 * The address which is used to access this system.
	 */
	defined('SITE_URL')			?	null	:	define('SITE_URL', 'http://localhost/');

	/**
	 * The timezone to be used by the system.
	 * See https://www.php.net/manual/en/timezones.php for a list of valid timezones.
	 */
	defined("TIMEZONE")			?	null	:	define("TIMEZONE", "UTC");

	/**
	 * The name of the column for the contact's name
	 */
	defined("TABLE_CONTACT_FULL_NAME")				?	null	:	define("TABLE_CONTACT_FULL_NAME", "Name");
	
	/**
	 * The name of the column for the contact's first name
	 */
	defined("TABLE_CONTACT_FIRST_NAME")			?	null	:	define("TABLE_CONTACT_FIRST_NAME", "First Name");
	
	/**
	 * The name of the column for the contact's middle name
	 */
	defined("TABLE_CONTACT_MIDDLE_NAME")			?	null	:	define("TABLE_CONTACT_MIDDLE_NAME", "Middle Name");
	
	/**
	 * The name of the column for the contact's last name
	 */
	defined("TABLE_CONTACT_LAST_NAME")			?	null	:	define("TABLE_CONTACT_LAST_NAME", "Last Name");

	/**
	 * The name of the column for the contact's address line 1
	 */
	defined("TABLE_CONTACT_ADDRESS_1")			?	null	:	define("TABLE_CONTACT_ADDRESS_1", "Address Line 1");

	/**
	 * The name of the column for the contact's address line 2
	 */
	defined("TABLE_CONTACT_ADDRESS_2")			?	null	:	define("TABLE_CONTACT_ADDRESS_2", "Address Line 2");

	/**
	 * The name of the column for the contact's town
	 */	
	defined("TABLE_CONTACT_TOWN")				?	null	:	define("TABLE_CONTACT_TOWN", "City");

	/**
	 * The name of the column for the contact's postal code
	 */

	defined("TABLE_CONTACT_POSTAL_CODE")		?	null	:	define("TABLE_CONTACT_POSTAL_CODE", "Zip Code");
	
	/**
	 * The name of the column for the contact's county or state
	 */
	defined("TABLE_CONTACT_COUNTY")			?	null	:	define("TABLE_CONTACT_COUNTY", "State");
	
	/**
	 * The name of the column for the contact's mobile number
	 */
	defined("TABLE_CONTACT_MOBILE_NUMBER")		?	null	:	define("TABLE_CONTACT_MOBILE_NUMBER", "Mobile Number");
	
	/**
	 * The name of the column for the contact's home number
	 */
	defined("TABLE_CONTACT_HOME_NUMBER")		?	null	:	define("TABLE_CONTACT_HOME_NUMBER", "Home Number");
	
	/**
	 * The name of the column for the contact's email address
	 */
	defined("TABLE_CONTACT_EMAIL")				?	null	:	define("TABLE_CONTACT_EMAIL", "Email");
	
	/**
	 * The name of the column for the contact's date of birth
	 */
	defined("TABLE_CONTACT_DATE_OF_BIRTH")		?	null	:	define("TABLE_CONTACT_DATE_OF_BIRTH", "DOB");