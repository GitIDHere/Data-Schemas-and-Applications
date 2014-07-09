<?php
	
	if(!empty($_GET['warning'])){
	
		$warning = $_GET['warning'];
		
		if($warning == "EMPTY_FIELDS"){
		
			$message = '<div id="warning_container"><p>Username or Password was submitted empty</p></div>';
			
		}else if($warning == "NOT_RECOG"){
		
			$message = '<div id="warning_container"><p>The Username or Password provided was not found in the database</p></div>';
			
		}
		
	}else{
	
		$message = null;
		
	}
?>