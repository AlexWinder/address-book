<?php
	// This class is used for redirecting users to particular pages
	class Redirect {
		
		// Static function used to redirect users to a particular page
		public static function to($location = null) {
			// Check that a $location has been sent
			if($location) {
				// Redirect the user
				header('Location: ' . $location);
				exit;
			}
		}
		
	}; // Close class Redirect

// EOF