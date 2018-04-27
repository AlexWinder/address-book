
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
					<?php //<a class="navbar-brand" href="index.php">Address Book</a> ?>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li <?php if($page_name == PAGENAME_INDEX) echo 'class="active"'; ?>><a href="<?php echo PAGELINK_INDEX; ?>"><i class="fa fa-address-book" aria-hidden="true"></i> <?php echo PAGENAME_INDEX; ?></a></li>
						<li <?php if($page_name == PAGENAME_USERS) echo 'class="active"'; ?>><a href="<?php echo PAGELINK_USERS; ?>"><i class="fa fa-users" aria-hidden="true"></i> <?php echo PAGENAME_USERS; ?></a></li>
						<li <?php if($page_name == PAGENAME_LOGS) echo 'class="active"'; ?>><a href="<?php echo PAGELINK_LOGS; ?>"><i class="fa fa-list" aria-hidden="true"></i> <?php echo PAGENAME_LOGS; ?></a></li>
						<li <?php if($page_name == PAGENAME_LOGOUT) echo 'class="active"'; ?>><a href="<?php echo PAGELINK_LOGOUT; ?>"><i class="fa fa-sign-out" aria-hidden="true"></i> <?php echo PAGENAME_LOGOUT; ?></a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>
		
		<div class="container pt-50">
			<h2><?php echo $page_name; ?></h2>
			
			<hr/>
