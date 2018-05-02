<?php
	// This class is for manipulating data relating to Contacts
	class Contact {
		// Variable to hold a DB instance
		private $db;
		
		// All variables for when all contacts are searched
		public $all = null; // Variable used to hold all contacts
		
		// All variables which are relating to when a user searches for a particular ID
		public $single = null, // Variable used to hold details of single contact ID
			   $email = null, // Contact email address
			   $full_name = null, // Variable used to hold full name of contact
			   $full_address = null; // Variable used to hold the full address of the contact
			   
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
					// Set the properties of the class as per the users details
					$this->full_name = htmlentities($this->full_name($result['first_name'], $result['middle_name'], $result['last_name']));
					$this->full_address = htmlentities($this->full_address($result['address_line_1'], $result['address_line_2'], $result['address_town'], $result['address_county'], $result['address_post_code']));
					$this->email = htmlentities($result['contact_email']);
					
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
		
		private function full_address($address_line_1, $address_line_2=null, $town, $county, $post_code) {
			// If the address has a value in address line 2
			if($address_line_2 != null) {
				// Create the address with the line 2 value
				return $address = $address_line_1 . ", " . $address_line_2 . ", " . $town . ", " . $county . ", " . $post_code;
			} else {
				// Don't include the line 2 value
				return $address = $address_line_1 . ", " . $town . ", " . $county . ", " . $post_code;
			}
		}
		
	}; // Close class Contact
	
// EOF