<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('security_failed');
	}; // Close if(!$user->authenticated)
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_USERS;

	// If the value of i in GET exists
	if(isset($_GET['i'])) {
		// Find user in database
		$found_user = $user->find_id($_GET['i']);
		
		// If a user is found in the database
		if($found_user) {
			
			// Create a variable to store the user full name - used in the page name
			$user_full_name = htmlentities($found_user["full_name"] . " [" . $found_user["username"] . "]");
			
			// Set page name as user could be found
			$subpage_name = $user_full_name . " - Update User";
		
			// Obtain a CSRF token to be used to prevent CSRF - this is stored in the $_SESSION
			$csrf_token = CSRF::get_token();
			
			// Assign all the various database values to their own variables
			// First check if value has been sent in $_POST, if not then check if it exists in the database, if not then assign as null
			if(!empty($_POST["username"]) && isset($_POST["username"])) 		{ $form_username = htmlentities($_POST["username"]); } 			elseif(!empty($found_user["username"]) && isset($found_user["username"])) 			{ $form_username = htmlentities($found_user["username"]); } 			else { $form_username = null; };
			if(!empty($_POST["full_name"]) && isset($_POST["full_name"])) 		{ $form_full_name = htmlentities($_POST["full_name"]); } 		elseif(!empty($found_user["full_name"]) && isset($found_user["full_name"])) 		{ $form_full_name = htmlentities($found_user["full_name"]); } 		else { $form_full_name = null; };
			
			// If submit button for updating name/username has been pressed then process the form
			if(isset($_POST["submit_name"]) && $_POST["submit_name"] == "submit_name") {
				// Validate all fields and ensure that required fields are submitted
				
				// Initialise the $errors array where errors will be sent and then retrieved from
				$errors = array();
				
				// Required fields, if a field is not present or empty then populate the $errors array
				if(!isset($_POST["username"]) 			|| empty($_POST["username"])) 			{ $errors[] = $validation["field_required"]["user"]["username"]; };
				if(!isset($_POST["full_name"]) 			|| empty($_POST["full_name"])) 			{ $errors[] = $validation["field_required"]["user"]["full_name"]; };
				
				// Check that the submitted CSRF token is the same as the one in the $_SESSION to prevent cross site request forgery
				if(!CSRF::check_token($_POST['csrf_token']))									{ $errors[] = $validation['invalid']['security']['csrf_token']; };
				
				// Length of fields
				$length_username 			= 		strlen($_POST["username"]);
				$length_full_name 			= 		strlen($_POST["full_name"]);
				
				// Named fields musn't be longer than length in the database, if they are then populate the $errors array
				if($length_username > 100) 		{ $errors[] = $validation["too_long"]["user"]["username"]; };
				if($length_username > 100) 		{ $errors[] = $validation["too_long"]["user"]["full_name"]; };
				
				// Check whether the new username is different from the current username
				if($_POST["username"] != $found_user["username"]) {
					// If it is then check if the new username already exists in the database
					if($user->find_username($_POST["username"])) {
						// Username already exists in the database
						$errors[] = $notification["user"]["update"]["name"]["duplicate"];
					};
				};
				
				// If no errors have been found during the field validations
				if(empty($errors)) {
					// Begin an array to store values to update the database
					$update_values = array();
					
					// Assign values to an array which will be used as part of the update
					if(isset($_POST['username']) && !empty($_POST["username"])) 						{ $update_values['username'] = $_POST['username']; } else { $update_values['username'] = null; };
					if(isset($_POST['full_name']) && !empty($_POST["full_name"])) 						{ $update_values['full_name'] = $_POST['full_name']; } else { $update_values['full_name'] = null; };
					
					// Execute the update
					$result = $user->update($update_values, $found_user['user_id']);
					
					// Check if the update was successful
					if($result){
						// User full name/username successfully updated on the database
						// Set session message
						$session->message_alert($notification["user"]["update"]["name"]["success"], "success");
						// Log action of add entry success, with user updated 
						// Create new Log instance, and log the action to the database
						$log = new Log('user_update_success', 'Details updated from ' . $found_user['full_name'] . ' [' . $found_user['username'] . '] to ' . $update_values['full_name'] . ' [' . $update_values['username'] . '] (' . $found_user['user_id'] . ')');
						// Redirect the user
						redirect_to("users.php");
					} else {
						// Set session message
						$session->message_alert($notification["user"]["update"]["name"]["failure"], "danger");
						// Log action of database entry failing
						// Create new Log instance, and log the action to the database
						$log = new Log('user_update_failed', 'database_details');
					};
					
				} else {
					// Form field validation has failed - $errors array is not empty
					// If there are any error messages in the $errors array then display them to the screen
					$session->message_validation($errors);
					// Log action of failing form process
					// Create new Log instance, and log the action to the database
					$log = new Log('user_update_failed', 'Failed user update due to form validation errors when updating details.');
				};
			}; // No submit button to change the full name/username of the user
			
			// If submit button for updating password has been pressed then process the form
			if(isset($_POST["submit_password"]) && $_POST["submit_password"] == "submit_password") {
				// Validate all fields and ensure that required fields are submitted
				
				// Initialise the $errors array where errors will be sent and then retrieved from
				$errors = array();
				
				// Required fields, if a field is not present or empty then populate the $errors array
				if(!isset($_POST["password"]) 			|| empty($_POST["password"])) 			{ $errors[] = $validation["field_required"]["user"]["password"]; };
				if(!isset($_POST["confirm_password"]) 	|| empty($_POST["confirm_password"])) 	{ $errors[] = $validation["field_required"]["user"]["confirm_password"]; };
				
				// Length of fields
				$length_password 			= 		strlen($_POST["password"]);
				$length_confirm_password 	= 		strlen($_POST["confirm_password"]);
				
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
					// Begin an array to store values to update the database
					$update_values = array();
					
					// Assign values to an array which will be used as part of the update
					if(isset($_POST['password']) && !empty($_POST['password'])) 						{ $update_values['hashed_password'] = $user->password_encrypt($_POST['password']); } else { $update_values['hashed_password'] = null; };
					
					// Execute the update
					$result = $user->update($update_values, $found_user['user_id']);
					
					// Check if the update was successful
					if($result){
						// User password successfully updated on the database
						// Set session message
						$session->message_alert($notification["user"]["update"]["password"]["success"], "success");
						// Log action of add entry success, with user updated 
						// Create new Log instance, and log the action to the database
						$log = new Log('user_update_success', 'Password updated for ' . $found_user['full_name'] . ' [' . $found_user['username'] . '] (' . $found_user['user_id'] . ')');
						// Redirect the user
						redirect_to("users.php");
					} else {
						// Set session message
						$session->message_alert($notification["user"]["update"]["password"]["failure"], "danger");
						// Log action of database entry failing
						// Create new Log instance, and log the action to the database
						$log = new Log('user_update_failed', 'database_password');
					};
					
				} else {
					// Form field validation has failed - $errors array is not empty
					// If there are any error messages in the $errors array then display them to the screen
					$session->message_validation($errors);
					// Create new Log instance, and log the action to the database
					$log = new Log('user_update_failed', 'Failed user update due to form validation errors when updating password.');
				};
			};// No submit button to change the password of the user
			
			// User has accessed the page and not sumitted the form
			// Create new Log instance, and log the page view to the database
			$log = new Log('view');
		
		} else {
			// User could not be found in the database
			// Set $subpage_name so that the title of each page is correct - user couldn't be found
			$subpage_name = 'User Not Found - Update User';
			// Send session message and redirect
			$session->message_alert($notification["user"]["update"]["not_found"], "danger");
			// Create new Log instance, and log the action to the database
			$log = new Log('not_found');
			// Redirect the user
			redirect_to("users.php");
		};
	} else {
		// Value of i in GET doesn't exist
		// Set $subpage_name so that the title of each page is correct - GET value not correct
		$subpage_name = 'Invalid GET Value - Update User';
		// Send session message and redirect
		$session->message_alert($notification["user"]["update"]["not_found"], "danger");
		// Create new Log instance, and log the action to the database
		$log = new Log('not_found');
		// Redirect the user
		redirect_to("users.php");
	};
	
	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");
