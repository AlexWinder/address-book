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
		<link rel="stylesheet" type="text/css" href="assets/DataTables/1.10.15/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="assets/DataTables/1.10.15/js/jquery.dataTables.js"></script>
		
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
		
		<!-- Font Awesome -->
		<link href="assets/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		
		<!-- Main CSS -->
		<link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">

	</head>
	