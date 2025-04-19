<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_CONTACTS;
	// Set $subpage_name as this page isn't the main section
	$subpage_name = PAGENAME_CONTACTSADD;
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	}; // Close if(!$user->authenticated)
	
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
			!empty($_POST['contact_email']) 			? $fields['contact_email'] = $_POST['contact_email'] 												: $fields['contact_email'] = null;
			!empty($_POST['date_of_birth']) 			? $fields['date_of_birth'] = $_POST['date_of_birth'] 												: $fields['date_of_birth'] = null;
			!empty($_POST['address_line_1']) 			? $fields['address_line_1'] =  $_POST['address_line_1'] 											: $fields['address_line_1'] = null;
			!empty($_POST['address_line_2']) 			? $fields['address_line_2'] = $_POST['address_line_2'] 												: $fields['address_line_2'] = null;
			!empty($_POST['address_town']) 				? $fields['address_town'] = $_POST['address_town']													: $fields['address_town'] = null;
			!empty($_POST['address_county']) 			? $fields['address_county'] = $_POST['address_county']												: $fields['address_county'] = null;
			!empty($_POST['address_post_code']) 		? $fields['address_post_code'] = $_POST['address_post_code'] 										: $fields['address_post_code'] = null;
			
			// Create the new contact, inserting the fields from the $fields array
			$result = $contact->create($fields);
			
			if($result){
				// Contact successfully added to the database
				// Log action of add entry success, with contact added 
				// Create new Log instance, and log the action to the database
				$log = new Log('contact_add_success', 'Contact of ' . $fields['first_name'] . ' ' . $fields['last_name'] . ' from ' . $fields['address_town'] . ' successfully created.');
				// Add session message
				$session->message_alert($notification["contact"]["add"]["success"], "success");
				// Redirect the user
				Redirect::to(PAGELINK_INDEX);
			} else {
				// Add session message
				$session->message_alert($notification["contact"]["add"]["failure"], "danger");
				// Log action of database entry failing
				// Create new Log instance, and log the action to the database
				$log = new Log('contact_add_failed', 'database');
			};
		} else {
			// Form field validation has failed - $errors array is not empty
			// If there are any error messages in the $errors array then display them to the screen
			$session->message_validation($errors);
			// Log action of failing form process
			// Create new Log instance, and log the action to the database
			$log = new Log('contact_add_failed', 'Failed user add due to form validation errors.');
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
			
			<form class="container form-horizontal" action="" method="post">
				<div class="row g-3">
					<h5>Name</h5>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="text" class="form-control form-control-sm" id="first_name" name="first_name" maxlength="50" <?php if(isset($_POST["first_name"])){ echo "value=\"" . htmlentities($_POST["first_name"]) . "\""; }; ?> required>
							<label for="first_name"><?php echo TABLE_CONTACT_FIRST_NAME; ?></label>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="text" class="form-control form-control-sm" id="middle_name" name="middle_name" maxlength="50" <?php if(isset($_POST["middle_name"])){ echo "value=\"" . htmlentities($_POST["middle_name"]) . "\""; }; ?> required>
							<label for="middle_name"><?php echo TABLE_CONTACT_MIDDLE_NAME; ?></label>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="text" class="form-control form-control-sm" id="last_name" name="last_name" maxlength="50" <?php if(isset($_POST["last_name"])){ echo "value=\"" . htmlentities($_POST["last_name"]) . "\""; }; ?> required>
							<label for="last_name"><?php echo TABLE_CONTACT_LAST_NAME; ?></label>
						</div>
					</div>					
				</div>
				<hr>
				<!-- Phone -->
				<div class="row g-3">
					<h5>Details</h5>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="tel" class="form-control" id="contact_number_home" name="contact_number_home" maxlength="20" <?php if(isset($_POST["contact_number_home"])){ echo "value=\"" . htmlentities($_POST["contact_number_home"]) . "\""; }; ?>>
							<label for="contact_number_home"><?php echo TABLE_CONTACT_HOME_NUMBER; ?></label>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="tel" class="form-control" id="contact_number_mobile" name="contact_number_mobile" maxlength="20" <?php if(isset($_POST["contact_number_mobile"])){ echo "value=\"" . htmlentities($_POST["contact_number_mobile"]) . "\""; }; ?>>
							<label for="contact_number_mobile"><?php echo TABLE_CONTACT_MOBILE_NUMBER; ?></label>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="email" class="form-control" id="contact_email" name="contact_email" maxlength="100" <?php if(isset($_POST["contact_email"])){ echo "value=\"" . htmlentities($_POST["contact_email"]) . "\""; }; ?>>
							<label for="contact_email"><?php echo TABLE_CONTACT_EMAIL; ?></label>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="date" class="form-control" id="date_of_birth" name="date_of_birth" <?php if(isset($_POST["date_of_birth"])){ echo "value=\"" . htmlentities($_POST["date_of_birth"]) . "\""; }; ?>>
							<label for="date_of_birth"><?php echo TABLE_CONTACT_DATE_OF_BIRTH; ?></label>
						</div>
					</div>
				</div>
				<hr>
				<!-- Address -->
				<div class="row g-3">
					<h5>Address</h5>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="text" class="form-control" id="address_line_1" name="address_line_1" maxlength="100" <?php if(isset($_POST["address_line_1"])){ echo "value=\"" . htmlentities($_POST["address_line_1"]) . "\""; }; ?> required>
							<label for="address_line_1"><?php echo TABLE_CONTACT_ADDRESS_1; ?></label>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="text" class="form-control" id="address_line_2" name="address_line_2" maxlength="100" <?php if(isset($_POST["address_line_2"])){ echo "value=\"" . htmlentities($_POST["address_line_2"]) . "\""; }; ?>>
							<label for="address_line_2"><?php echo TABLE_CONTACT_ADDRESS_2; ?></label>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="text" class="form-control" id="address_town" name="address_town" maxlength="100" <?php if(isset($_POST["address_town"])){ echo "value=\"" . htmlentities($_POST["address_town"]) . "\""; }; ?> required>
							<label for="address_town"><?php echo TABLE_CONTACT_TOWN; ?></label>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="text" class="form-control" id="address_county" name="address_county" maxlength="100" <?php if(isset($_POST["address_county"])){ echo "value=\"" . htmlentities($_POST["address_county"]) . "\""; }; ?> required>
							<label for="address_county"><?php echo TABLE_CONTACT_COUNTY; ?></label>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-floating">
							<input type="text" class="form-control" id="address_post_code" name="address_post_code" maxlength="20" <?php if(isset($_POST["address_post_code"])){ echo "value=\"" . htmlentities($_POST["address_post_code"]) . "\""; }; ?> required>
							<label for="address_post_code"><?php echo TABLE_CONTACT_POSTAL_CODE; ?></label>
						</div>
					</div>
				</div>
				<input type="hidden" name="csrf_token" value="<?php echo htmlentities($csrf_token); ?>"/>
				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
					</div>
				</div>

			</form>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>