?>
	
			<!-- CONTENT -->
			<?php $session->output_message(); ?>
			
			
			<form class="form-horizontal" action="" method="post">
				<div class="col-sm-offset-1">
					<h4>Update Name/Username</h4>
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-11">
						<p><strong>WARNING</strong> Changing your own user account "Full Name" or "Username" will cause you to be automatically logged out. If you change your username be sure that you know your new username so that you will be able to regain access to the system.</p>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Full Name</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="full_name" placeholder="Full Name" maxlength="100" <?php if(!empty($form_full_name)) { echo "value=\"" . $form_full_name . "\""; }; ?> required>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Username</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="username" placeholder="Username" maxlength="100" <?php if(!empty($form_username)) { echo "value=\"" . $form_username . "\""; }; ?> required>
					</div>
				</div>
				
				<input type="hidden" name="csrf_token" value="<?php echo htmlentities($csrf_token); ?>"/>
				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" name="submit_name" value="submit_name" class="btn btn-default">Update Name/Username</button>
					</div>
				</div>
			</form>
			
			<hr>
			
			<form class="form-horizontal" action="" method="post">
				<div class="col-sm-offset-1">
					<h4>Update Password</h4>
				</div>
				
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
						<button type="submit" name="submit_password" value="submit_password" class="btn btn-default">Update Password</button>
					</div>
				</div>
			</form>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>