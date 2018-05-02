<?php
	// This class is for manipulating data relating to Contacts
	class Contact {
		// Variable to hold a DB instance
		private $db;
		
		// All variables for when all contacts are searched
		public $all = null; // Variable used to hold all contacts
		
		// All variables which are relating to when a user searches for a particular ID
		public $single = null, // Variable used to hold details of single contact ID
			   $full_name = null; // Variable used to hold full name of contact
		
		// Constructor
		public function __construct($id = null) {
			// Set the $db with an instance of the database
			$this->db = DB::get_instance();
			
			// If an $id is sent through then return the contact associated with the ID
			// Check if an $id has been sent
			if($id) {
				// $id has been sent, find only that contact
				$this->find_id($id);
			} else {
				// No $id has been sent, find all contacts
				$this->find_all();
			}
		}
		
		// Class to find all contacts in the database
		public function find_all() {
			// Return all 
			return $this->all = $this->db->query('SELECT * FROM contacts', PDO::FETCH_ASSOC);
		}
		
		// Class to find specific contact in the database
		public function find_id($id = null) {
			// Check if $id has been sent
			if($id) {
				// Begin prepared statement to find single ID in database
				$sql = '
					SELECT * FROM contacts 
					WHERE contact_id = :contact_id
				';
				$stmt = $this->db->prepare($sql);
				
				// Pass in the $id into the prepared statement and execute
				$stmt->bindParam(':contact_id', $id);
				$stmt->execute();
				
				// Fetch the results from the prepared statement
				$result = $stmt->fetch();
				
				// Check if a contact could be found
				if($result) {
					// Set the $full_name as per the users details
					$this->full_name = $this->full_name($result['first_name'], $result['middle_name'], $result['last_name']);
					// Contact found, return all of the details
					return $this->single = $result;
				} else {
					// Contact not found, return false
					return false;
				}
			} else {
				// $id not sent, return false
				return false;
			}
		}
		
		private function full_name($first_name, $middle_name = null, $last_name){
			// If the person has a middle name
			if($middle_name != null ) {
				// Create their name with a middle name
				return $first_name . " " . $middle_name . " " . $last_name;
			} else {
				// Don't include the middle name
				return $first_name . " " . $last_name;
			}
		}
		
	}; // Close class Contact
	
// EOF