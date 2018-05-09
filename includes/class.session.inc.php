<?php
	class Session {
		// Constructor
		public function __construct() {
			// Start the session
			session_start();
			// Log the users details
			$this->obtain_user_details();
		}
		
		// Used for testing purposes to output the contents of the $_SESSION array
		public function debug() {
			echo '<pre>';
			print_r($_SESSION);
			echo '</pre>';
		}
		
		// Set a session key with a value
		public function set($key, $value) {
			if(isset($_SESSION[$key]) || !empty($_SESSION[$key])) {
				// Concatenate if value is already present
				$_SESSION[$key] .= $value;
			} else {
				// Otherwise set as a new value
				$_SESSION[$key] = $value;
			}
		}
		
		// Retrieve a session key
		public function get($key) {
			if(isset($_SESSION[$key])) {
				return $_SESSION[$key];
			} else {
				return false;
			}
		}
		
		// Remove a particular session key
		public function remove($key) {
			$_SESSION[$key] = '';
			unset($_SESSION[$key]);
		}
		
		// Destroy all data in the session and then destroy the session content
		public function destroy() {
			// Unset all session keys
			session_unset();
			// Set the $_SESSION as an empty array
			$_SESSION = array();
			// Destroy the session
			session_destroy();
		}
		
		// Used to output a one-time message from $_SESSION['message'] and then delete it to avoid replication
		public function output_message() {
			// First check if there is a message
			if($this->get('message')) {
				// Output the contents of the $_SESSION['message']
				echo $this->get('message');
				// Remove any contents of the message to avoid duplication
				$this->remove('message');
			}
		}
		
		// Construct a Bootstrap alert using a message, and a message type to determine colour/type of message
		public function message_alert($message_content = 'An alert has been called, but not specified!', $message_type = 'warning') {
			// The type of message, determining the colour/type of the alert
			switch($message_type){
				case "success":
					$message = "<div class=\"alert alert-success\" role=\"alert\">";
					break;
				case "info":
					$message = "<div class=\"alert alert-info\" role=\"alert\">";
					break;
				case "warning":
					$message = "<div class=\"alert alert-warning\" role=\"alert\">";
					break;
				case "danger":
				default:
					$message = "<div class=\"alert alert-danger\" role=\"alert\">";
					break;
			};
			
			// Add the content of the message
			$message .= $message_content;
			$message .= "</div>";
			
			// Set the message in the session
			$this->set('message', $message);
		}
		
		// Construct a Bootstrap alert using an array of errors to build a validation failure message
		public function message_validation($errors = array()) {
			// Cycle through an array of validation errors to display a validation failured message to the screen
			$alert = "<div class=\"alert alert-danger\" role=\"alert\">";
			$alert .= "<p>The form was unable to be submitted due to the following errors:<p>";
			$alert .= "<ol>";
			foreach($errors as $error) {
				$alert .= "<li>" . $error . "</li>";
			}
			$alert .= "</ol>";
			$alert .= "<p>Please correct these errors and then try again.</p>";
			$alert .= "</div>";
			
			// Set the validation errors in the session
			$this->set('message', $alert);
		}
		
		// Used to store particular details about the user in the session
		private function obtain_user_details() {
			// Check if the users IP address has been logged
			if(!$this->get('user_ip')) {
				// Log the users IP address
				$this->set('user_ip', $_SERVER['REMOTE_ADDR']);
			}
			// Check if the users HTTP agent has been logged
			if(!$this->get('user_agent')) {
				// Log the users HTTP agent
				$this->set('user_agent', $_SERVER['HTTP_USER_AGENT']);
			}
		}
		
	}; // Close class Session
	
// EOF