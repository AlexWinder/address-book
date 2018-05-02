<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Check that the user is logged in
	require_once("../includes/authenticated.inc.php");
	
	// setting $datatables_required to 1 will ensure it is included in the <head> in layout.head.inc.php and so the <script> is called in the layout.footer.inc.php
	$datatables_required = 1;
	// Table ID to relate to the datatable, as identified in the <table> and in the <script>, needed to identify which tables to make into datatables
	$datatables_table_id = "contacts";
	// No datatable option required for this page
	$datatables_option = null;
	
	// Obtain all contacts from the database, which will be used to populate the table
	$contacts = new Contact();

	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_INDEX;
	
	// Log action of accessing the page
	log_action("view");
	
	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");
	
?>
			<!-- CONTENT -->
			<?php session_message(); ?>
			<table id="<?php echo $datatables_table_id; ?>">
				<thead>
					<tr>
						<th>Name</th>
						<th>Town</th>
						<th>Mobile Number</th>
						<th>Email Address</th>
					</tr>
				</thead>
				<tbody>
<?php
				// Cycle through each item obtained from find_all_contacts() and display them in the DataTable
				foreach($contacts->all as $contact){
				?>
					<tr>
						<td><a href="view-contact.php?i=<?php echo urlencode($contact["contact_id"]); ?>"><?php echo htmlentities(full_name($contact["first_name"], $contact["middle_name"], $contact["last_name"])); ?></a></td>
						<td><?php echo htmlentities($contact["address_town"]); ?></td>
						<td><?php if(!empty($contact["contact_number_mobile"])) { echo htmlentities($contacts->format_phone_number($contact["contact_number_mobile"])); } else { echo "NOT SPECIFIED"; }; ?></td>
						<td><?php if(!empty($contact["contact_email"])) { echo "<a href=\"mailto:" . htmlentities($contact["contact_email"]) . "\">" .  htmlentities($contact["contact_email"]) . "</a>"; } else { echo "NOT SPECIFIED"; }; ?></td>
					</tr>
<?php
				// Closing the foreach loop once final item in $contacts has been displayed
				};
					?>
				</tbody>
			</table>
			<a href="add-contact.php" type="button" class="btn btn-info">Add Contact</a>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>