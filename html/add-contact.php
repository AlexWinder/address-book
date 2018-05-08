<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Check that the user is logged in
	require_once("../includes/authenticated.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_CONTACTS;
	// Set $subpage_name as this page isn't the main section
	$subpage_name = "Add Contact";
	
	// Obtain a CSRF token to be used to prevent CSRF - this is stored in the $_SESSION
	$csrf_token = CSRF::get_token();
	
	// If submit button has been pressed then process the form
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
		
		// Check that the submitted CSRF token is the same as the one in the $_SESSION to prevent cross site request forgery
		if(!CSRF::check_token($_POST['csrf_token']))									{ $errors[] = $validation['invalid']['security']['csrf_token']; };
		
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
			
			// Initialise a new Contact object
			$contact = new Contact();
			
			// Prepare an array to be used to insert into the database
			$fields = array();
			
			// Populate the $fields array with values where applicable
			!empty($_POST['first_name']) 				? $fields['first_name'] = $_POST['first_name']														: $fields['first_name'] = null;
			!empty($_POST['middle_name']) 				? $fields['middle_name'] = $_POST['middle_name'] 													: $fields['middle_name'] = null;
			!empty($_POST['last_name']) 				? $fields['last_name'] = $_POST['last_name'] 														: $fields['last_name'] = null;
			!empty($_POST['contact_number_home']) 		? $fields['contact_number_home'] = $contact->remove_white_space($_POST['contact_number_home']) 		: $fields['contact_number_home'] = null;
			!empty($_POST['contact_number_mobile']) 	? $fields['contact_number_mobile'] = $contact->remove_white_space($_POST['contact_number_mobile'])	: $fields['contact_number_mobile'] = null;
			!empty($_POST['contact_email']) 			? $fields['contact_email'] = mysql_prep($_POST['contact_email']) 									: $fields['contact_email'] = null;
			!empty($_POST['date_of_birth']) 			? $fields['date_of_birth'] = mysql_prep($_POST['date_of_birth']) 									: $fields['date_of_birth'] = null;
			!empty($_POST['address_line_1']) 			? $fields['address_line_1'] = mysql_prep( $_POST['address_line_1']) 								: $fields['address_line_1'] = null;
			!empty($_POST['address_line_2']) 			? $fields['address_line_2'] = mysql_prep($_POST['address_line_2']) 									: $fields['address_line_2'] = null;
			!empty($_POST['address_town']) 				? $fields['address_town'] = mysql_prep($_POST['address_town']) 										: $fields['address_town'] = null;
			!empty($_POST['address_county']) 			? $fields['address_county'] = mysql_prep($_POST['address_county']) 									: $fields['address_county'] = null;
			!empty($_POST['address_post_code']) 		? $fields['address_post_code'] = mysql_prep($_POST['address_post_code']) 							: $fields['address_post_code'] = null;
			
			// Create the new contact, inserting the fields from the $fields array
			$result = $contact->create($fields);
			
			if($result){
				// Contact successfully added to the database
				// Log action of add entry success, with contact added 
				log_action("add_success", "Contact added: " . $form_first_name . " " . $form_last_name . " from " . $form_address_town . " (" . $id . ")");
				$_SESSION["message"] = construct_message($notification["contact"]["add"]["success"], "success");
				redirect_to("index.php");
			} else {
				$_SESSION["message"] = construct_message($notification["contact"]["add"]["failure"], "danger");
				// Log action of database entry failing
				log_action("add_failed", $logging["database"]["failure"]);
			};
		} else {
			// Form field validation has failed - $errors array is not empty
			// If there are any error messages in the $errors array then display them to the screen
			$_SESSION["message"] = validation_failure_message($errors);
			
			// Log action of failing form process
			$log_errors = log_validation_failures($errors);
			log_action("add_failed", $log_errors);
		};
	} else {
		// Form has not been submitted
		// Log action of accessing the page
		log_action("view");
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
					<label class="col-sm-2 control-label">First Name</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="first_name" placeholder="First Name" maxlength="50" <?php if(isset($_POST["first_name"])){ echo "value=\"" . htmlentities($_POST["first_name"]) . "\""; }; ?> required>
					</div>
					
					<label class="col-sm-2 control-label">Middle Name</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="middle_name" placeholder="Middle Name" maxlength="50" <?php if(isset($_POST["middle_name"])){ echo "value=\"" . htmlentities($_POST["middle_name"]) . "\""; }; ?>>
					</div>
					
					<label class="col-sm-2 control-label">Last Name</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="last_name" placeholder="Last Name" maxlength="50" <?php if(isset($_POST["last_name"])){ echo "value=\"" . htmlentities($_POST["last_name"]) . "\""; }; ?> required>
					</div>
				</div>
				
				<hr>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Contact Number Home</label>
					<div class="col-sm-4">
						<input type="number" class="form-control" name="contact_number_home" placeholder="Contact Number Home" maxlength="20" <?php if(isset($_POST["contact_number_home"])){ echo "value=\"" . htmlentities($_POST["contact_number_home"]) . "\""; }; ?>>
					</div>
					
					<label class="col-sm-2 control-label">Contact Number Mobile</label>
					<div class="col-sm-4">
						<input type="number" class="form-control" name="contact_number_mobile" placeholder="Contact Number Mobile" maxlength="20" <?php if(isset($_POST["contact_number_mobile"])){ echo "value=\"" . htmlentities($_POST["contact_number_mobile"]) . "\""; }; ?>>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Email</label>
					<div class="col-sm-4">
						<input type="email" class="form-control" name="contact_email" placeholder="Email" maxlength="100" <?php if(isset($_POST["contact_email"])){ echo "value=\"" . htmlentities($_POST["contact_email"]) . "\""; }; ?>>
					</div>
					
					<label class="col-sm-2 control-label">Date Of Birth</label>
					<div class="col-sm-4">
						<input type="date" class="form-control" name="date_of_birth" placeholder="Date Of Birth" <?php if(isset($_POST["date_of_birth"])){ echo "value=\"" . htmlentities($_POST["date_of_birth"]) . "\""; }; ?>>
					</div>
				</div>
				
				<hr>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Address Line 1</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="address_line_1" placeholder="Address Line 1" maxlength="100" <?php if(isset($_POST["address_line_1"])){ echo "value=\"" . htmlentities($_POST["address_line_1"]) . "\""; }; ?> required>
					</div>
					
					<label class="col-sm-2 control-label">Address Line 2</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="address_line_2" placeholder="Address Line 2" maxlength="100" <?php if(isset($_POST["address_line_2"])){ echo "value=\"" . htmlentities($_POST["address_line_2"]) . "\""; }; ?>>
					</div>
					
					<label class="col-sm-2 control-label">Address Town</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="address_town" placeholder="Address Town" maxlength="100" <?php if(isset($_POST["address_town"])){ echo "value=\"" . htmlentities($_POST["address_town"]) . "\""; }; ?> required>
					</div>
					
					<label class="col-sm-2 control-label">Address County</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="address_county" placeholder="Address County" maxlength="100" <?php if(isset($_POST["address_county"])){ echo "value=\"" . htmlentities($_POST["address_county"]) . "\""; }; ?> required>
					</div>
					
					<label class="col-sm-2 control-label">Address Postcode</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="address_post_code" placeholder="Address Postcode" maxlength="20" <?php if(isset($_POST["address_post_code"])){ echo "value=\"" . htmlentities($_POST["address_post_code"]) . "\""; }; ?> required>
					</div>
				</div>
				
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