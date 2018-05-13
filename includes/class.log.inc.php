<?php
	// This class is used for logging user actions to the database in the logs table
	class Log {
		
		private $db = null; // Used to store an instance of the database
		
		// Constructor
		public function __construct() {
			// Obtain an instance of the database
			$this->db = DB::get_instance();
		}
		
		// Find all logs from the database
		public function find_all() {
			// Return all 
			return $this->all = $this->db->query('SELECT * FROM logs', PDO::FETCH_ASSOC);
		}
		
	}; // Close class Log
// EOF