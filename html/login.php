<?php
	// Require relevent information for config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_LOGIN;
	
	// Obtain a CSRF token to be used to prevent CSRF - this is stored in the $_SESSION
	$csrf_token = CSRF::get_token();
	
	// If user is already authenticated - redirect to index
	if($user->authenticated) {
		// User is already logged in, so will be redirected
		// Create new Log instance, and log the action to the database
		$log = new Log('login_redirect');
		// Set session message
		$session->message_alert($notification["login"]["redirect"], "info");
		// Redirect the user
		Redirect::to(PAGELINK_INDEX);
	}; // Close if($user->authenticated)
	
	// If submit button has been pressed then process the form
	if(isset($_POST["submit"]) && $_POST["submit"] == "submit") {
		
		// Validate all fields and ensure that required fields are submitted
		// Initialise the $errors array where errors will be sent and then retrieved from
		$errors = array();
		
		// Required fields, if a field is not present or empty then populate the $errors array
		if(!isset($_POST["username"]) 			|| empty($_POST["username"])) 			{ $errors[] = $validation["field_required"]["user"]["username"]; };
		if(!isset($_POST["password"]) 			|| empty($_POST["password"])) 			{ $errors[] = $validation["field_required"]["user"]["password"]; };
		
		// Check that the submitted CSRF token is the same as the one in the $_SESSION to prevent cross site request forgery
		if(!CSRF::check_token($_POST['csrf_token']))									{ $errors[] = $validation['invalid']['security']['csrf_token']; };
		
		// If no errors have been found during the field validations
		if(empty($errors)) {
			// Give each value in the form it's own variable if it is submitted
			// Attempt a login with the submitted username/password
			$found_user = $user->attempt_login($_POST['username'], $_POST['password']);
			
			// If user is found in the database and password is found to be correct
			if($found_user) {
				// Correct username and password has been supplied
				
				// Set the user as logged in
				$user->set_logged_in($found_user);
				
				// Set success message
				$session->message_alert($notification["login"]["success"], "success");
				
				// Log action
				// Create new Log instance, and log the action to the database
				$log = new Log('login_success');
				
				// Redirect the user
				Redirect::to(PAGELINK_INDEX);
				
			} else {
				// Username/password not successfully authenticated
				$session->message_alert($notification["login"]["failure"], "danger");
				// Create new Log instance, and log the action to the database
				$log = new Log('login_failed', 'Failed authentication for username: ' . $_POST['username']);
			};
		} else {
			// Form field validation has failed - $errors array is not empty
			// If there are any error messages in the $errors array then display them to the screen
			$session->message_validation($errors);
			// Create new Log instance, and log the action to the database
			$log = new Log('login_failed', 'Failed login due to form validation errors.');
		};
	};
	
	// Create new Log instance, and log the page view to the database
	$log = new Log('view');
	
	// Require head content in the page
	require_once("../includes/layout.head.inc.php");

	?>

	<body>
		<div class="container">
			
			<div class="pt-15"></div>
			
			<!-- CONTENT -->
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
				
					<form class="form-signin" action="" method="post">
						<h2 class="form-signin-heading text-center"><?php echo $page_name; ?></h2>
						
						<?php $session->output_message(); ?>
						
						<div class="pt-15">
							<label class="sr-only">Username</label>
							<input type="text" name="username" class="form-control" placeholder="Username" <?php if(isset($_POST["username"])) { echo "value=\"{$_POST["username"]}\""; }; ?> autofocus>
						</div>
						<div class="pt-15">
							<label class="sr-only">Password</label>
							<input type="password" name="password" class="form-control" placeholder="Password">
						</div>
						<input type="hidden" name="csrf_token" value="<?php echo htmlentities($csrf_token); ?>"/>
						<div class="pt-15 text-center">
							<button class="btn btn-info" name="submit" type="submit" value="submit">Sign in</button>
						</div>
					</form>
					
				</div>
			</div>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>