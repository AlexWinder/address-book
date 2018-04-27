
    <body>
		<!-- Fixed navbar -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.php">Address Book</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li class="active"><a href="index.php"><i class="fa fa-address-book" aria-hidden="true"></i> Home</a></li>
						<li><a href="users.php"><i class="fa fa-users" aria-hidden="true"></i> Users</a></li>
						<li><a href="logs.php"><i class="fa fa-list" aria-hidden="true"></i> Logs</a></li>
						<li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>
		
		<div class="container pt-50">
			<h2><?php echo $page_name; ?></h2>
			
			<hr/>

