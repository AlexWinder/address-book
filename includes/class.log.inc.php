<?php
	// This class is used for logging user actions to the database in the logs table
	class Log {
		
		private $db = null; // Used to store an instance of the database
		
		// Constructor
		public function __construct($action = null) {
			// Obtain an instance of the database
			$this->db = DB::get_instance();
			
			// If action was sent then process a new action to be added to the database
			if($action) {
				// $action has been sent, add to the database
				$this->action($action);
			}
		}
		
		// Find all logs from the database
		public function find_all() {
			// Return all 
			return $this->all = $this->db->query('SELECT * FROM logs', PDO::FETCH_ASSOC);
		}
		
		// Method to add a new entry to the logs table in the database
		public function action() {
			
		}
		
		// Method to obtain the current datetime in MySQL format
		private function current_mysql_datetime() {
			// Return the current time in MySQL datetime formate 
			return date('Y-m-d H:i:s', time());
		}
		
	}; // Close class Log
// EOF