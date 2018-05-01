<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Check that the user is logged in
	require_once("../includes/authenticated.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_USERS;
	// If contact could be set - correct GET request, or valid GET i value
	if(isset($contact_full_name)) {
		$subpage_name = $contact_full_name - "Delete Contact";
	} else {
		$subpage_name = "Contact Not Found - Delete Contact";
	};
		
	// If the value of i in GET exists
	if(isset($_GET["i"])) {
		// Sanitise the GET value
		$id = mysql_prep(urldecode($_GET["i"]));
		
		// Find contact in database
		$contact = find_contact_by_id($id);
		
		// Create a varibale to store the contact full name
		$contact_full_name = htmlentities(full_name($contact["first_name"], $contact["middle_name"], $contact["last_name"]));
		// Set page name as contact could be found
		$subpage_name = $contact_full_name . " - Delete Contact";
		
		// If a contact is found in the database
		if($contact) {
			// Check that the user has submitted the form
			if(isset($_POST["submit"]) && $_POST["submit"] == "submit") {
				// Ensure that the user actually wants to delete the user
				if(isset($_POST["confirm_delete"])) {
					
					// Construct and run the query on the database
					$sql = "DELETE FROM contacts WHERE contact_id = '{$id}' LIMIT 1";
					$result = mysqli_query($db, $sql);
					
					// Confirm that the result was successful, and that only 1 item was deleted
					if($result && mysqli_affected_rows($db) == 1) {
						// Contact successfully deleted
						$_SESSION["message"] = construct_message($notification["contact"]["delete"]["success"], "success");
						// Log action of database entry success
						log_action("delete_success", "Contact of " . $contact["first_name"] . " " . $contact["last_name"] . " from " . $contact["address_town"] . " was deleted.");
						redirect_to("index.php");
					} else {
						// Contact failed to be deleted
						$_SESSION["message"] = construct_message($notification["contact"]["delete"]["failure"], "danger");
						// Log action of database entry failing
						log_action("delete_failed", $logging["database"]["failure"]);
					};
					
				} else {
					// User did not confirm that they would like to delete the user
					// Set a failure message and redirect them to view the contact
					$_SESSION["message"] = construct_message($validation["field_required"]["contact"]["confirm_delete"], "danger");
					// Log action of failing to confirm delete
					log_action("delete_failed", "User did not confirm that they wanted to delete the contact.");
					redirect_to("view-contact.php?i=" . $id);
				};
				
			}; // User has not submitted the form - do nothing
			
			// User has accessed the page and not sumitted the form
			log_action("view");
			
		} else {
			// Contact could not be found in the database
			// Send message and redirect
			$_SESSION["message"] = construct_message($notification["contact"]["delete"]["not_found"], "danger");
			$page_name = "Delete Contact - Contact Not Found";
			// Log user accessing incorrect GET value
			log_action("not_found", $logging["page"]["not_exist"]);
			redirect_to("index.php");
		};
		
	} else {
		// Value of i in GET doesn't exist, send message and redirect
		$_SESSION["message"] = construct_message($notification["contact"]["delete"]["not_found"], "danger");
		// Set $page_name so that the title of each page is correct - contact couldn't be found
		$page_name = "Delete Contact - Contact Not Found";
		// Log user accessing incorrect GET key
		log_action("not_found", $logging["page"]["not_exist"]);
		redirect_to("index.php");
	}; 

	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");

?>
			<!-- CONTENT -->
			<?php session_message(); ?>
			<h3>WARNING</h3>
			<p><strong>This process is <u>IRREVERSIBLE</u>. Once a contact has been deleted the only way to restore them to the contact list is by manually re-adding.</strong></p>
			<p>Please confirm that you would like to <strong>permanently delete</strong> <?php if(isset($contact_full_name)) { echo $contact_full_name; }; ?> from the system.</p>
			
			<form class="form-horizontal" action="" method="post">
				
				<div class="checkbox">
					<label>
						<input type="checkbox" name="confirm_delete"> Yes, I am sure that I want to <strong>permanently delete</strong> <?php if(isset($contact_full_name)) { echo $contact_full_name; }; ?>
					</label>
				</div>
				
				<hr>
				
				<div >
					<button type="submit" name="submit" value="submit" class="btn btn-danger">Delete Contact</button>
				</div>
			</form>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>