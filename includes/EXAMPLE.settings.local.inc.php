<?php	
	// The IP/hostname of the MySQL/MariaDB server
	defined("DB_SERVER")    	?	null	:	define("DB_SERVER", "127.0.0.1");
	// The username of the account which has access to the database on the MySQL/MariaDB server
	defined("DB_USER")			?	null	:	define("DB_USER", "root");
	// The password (if any) associated with the DB_USER account
	defined("DB_PASS")			?	null	:	define("DB_PASS", "");
	// The database name (if you imported the sql.sql file and didn't change any settings then this will be address_book)
	defined("DB_NAME")			?	null	:	define("DB_NAME", "address_book");
	// The site URL
	defined("SITE_URL")			?	null	:	define("SITE_URL", "http://localhost/");