<?php

	// Requires constants to be set inside settings.config.inc.php

	// Create a database connection
	$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	// Test if connection succeeded
	if(mysqli_connect_errno()) {
		die(
		"Database connection failed: " . mysqli_connect_error()
		);
	}
	

?>