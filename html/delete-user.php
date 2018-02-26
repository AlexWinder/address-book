<?php
	// Require relevent information for config.inc.php, including functions and database access
	require_once("../includes/config.inc.php");
	
	// Check that the user is logged in
	require_once("../includes/authenticated.inc.php");
	
	// Set $page_name so that the title of each page is correct
	// If user name could be set - correct GET request, or valid GET i value
	if(isset($user_full_name)) {
		$page_name = "Delete User - " . $user_full_name;
	} else {
		$page_name = "Delete User - User Not Found";
	};
	
	// If the value of i in GET exists
	if($_GET["i"]) {
		// Sanitise the GET value
		$id = mysql_prep(urldecode($_GET["i"]));
		
		// Find user in database
		$user = find_user_by_id($id);
		
		// Create a variable to store the user full name and username
		$user_full_name_with_username = htmlentities($user["full_name"] . " [" . $user["username"] . "]");
		
		// Set page name as user could be found
		$page_name = "Delete User - " . $user_full_name_with_username;
		
		// If a user is found in the database
		if($user) {
			// Check that the user has submitted the form
			if(isset($_POST["submit"]) && $_POST["submit"] == "submit") {
				// Ensure that the user actually wants to delete the user
				if(isset($_POST["confirm_delete"])) {
					// The user is not allowed to delete their own user
					if($user["user_id"] != $_SESSION["user_user_id"]) {
						// Construct and run the query on the database
						$sql = "DELETE FROM users WHERE user_id = '{$id}' LIMIT 1";
						$result = mysqli_query($db, $sql);
						
						// Confirm that the result was successful, and that only 1 item was deleted
						if($result && mysqli_affected_rows($db) == 1) {
							// User successfully deleted
							$_SESSION["message"] = construct_message($notification["user"]["delete"]["success"], "success");
							// Log action of add entry success, with user deleted
							log_action("delete_success", "User " . $user_full_name_with_username . " was deleted.");
							redirect_to("users.php");
						} else {
							// User failed to be deleted
							$_SESSION["message"] = construct_message($notification["user"]["delete"]["failure"], "danger");
							// Log action of database entry failing
							log_action("delete_failed", $logging["database"]["failure"]);
						};
					} else {
						// User has attempted to delete their own account from the system
						$_SESSION["message"] = construct_message($notification["user"]["delete"]["self"], "danger");
						// Log action of database entry failing
						log_action("delete_failed", "User attempted to delete their own user account.");
						redirect_to("users.php");
					};
					
				} else {
					// User did not confirm that they would like to delete the user
					// Set a failure message and redirect them to view the user list
					$_SESSION["message"] = construct_message($validation["field_required"]["user"]["confirm_delete"], "danger");
					// Log action of failing to confirm delete
					log_action("delete_failed", "User did not confirm that they wanted to delete the user.");
					redirect_to("users.php");
				};
				
			}; // User has not submitted the form - do nothing
			
			// User has accessed the page and not sumitted the form
			log_action("view");
			
		} else {
			// Contact could not be found in the database
			// Send message and redirect
			$_SESSION["message"] = construct_message($notification["user"]["delete"]["not_found"], "danger");
			// Set $page_name so that the title of each page is correct - user couldn't be found
			$page_name = "Delete User - User Not Found";
			// Log user accessing incorrect GET value
			log_action("not_found", $logging["page"]["not_exist"]);
			redirect_to("users.php");
		};
		
	} else {
		// Value of i in GET doesn't exist, send message and redirect
		$_SESSION["message"] = construct_message($notification["user"]["delete"]["not_found"], "danger");
		// Set $page_name so that the title of each page is correct - user couldn't be found
		$page_name = "Delete User - User Not Found";
		// Log user accessing incorrect GET key
		log_action("not_found", $logging["page"]["not_exist"]);
		redirect_to("users.php");
	};
	
	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");

?>
			<!-- CONTENT -->
			<?php session_message(); ?>
			<h3>WARNING</h3>
			<p><strong>This process is <u>IRREVERSIBLE</u>. Once a user has been deleted the only way to restore them to the user list is by manually re-adding.</strong></p>
			<p>Please confirm that you would like to delete <?php echo $user_full_name_with_username; ?> from the system.</p>
			
			<form class="form-horizontal" action="" method="post">
				
				<div class="checkbox">
					<label>
						<input type="checkbox" name="confirm_delete"> Yes, I am sure that I want to delete <?php echo $user_full_name_with_username; ?>
					</label>
				</div>
				
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