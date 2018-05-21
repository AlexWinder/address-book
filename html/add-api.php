<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_API;
	// Set $subpage_name as this page isn't the main section
	$subpage_name = PAGENAME_APIADD;
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	}; // Close if(!$user->authenticated)
	
	// Obtain a CSRF token to be used to prevent CSRF - this is stored in the $_SESSION
	$csrf_token = CSRF::get_token();
	
	// Generate new API instance
	$api = new API();
	
	// Obtain a token to be used as an API token
	if(!$session->get('api_token')) {
		// Set session 
		$session->set('api_token', $api->generate_token(12));
		$api_token = $session->get('api_token');
	} else {
		$api_token = $session->get('api_token');
	}; // Close if(!$session->get('api_token'))
	
	// If submit button has been pressed then process the form
	if(isset($_POST["submit"]) && $_POST["submit"] == "submit") {
		// Validate all fields and ensure that required fields are submitted
		
		// Initialise the $errors are where errors will be sent and then retrieved from
		$errors = array();
		
		// If IP address submitted, check is in valid format
		if(isset($_POST['ip_address']) && !empty($_POST['ip_address'])) {
			// Check if IP address is in valid format
			if(!$api->check_ip($_POST['ip_address'])) { $errors[] = $validation['invalid']['format']['ip_address']; };
		}; // Close if(isset($_POST['ip_address']) {
		
		// Check that the submitted CSRF token is the same as the one in the $_SESSION to prevent cross site request forgery
		if(!CSRF::check_token($_POST['csrf_token']))									{ $errors[] = $validation['invalid']['security']['csrf_token']; };
		
		// If no errors have been found during the field validations
		if(empty($errors)) {
			// Prepare an array to be used to insert into the database
			$fields = array();
			
			// Populate the $fields array with values where applicable
			$fields['api_id'] = $api_token;
			!empty($_POST['ip_address']) 		? $fields['ip'] = $_POST['ip_address']					: $fields['ip'] = null;
			!empty($_POST['cosmetic_name']) 	? $fields['cosmetic_name'] = $_POST['cosmetic_name'] 	: $fields['cosmetic_name'] = null;
			
			// Create the new API token, inserting the fields from the $fields array
			$result = $api->create($fields);
			
			// Check if the submission was successful
			if($result){
				// API token was successfully added to the database
				// Log action of add entry success, with API token added 
				// Create new Log instance, and log the action to the database
				$log = new Log('api_add_success', 'Token (' . $api_token . ') created.');
				// Delete the API token from session to avoid resubmission of the same API token
				$session->remove('api_token');
				// Add session message
				$session->message_alert($notification["api"]["add"]["success"], "success");
				// Redirect the user
				Redirect::to(PAGELINK_API);
				
			} else {
				// Add session message
				$session->message_alert($notification["api"]["add"]["failure"], "danger");
				// Log action of database entry failing
				// Create new Log instance, and log the action to the database
				$log = new Log('api_add_failed', 'database');
			}; // Close if($result)
			
		} else {
			// Form field validation has failed - $errors array is not empty
			// If there are any error messages in the $errors array then display them to the screen
			$session->message_validation($errors);
			// Log action of failing form process
			// Create new Log instance, and log the action to the database
			$log = new Log('api_add_failed', 'Failed API token add due to form validation errors.');
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
					<label class="col-sm-2 control-label">API Token</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" value="<?php echo htmlentities($api_token); ?>" disabled>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Cosmetic Name*</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="cosmetic_name" placeholder="Give the API token a cosmetic name to allow you to easily identify it (Optional)" <?php if(isset($_POST["cosmetic_name"])){ echo "value=\"" . htmlentities($_POST["cosmetic_name"]) . "\""; }; ?>>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">IP Address**</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="ip_address" placeholder="Restrict the API token to a single IP address (Optional)" <?php if(isset($_POST["ip_address"])){ echo "value=\"" . htmlentities($_POST["ip_address"]) . "\""; }; ?>>
						<small>You are accessing this page from IP address - <?php echo htmlentities($_SERVER['REMOTE_ADDR']); ?></small>
					</div>
				</div>
				
				<hr />
				
				<p>* = Optional field</p>
				<p>** = If no IP address is specified, then the API token will be able to be used from any IP address. If this is not intended then specify the single IP address from which the API token will be used. Also note that you are unable to change the IP address once set. For security purposes you will need to create a new API token if you wish to use a different IP address.</p>
				
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