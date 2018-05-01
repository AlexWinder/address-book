<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Check that the user is logged in
	require_once("../includes/authenticated.inc.php");
	
	// setting $datatables_required to 1 will ensure it is included in the <head> in layout.head.inc.php and so the <script> is called in the layout.footer.inc.php
	$datatables_required = 1;
	// Table ID to relate to the datatable, as identified in the <table> and in the <script>, needed to identify which tables to make into datatables
	$datatables_table_id = "logs";
	// Set the datatable option to order the first column in a descending order
	$datatables_option = "\"order\": [[ 0, \"desc\" ]]";
	
	// Obtain all logs, using only the required fields, which will be used to populate the table
	$logs = find_columns_from_logs();
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_LOGS;
	
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
						<th>Date</th>
						<th>Action</th>
						<th>User</th>
						<th>IP</th>
					</tr>
				</thead>
				<tbody>
<?php
				// Cycle through each item obtained from find_columns_from_logs() and display them in the DataTable
				foreach($logs as $log){
				?>
					<tr>
						<td><?php echo htmlentities($log["datetime"]); ?></td>
						<td><?php echo htmlentities($log["action"]); ?></td>
						<td><?php echo htmlentities($log["user"]); ?></td>
						<td><?php echo htmlentities($log["ip"]); ?></td>
					</tr>
<?php
				// Closing the foreach loop once final item in $logs has been displayed
				};
					?>
				</tbody>
			</table>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>