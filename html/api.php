<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_API;
	
	// Check if user is making an API call - check if GET value has been sent
	if(isset($_GET['t']) && !empty($_GET['t'])) {
		// User is making an API call
		
		// Stop the page loading any further
		die();
	}; // Close if(isset($_GET['t']) && !empty($_GET['t'])) - user has not sent an API request
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	}; // Close if(!$user->authenticated)
	
	// Create new Log instance, and log the page view to the database
	$log = new Log('view');
	
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