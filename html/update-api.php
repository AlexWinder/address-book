<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_API;
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	}; // Close if(!$user->authenticated)

	// If the value of i in GET exists
	if(isset($_GET["i"])) {
		// Find API token in database
		$api = new API();
		$api->find_id($_GET['i']);
		
		// If an API token is found in the database
		if($api->found) {
			// Obtain a CSRF token to be used to prevent CSRF - this is stored in the $_SESSION
			$csrf_token = CSRF::get_token();
			
			// Set page name as contact could be found
			$subpage_name = $api->token . ' - ' . PAGENAME_APIUPDATE;

			// Check that the user has submitted the form
			if(isset($_POST["submit"]) && $_POST["submit"] == "submit") {
				// Validate all fields and ensure that required fields are submitted
				
				// Initialise the $errors are where errors will be sent and then retrieved from
				$errors = array();

				// Check that the submitted CSRF token is the same as the one in the $_SESSION to prevent cross site request forgery
				if(!CSRF::check_token($_POST['csrf_token']))									{ $errors[] = $validation['invalid']['security']['csrf_token']; };
				
				// If no errors have been found during the field validations
				if(empty($errors)) {
				
				

				} else {
					// Form field validation has failed - $errors array is not empty
					// If there are any error messages in the $errors array then display them to the screen
					$session->message_validation($errors);
					// Log action of failing form process
					// Create new Log instance, and log the action to the database
					$log = new Log('api_update_failed', 'Failed API token update due to form validation errors.');
				};
			}; // User has not submitted the form - do nothing
			
			// User has accessed the page and not sumitted the form
			// Create new Log instance, and log the page view to the database
			$log = new Log('view');
		} else {
			// API token could not be found in the database
			// Set $subpage_name so that the title of each page is correct - API token couldn't be found
			$subpage_name = 'API Token Not Found - ' . PAGENAME_APIUPDATE;
			// Send message and redirect
			$session->message_alert($notification["api"]["update"]["not_found"], "danger");
			// Log user accessing incorrect GET value
			// Create new Log instance, and log the action to the database
			$log = new Log('not_found');
			// Redirect the user
			Redirect::to(PAGELINK_API);
		};

	} else {
		// Value of i in GET doesn't exist, send session message and redirect
		$session->message_alert($notification["api"]["update"]["not_found"], "danger");
		// Set $page_name so that the title of each page is correct - GET value not correct
		$subpage_name = 'Invalid GET Value - ' . PAGENAME_APIUPDATE;
		// Create new Log instance, and log the action to the database
		$log = new Log('not_found');
		// Redirect the user
		Redirect::to(PAGELINK_API);
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
					<label class="col-sm-2 control-label">API Token</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" value="<?php echo htmlentities($api->token); ?>" disabled>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Cosmetic Name*</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="cosmetic_name" placeholder="Give the API token a cosmetic name to allow you to easily identify it (Optional)" <?php if(isset($_POST["cosmetic_name"])){ echo "value=\"" . htmlentities($_POST["cosmetic_name"]) . "\""; } elseif(isset($api->name) && !empty($api->name)) { echo "value=\"" . htmlentities($api->name) . "\""; }; ?>>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">IP Address**</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="ip_address" value="<?php if(isset($api->ip) && !empty($api->ip)) { echo htmlentities($api->ip); } else { echo "Accessible from any IP"; } ?>" disabled>
						<small>You are unable to update the IP address associated with the API token. To allow access from another IP address you need to <a href="<?php echo PAGELINK_APIADD; ?>">create a new API token</a>.</small>
					</div>
				</div>
				
				<input type="hidden" name="csrf_token" value="<?php echo htmlentities($csrf_token); ?>"/>

				<hr>

				<p>* = Optional field</p>
				<p>** = If no IP address is specified, then the API token will be able to be used from any IP address. You are unable to update the IP address once an API token has been created. For security purposes you will need to create a new API token if you wish to use a different IP address.</p>
				

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" name="submit" value="submit" class="btn btn-default">Submit</button>
					</div>
				</div
			</form>

			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>