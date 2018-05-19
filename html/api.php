<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_API;
	
	// Check if user is making an API call - check if GET value has been sent
	if(isset($_GET['t']) && !empty($_GET['t'])) {
		// User is making an API call
		$api = new API($_GET['t'], $_GET['a'], $_GET['q']);
		
		// Output the array_result in JSON format
		echo json_encode($api->array_result);
		
		// Stop the page loading any further
		die();
	}; // Close if(isset($_GET['t']) && !empty($_GET['t'])) - user has not sent an API request
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	}; // Close if(!$user->authenticated)
	
	// setting $datatables_required to 1 will ensure it is included in the <head> in layout.head.inc.php and so the <script> is called in the layout.footer.inc.php
	$datatables_required = 1;
	// Table ID to relate to the datatable, as identified in the <table> and in the <script>, needed to identify which tables to make into datatables
	$datatables_table_id = 'api';
	// No datatable option required for this page
	$datatables_option = null;

	// Create a new API instance, mainly for obtaining all tokens from the database
	$api = new API();
	$api = $api->find_all();
	
	// Create new Log instance, and log the page view to the database
	$log = new Log('view');
	
	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");
	
?>
			<!-- CONTENT -->
			<?php $session->output_message(); ?>
			
			<table id="<?php echo $datatables_table_id; ?>">
				<thead>
					<tr>
						<th>API Token</th>
						<th>Name</th>
						<th>Authorised IP</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?php
				// Cycle through each item in $contacts and display them in the DataTable
				foreach($api as $token){
				?>
					<tr>
						<td><?php echo htmlentities($token['api_id']); ?></td>
						<td><?php echo htmlentities($token['cosmetic_name']); ?></td>
						<td><?php echo htmlentities($token['ip'] ? $token['ip'] : 'N/A'); ?></td>
						<td><a href="<?php echo PAGELINK_APIUPDATE; ?>?i=<?php echo urlencode($token['api_id']); ?>">Update</a> &bull; <a href="<?php echo PAGELINK_APIDELETE; ?>?i=<?php echo urlencode($token['api_id']); ?>">Delete</a></td>
					</tr>
<?php
				// Closing the foreach loop once final item in $contacts has been displayed
				};
					?>
				</tbody>
			</table>
			<a href="<?php echo PAGELINK_APIADD; ?>" type="button" class="btn btn-info">New API Token</a>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>