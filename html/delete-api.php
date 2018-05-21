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
		$api = $api->find_id($_GET['i']);

		// If a API token is found in the database
		if($api) {
			// Set page name as API token could be found
			$subpage_name = $api['api_id'] . ' - ' . PAGENAME_APIDELETE;
			
			// Obtain a CSRF token to be used to prevent CSRF - this is stored in the $_SESSION
			$csrf_token = CSRF::get_token();

			// Check that the user has submitted the form
			if(isset($_POST["submit"]) && $_POST["submit"] == "submit") {
			
			}; // User has not submitted the form - do nothing

			// User has accessed the page and not sumitted the form
			// Create new Log instance, and log the page view to the database
			$log = new Log('view');

		} else {
			// API token could not be found in the database
			// Send session message and redirect
			$session->message_alert($notification["api"]["delete"]["not_found"], "danger");
			// Set $subpage_name so that the title of each page is correct - contact couldn't be found
			$subpage_name = 'API Token Not Found - ' . PAGENAME_APIDELETE;
			// Create new Log instance, and log the action to the database
			$log = new Log('not_found');
			// Redirect the user
			Redirect::to(PAGELINK_API);
		};

	} else {
		// Value of i in GET doesn't exist, send session message and redirect
		$session->message_alert($notification["api"]["delete"]["not_found"], "danger");
		// Set $page_name so that the title of each page is correct - GET value not correct
		$subpage_name = 'Invalid GET Value - ' . PAGENAME_APIDELETE;
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
			
			<h3>WARNING</h3>
			<p><strong>This process is <u>IRREVERSIBLE</u>. Once an API token has been deleted there is no way to restore.</strong></p>
			<p>Please confirm that you would like to <strong>permanently delete</strong> API token <?php echo $api['api_id']; ?> from the system.</p>

			<form class="form-horizontal" action="" method="post">

				<div class="checkbox">
					<label>
						<input type="checkbox" name="confirm_delete"> Yes, I am sure that I want to <strong>permanently delete</strong> API token <?php echo $api['api_id']; ?>
					</label>
				</div>
				
				<input type="hidden" name="csrf_token" value="<?php echo htmlentities($csrf_token); ?>"/>

				<hr>

				<div >
					<button type="submit" name="submit" value="submit" class="btn btn-danger">Delete API Token</button>
				</div>
			</form>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>