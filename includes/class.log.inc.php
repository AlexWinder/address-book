<?php
	// This class is used for logging user actions to the database in the logs table
	class Log {
		
		private $db = null; // Used to store an instance of the database
		
		// Constructor
		public function __construct($action = null, $additional_message = null) {
			// Obtain an instance of the database
			$this->db = DB::get_instance();
			
			// If action was sent then process a new action to be added to the database
			if($action) {
				// $action has been sent, add to the database
				$this->action($action, $additional_message);
			}
		}
		
		// Find all logs from the database
		public function find_all() {
			// Return all 
			return $this->all = $this->db->query('SELECT * FROM logs', PDO::FETCH_ASSOC);
		}
		
		// Method to add a new entry to the logs table in the database
		public function action($action = null, $additional_message = null) {
			global $user;
			
			// Define the SQL to be used to make changes to the database
			$sql = '
				INSERT INTO logs ( 
					datetime, 
					action, 
					url, 
					user, 
					ip, 
					user_agent 
				) VALUES ( 
					:datetime, 
					:action, 
					:url, 
					:user, 
					:ip, 
					:user_agent 
				)
			';
			
			// Begin a prepared statement using the previous $sql
			$stmt = $this->db->prepare($sql);
			
			// Bind values to the prepared statement
			$datetime = $this->current_mysql_datetime();
			$stmt->bindParam(':datetime', $datetime);
			$action = $this->get_action($action, $additional_message);
			$stmt->bindParam(':action', $action);
			$stmt->bindParam(':url', $_SERVER['REQUEST_URI']);
			$name = $user->username ? $user->name . ' [' . $user->username . ']' : 'Unknown';
			$stmt->bindParam(':user', $name);
			$stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
			$stmt->bindParam(':user_agent', $_SERVER['HTTP_USER_AGENT']);
			
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
		}
		
		// Returns a string of an action, based on an input
		private function get_action($action = null, $additional_message = null) {
			// Run through a switch statement to specify an action and return
			switch($action) {
				case 'view' : // For page views
					$action = 'Page Viewed: (' . page_name() . ')'; // Use the page_name function to specify which page a user has visited
					break;
				case 'not_found' : // For accessing pages which couldn't be found, such as invalid $_GET values or values which couldn't be found in the database
					$action = "Result Not Found: (" . page_name() . ')';
					break;
				case 'login_failed' :
					$action = 'Login Failed';
					if($additional_message) {
						$action .= ': ' . $additional_message;
					};
					break;
				case 'login_success' :
					$action = 'Login Success';
					break;
				case 'login_redirect' :
					$action = 'User redirected from login page due to already being logged in';
					break;
				case 'logout_success' :
					$action = 'Logout Success';
					break;
				case 'logout_security' :
					$action = 'Automatic logout due to a failed security check';
					break;
				case 'not_authenticated' :
					$action = 'Unauthenticated User Attempted Accessing Page: (' . page_name() . ')';
					break;
				case 'user_add_failed' :
					$action = 'User Add Failed';
					if($additional_message == 'database') {
						$action .= ': There was an error making changes to the database.';
					} elseif($additional_message) {
						$action .= ': ' . $additional_message;
					}
					break;
				case 'user_add_success' :
					$action = 'User Add Success';
					if($additional_message) {
						$action .= ': ' . $additional_message;
					};
					break;
				case 'user_delete_failed' :
					$action = 'User Delete Failed';
					if($additional_message == 'database') {
						$action .= ': There was an error making changes to the database.';
					} elseif($additional_message) {
						$action .= ': ' . $additional_message;
					}
					break;
				case 'user_delete_success' :
					$action = 'User Delete Success';
					if($additional_message) {
						$action .= ': ' . $additional_message;
					};
					break;
				case 'user_update_failed' :
					$action = 'User Update Failed';
					if($additional_message == 'database_password') {
						$action .= ': There was an error making changes to the database to update password.';
					} elseif($additional_message == 'database_details') {
						$action .= ': There was an error making changes to the database to update details.';
					} elseif($additional_message) {
						$action .= ': ' . $additional_message;
					}
					break;
				case 'user_update_success' :
					$action = 'User Update Success';
					if($additional_message) {
						$action .= ': ' . $additional_message;
					};
					break;
				case 'contact_add_failed' :
					$action = 'Contact Add Failed';
					if($additional_message == 'database') {
						$action .= ': There was an error making changes to the database.';
					} elseif($additional_message) {
						$action .= ': ' . $additional_message;
					}
					break;
				case 'contact_add_success' :
					$action = 'Contact Add Success';
					if($additional_message) {
						$action .= ': ' . $additional_message;
					};
					break;
				case 'contact_delete_failed' :
					$action = 'Contact Delete Failed';
					if($additional_message == 'database') {
						$action .= ': There was an error making changes to the database.';
					} elseif($additional_message) {
						$action .= ': ' . $additional_message;
					}
					break;
				case 'contact_delete_success' :
					$action = 'Contact Delete Success';
					if($additional_message) {
						$action .= ': ' . $additional_message;
					};
					break;
				case 'contact_update_failed' :
					$action = 'Contact Update Failed';
					if($additional_message == 'database') {
						$action .= ': There was an error making changes to the database.';
					} elseif($additional_message) {
						$action .= ': ' . $additional_message;
					}
					break;
				case 'contact_update_success' :
					$action = 'Contact Update Success';
					if($additional_message) {
						$action .= ': ' . $additional_message;
					};
					break;
				case 'api_call_success' :
					$action = 'API Call Success';
					if($additional_message) {
						$action .= ': ' . $additional_message;
					};
					break;
				case 'api_call_failed' :
					$action = 'API Call Failed';
					if($additional_message) {
						$action .= ': ' . $additional_message;
					};
					break;
				case 'api_add_failed' :
					$action = 'API Token Add Failed';
					if($additional_message) {
						$action .= ': ' . $additional_message;
					};
					break;
				default :
					$action = 'Action Unspecified!';
					break;
			}
			
			// Return the $action
			return $action;
		}
		
		// Method to obtain the current datetime in MySQL format
		private function current_mysql_datetime() {
			// Return the current time in MySQL datetime formate 
			return date('Y-m-d H:i:s', time());
		}
		
	}; // Close class Log
// EOF