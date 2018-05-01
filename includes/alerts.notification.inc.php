<?php
	
	// Array of messages to be displayed to the user for various scenarios, such as wrong password, page not found, timeout, etc.
	$notification = array (
		
		// Notifications which display when a user logs in
		"login" => array (
			"success" => "You have been logged in successfully.",
			"failure" => "Username/password combination not found."
		),
		
		// Notifications relating to users which have access to the system
		"user" => array (
			"add" => array (
				"success" => "New user has been successfully added.",
				"failure" => "There was an error adding a user. Please check all fields and try again",
				"duplicate" => "That username already exists. Please choose a different username.",
			),
			"delete" => array (
				"success" => "User successfully deleted.",
				"failure" => "There was an error deleting a user.",
				"not_found" => "The user you have tried to delete could not be found in the database.",
				"self" => "You are unable to delete yourself from the system.",
			),
			"update" => array (
				"name" => array (
					"success" => "Full name/username has been updated successfully.",
					"failure" => "There was an error updating the name/username. Please check all fields and try again",
					"duplicate" => "Username already exists. Please choose a different username.",
				),
				"password" => array (
					"success" => "Password successfully updated.",
					"failure" => "There was an error updating the password. Please check all fields and try again",
				),
				"not_found" => "The user you are trying to update could not be found. Please check and try again.",
			),
			
		),
		
		// Notifications relating to contacts in the address book
		"contact" => array (
			
			// Adding contacts to the address book
			"add" => array (
				"success" => "New contact has been successfully added.",
				"failure" => "Contact could not be created. Please check all fields and then try again. If you continue to see this message please contact a system administrator.",
			),
			
			// Updating existing contacts in the address book
			"update" => array (
				"success" => "Contact has been successfully updated.",
				"failure" => "Contact could not be updated. Please check all fields and try again.",
				"not_found" => "The contact you are trying to update could not be found. Please check and try again.",
			),
			
			// Deleting a contact from the address book
			"delete" => array (
				"success" => "Contact has been successfully deleted.",
				"failure" => "There was an error deleting a contact. Please check and try again.",
				"not_found" => "The contact you are trying to delete could not be found. Please check and try again."
			),
			
			// Viewing contacts in the address book
			"view" => array (
				"not_found" => "The contact you are searching for could not be found. Please check and try again. If you continue to see this message please contact a system administrator."
			)
		),
		
		// Items relating to log messages, primarily if a log has failed to be added
		"log" => array (
			"add" => array (
				"failure" => "There was an issue with logging your actions to the system. Please contact a system administrator.",
			),
		),
		
		// Items relating to the user currently logged in via authenticated.inc.php
		"authenticate" => array (
			"not_found" => "Your user account doesn't appear to exist in the database anymore.",
			"details_changed" => "Your user account information has changed. Please log in to reverify yourself.",
			"agent_changed" => "Your device appears to have changed. Please log in to reverify yourself.",
			"ip_changed" => "Your IP address appears to have changed. Please log in to reverify yourself.",
			// Page time out - likely caused by making no actions on the system but being logged in
			"timeout" => "Your session has expired. Please log in to reverify yourself on the system.",
		)
		
		
	);

?>