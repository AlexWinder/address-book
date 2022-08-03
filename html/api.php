<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_API;
	
	// Check if user is making an API call - check if GET value has been sent
	if(isset($_GET['t']) && !empty($_GET['t'])) {
		// User is making an API call
		$api = new API($_GET['t'], $_GET['m'], $_GET['q']);

		// Output the array_result in JSON format
		header("Content-Type: application/json");
		http_response_code($api->http_response);
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
	$tokens = $api->find_all();
	
	// Create new Log instance, and log the page view to the database
	$log = new Log('view');
	
	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");
	
?>
			<!-- CONTENT -->
			<?php $session->output_message(); ?>
			
			<p>API calls are made using a HTTP GET request to this page, using the values:
			<ul>
				<li><strong>t</strong> for the API token</li>
				<li><strong>m</strong> for the API method</li>
				<li><strong>q</strong> for the API query string</li>
			</ul>
			
			<p>For example, <?php echo htmlentities(site_url()); ?>/<?php echo PAGELINK_API; ?>?t=<strong>APITOKEN</strong>&m=<strong>APIMETHOD</strong>&q=<strong>APIQUERY</strong></p>
			
			<p>Results are returned in a JSON array.</p>
			<ul>
				<li>The index of 'success' will indicate a '0' if a result couldn't be found, or a '1' if it could.</li>
				<li>The result of the API call is returned under the 'result' index.</li>
				<li>For troubleshooting, 'result_message' will display any errors should they occur.</li>
			</ul>
			<p>If an API token has no authorised IP address associated with it, then this means that the token can be used from any IP address. If this is not intended then specify an IP address when creating the API token.</p>
			
			<hr />
			
			<p>The following API methods are available:</p>
			
			<ul>
			<?php foreach($api->available_methods as $method => $description) { ?>
			<li><strong><?php echo htmlentities($method); ?></strong> - <?php echo htmlentities($description); ?></li>
			<?php }; // Close ?>
			</ul>
			
			<hr />
			
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
				foreach($tokens as $token){
				?>
					<tr>
						<td><?php echo htmlentities($token['api_id']); ?></td>
						<td><?php echo htmlentities($token['cosmetic_name'] ? htmlentities($token['cosmetic_name']) : 'Not specified'); ?></td>
						<td><?php echo htmlentities($token['ip'] ? $token['ip'] : 'Accessible from any IP'); ?></td>
						<td><a href="<?php echo PAGELINK_APIUPDATE; ?>?i=<?php echo urlencode($token['api_id']); ?>">Update</a> &bull; <a href="<?php echo PAGELINK_APIDELETE; ?>?i=<?php echo urlencode($token['api_id']); ?>">Delete</a></td>
					</tr>
<?php
				// Closing the foreach loop once final item in $contacts has been displayed
				};
					?>
				</tbody>
			</table>
			<a href="<?php echo PAGELINK_APIADD; ?>" type="button" class="btn btn-info">Add API Token</a>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>