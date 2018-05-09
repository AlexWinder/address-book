<?php
	class User {
		// User class used to manipulate users and to check if user is logged in/authenticated
		
		private $db = null; // Used to store an instance of the database
		
		// Constructor
		public function __construct() {
			// Obtain an instance of the database
			$this->db = DB::get_instance();
		}
		
		// Used to attempt a login based on a passed in username/password
		public function attempt_login($username = null, $password = null) {
			// Check if $username and $password are set
			if(isset($username) && isset($password) && !empty($username) && !empty($password)) {
				// $username and $password set
				// Lookup the $username
				$user = $this->find_username($username);
				
				// Check if username could be found
				if($user) {
					// Username found
					// Check that the password is correct
					$result = $this->password_check($password, $user['hashed_password']);
					
					// Check that the password matches
					if($result) {
						// Username and password matches
						// Return the $user details
						return $user;
					} else {
						// Password doesn't match
						return false;
					}
				} else {
					// Username not found
					return false;
				}
			} else {
				// $username and $password not set
				return false;
			} // Close if(isset($username) && isset($password) && !empty($username) && !empty($password))
		}
	
		// Used for verifying that users supplied password matches
		private function password_check($password, $existing_hash) {
			// Exisiting hash contains format and salt at start
			$hash = crypt($password, $existing_hash);
			// Check that passwords are correct
			if($hash === $existing_hash) {
				// Passwords match
				return true;
			} else {
				// Passwords don't match
				return false;
			}
		}
	
		// Method to find a specific username
		public function find_username($username = null) {
			// If the $username has been sent
			if(!empty($username)) {
				// Prepare a SQL query to search for a username from the database
				$sql = "
					SELECT * FROM users 
					WHERE username = :username
				";
				$stmt = $this->db->prepare($sql);
				
				// Pass in the $username into the prepared statement and execute
				$stmt->bindParam(':username', $username);
				
				// Execute the query
				$stmt->execute();
				
				// Fetch the results from the prepared statement
				$result = $stmt->fetch();
				
				// Check if a username could be found
				if($result) {
					// Username found, return all of the details
					return $result;
				} else {
					// Username not found, return false
					return false;
				}
			} else {
				// $username hasn't been sent
				return false;
			}
		}
		
	} // Close class User
// EOF