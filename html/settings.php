<?php
	// Require relevent information for settings.config.inc.php, including functions and database access
	require_once("../includes/settings.config.inc.php");
	
    // Require the Contact class to execute private functions
    require_once("../includes/class.contact.inc.php");

	// Set $page_name so that the title of each page is correct
	$page_name = PAGENAME_SETTINGS;
	
	// Check if $user is authenticated
	if(!$user->authenticated) {
		$user->logout('not_authenticated');
	}; // Close if(!$user->authenticated)
	
	// setting $datatables_required to 1 will ensure it is included in the <head> in layout.head.inc.php and so the <script> is called in the layout.footer.inc.php
	$datatables_required = 0;
	
    // Obtain all users from the database, which will be used to populate the table
	$users = $user->find_all();
	
	// Create new Log instance, and log the page view to the database
	$log = new Log('view');
	
    // Check if the form is submitted
    if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
        // Check if a file was uploaded
        if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == UPLOAD_ERR_OK) {
            // Get the file path
            $filePath = $_FILES['csvFile']['tmp_name'];

            // Create a new Contact instance
            $contact = new Contact();

            try {
                // Call the importCSV function
                $contact->importCSV($filePath);
                echo "<p>CSV file imported successfully.</p>";
            } catch (Exception $e) {
                echo "<p>Error importing CSV file: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p>No file uploaded or there was an upload error.</p>";
        }
    }

    function exportCSVTemplate() {
        // Create a new Contact instance
        $contact = new Contact();
        $filename = $contact->exportCSVTemplate();

        // Set headers to trigger the download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Read the file and output its contents
        readfile($filename);

        // Delete the file after download
        unlink($filename);
        exit;
    }

    function exportContacts() {
        // Create a new Contact instance
        $contact = new Contact();
        $filename = $contact->exportCSV();

        // Set headers to trigger the download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Read the file and output its contents
        readfile($filename);

        // Delete the file after download
        unlink($filename);
        exit;
    }
    
    // Check if the export template is requested
    if(isset($_GET['action'])){
        if ($_GET['action'] == 'exportCSVTemplate') {
            exportCSVTemplate();
        } else if($_GET['action'] == 'exportContacts') {
            exportContacts();
        }
    }


	// Require head content in the page
	require_once("../includes/layout.head.inc.php");
	// Requre navigation content in the page
	require_once("../includes/layout.navigation.inc.php");
?>
	
			<!-- CONTENT -->
			<?php $session->output_message(); ?>

            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Import Contacts</h3>
                                <form action="" method="post" enctype="multipart/form-data">
                                    
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" id="csvFile" accept=".csv" required>
                                        <label class="input-group-text" for="csvFile">Upload</label>
                                    </div>
                                    <button class="btn btn-info" type="submit" name="submit">Import Contacts</button>
                                    <button class="btn btn-info" onclick="window.location.href='?action=exportCSVTemplate'">Download Template File</button>

                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Export Contacts</h3>
                                <button class="btn btn-info" onclick="window.location.href='?action=exportContacts'">Export Contacts</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            
			<!-- /CONTENT -->

<?php
	// Requre footer content in the page, including any relevant scripts
	require_once("../includes/layout.footer.inc.php");
?>