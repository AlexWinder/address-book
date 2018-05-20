<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_API;
	// Set $subpage_name as this page isn't the main section
	$subpage_name = PAGENAME_APIADD;
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	}; // Close if(!$user->authenticated)
	
	// Obtain a CSRF token to be used to prevent CSRF - this is stored in the $_SESSION
	$csrf_token = CSRF::get_token();
	
	// Generate new API instance
	$api = new API();
	
	// Obtain a token to be used as an API token
	if(!$session->get('api_token')) {
		// Set session 
		$session->set('api_token', $api->generate_token(12));
		$api_token = $session->get('api_token');
	} else {
		$api_token = $session->get('api_token');
	}; // Close if(!$session->get('api_token'))

	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");
?>
	
			<!-- CONTENT -->
			<?php $session->output_message(); ?>
			
			<form class="form-horizontal" action="" method="post">
				
				<div class="form-group">
					<label class="col-sm-2 control-label">API Token</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" value="<?php echo htmlentities($api_token); ?>" disabled>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Cosmetic Name*</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="cosmetic_name" placeholder="Give the API token a cosmetic name to allow you to easily identify it (Optional)" <?php if(isset($_POST["cosmetic_name"])){ echo "value=\"" . htmlentities($_POST["cosmetic_name"]) . "\""; }; ?>>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">IP Address**</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="ip_address" placeholder="Restrict the API token to a single IP address (Optional)" <?php if(isset($_POST["ip_address"])){ echo "value=\"" . htmlentities($_POST["ip_address"]) . "\""; }; ?>>
					</div>
				</div>
				
				<hr />
				
				<p>* = Optional field</p>
				<p>** = If no IP address is specified, then the API token will be able to be used from any IP address. If this is not intended then specify the single IP address from which the API token will be used.</p>
				
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