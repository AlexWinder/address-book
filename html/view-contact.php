<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('security_failed');
	}; // Close if(!$user->authenticated)
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_CONTACTS;

	// If the value of i in GET exists
	if($_GET["i"]) {
		
		// Find contact in database
		$contact = new Contact($_GET['i']);
		
		// If a contact could be found
		if($contact->found) {
			// Set $subpage_name as this page isn't the main section
			$subpage_name = $contact->full_name . " - View Details";
			
			// Create new Log instance, and log the page view to the database
			$log = new Log('view');
			
		} else {
			// Contact could not be found in the database
			// Set $subpage_name so that the title of each page is correct - contact not found
			$subpage_name = 'Contact Not Found - View Contact';
			// Log user accessing contact which doesn't exist
			// Create new Log instance, and log the action to the database
			$log = new Log('not_found');
			// Send session message and redirect
			$session->message_alert($notification["contact"]["view"]["not_found"], "danger");
			// Redirect the user
			redirect_to("index.php");
		}
	} else {
		// Value of i in GET doesn't exist, send message and redirect
		// Set $subpage_name so that the title of each page is correct - GET value not correct
		$subpage_name = 'Invalid GET Value - View Contact';
		// Log user accessing incorrect GET key
		// Create new Log instance, and log the action to the database
		$log = new Log('not_found');
		// Set session message
		$session->message_alert($notification["contact"]["view"]["not_found"], "danger");
		// Redirect the user
		redirect_to("index.php");
	};
	
	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");
?>
			
			<!-- CONTENT -->
			<?php $session->output_message(); ?>
			
			<div class="row">
				<div class="col-xs-4 col-sm-3">
					<h3>Full Name:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><?php echo $contact->full_name; ?></h3>
				</div>
			</div>
			
				
			<?php if(!empty($contact->number['home']['raw'])) { ?>
			<div class="row">
				<div class="col-xs-4 col-sm-3">
					<h3>Home Number:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><a href="tel:<?php echo $contact->number['home']['raw']; ?>"><?php echo $contact->number['home']['formatted']; ?></a></h3>
				</div>
			</div>
			<?php }; ?>
				
			<?php if(!empty($contact->number['mobile']['raw'])) { ?>
			<div class="row">
				<div class="col-xs-4 col-sm-3">
					<h3>Mobile Number:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><a href="tel:<?php echo $contact->number['mobile']['raw']; ?>"><?php echo $contact->number['mobile']['formatted']; ?></a></h3>
				</div>
			</div>
			<?php }; ?>
			
			<?php if($contact->email) { ?>
			<div class="row">	
				<div class="col-xs-4 col-sm-3">
					<h3>Email Address:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><a href="mailto:<?php echo $contact->email; ?>"><?php echo $contact->email; ?></a></h3>
				</div>
			</div>
			<?php }; ?>
				
			<?php if($contact->date_of_birth) { ?>
			<div class="row">
				<div class="col-xs-4 col-sm-3">
					<h3>Date Of Birth:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><?php echo $contact->date_of_birth; ?></h3>
				</div>
			</div>
			<?php }; ?>
			
			<div class="row">
				<div class="col-xs-4 col-sm-3">
					<h3>Address:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><?php echo $contact->full_address; ?></h3>
				</div>
			</div>
			
			<hr>
			
			<a href="update-contact.php?i=<?php echo urlencode($contact->single["contact_id"]); ?>" type="button" role="button" class="btn btn-info">Update Contact</a>
			<a href="delete-contact.php?i=<?php echo urlencode($contact->single["contact_id"]); ?>" type="button" role="button" class="btn btn-danger">Delete Contact</a>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>