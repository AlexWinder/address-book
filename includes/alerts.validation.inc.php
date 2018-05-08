<?php

	// Array of messages to be displayed when a form validation fails, such as first name exceeds number of characters, or password length is too short
	$validation = array (
		
		// Required fields
		"field_required" => array (
			// Contact related fields
			"contact" => array (
				"first_name" => "First name is a required field. Please enter a first name.",
				"last_name" => "Last name is a required field. Please enter a last name.",
				"address_line_1" => "Address line 1 is a required field. Please enter an address.",
				"address_town" => "Address town is a required field. Please enter a town.",
				"address_county" => "Address county is a required field. Please enter a county.",
				"address_post_code" => "Post code is a required field. Please enter a post code.",
				
				// Delete a contact
				"confirm_delete" => "You did not confirm that you would like to delete this contact.",
			),
			
			// User account related fields
			"user" => array(
				"username" => "Username is a required field. Please enter a username.",
				"full_name" => "Full name is a required field. Please enter a full name.",
				"password" => "Password is a required field. Please enter a password.",
				"confirm_password" => "Confirm password is a required field. Please enter a confirmed password.",
				
				// Delete a user
				"confirm_delete" => "You did not confirm that you would like to delete this user.",
			),
		),
		
		// Field are of an invalid format or match
		'invalid' => array(
			'security' => array(
				'csrf_token' => 'Your form submission has been blocked due to an failing a CSRF security check. Please refresh the page and try again.',
			),
		),
		
		// Field lengths are too long
		"too_long" => array (
			// Contact related fields
			"contact" => array (
				"first_name" => "The length of the first name field must not exceed 50 characters.",
				"middle_name" => "The length of the middle name field must not exceed 50 characters.",
				"last_name" => "The length of the last name field must not exceed 50 characters.",
				"contact_number_home" => "The length of the home contact number field must not exceed 20 characters.",
				"contact_number_mobile" => "The length of the mobile contact number field must not exceed 20 characters.",
				"contact_email" => "The length of the contact email field must not exceed 100 characters.",
				"address_line_1" => "The length of the address line 1 field must not exceed 100 characters.",
				"address_line_2" => "The length of the address line 2 field must not exceed 100 characters.",
				"address_town" => "The length of the town field must not exceed 100 characters.",
				"address_county" => "The length of the country field must not exceed 100 characters.",
				"address_post_code" => "The length of the post code field must not exceed 100 characters.",
			),
			
			// User account related fields
			"user" => array (
				"username" => "The length of the username field must not exceed 100 characters.",
				"full_name" => "The length of the full name field must not exceed 100 characters.",
			),
		),
		
		// Field lengths are too short
		"too_short" => array (
			// User account related fields
			"user" => array (
				"password" => "Your password must be a mimum of 8 characters in length.",
			),
		),
		
		// Password related validations
		"password" => array (
			"no_match" => "The passwords you have supplied do not match.",
			"no_uppercase" => "Your password must contain at least 1 upper case character (A-Z).",
			"no_lowercase" => "Your password must contain at least 1 lower case character (a-z).",
			"no_numeric" => "Your password must contain at least 1 lower case character (0-9).",
		),

	);
	
	// Create a bootstrap error message if an array of errors is sent to the function
	function validation_failure_message($messages_array) {
		$alert = "<div class=\"alert alert-danger\" role=\"alert\">";
		$alert .= "<p>The form was unable to be submitted due to the following errors:<p>";
		$alert .= "<ol>";
		foreach($messages_array as $message) {
			$alert .= "<li>" . $message . "</li>";
		}
		$alert .= "</ol>";
		$alert .= "<p>Please correct these errors and then try again.</p>";
		$alert .= "</div>";
		
		return $alert;
	};
?>