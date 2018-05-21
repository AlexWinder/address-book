<?php
	// All site function are in this file which can not be attributed to a class
	
	// Function to return the correct page name from the $page_name and $subpage_name variables
	function page_name() {
		// Bring in $page_name and $subpage_name if set
		global $page_name;
		global $subpage_name;
		
		// If both $subpage_name and $page_name are set, return both
		if(isset($page_name) && isset($subpage_name)) {
			return $subpage_name . " - " . $page_name;
		} else { // Otherwise just return the $page_name
			return $page_name;
		};
	}; // Close function page_name()

	// Function to check if SITE_URL has a forward slash at the end to ensure that correct URL format is used
	function site_url() {
		// Trim any forward slashes at the end
		return rtrim(SITE_URL, '/');
	}

// EOF