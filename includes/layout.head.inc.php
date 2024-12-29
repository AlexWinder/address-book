<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo page_name(); ?></title>
		
		<!-- jQuery -->
		<script src="assets/jQuery/3.5.0/jquery.min.js"></script>
<?php
		if(isset($datatables_required) && $datatables_required == 1) {
			$datatables_source = <<<FILEDOC
		
		<!-- DataTables -->
		<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

		<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
		<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

		
FILEDOC;
			echo $datatables_source;
		};
?>
		
		<!-- Twitter Bootstrap -->
		<!-- Minified CSS -->
		<link rel="stylesheet" href="assets/bootstrap/3.4.1/css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="assets/bootstrap/3.4.1/css/bootstrap-theme.min.css">
		<!-- Minified JavaScript for Bootstrap -->
		<script src="assets/bootstrap/3.4.1/js/bootstrap.min.js"></script>

		<!-- Material Icons -->
		<script src="//code.iconify.design/1/1.0.6/iconify.min.js"></script>

		<!-- Main CSS -->
		<link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">

	</head>
	