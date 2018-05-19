<?php
	class API {
		// API class used when a user makes an API call
		
		private $db = null, // Used to store an instance of the database
				$result_messages = array( // All result messages from the different types of API call
					'incomplete' => 'An incomplete API call was made. Please follow the documentation to ensure that you are sending all the required settings.',
					'invalid_token' => 'An invalid token was sent. This means that the token does not exist or you are making an API call from an unauthorised IP address.',
				);
				
		public	$success = 0, // When an API call is valid this will be 1
				$method = null, // The method used as part of the API call
				$query = null, // The query of the API call which relates to the $method
				$result = null, // The result of the API call, if any
				$result_message = null; // The result of the API call, if any
				
		// Constructor
		public function __construct($token = null, $method = null, $query = null) {
			// Obtain an instance of the database
			$this->db = DB::get_instance();
			// Process the submitted API call
			$this->call($token, $method, $query);
		}
		
		// Method to call API
		private function call($token, $method, $query){
			// Check if token, method and query have been submitted
			if($token && $method && $query) {
				// Check if $token is valid
				if($this->check_token($token)) {
					
				} else {
					// $token is not valid
					$this->result = $this->result = 'invalid_token';
					$this->result_message = $this->result_messages[$this->result];
				}
			} else {
				// Not set, return incomplete API call
				$this->result = $this->result = 'incomplete';
				$this->result_message = $this->result_messages[$this->result];
			}
		}
		
		// Method to check if a token is valid or not
		private function check_token($token = null) {
			// Check if token was sent
			if($token) {
				// Begin prepared statement to find single ID in database
				$sql = '
					SELECT * FROM api 
					WHERE api_id = :api_id
				';
				$stmt = $this->db->prepare($sql);
				
				// Pass in the $id into the prepared statement and execute
				$stmt->bindParam(':api_id', $token);
				$stmt->execute();
				
				// Fetch the results from the prepared statement
				$result = $stmt->fetch();
				
				// Check if an API token could be found
				if($result) {
					// Check if the IP address authorised against the API call matches that of the end user making the API call
					// Also, if IP address is empty then accept any IP address
					if(empty($result['ip']) || ($result['ip'] == $_SERVER['REMOTE_ADDR'])) {
						// Token is correct
						return true;
					} else {
						// IP address doesn't match
						return false;
					}
				} else {
					// API token couldn't be found
					return false;
				}
			} else {
				// Token not sent
				return false;
			}
		}
		
	} // Close class API
// EOF