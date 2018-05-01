<?php
	defined("DB_SERVER")    	?	null	:	define("DB_SERVER", "127.0.0.1");
	defined("DB_USER")			?	null	:	define("DB_USER", "root");
	defined("DB_PASS")			?	null	:	define("DB_PASS", "");
	defined("DB_NAME")			?	null	:	define("DB_NAME", "address_book");
	
	// Display errors
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
?>