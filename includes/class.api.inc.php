<?php
	class API {
		// API class used when a user makes an API call
		
		private $db = null; // Used to store an instance of the database
		
		// Constructor
		public function __construct() {
			// Obtain an instance of the database
			$this->db = DB::get_instance();
		}
		
	} // Close class API
// EOF