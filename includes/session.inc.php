<?php

	// Contains all information relating to the session
	
	// Start session
	session_start();
	
	// All session functions
	
	// Display a message if it exists in the session
	function session_message() {
		if(isset($_SESSION["message"])) {
			echo $_SESSION["message"];
			// Clear message after use
			$_SESSION["message"] = null;
		};
	};
	
	// Construct a Bootstrap alert using a message, and a message type to determine colour/type of message
	function construct_message($message_content="An alert has been called, but not specified!", $message_type="warning") {
		// The type of message, determining the colour
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
				$message = "<div class=\"alert alert-danger\" role=\"alert\">";
				break;
		};
		// Display the actual message
		$message .= $message_content;
		$message .= "</div>";
		
		return $message;
	};

?>