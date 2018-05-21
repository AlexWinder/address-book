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
					'findNumber' => 'Obtain the first contact found based on a queried phone number. Note that if more than one contact has the same phone number this will only return the first, based on last name in alphabetical order. Example, ' . PAGELINK_API . '?t=APITOKEN&m=findNumber&q=0987654321 will return the result (if it exists) for the phone number 0987654321.'
				),
				$array_result = null; // Used to build a JSON format to return a result
		
		// Properties relating to when an API token is looked up
		public	$found = false, // When an API token is found
				$token = null, // The API tokens ID
				$name = null, // The API token cosmetic name - if any
				$ip = null; // The IP address the API token is restricted to - if any

		// Constructor
		public function __construct($token = null, $method = null, $query = null) {
			// Obtain an instance of the database
			$this->db = DB::get_instance();
			// Check if $token sent
			if($token) {
				// Process the submitted API call
				$this->call($token, $method, $query);
			};
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
							
							// Create new Log instance, and log the action to the database
							$log = new Log('api_call_success', 'Token (' . $token . ') called Method (' . $method . ') with Query (' . $query . ')');
						} else {
							// No result could be found
							$this->result = 'no_result';
							$this->result_message = $this->result_messages[$this->result];
							
							// Create new Log instance, and log the action to the database
							$log = new Log('api_call_failed', 'Token (' . $token . ') called Method (' . $method . ') with Query (' . $query . ') - No Result');
						}
					} else {
						// $method is not valid
						$this->result = $this->result = 'invalid_method';
						$this->result_message = $this->result_messages[$this->result];
						
						// Create new Log instance, and log the action to the database
						$log = new Log('api_call_failed', 'Token (' . $token . ') called Method (' . $method . ') with Query (' . $query . ') - Invalid Method');
					}
				} else {
					// $token is not valid
					$this->result = $this->result = 'invalid_token';
					$this->result_message = $this->result_messages[$this->result];
					
					// Create new Log instance, and log the action to the database
					$log = new Log('api_call_failed', 'Token (' . $token . ') called Method (' . $method . ') with Query (' . $query . ') - Invalid Token');
				}
			} else {
				// Not set, return incomplete API call
				$this->result = $this->result = 'incomplete';
				$this->result_message = $this->result_messages[$this->result];
				
				// Create new Log instance, and log the action to the database
				$log = new Log('api_call_failed', 'Invalid Query - Token and/or Method and/or Query not sent');
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

		// Method to find specific API token in the database
		public function find_id($id = null) {
			// Check if $id has been sent
			if($id) {
				// Begin prepared statement to find single ID in database
				$sql = '
					SELECT * FROM api 
					WHERE api_id = :api_id
				';
				$stmt = $this->db->prepare($sql);
				
				// Pass in the $id into the prepared statement and execute
				$stmt->bindParam(':api_id', $id);
				$stmt->execute();
				
				// Fetch the results from the prepared statement
				$result = $stmt->fetch();
				
				// Check if a API token could be found
				if($result) {
					// API token found
					$this->found = true;
					// Set properties relating to the API token
					$this->token = $result['api_id'];
					$this->name = $result['cosmetic_name'];
					$this->ip = $result['ip'];
				} else {
					// Contact not found, return false
					return false;
				}
			} else {
				// $id not sent, return false
				return false;
			}
		}

		// Method to delete a particular API token
		public function delete() {
			// This method will only be called if a find_id of an API has been searched first - such as $api->find_id('ABCDEFGHIJKL');
			if($this->found) {
				// Begin prepared statement to delete a single ID from the database
				$sql = '
					DELETE FROM api 
					WHERE api_id = :api_id 
					LIMIT 1
				';
				$stmt = $this->db->prepare($sql);

				// Pass in the $token into the prepared statement and execute
				$stmt->bindParam(':api_id', $this->token);

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

		// Method to update a particular contact
		public function update($values = array()) {
			// This method will only be called if used from a search of a particular ID during instantiation, such as such as $api->find_id('ABCDEFGHIJKL');
			if($this->found) {
				// This method works by accepting a $values array which contains the details of the fields which are to be updated
				// Check that the array isn't empty
				if(!empty($values)) {
					// Array has values, begin building the SQL query to be used to update API token
					$sql = "UPDATE api SET ";
					
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
					
					// Specify which API token to update and limit to update only 1 record as a fail-safe
					$sql .= "WHERE api_id = :api_id ";
					$sql .= "LIMIT 1";
					
					// Begin a prepared statement using the previous $sql
					$stmt = $this->db->prepare($sql);
					
					// Pass in values from the $values array to complete the prepared statement
					foreach($values as $key => &$value) {
						$stmt->bindParam(':' . $key, $value);
					}
					// Bind the API token ID to the prepared statement
					$stmt->bindParam(':api_id', $this->token);
					
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
		
		// Method to generate a new API token
		public function generate_token($token_length) {
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
		
		// Method to check if submitted IP address is in IP address format
		public function check_ip($ip) {
			return filter_var($ip, FILTER_VALIDATE_IP);
		}
		
		// Method to create a new user
		public function create($values = array()) {
			// This method works by accepting a $values array which contains the details of the fields which are to be inserted
			// Check that the array isn't empty
			if(!empty($values)) {
				// Array has values, begin building the SQL query to be used to create user
				$sql = "INSERT INTO api (";
				
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
				$stmt = $this->db->prepare($sql);
				
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
		
	} // Close class API
// EOF