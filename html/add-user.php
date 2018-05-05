<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Check that the user is logged in
	require_once("../includes/authenticated.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_USERS;
	$subpage_name = "Add User";
	
	// If submit button has been pressed then process the form
	if(isset($_POST["submit"]) && $_POST["submit"] == "submit") {
		
		// Validate all fields and ensure that required fields are submitted
		
		// Initialise the $errors array where errors will be sent and then retrieved from
		$errors = array();
		
		// Required fields, if a field is not present or empty then populate the $errors array
		if(!isset($_POST["username"]) 			|| empty($_POST["username"])) 			{ $errors[] = $validation["field_required"]["user"]["username"]; };
		if(!isset($_POST["full_name"]) 			|| empty($_POST["full_name"])) 			{ $errors[] = $validation["field_required"]["user"]["full_name"]; };
		if(!isset($_POST["password"]) 			|| empty($_POST["password"])) 			{ $errors[] = $validation["field_required"]["user"]["password"]; };
		if(!isset($_POST["confirm_password"]) 	|| empty($_POST["confirm_password"])) 	{ $errors[] = $validation["field_required"]["user"]["confirm_password"]; };
		
		// Length of fields
		$length_username 			= 		strlen($_POST["username"]);
		$length_full_name 			= 		strlen($_POST["full_name"]);
		$length_password 			= 		strlen($_POST["password"]);
		$length_confirm_password 	= 		strlen($_POST["confirm_password"]);
		
		// Named fields musn't be longer than length in the database, if they are then populate the $errors array
		if($length_username > 100) 		{ $errors[] = $validation["too_long"]["user"]["username"]; };
		if($length_username > 100) 		{ $errors[] = $validation["too_long"]["user"]["full_name"]; };
		
		// Password validation
		// Password must be at least 8 characters in length
		if($length_password < 8) 		{ $errors[] = $validation["too_short"]["user"]["password"]; };
		// Password must be the same as the confirmed password
		if($_POST["password"] !== $_POST["confirm_password"]) 	{ $errors[] = $validation["password"]["no_match"]; };
		
		// Password must contain at least 1 lower case character (a-z)
		if(preg_match("~[a-z]~", $_POST["password"]) == 0) { $errors[] = $validation["password"]["no_lowercase"]; };
		// Password must contain at least 1 upper case character (A-Z)
		if(preg_match("~[A-Z]~", $_POST["password"]) == 0) { $errors[] = $validation["password"]["no_uppercase"]; };
		// Password must contain at least 1 numeric character (0-9)
		if(preg_match("~[0-9]~", $_POST["password"]) == 0) { $errors[] = $validation["password"]["no_numeric"]; };
		
		// If no errors have been found during the field validations
		if(empty($errors)) {
			
			// Give each value in the form it's own variable if it is submitted
			// Variable used to submit to the database
			!empty($_POST["username"]) 		? $form_username = mysql_prep($_POST["username"]) 					: $form_username = null;
			!empty($_POST["full_name"]) 	? $form_full_name = mysql_prep($_POST["full_name"]) 				: $form_full_name = null;
			!empty($_POST["password"])		? $form_processed_password = password_encrypt($_POST["password"])	: $form_processed_password = null;
			$id = generate_key(11);
			
			// Check if user submitted username already exists in the database
			$username_exists = find_user_by_username($form_username);
			
			if(!$username_exists) {
				
				// Create SQL query to input into the database
				$sql = "INSERT INTO users (";
				$sql .= "user_id, username, hashed_password, full_name";
				$sql .= ") VALUES (";
				$sql .= "'{$id}', '{$form_username}', '{$form_processed_password}', '{$form_full_name}'";
				$sql .= ")";
				
				// Run the database query
				$result = mysqli_query($db, $sql);
				
				// Test whether the query was successful
				if($result){
					// User successfully added to the database
					// Log action of add entry success, with contact added 
					log_action("add_success", "User added: " . $form_full_name . " [" . $form_username . "]");
					
					$_SESSION["message"] = construct_message($notification["user"]["add"]["success"], "success");
					redirect_to("users.php");
				} else {
					$_SESSION["message"] = construct_message($notification["user"]["add"]["failure"], "danger");
					// Log action of database entry failing
					log_action("add_failed", $logging["database"]["failure"]);
				};
				
			} else {
				// Username already exists in the database
				$_SESSION["message"] = construct_message($notification["user"]["add"]["duplicate"], "danger");
				
				// Log action of failing form process
				$log_errors = log_validation_failures($errors);
				log_action("add_failed", "Username of " .  $form_username . " already exists.");
			};
			
		} else {
			// Form field validation has failed - $errors array is not empty
			// If there are any error messages in the $errors array then display them to the screen
			$_SESSION["message"] = validation_failure_message($errors);
			
			// Log action of failing form process
			$log_errors = log_validation_failures($errors);
			log_action("add_failed", $log_errors);
		};
		
	} else {
		// Form has not been submitted
		// Log action of accessing the page
		log_action("view");
	};
	
	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");
?>
	
			<!-- CONTENT -->
			<?php $session->output_message(); ?>
			
			<form class="form-horizontal" action="" method="post">
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Full Name</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="full_name" placeholder="Full Name" maxlength="100" <?php if(isset($_POST["full_name"])){ echo "value=\"" . htmlentities($_POST["full_name"]) . "\""; }; ?> required>
					</div>
				</div>
				
				<hr>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Username</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="username" placeholder="Username" maxlength="100" <?php if(isset($_POST["username"])){ echo "value=\"" . htmlentities($_POST["username"]) . "\""; }; ?> required>
					</div>
				</div>
				
				<hr>
				
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-11">
						<p>Passwords <strong>MUST</strong> comply with the following rules:</p>
						<ul>
							<li>Password must be a minimum of 8 characters in length.</li>
							<li>Password must contain at least 1 upper-case character (A-Z).</li>
							<li>Password must contain at least 1 lower-case character (a-z).</li>
							<li>Password must contain at least 1 numeric character (0-9).</li>
						</ul>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Password</label>
					<div class="col-sm-4">
						<input type="password" class="form-control" name="password" placeholder="Password" required>
					</div>
					
					<label class="col-sm-2 control-label">Confirm Password</label>
					<div class="col-sm-4">
						<input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
					</div>
				</div>
				
				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" name="submit" value="submit" class="btn btn-default">Submit</button>
					</div>
				</div>
			</form>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>