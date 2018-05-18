<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_USERS;
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	}; // Close if(!$user->authenticated)
	
	// setting $datatables_required to 1 will ensure it is included in the <head> in layout.head.inc.php and so the <script> is called in the layout.footer.inc.php
	$datatables_required = 1;
	// Table ID to relate to the datatable, as identified in the <table> and in the <script>, needed to identify which tables to make into datatables
	$datatables_table_id = "users";
	// No datatable option required for this page
	$datatables_option = null;
	
	// Obtain all users from the database, which will be used to populate the table
	$users = $user->find_all();
	
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
						<th>Name</th>
						<th>Username</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?php
				// Cycle through each item in $users and display them in the DataTable
				foreach($users as $user){
				?>
					<tr>
						<td><?php echo htmlentities($user["full_name"]); ?></td>
						<td><?php echo htmlentities($user["username"]); ?></td>
						<td><a href="update-user.php?i=<?php echo urlencode($user["user_id"]); ?>">Update</a> &bull; <a href="delete-user.php?i=<?php echo urlencode($user["user_id"]); ?>">Delete</a></td>
					</tr>
<?php
				// Closing the foreach loop once final item in $contacts has been displayed
				};
					?>
				</tbody>
			</table>
			<a href="add-user.php" type="button" class="btn btn-info">Add User</a>
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>