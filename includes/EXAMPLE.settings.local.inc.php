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
	 * mysql-1    | 2025-03-27 08:52:31+00:00 [Note] [Entrypoint]: GENERATED ROOT PASSWORD: iNBqruolQSo6ZEsc8ZXyQ5QpUWke9KF5
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