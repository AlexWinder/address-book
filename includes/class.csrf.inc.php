<?php
	// This class is used in the prevention of cross site request forgery
	class CSRF {
		
		// Obtain the token from the $_SESSION
		public static function get_token() {
			
			// Bring in the $session variable
			global $session;
			// Check if there is a csrf_token in the $_SESSION
			if(!$session->get('csrf_token')) {
				// Token doesn't exist, create one
				self::set_token();
			} 
			// Return the token
			return $session->get('csrf_token');
		}
		
		// Used to check a submitted token with a token stored in the session
		public static function check_token($submitted_token) {
			// Check if a token was submitted
			if($submitted_token) {
				// Token was submitted
				// Bring in the session variable
				global $session;
				
				// Check if the submitted token matches the one in the database
				if($submitted_token == $session->get('csrf_token')) {
					// Token is the same
					return true;
				} else {
					// Token is not the same
					return false;
				}
			} else {
				// Token wasn't submitted
				return false;
			}
		}
		
		// Used to set a token in the session if one couldn't be found
		private static function set_token() {
			// Bring in the $session variable
			global $session;
			// Generate a random token of 64 length
			$token = self::generate_token(64);
			// Store it in the $_SESSION
			$session->set('csrf_token', $token);
		}
		
		// Used to generate a token
		private static function generate_token($token_length) {
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
		
	}; // Close class
// EOF