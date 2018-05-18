<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_USERS;
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	}; // Close if(!$user->authenticated)
	
	// If the value of i in GET exists
	if($_GET["i"]) {
		// Find user in database
		$found_user = $user->find_id($_GET['i']);
		
		// Create a variable to store the user full name and username
		$user_full_name_with_username = htmlentities($found_user["full_name"] . " [" . $found_user["username"] . "]");
		
		// Set page name as user could be found
		$subpage_name = $user_full_name_with_username . ' - ' . PAGENAME_USERSDELETE;
		
		// Obtain a CSRF token to be used to prevent CSRF - this is stored in the $_SESSION
		$csrf_token = CSRF::get_token();
		
		// If a user is found in the database
		if($found_user) {
			// Check that the user has submitted the form
			if(isset($_POST["submit"]) && $_POST["submit"] == "submit") {
				// Ensure that the user actually wants to delete the user
				if(isset($_POST["confirm_delete"])) {
					// Validate all fields and ensure that required fields are submitted
				
					// Initialise the $errors are where errors will be sent and then retrieved from
					$errors = array();
					
					// Check that the submitted CSRF token is the same as the one in the $_SESSION to prevent cross site request forgery
					if(!CSRF::check_token($_POST['csrf_token']))									{ $errors[] = $validation['invalid']['security']['csrf_token']; };
					
					// If no errors have been found during the field validations
					if(empty($errors)) {
					
						// The user is not allowed to delete their own user
						if($found_user['user_id'] != $session->get('authenticated_user_id')) {
							// Delete the user
							$result = $user->delete($found_user['user_id']);
							
							// Confirm that the result was successful, and that only 1 item was deleted
							if($result) {
								// User successfully deleted
								// Set session message
								$session->message_alert($notification["user"]["delete"]["success"], "success");
								// Log action of add entry success, with user deleted
								// Create new Log instance, and log the action to the database
								$log = new Log('user_delete_success', 'User - ' . $user_full_name_with_username);
								// Redirect the user
								Redirect::to(PAGELINK_USERS);
							} else {
								// User failed to be deleted
								// Set session message
								$session->message_alert($notification["user"]["delete"]["failure"], "danger");
								// Log action of database entry failing
								// Create new Log instance, and log the action to the database
								$log = new Log('user_delete_failed', 'database');
							};
						} else {
							// User has attempted to delete their own account from the system
							// Set session message
							$session->message_alert($notification["user"]["delete"]["self"], "danger");
							// Log action of database entry failing
							// Create new Log instance, and log the action to the database
							$log = new Log('user_delete_failed', 'User attempted to delete their own user account.');
							// Redirect the user
							Redirect::to(PAGELINK_USERS);
						};
					
					} else {
						// Form field validation has failed - $errors array is not empty
						// If there are any error messages in the $errors array then display them to the screen
						$session->message_validation($errors);
					};
					
				} else {
					// User did not confirm that they would like to delete the user
					// Set a failure session message and redirect them to view the user list
					$session->message_alert($validation["field_required"]["user"]["confirm_delete"], "danger");
					// Log action of failing to confirm delete
					// Create new Log instance, and log the action to the database
					$log = new Log('user_delete_failed', 'User did not confirm that they wanted to delete the user.');
					// Redirect the user
					Redirect::to(PAGELINK_USERS);
				};
			}; // User has not submitted the form - do nothing
			
			// User has accessed the page and not sumitted the form
			// Create new Log instance, and log the page view to the database
			$log = new Log('view');
			
		} else {
			// Contact could not be found in the database
			// Send session message and redirect
			$session->message_alert($notification["user"]["delete"]["not_found"], "danger");
			// Set $subpage_name so that the title of each page is correct - user couldn't be found
			$subpage_name = 'User Not Found - ' . PAGENAME_USERSDELETE;
			// Log user accessing incorrect GET value
			// Create new Log instance, and log the action to the database
			$log = new Log('not_found');
			// Redirect the user
			Redirect::to(PAGELINK_USERS);
		};
	} else {
		// Value of i in GET doesn't exist, send message and redirect
		// Send session message
		$session->message_alert($notification["user"]["delete"]["not_found"], "danger");
		// Set $subpage_name so that the title of each page is correct - GET value not correct
		$subpage_name = 'Invalid GET Value - ' . PAGENAME_USERSDELETE;
		// Log user accessing incorrect GET key
		// Create new Log instance, and log the action to the database
		$log = new Log('not_found');
		// Redirect the user
		Redirect::to(PAGELINK_USERS);
	};
	
	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");

?>
			<!-- CONTENT -->
			<?php $session->output_message(); ?>
			
			<h3>WARNING</h3>
			<p><strong>This process is <u>IRREVERSIBLE</u>. Once a user has been deleted the only way to restore them to the user list is by manually re-adding.</strong></p>
			<p>Please confirm that you would like to <strong>permanently delete</strong> <?php echo $user_full_name_with_username; ?> from the system.</p>
			
			<form class="form-horizontal" action="" method="post">
				
				<div class="checkbox">
					<label>
						<input type="checkbox" name="confirm_delete"> Yes, I am sure that I want to <strong>permanently delete</strong> <?php echo $user_full_name_with_username; ?>
					</label>
				</div>
				
				<input type="hidden" name="csrf_token" value="<?php echo htmlentities($csrf_token); ?>"/>
				
				<hr>
				
				<div >
					<button type="submit" name="submit" value="submit" class="btn btn-danger">Delete User</button>
				</div>
			</form>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>