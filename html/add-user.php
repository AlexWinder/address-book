<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_USERS;
	// Set $subpage_name as this page isn't the main section
	$subpage_name = PAGENAME_USERSADD;
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	}; // Close if(!$user->authenticated)
	
	// Obtain a CSRF token to be used to prevent CSRF - this is stored in the $_SESSION
	$csrf_token = CSRF::get_token();
	
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
		
		// Check that the submitted CSRF token is the same as the one in the $_SESSION to prevent cross site request forgery
		if(!CSRF::check_token($_POST['csrf_token']))									{ $errors[] = $validation['invalid']['security']['csrf_token']; };
		
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
			
			// Check if user submitted username already exists in the database
			$username_exists = $user->find_username($_POST['username']);
			
			if(!$username_exists) {
				
				// Prepare an array to be used to insert into the database
				$fields = array();
				
				// Populate the $fields array with values where applicable
				!empty($_POST['username']) 					? $fields['username'] = $_POST['username']									: $fields['username'] = null;
				!empty($_POST['full_name']) 				? $fields['full_name'] = $_POST['full_name'] 								: $fields['full_name'] = null;
				!empty($_POST['password']) 					? $fields['hashed_password'] = $user->password_encrypt($_POST['password']) 	: $fields['hashed_password'] = null;
				
				// Create the new contact, inserting the fields from the $fields array
				$result = $user->create($fields);
				
				// Test whether the query was successful
				if($result){
					// User successfully added to the database
					// Create new Log instance, and log the action to the database
					$log = new Log('user_add_success', 'User of ' . $fields['full_name'] . ' [' . $fields['username'] . '] successfully created.');
					// Set session message
					$session->message_alert($notification["user"]["add"]["success"], "success");
					// Redirect the user
					Redirect::to(PAGELINK_INDEX);
				} else {
					// Set session message
					$session->message_alert($notification["user"]["add"]["failure"], "danger");
					// Log action of database entry failing
					// Create new Log instance, and log the action to the database
					$log = new Log('user_add_failed', 'database');
				};
				
			} else {
				// Username already exists in the database
				// Set session message
				$session->message_alert($notification["user"]["add"]["duplicate"], "danger");
				// Log action of failing form process
				// Create new Log instance, and log the action to the database
				$log = new Log('user_add_failed', 'Failed user add due to username [' . $_POST['username'] . '] already exists.');
			};
			
		} else {
			// Form field validation has failed - $errors array is not empty
			// If there are any error messages in the $errors array then display them to the screen
			$session->message_validation($errors);
			// Create new Log instance, and log the action to the database
			$log = new Log('user_add_failed', 'Failed user add due to form validation errors.');
		};
		
	} else {
		// Form has not been submitted
		// Log action of accessing the page
		// Create new Log instance, and log the page view to the database
		$log = new Log('view');
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
				
				<input type="hidden" name="csrf_token" value="<?php echo htmlentities($csrf_token); ?>"/>
				
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