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
		
		// Method to update a particular contact
		public function update($values = array()) {
			// This method will only be called if used from a search of a particular ID during instantiation, such as $contact = new Contact(3298)
			if($this->found) {
				// This method works by accepting a $values array which contains the details of the fields which are to be updated
				// Check that the array isn't empty
				if(!empty($values)) {
					// Array has values, begin building the SQL query to be used to update contact
					$sql = "UPDATE contacts SET ";
					
					// Count the number of values in the array so that a comma (,) is added after each section of the loop apart from the last one
					$i = 0;
					$c = count($values);
					
					// Cycle through each value in the array
					foreach($values as $key => $value) {
						if($i++ < $c - 1) {
							// Append to the $sql, and include a comma
							$sql .= $key . " = :" . $key . ", ";
						} else {
							// Append to the $sql, but leave off the comma
							$sql .= $key . " = :" . $key . " ";
						}
					}
					
					// Specify which contact to update and limit to update only 1 record as a fail-safe
					$sql .= "WHERE contact_id = :contact_id ";
					$sql .= "LIMIT 1";
					
					// Begin a prepared statement using the previous $sql
					$stmt = $this->db->prepare($sql);
					
					// Pass in values from the $values array to complete the prepared statement
					foreach($values as $key => &$value) {
						$stmt->bindParam(':' . $key, $value);
					}
					// Bind the contact ID to the prepared statement
					$stmt->bindParam(':contact_id', $this->single['contact_id']);
					
					// Execute the prepared statement
					$result = $stmt->execute();
					
					// Check if successful
					if($result) {
						// Update successful
						return true;
					} else {
						// Update failed
						return false;
					}
				} else {
					// Array was empty
					return false;
				}
			} else {
				// User wasn't found
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
		
		// Method to create a new contact
		public function create($values = array()) {
			// This method works by accepting a $values array which contains the details of the fields which are to be inserted
			// Check that the array isn't empty
			if(!empty($values)) {
				// Obtain a DB instance
				$db = DB::get_instance();
				
				// Array has values, begin building the SQL query to be used to create contact
				$sql = "INSERT INTO contacts (";
				
				// Add in the contact_id as won't be submitted as part of the $values array
				$sql .= "contact_id, ";
				
				// Count the number of values in the array so that a comma (,) is added after each section of the loop apart from the last one
				$i = 0;
				$c = count($values);
				
				// Cycle through each value in the array
				foreach($values as $key => $value) {
					if($i++ < $c - 1) {
						// Append to the $sql, and include a comma
						$sql .= $key . ", ";
					} else {
						// Append to the $sql, but leave off the comma
						$sql .= $key . " ";
					}
				}
				
				$sql .= ") VALUES (";
				
				// Add in the contact_id as won't be submitted as part of the $values array
				$sql .= ":contact_id, ";
				
				// Reset counters
				// Count the number of values in the array so that a comma (,) is added after each section of the loop apart from the last one
				$i = 0;
				$c = count($values);
				
				// Cycle through each value in the array, this time specifying the keys to insert as part of the prepared statement
				foreach($values as $key => $value) {
					if($i++ < $c - 1) {
						// Append to the $sql, and include a comma
						$sql .= ":" . $key . ", ";
					} else {
						// Append to the $sql, but leave off the comma
						$sql .= ":" . $key . " ";
					}
				}
				// End the $sql
				$sql .= ")";
				
				// Begin a prepared statement using the previous $sql
				$stmt = $db->prepare($sql);
				
				// Generate an ID with a length of 12
				$id = $this->generate_id(12);
				$stmt->bindParam(':contact_id', $id);
				
				// Pass in values from the $values array to complete the prepared statement
				foreach($values as $key => &$value) {
					$stmt->bindParam(':' . $key, $value);
				}
				
				// Execute the prepared statement
				$result = $stmt->execute();
				
				// Check if successful
				if($result) {
					// Insert successful
					return true;
				} else {
					// Insert failed
					return false;
				}
			} else {
				// Array was empty
				return false;
			}
		}
		
		// Generate an ID to be used as the unique key associated with a new contact which is being created
		private function generate_id($token_length) {
			// Used to generate a token
			// Initialise a variable used to store the token
			$token = null;
			// Create a salt of accepted characters
			$salt = "abcdefghjkmnpqrstuvxyzABCDEFGHIJKLMNOPQRSTUVXYZ0123456789";
			
			srand((double)microtime()*1000000);
			$i = 0;
			while ($i < $token_length) {
				$num = rand() % strlen($salt);
				$tmp = substr($salt, $num, 1);
				$token = $token . $tmp;
				$i++;
			}
			// Return the token
			return $token;
		}
		
		public function format_phone_number($phone_number) {
			// Remove all white space from the phone number
			$phone_number = $this->remove_white_space($phone_number);
			// Insert a space at position 5 in a phone number, formatting as 01234 567890
			return substr_replace($phone_number, " ", 5, 0);
		}
		
		public function remove_white_space($string) {
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