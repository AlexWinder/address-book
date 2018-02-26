<?php
	// Require relevent information for config.inc.php, including functions and database access
	require_once("../includes/config.inc.php");
	
	// Check that the user is logged in
	require_once("../includes/authenticated.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = "Add Contact";
	
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
			
			$id = generate_key(11);
			
			// Create SQL query to input into the database
			// If a field is optional, then pass through an if statement to set if it has a value, or set as null if not
			$sql = "INSERT INTO contacts (";
			$sql .= "contact_id, first_name, middle_name, last_name, contact_number_home, contact_number_mobile, contact_email, date_of_birth, address_line_1, address_line_2, address_town, address_county, address_post_code ";
			$sql .= ") VALUES ( ";
			$sql .= "'{$id}', '{$form_first_name}', ";
			
			// Middle name is optional, so can be sent as a null value
			if(!is_null($form_middle_name)) { $sql .= " '{$form_middle_name}', "; } else { $sql .= " NULL, "; };
			
			$sql .= "'{$form_last_name}', ";
			
			// Home phone number is optional so can be sent as a null value
			if(!is_null($form_home_number)) { $sql .= "'{$form_home_number}', "; } else { $sql .= " NULL, "; };
			
			// Mobile phone number is optional so can be sent as a null value
			if(!is_null($form_mobile_number)) {	$sql .= "'{$form_mobile_number}', "; } else { $sql .= " NULL, "; };
			
			// Email address is optional so can be sent as a null value
			if(!is_null($form_email_address)) { $sql .= "'{$form_email_address}', "; } else { $sql .= " NULL, "; };
			
			// Date of birth is optional so can be sent as a null value
			if(!is_null($form_date_of_birth)) { $sql .= "'{$form_date_of_birth}', "; } else { $sql .= " NULL, "; };
			
			$sql .= "'{$form_address_line_1}', ";
			
			// Address line 2 is optional so can be sent as a null value
			if(!is_null($form_address_line_2)) { $sql .= "'{$form_address_line_2}', "; } else { $sql .= " NULL, "; };
			
			$sql .= "'{$form_address_town}', '{$form_address_county}', '{$form_address_post_code}' ";
			$sql .= ")";

			$result = mysqli_query($db, $sql);
			
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
			<?php session_message(); ?>
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