<?php
	// Require relevent information for config.inc.php, including functions and database access
	require_once("../includes/config.inc.php");
	
	// Check that the user is logged in
	require_once("../includes/authenticated.inc.php");
	
	// If the value of i in GET exists
	if($_GET["i"]) {
		// Sanitise the GET value
		$id = mysql_prep(urldecode($_GET["i"]));
		
		// Find contact in database
		$contact = find_contact_by_id($id);
		
		// If a contact could be found
		if($contact) {
			// Assign various variables prior to populating the page
			$contact_full_name = htmlentities(full_name($contact["first_name"], $contact["middle_name"], $contact["last_name"]));
			$contact_home_number = htmlentities(remove_white_space($contact["contact_number_home"]));
			$contact_home_number_formatted = htmlentities(format_phone_number($contact["contact_number_home"]));
			$contact_mobile_number = htmlentities(remove_white_space($contact["contact_number_mobile"]));
			$contact_mobile_number_formatted = htmlentities(format_phone_number($contact["contact_number_mobile"]));
			$contact_email = htmlentities($contact["contact_email"]);
			$contact_full_address = htmlentities(full_address($contact["address_line_1"], $contact["address_line_2"], $contact["address_town"], $contact["address_county"], $contact["address_post_code"]));
			
			// If the date of birth field is set to NULL this will generate a date of 1970-01-01 when passed through the cosmetic_date_from_mysqldate() function_exists
			// So check if the field is empty, if it is then it is not needed in the form
			if(!empty($contact["date_of_birth"])) {
				$contact_date_of_birth = htmlentities(cosmetic_date_from_mysqldate($contact["date_of_birth"]));
			} else {
				$contact_date_of_birth = null;
			};
			
			// Set $page_name so that the title of each page is correct
			$page_name = "View Contact - " . $contact_full_name;
			
			// Log action of accessing the page
			log_action("view");
			
		} else {
			// Contact could not be found in the database
			// Set $page_name so that the title of each page is correct - contact not found
			$page_name = "View Contact - Contact Not Found";
			// Log user accessing incorrect GET value
			log_action("not_found", $logging["page"]["not_exist"]);
			// Send message and redirect
			$_SESSION["message"] = construct_message($notification["contact"]["view"]["not_found"], "danger");
			redirect_to("index.php");
		}
	} else {
		// Value of i in GET doesn't exist, send message and redirect
		// Set $page_name so that the title of each page is correct - GET value not correct
		$page_name = "View Contact - Contact Not Found";
		// Log user accessing incorrect GET key
		log_action("not_found", $logging["page"]["not_exist"]);
		$_SESSION["message"] = construct_message($notification["contact"]["view"]["not_found"], "danger");
		redirect_to("index.php");
	};
	
	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");
?>
			
			<!-- CONTENT -->
			<?php session_message(); ?>
			<div class="row">
				<div class="col-xs-4 col-sm-3">
					<h3>Full Name:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><?php echo $contact_full_name; ?></h3>
				</div>
			</div>
			
				
			<?php if(!empty($contact_home_number)) { ?>
			<div class="row">
				<div class="col-xs-4 col-sm-3">
					<h3>Home Number:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><a href="tel:<?php echo $contact_home_number; ?>"><?php echo $contact_home_number_formatted; ?></a></h3>
				</div>
			</div>
			<?php }; ?>
				
			<?php if(!empty($contact_mobile_number)) { ?>
			<div class="row">
				<div class="col-xs-4 col-sm-3">
					<h3>Mobile Number:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><a href="tel:<?php echo $contact_mobile_number; ?>"><?php echo $contact_mobile_number_formatted; ?></a></h3>
				</div>
			</div>
			<?php }; ?>
			
			<?php if(!empty($contact_email)) { ?>
			<div class="row">	
				<div class="col-xs-4 col-sm-3">
					<h3>Email Address:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><a href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a></h3>
				</div>
			</div>
			<?php }; ?>
				
			<?php if(!empty($contact_date_of_birth)) { ?>
			<div class="row">
				<div class="col-xs-4 col-sm-3">
					<h3>Date Of Birth:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><?php echo $contact_date_of_birth; ?></h3>
				</div>
			</div>
			<?php }; ?>
			
			<div class="row">
				<div class="col-xs-4 col-sm-3">
					<h3>Address:</h3>
				</div>
				
				<div class="col-xs-8 col-sm-9">
					<h3><?php echo $contact_full_address; ?></h3>
				</div>
			</div>
			
			<hr>
			
			<a href="update-contact.php?i=<?php echo urlencode($contact["contact_id"]); ?>" type="button" role="button" class="btn btn-info">Update Contact</a>
			<a href="delete-contact.php?i=<?php echo urlencode($contact["contact_id"]); ?>" type="button" role="button" class="btn btn-danger">Delete Contact</a>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>