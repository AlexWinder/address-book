<?php
	// This class is for returning an instance of the DB with a getInstance() method call
	class DB {
		
		// Hold any instantiated PDO object for DB in an $instance as part of a singleton call
		// Set default to null
		protected static $instance = null;
		
		protected function __construct() {}
		
		// Database static method for obtaining Singleton PDO call
		public static function get_instance() {
			// If $instance hasn't been set
			if(empty(self::$instance)) {
				// Attempt to create a new PDO connection
				try {
					// Set $instance to a new PDO, as currently not set
					self::$instance = new PDO(DB_TYPE.":host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
				} catch(PDOException $error) {
					// If an error has been found
					echo $error->getMessage();
				}
			}
			// Return the Singleton $instance
			return self::$instance;
		}
		
	}; // Close class DB
	
// EOF