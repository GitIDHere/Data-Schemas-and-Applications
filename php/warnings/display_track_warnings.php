<?php

	// When the user favourite a track, an appropriate message will be displayed to them based on what string value $_GET['warning'] contains.
	
	if(!empty($_GET['warning'])){
	
		// Get the content of $_GET['warning'];
		$warning = $_GET['warning'];
				
		switch($warning){
		
			case "SUCCESS":
				// Display a mesaage which confirms that thay have successfully favorited the track.
				$message = '<div id="warning_container"><p>Successfully favorited this track</p></div>';
			break;
			case "FAILED":
				// Display a mesaage which notfies that thay have already favorited the track.
				$message = '<div id="warning_container"><p>You have already favorited this track</p></div>';
			break;
			case "ERROR":
				// Notify the user that an error occured.
				$message = '<div id="warning_container"><p>Something went wrong whilst favoriting the track.</p></div>';
			break;
			default:
				$message = null;
		}	
		
	}else{
	
		$message = null;
		
	}
?>