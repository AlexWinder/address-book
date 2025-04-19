
    <body>
		<!-- Fixed navbar -->
		<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo PAGELINK_INDEX; ?>">Contacts System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item <?php if($page_name == PAGENAME_INDEX || $page_name == PAGENAME_CONTACTS) echo 'active'; ?>">
                        <a class="nav-link" href="<?php echo PAGELINK_INDEX; ?>"><span class="iconify" data-icon="mdi-book-account"></span> <?php echo PAGENAME_INDEX; ?></a>
                    </li>
                    <li class="nav-item <?php if($page_name == PAGENAME_USERS) echo 'active'; ?>">
                        <a class="nav-link" href="<?php echo PAGELINK_USERS; ?>"><span class="iconify" data-icon="mdi-account-group" aria-hidden="true"></span> <?php echo PAGENAME_USERS; ?></a>
                    </li>
                    <li class="nav-item <?php if($page_name == PAGENAME_LOGS) echo 'active'; ?>">
                        <a class="nav-link" href="<?php echo PAGELINK_LOGS; ?>"><span class="iconify" data-icon="mdi-format-list-bulleted-square" aria-hidden="true"></span> <?php echo PAGENAME_LOGS; ?></a>
                    </li>
                    <li class="nav-item <?php if($page_name == PAGENAME_API) echo 'active'; ?>">
                        <a class="nav-link" href="<?php echo PAGELINK_API; ?>"><span class="iconify" data-icon="mdi-tune" aria-hidden="true"></span> <?php echo PAGENAME_API; ?></a>
                    </li>
                    <li class="nav-item <?php if($page_name == PAGENAME_SETTINGS) echo 'active'; ?>">
                        <a class="nav-link" href="<?php echo PAGELINK_SETTINGS; ?>"><span class="iconify" data-icon="mdi-power-plug" aria-hidden="true"></span> <?php echo PAGENAME_SETTINGS; ?></a>
                    </li>
                    <li class="nav-item <?php if($page_name == PAGENAME_LOGOUT) echo 'active'; ?>">
                        <a class="nav-link" href="<?php echo PAGELINK_LOGOUT; ?>"><span class="iconify" data-icon="mdi-logout" aria-hidden="true"></span> <?php echo PAGENAME_LOGOUT; ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
		
		<div class="container pt-55">
			<h2><?php if(isset($subpage_name)) { echo $subpage_name; } else { echo $page_name; } ?></h2>
			
			<hr/>
