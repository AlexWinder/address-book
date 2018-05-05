<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Check that the user is logged in
	require_once("../includes/authenticated.inc.php");
	
	// If the value of i in GET exists
	if(isset($_GET["i"])) {
		
		// Find contact in database
		$contact = new Contact($_GET['i']);
		
		// If a contact is found in the database
		if($contact->found) {
				
			// Set $page_name so that the title of each page is correct
			$page_name = PAGENAME_CONTACTS;
			
			// Set page name as contact could be found
			$subpage_name = $contact->full_name . " - Update Contact";
		
			// Assign all the various database values to their own variables
			// First check if value has been sent in $_POST, if not then check if it exists in the database, if not then assign as null
			if(!empty($_POST["first_name"]) && isset($_POST["first_name"])) 						{ $form_first_name = htmlentities($_POST["first_name"]); } 							elseif(!empty($contact->single["first_name"]) && isset($contact->single["first_name"])) 							{ $form_first_name = htmlentities($contact->single["first_name"]); } 						else { $form_first_name = null; };
			if(!empty($_POST["middle_name"]) && isset($_POST["middle_name"])) 						{ $form_middle_name = htmlentities($_POST["middle_name"]); } 						elseif(!empty($contact->single["middle_name"]) && isset($contact->single["middle_name"])) 							{ $form_middle_name = htmlentities($contact->single["middle_name"]); } 						else { $form_middle_name = null; };
			if(!empty($_POST["last_name"]) && isset($_POST["last_name"])) 							{ $form_last_name = htmlentities($_POST["last_name"]); } 							elseif(!empty($contact->single["last_name"]) && isset($contact->single["last_name"])) 								{ $form_last_name = htmlentities($contact->single["last_name"]); } 							else { $form_last_name = null; };
			if(!empty($_POST["contact_number_home"]) && isset($_POST["contact_number_home"]))		{ $form_contact_number_home = htmlentities($_POST["contact_number_home"]); } 		elseif(!empty($contact->single["contact_number_home"]) && isset($contact->single["contact_number_home"])) 			{ $form_contact_number_home = htmlentities($contact->single["contact_number_home"]); } 		else { $form_contact_number_home = null; };
			if(!empty($_POST["contact_number_mobile"]) && isset($_POST["contact_number_mobile"])) 	{ $form_contact_number_mobile = htmlentities($_POST["contact_number_mobile"]); } 	elseif(!empty($contact->single["contact_number_mobile"]) && isset($contact->single["contact_number_mobile"])) 		{ $form_contact_number_mobile = htmlentities($contact->single["contact_number_mobile"]); } 	else { $form_contact_number_mobile = null; };
			if(!empty($_POST["contact_email"]) && isset($_POST["contact_email"])) 					{ $form_contact_email = htmlentities($_POST["contact_email"]); } 					elseif(!empty($contact->single["contact_email"]) && isset($contact->single["contact_email"])) 						{ $form_contact_email = htmlentities($contact->single["contact_email"]); } 					else { $form_contact_email = null; };
			if(!empty($_POST["date_of_birth"]) && isset($_POST["date_of_birth"])) 					{ $form_date_of_birth = htmlentities($_POST["date_of_birth"]); } 					elseif(!empty($contact->single["date_of_birth"]) && isset($contact->single["date_of_birth"])) 						{ $form_date_of_birth = htmlentities($contact->single["date_of_birth"]); } 					else { $form_date_of_birth = null; };
			if(!empty($_POST["address_line_1"]) && isset($_POST["address_line_1"])) 				{ $form_address_line_1 = htmlentities($_POST["address_line_1"]); } 					elseif(!empty($contact->single["address_line_1"]) && isset($contact->single["address_line_1"])) 					{ $form_address_line_1 = htmlentities($contact->single["address_line_1"]); } 				else { $form_address_line_1 = null; };
			if(!empty($_POST["address_line_2"]) && isset($_POST["address_line_2"])) 				{ $form_address_line_2 = htmlentities($_POST["address_line_2"]); } 					elseif(!empty($contact->single["address_line_2"]) && isset($contact->single["address_line_2"])) 					{ $form_address_line_2 = htmlentities($contact->single["address_line_2"]); } 				else { $form_address_line_2 = null; };
			if(!empty($_POST["address_town"]) && isset($_POST["address_town"])) 					{ $form_address_town = htmlentities($_POST["address_town"]); } 						elseif(!empty($contact->single["address_town"]) && isset($contact->single["address_town"])) 						{ $form_address_town = htmlentities($contact->single["address_town"]); } 					else { $form_address_town = null; };
			if(!empty($_POST["address_county"]) && isset($_POST["address_county"])) 				{ $form_address_county = htmlentities($_POST["address_county"]); } 					elseif(!empty($contact->single["address_county"]) && isset($contact->single["address_county"])) 					{ $form_address_county = htmlentities($contact->single["address_county"]); } 				else { $form_address_county = null; };
			if(!empty($_POST["address_post_code"]) && isset($_POST["address_post_code"])) 			{ $form_address_post_code = htmlentities($_POST["address_post_code"]); } 			elseif(!empty($contact->single["address_post_code"]) && isset($contact->single["address_post_code"])) 				{ $form_address_post_code = htmlentities($contact->single["address_post_code"]); } 			else { $form_address_post_code = null; };
			
			// Check that the user has submitted the form
			if(isset($_POST["submit"]) && $_POST["submit"] == "submit") {
				// Validate all fields and ensure that required fields are submitted
				
				// Initialise the $errors are where errors will be sent and then retrieved from
				$errors = array();
				
				// Required fields, if a field is not present or empty then populate the $errors array
				if(!isset($_POST["first_name"]) 		|| empty($_POST["first_name"])) 		{ $errors[] = $validation["field_required"]["contact"]["first_name"]; };
				if(!isset($_POST["last_name"]) 			|| empty($_POST["last_name"])) 			{ $errors[] = $validation["field_required"]["contact"]["last_name"]; };
				if(!isset($_POST["address_line_1"]) 	|| empty($_POST["address_line_1"])) 	{ $errors[] = $validation["field_required"]["contact"]["address_line_1"]; };
				if(!isset($_POST["address_town"]) 		|| empty($_POST["address_town"])) 		{ $errors[] = $validation["field_required"]["contact"]["address_town"]; };
				if(!isset($_POST["address_county"]) 	|| empty($_POST["address_county"])) 	{ $errors[] = $validation["field_required"]["contact"]["address_county"]; };
				if(!isset($_POST["address_post_code"]) 	|| empty($_POST["address_post_code"])) 	{ $errors[] = $validation["field_required"]["contact"]["address_post_code"]; };
				
				// Length of fields
				$length_first_name = 		strlen($_POST["first_name"]);
				$length_middle_name = 		strlen($_POST["middle_name"]);
				$length_last_name = 		strlen($_POST["last_name"]);
				$length_home_number = 		strlen($_POST["contact_number_home"]);
				$length_mobile_number = 	strlen($_POST["contact_number_mobile"]);
				$length_contact_email = 	strlen($_POST["contact_email"]);
				$length_address_line_1 =	strlen($_POST["address_line_1"]);
				$length_address_line_2 = 	strlen($_POST["address_line_2"]);
				$length_address_town = 		strlen($_POST["address_town"]);
				$length_address_county = 	strlen($_POST["address_county"]);
				$length_address_post_code = strlen($_POST["address_post_code"]);
				
				// Name fields musn't be longer than length in the database, if they are then populate the $errors array
				if($length_first_name > 50) 		{ $errors[] = $validation["too_long"]["contact"]["first_name"]; }; 
				if($length_middle_name > 50) 		{ $errors[] = $validation["too_long"]["contact"]["middle_name"]; }; 
				if($length_last_name > 50) 			{ $errors[] = $validation["too_long"]["contact"]["last_name"]; }; 
				if($length_home_number > 20) 		{ $errors[] = $validation["too_long"]["contact"]["contact_number_home"]; }; 
				if($length_mobile_number > 20) 		{ $errors[] = $validation["too_long"]["contact"]["contact_number_mobile"]; }; 
				if($length_contact_email > 100) 	{ $errors[] = $validation["too_long"]["contact"]["contact_email"]; }; 
				if($length_address_line_1 > 100) 	{ $errors[] = $validation["too_long"]["contact"]["address_line_1"]; }; 
				if($length_address_line_2 > 100) 	{ $errors[] = $validation["too_long"]["contact"]["address_line_2"]; }; 
				if($length_address_town > 100) 		{ $errors[] = $validation["too_long"]["contact"]["address_town"]; }; 
				if($length_address_county > 100) 	{ $errors[] = $validation["too_long"]["contact"]["address_county"]; }; 
				if($length_address_post_code > 20) 	{ $errors[] = $validation["too_long"]["contact"]["address_post_code"]; };
				
				// If no errors have been found during the field validations
				if(empty($errors)) {
					
					// Give each value in the form it's own variable if it is submitted
					// Variable used to submit to the database
					!empty($_POST["first_name"]) 				? $form_first_name = mysql_prep($_POST["first_name"]) 										: $form_first_name = null;
					!empty($_POST["middle_name"]) 				? $form_middle_name = mysql_prep($_POST["middle_name"]) 									: $form_middle_name = null;
					!empty($_POST["last_name"]) 				? $form_last_name = mysql_prep($_POST["last_name"]) 										: $form_last_name = null;
					!empty($_POST["contact_number_home"]) 		? $form_home_number = mysql_prep(remove_white_space($_POST["contact_number_home"])) 		: $form_home_number = null;
					!empty($_POST["contact_number_mobile"]) 	? $form_mobile_number = mysql_prep(remove_white_space($_POST["contact_number_mobile"])) 	: $form_mobile_number = null;
					!empty($_POST["contact_email"]) 			? $form_email_address = mysql_prep($_POST["contact_email"]) 								: $form_email_address = null;
					!empty($_POST["date_of_birth"]) 			? $form_date_of_birth = mysql_prep($_POST["date_of_birth"]) 								: $form_date_of_birth = null;
					!empty($_POST["address_line_1"]) 			? $form_address_line_1 = mysql_prep( $_POST["address_line_1"]) 								: $form_address_line_1 = null;
					!empty($_POST["address_line_2"]) 			? $form_address_line_2 = mysql_prep($_POST["address_line_2"]) 								: $form_address_line_2 = null;
					!empty($_POST["address_town"]) 				? $form_address_town = mysql_prep($_POST["address_town"]) 									: $form_address_town = null;
					!empty($_POST["address_county"]) 			? $form_address_county = mysql_prep($_POST["address_county"]) 								: $form_address_county = null;
					!empty($_POST["address_post_code"]) 		? $form_address_post_code = mysql_prep($_POST["address_post_code"]) 						: $form_address_post_code = null;
					
					// Create SQL query to update entry in the database
					$sql = "UPDATE contacts SET ";
					$sql .= "first_name = '{$form_first_name}', ";
					// Middle name is optional - if no value then send NULL
					if(!is_null($form_middle_name)) { $sql .= "middle_name = '{$form_middle_name}', "; } else { $sql .= "middle_name = NULL, "; };
					$sql .= "last_name = '{$form_last_name}', ";
					// Home phone number is optional - if no value then send NULL
					if(!is_null($form_home_number)) { $sql .= "contact_number_home = '{$form_home_number}', "; } else { $sql .= "contact_number_home = NULL, "; };
					// Mobile phone number is optional - if no value then send NULL
					if(!is_null($form_mobile_number)) { $sql .= "contact_number_mobile = '{$form_mobile_number}', "; } else { $sql .= "contact_number_mobile = NULL, "; };
					// Email address is optional - if no value then send NULL
					if(!is_null($form_email_address)) { $sql .= "contact_email = '{$form_email_address}', "; } else { $sql .= "contact_email = NULL, "; };
					// Date of birth is optional - if no value then send NULL
					if(!is_null($form_date_of_birth)) { $sql .= "date_of_birth = '{$form_date_of_birth}', "; } else { $sql .= "date_of_birth = NULL, "; };
					$sql .= "address_line_1 = '{$form_address_line_1}', ";
					// Address line 2 is optional - if no value then send NULL
					if(!is_null($form_address_line_2)) { $sql .= "address_line_2 = '{$form_address_line_2}', "; } else { $sql .= "address_line_2 = NULL, "; };
					$sql .= "address_town = '{$form_address_town}', ";
					$sql .= "address_county = '{$form_address_county}', ";
					$sql .= "address_post_code = '{$form_address_post_code}' ";
					$sql .= "WHERE contact_id = '{$id}' ";
					$sql .= "LIMIT 1";
					
					$result = mysqli_query($db, $sql);
					
					if($result){
						// Contact successfully updated on the database
						$_SESSION["message"] = construct_message($notification["contact"]["update"]["success"], "success");
						// Log action of add entry success, with contact updated 
						log_action("update_success", "Contact Updated: " . $form_first_name . " " . $form_last_name . " from " . $form_address_town . " (" . $_GET['i'] . ")");
						redirect_to("index.php");
					} else {
						$_SESSION["message"] = construct_message($notification["contact"]["update"]["failure"], "danger");
						// Log action of database entry failing
						log_action("update_failed", $logging["database"]["failure"]);
					};
					
				} else {
					// Form field validation has failed - $errors array is not empty
					// If there are any error messages in the $errors array then display them to the screen
					$_SESSION["message"] = validation_failure_message($errors);
					// Log action of failing form process
					$log_errors = log_validation_failures($errors);
					log_action("update_failed", $log_errors);
				};
				
			}; // User has not submitted the form - do nothing
			
			// User has accessed the page and not sumitted the form
			log_action("view");
		} else {
			// Contact could not be found in the database
			// Set $page_name so that the title of each page is correct - contact couldn't be found
			$page_name = "Update Contact - Contact Not Found";
			// Send message and redirect
			$_SESSION["message"] = construct_message($notification["contact"]["update"]["not_found"], "danger");
			// Log user accessing incorrect GET value
			log_action("not_found", $logging["page"]["not_exist"]);
			redirect_to("index.php");
		};
		
	} else {
		// Value of i in GET doesn't exist, send message and redirect
		// Set $page_name so that the title of each page is correct - contact couldn't be found
		$page_name = "Update Contact - Contact Not Found";
		$_SESSION["message"] = construct_message($notification["contact"]["update"]["not_found"], "danger");
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
			<form class="form-horizontal" action="" method="post">
				
				<div class="form-group">
					<label class="col-sm-2 control-label">First Name</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="first_name" placeholder="First Name" maxlength="50" <?php if(!empty($form_first_name)) { echo "value=\"" . $form_first_name . "\""; }; ?> required>
					</div>
					
					<label class="col-sm-2 control-label">Middle Name</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="middle_name" placeholder="Middle Name" maxlength="50" <?php if(!empty($form_middle_name)) { echo "value=\"" . $form_middle_name . "\""; }; ?>>
					</div>
					
					<label class="col-sm-2 control-label">Last Name</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="last_name" placeholder="Last Name" maxlength="50" <?php if(!empty($form_last_name)) { echo "value=\"" . $form_last_name . "\""; }; ?> required>
					</div>
				</div>
				
				<hr>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Contact Number Home</label>
					<div class="col-sm-4">
						<input type="number" class="form-control" name="contact_number_home" placeholder="Contact Number Home" maxlength="20" <?php if(!empty($form_contact_number_home)) { echo "value=\"" . $form_contact_number_home . "\""; }; ?>>
					</div>
					
					<label class="col-sm-2 control-label">Contact Number Mobile</label>
					<div class="col-sm-4">
						<input type="number" class="form-control" name="contact_number_mobile" placeholder="Contact Number Mobile" maxlength="20" <?php if(!empty($form_contact_number_mobile)) { echo "value=\"" . $form_contact_number_mobile . "\""; }; ?>>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Email</label>
					<div class="col-sm-4">
						<input type="email" class="form-control" name="contact_email" placeholder="Email" maxlength="100" <?php if(!empty($form_contact_email)) { echo "value=\"" . $form_contact_email . "\""; }; ?>>
					</div>
					
					<label class="col-sm-2 control-label">Date Of Birth</label>
					<div class="col-sm-4">
						<input type="date" class="form-control" name="date_of_birth" placeholder="Date Of Birth" <?php if(!empty($form_date_of_birth)) { echo "value=\"" . $form_date_of_birth . "\""; }; ?>>
					</div>
				</div>
				
				<hr>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Address Line 1</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="address_line_1" placeholder="Address Line 1" maxlength="100" <?php if(!empty($form_address_line_1)) { echo "value=\"" . $form_address_line_1 . "\""; }; ?> required>
					</div>
					
					<label class="col-sm-2 control-label">Address Line 2</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="address_line_2" placeholder="Address Line 2" maxlength="100" <?php if(!empty($form_address_line_2)) { echo "value=\"" . $form_address_line_2 . "\""; }; ?>>
					</div>
					
					<label class="col-sm-2 control-label">Address Town</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="address_town" placeholder="Address Town" maxlength="100" <?php if(!empty($form_address_town)) { echo "value=\"" . $form_address_town . "\""; }; ?> required>
					</div>
					
					<label class="col-sm-2 control-label">Address County</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="address_county" placeholder="Address County" maxlength="100" <?php if(!empty($form_address_county)) { echo "value=\"" . $form_address_county . "\""; }; ?> required>
					</div>
					
					<label class="col-sm-2 control-label">Address Postcode</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="address_post_code" placeholder="Address Postcode" maxlength="20" <?php if(!empty($form_address_post_code)) { echo "value=\"" . $form_address_post_code . "\""; }; ?> required>
					</div>
				</div>
				
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