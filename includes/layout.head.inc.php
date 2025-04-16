<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo page_name(); ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- jQuery -->
		<script src="assets/libraries/jQuery/3.6.4/jquery-3.6.4.min.js"></script>
<?php
		if(isset($datatables_required) && $datatables_required == 1) {
			$datatables_source = <<<FILEDOC
		
		<!-- DataTables -->
		<link rel="stylesheet" href="assets/libraries/dataTables/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="assets/libraries/dataTables/css/buttons.dataTables.min.css">

		<script src="assets/libraries/dataTables/js/jquery.dataTables.min.js"></script>
		<script src="assets/libraries/dataTables/js/dataTables.buttons.min.js"></script>
		<script src="assets/libraries/dataTables/js/buttons.colVis.min.js"></script>
		<script src="assets/libraries/dataTables/js/buttons.html5.min.js"></script>

FILEDOC;
			echo $datatables_source;
		};
?>
		<!-- Bootstrap CSS and JS -->
		<link rel="stylesheet" href="assets/libraries/bootstrap/5.3.3/css/bootstrap.min.css">
		<script src="assets/libraries/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

		<!-- Material Icons -->
		<script src="assets/libraries/iconify/1.0.6/iconify.min.js"></script>

		<!-- Main CSS -->
		<link rel="stylesheet" href="assets/css/main.css?v=<?php echo time(); ?>">

	</head>
	