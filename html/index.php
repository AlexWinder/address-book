<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_INDEX;
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	}; // Close if(!$user->authenticated)
	
	// setting $datatables_required to 1 will ensure it is included in the <head> in layout.head.inc.php and so the <script> is called in the layout.footer.inc.php
	$datatables_required = 1;
	// Table ID to relate to the datatable, as identified in the <table> and in the <script>, needed to identify which tables to make into datatables
	$datatables_table_id = 'contacts';
	// No datatable option required for this page
	$datatables_option = 'dom: \'Bfrtip\', // Define the layout for buttons and table controls
        buttons: [
            {
                extend: \'colvis\', // Column visibility button
                columns: \':not(.noVis)\',
                collectionLayout: \'one-column\',
                text: \'Column visibility\',
                titleAttr: \'Select columns to display\'
            }
        ],
        columnDefs: [
            {
                targets: -1, 
                className: \'noExport noVis\'
            }
        ],
		stateSave: true';
	
	// Obtain all contacts from the database, which will be used to populate the table
	$contacts = new Contact();
	
	// Create new Log instance, and log the page view to the database
	$log = new Log('view');
	
	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");
	
?>
			<!-- CONTENT -->
			<?php $session->output_message(); ?>
			
			<table class="display nowrap" id="<?php echo $datatables_table_id; ?>">
				<thead>
					<tr>
						<th><?php echo getenv('TABLE_CONTACT_NAME'); ?></th>
						<th><?php echo getenv('TABLE_CONTACT_ADDRESS_1'); ?></th>
						<th><?php echo getenv('TABLE_CONTACT_ADDRESS_2'); ?></th>
						<th><?php echo getenv('TABLE_CONTACT_TOWN'); ?></th>
						<th><?php echo getenv('TABLE_CONTACT_POSTAL_CODE'); ?></th>
						<th><?php echo getenv('TABLE_CONTACT_COUNTY'); ?></th>
						<th><?php echo getenv('TABLE_CONTACT_MOBILE_NUMBER'); ?></th>
						<th><?php echo getenv('TABLE_CONTACT_HOME_NUMBER'); ?></th>
						<th><?php echo getenv('TABLE_CONTACT_EMAIL'); ?></th>
						<th><?php echo getenv('TABLE_CONTACT_DATE_OF_BIRTH'); ?></th>
						<th class="noVis">Actions</th>
					</tr>
				</thead>
				<tbody>
<?php
				// Cycle through each item in $contacts and display them in the DataTable
				foreach($contacts->all as $contact){
				?>
					<tr>
						<td><?php echo htmlentities($contacts->full_name($contact["first_name"], $contact["middle_name"], $contact["last_name"])); ?></td>
						<td><?php echo htmlentities($contact["address_line_1"]); ?></td>
						<td><?php echo htmlentities($contact["address_line_2"]); ?></td>
						<td><?php echo htmlentities($contact["address_town"]); ?></td>
						<td><?php echo htmlentities($contact["address_post_code"]); ?></td>
						<td><?php echo htmlentities($contact["address_county"]); ?></td>
						<td><?php if(!empty($contact["contact_number_mobile"])) { echo htmlentities($contacts->format_phone_number($contact["contact_number_mobile"])); } else { echo "NOT SPECIFIED"; }; ?></td>
						<td><?php if(!empty($contact["contact_number_home"])) { echo htmlentities($contacts->format_phone_number($contact["contact_number_home"])); } else { echo "NOT SPECIFIED"; }; ?></td>
						<td><?php if(!empty($contact["contact_email"])) { echo "<a href=\"mailto:" . htmlentities($contact["contact_email"]) . "\">" .  htmlentities($contact["contact_email"]) . "</a>"; } else { echo "NOT SPECIFIED"; }; ?></td>
						<td><?php if(!empty($contact["date_of_birth"])) { echo htmlentities($contact["date_of_birth"]); } else { echo "NOT SPECIFIED"; }; ?></td>
						<td><a href="<?php echo PAGELINK_CONTACTSVIEW; ?>?i=<?php echo urlencode($contact["contact_id"]); ?>">View</a></td>
					</tr>
<?php
				// Closing the foreach loop once final item in $contacts has been displayed
				};
					?>
				</tbody>
			</table>
			<a href="<?php echo PAGELINK_CONTACTSADD; ?>" type="button" class="btn btn-info">Add Contact</a>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>