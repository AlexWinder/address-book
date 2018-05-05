<?php

	// Contains all information relating to the session
		
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