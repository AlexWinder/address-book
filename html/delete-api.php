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
			
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>