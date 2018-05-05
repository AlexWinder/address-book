<?php
	// This class is for manipulating data relating to Contacts
	class Contact {
		// Variable to hold a DB instance
		private $db;
		
		// All variables for when all contacts are searched
		public $all = null; // Variable used to hold all contacts
		
		// All variables which are relating to when a user searches for a particular ID
		public $found = false, // Used to check if a contact could be found or not
			   $single = null, // Variable used to hold details of single contact ID
			   $email = null, // Contact email address
			   $date_of_birth = null, // Users date of birth
			   $number = array(), // Used as an array to store various formatted and unformatted phone numbers
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
		
		// Method to find all contacts in the database
		public function find_all() {
			// Return all 
			return $this->all = $this->db->query('SELECT * FROM contacts', PDO::FETCH_ASSOC);
		}
		
		// Method to find specific contact in the database
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
					// Contact found
					$this->found = true; // Specify that a contact could be found
					// Set the properties of the class as per the users details
					$this->full_name = htmlentities($this->full_name($result['first_name'], $result['middle_name'], $result['last_name']));
					$this->full_address = htmlentities($this->full_address($result['address_line_1'], $result['address_line_2'], $result['address_town'], $result['address_county'], $result['address_post_code']));
					$this->email = htmlentities($result['contact_email']);
					$this->date_of_birth = htmlentities($this->cosmetic_mysqldate($result["date_of_birth"]));
					$this->number['home']['raw'] = htmlentities($result['contact_number_home']);
					$this->number['home']['formatted'] = htmlentities($this->format_phone_number($result['contact_number_home']));
					$this->number['mobile']['raw'] = htmlentities($result['contact_number_mobile']);
					$this->number['mobile']['formatted'] = htmlentities($this->format_phone_number($result['contact_number_mobile']));
					
					// Return all of the details of the contact
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

		// Method to delete a particular contact
		public function delete() {
			// This method will only be called if used from a search of a particular ID during instantiation, such as $contact = new Contact(3298)
			if($this->found) {
				// Begin prepared statement to delete a single ID from the database
				$sql = '
					DELETE FROM contacts 
					WHERE contact_id = :contact_id 
					LIMIT 1
				';
				$stmt = $this->db->prepare($sql);

				// Pass in the $id into the prepared statement and execute
				$stmt->bindParam(':contact_id', $this->single['contact_id']);

				// Execute the prepared statement
				$result = $stmt->execute();

				if($result) {
					// Delete was successful
					return true;
				} else {
					// Delete failed
					return false;
				}
			} else {
				// Being called as not part of an ID instance
				return false;
			}
			
		}
		
		public function format_phone_number($phone_number) {
			// Remove all white space from the phone number
			$phone_number = $this->remove_white_space($phone_number);
			// Insert a space at position 5 in a phone number, formatting as 01234 567890
			return substr_replace($phone_number, " ", 5, 0);
		}
		
		private function remove_white_space($string) {
			// Remove all white space within the string
			return preg_replace('/\s+/', '', $string);
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
		
		private function cosmetic_mysqldate($mysql_date = null) {
			if($mysql_date) {
				// Convert MySQL date to a UNIX time stamp
				$unix_date = strtotime($mysql_date);
				
				// Format date into correct string, example: Saturday 1st May 1993
				$cosmetic_date = date('jS F Y', $unix_date);
				return $cosmetic_date;
			} else {
				return false;
			}
		}
		
	}; // Close class Contact
	
// EOF