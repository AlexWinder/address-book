<?php
	class API {
		// API class used when a user makes an API call
		
		private $db = null, // Used to store an instance of the database
				$result_messages = array( // All result messages from the different types of API call
					'incomplete' => 'An incomplete API call was made. Please follow the documentation to ensure that you are sending all the required settings.',
					'invalid_token' => 'An invalid API token was sent. This means that the token does not exist or you are making an API call from an unauthorised IP address.',
					'invalid_method' => 'An invalid API method was requested. Please follow the documentation and check your requested method exists, this includes correct spelling and upper/lower case characters.',
					'no_result' => 'A result could not be found.',
					'success' => 'API call successful.'
				);
		
		// Properties relating to when an API call is made
		public	$success = 0, // When an API call is valid this will be 1
				$method = null, // The method used as part of the API call
				$query = null, // The query of the API call which relates to the $method
				$result = null, // The result of the API call, if any
				$result_message = null, // The result of the API call, if any
				$available_methods = array( // The different types of methods available, with their descriptions
					'findNumber' => 'Obtain the first contact found based on a queried phone number. Note that if more than one contact has the same phone number this will only return the first, based on last name in alphabetical order.'
				),
				$array_result = null; // Used to build a JSON format to return a result

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
					// Check if the $method is valid
					if($this->is_method($method)) {
						// Set properties
						$this->method = $method;
						$this->query = $query;

						// Execute the $method with the query
						if($result = $this->execute_method($method, $query)) {
							// Successful API call
							$this->success = 1;
							$this->result = $result;
							$this->result_message = $this->result_messages['success'];
						} else {
							// No result could be found
							$this->result = 'no_result';
							$this->result_message = $this->result_messages[$this->result];
						}
					} else {
						// $method is not valid
						$this->result = $this->result = 'invalid_method';
						$this->result_message = $this->result_messages[$this->result];
					}
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
			
			// Build an array which will be used to output the main details of the API call
			$this->array_result = array(
				'success' => $this->success,
				'method' => $this->method,
				'query' => $this->query,
				'result' => $this->result,
				'result_message' => $this->result_message
			);
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
		
		// Method to check if a method submitted as part of an API call is valid
		private function is_method($method) {
			// Check if $method has been sent
			if($method) {
				// Check if $method is valid
				return array_key_exists($method, $this->available_methods);
			} else {
				// Method hasn't been sent
				return false;
			}
		}
		
		// Method to execute a method as part of an API call
		private function execute_method($method = null, $query = null) {
			// Check if $method and $query have both been sent
			if($method && $query) {
				// Use switch to run different methods based on the $method
				switch($method) {
					case 'findNumber' :
						// Method to find a single contact based on a number
						$contact = new Contact();
						return $contact->find_number($query);
						break;
				}
			} else {
				// $method and $query not sent
				return false;
			}
		}
		
		// Method to find all api tokens in the database
		public function find_all() {
			// Return all 
			return $this->all = $this->db->query('SELECT * FROM api', PDO::FETCH_ASSOC);
		}
		
	} // Close class API
// EOF