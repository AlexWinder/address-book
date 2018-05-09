<?php
	class User {
		// User class used to manipulate users and to check if user is logged in/authenticated
		
		private $db = null; // Used to store an instance of the database
		
		public 	$authenticated = false, // Used to know if the user is authenticated or not
				$details = false, // Used to store all details about the user in an array
				$name = false; // The users name as retrieved from the database
		
		// Constructor
		public function __construct() {
			// Obtain an instance of the database
			$this->db = DB::get_instance();
			// Check that the user has been authenticated
			$this->is_authenticated();
			// Check that the users security checked settings haven't changed
			$this->check_security();
		}
		
		// Used to check if the user has been authenticated
		public function is_authenticated() {
			// Pull in global $session
			global $session;
			// Check if user is authenticated
			if($session->get('authenticated_user') == 1) {
				
				// Check if user ID has been submitted
				if($session->get('authenticated_user_id')) {
					// Lookup the user_id in the DB
					$result = $this->find_id($session->get('authenticated_user_id'));
					
					// Check if the user ID is valid in the database
					if($result) {
						// User is logged in
						$this->authenticated = true;
						
						// Pass through details of the $result in raw format to the object
						$this->details = $result;
						
						// Build the users full name for ease in future
						$this->name = $result['full_name'];
					} else {
						// User does not exist in the database - remove all authentication as a fail-safe
						$this->remove_authenticated();
					}
				} else {
					// User hasn't been authenticated correctly - user_id not present, remove all authentication as a fail-safe
					$this->remove_authenticated();
				}
			} else {
				// User hasn't been authenticated, remove all authentication as a fail-safe
				$this->remove_authenticated();
			}
		}
		
		// Used to check if users details have changed at any point and to automatically log a user out if they have changed
		private function check_security() {
			// Pull in global $session
			global $session;
			
			// Check that the users IP address hasn't changed
			if(($_SERVER['REMOTE_ADDR'] != $session->get('user_ip'))) {
				// Delete the $session->get('user_ip') to avoid redirects
				$session->remove('user_ip');
				// Log the user out
				$this->logout('security_failed');
			};
				
			// Check that the users HTTP agent hasn't changed
			if(($_SERVER['HTTP_USER_AGENT'] != $session->get('user_agent'))) {
				// Delete the $session->get('user_agent') to avoid redirects
				$session->remove('user_agent');
				// Log the user out
				$this->logout('security_failed');
			};
				
			// Check details if the user has been authenticated
			if($this->authenticated) {
				// Check that the users IP address is the same as when they logged in
				if(($_SERVER['REMOTE_ADDR'] != $session->get('authenticated_user_ip'))) {
					// Delete the $session->get('authenticated_user_ip') to avoid redirects
					$session->remove('authenticated_user_ip');
					// Log the user out
					$this->logout('security_failed');
				}
				// Check that the users HTTP agent is the same as when they logged in
				if(($_SERVER['HTTP_USER_AGENT'] != $session->get('authenticated_user_agent'))) {
					// Delete the $session->get('authenticated_user_agent') to avoid redirects
					$session->remove('authenticated_user_agent');
					// Log the user out
					$this->logout('security_failed');
				}
			}
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
		
		// Used to remove any authenticated user 
		public function remove_authenticated() {
			// Call in the $session as required
			global $session;
			
			// Remove Session values first
			$session->remove('authenticated_user');
			$session->remove('authenticated_user_ip');
			$session->remove('authenticated_user_agent');
			$session->remove('authenticated_user_time');
			$session->remove('authenticated_user_id');
			$session->remove('authenticated_user_username');
			
			// Remove any set properties in this instance
			$this->authenticated = false;
			$this->details = false;
			$this->name = false;
		}
		
		// Used to set various session values which will be used for checking that the user is logged in correctly
		// Called after a user has successfully been logged in
		public function set_logged_in($user = null) {
			// Call in the $session as required
			global $session; 
			
			// Check that the relevant information has been sent
			if(isset($user) && !empty($user)) {
				// Remove any pre-existing settings
				$this->remove_authenticated();
				
				// Set value to notify that the user has been authenticated
				$session->set('authenticated_user', '1');
				$this->authenticated = true;
				
				// Set details gathered about the session from when the user was authenticated
				// Remove any pre-existing details
				$session->set('authenticated_user_ip', $_SERVER['REMOTE_ADDR']);
				$session->set('authenticated_user_agent', $_SERVER['HTTP_USER_AGENT']);
				$session->set('authenticated_user_time', time());
				
				// Set details gathered about the user
				$session->set('authenticated_user_id', $user['user_id']);
				$session->set('authenticated_user_username', $user['username']);
			}
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
		
		// Method to find a specific user by their user_id
		public function find_id($id = null) {
			// If the $id has been sent
			if(!empty($id)) {
				// Prepare a SQL query to search for a id from the database
				$sql = "
					SELECT * FROM users 
					WHERE user_id = :user_id
				";
				$stmt = $this->db->prepare($sql);
				
				// Pass in the $id into the prepared statement and execute
				$stmt->bindParam(':user_id', $id);
				
				// Execute the query
				$stmt->execute();
				
				// Fetch the results from the prepared statement
				$result = $stmt->fetch();
				
				// Check if a user could be found
				if($result) {
					// User found, return all of the details
					return $result;
				} else {
					// User not found, return false
					return false;
				}
			} else {
				// $id hasn't been sent
				return false;
			}
		}
		
		// Used to log a user out
		public function logout($message = null) {
			// Bring in the global Session
			global $session;
			// Bring in the $notification array so that standard messages are brought in
			global $notification;
			
			// Remove authenticated elements
			$this->remove_authenticated();
			
			// Use switch statement to determine if user has been automatically logged out due to failing a security check
			switch($message) {
				case 'security_failed':
					$session->message_alert($notification['logout']['security_failed'], 'danger');
					break;
				default:
					// No $message has been sent, use the default logout message
					$session->message_alert($notification['logout']['success'], 'success');
			}
			
			// Redirect user
			Redirect::to(PAGELINK_LOGIN);
		}
		
	} // Close class User
// EOF