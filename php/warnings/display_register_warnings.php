<?php
		if(!empty($_GET['warning'])){
		
			$warning = $_GET['warning'];
			
			
			switch($warning){
			
				case "EMPTY_FIELDS":
					$message = '<div id="warning_container"><p>One or more of the fields were submitted was empty</p></div>';
				break;
				case "NUM_INPUT":
					$message = '<div id="warning_container"><p>Username cannot be all numbers</p></div>';
				break;
				case "EXISTS":
					$message = '<div id="warning_container"><p>The username entered already exists</p></div>';
				break;	
				case "PASS_NO_MATCH":
					$message = '<div id="warning_container"><p>Password and Re-password did not match</p></div>';
				break;
				case "SPECIALS":
					$message = '<div id="warning_container"><p>The username entered must not contain any punctuation or special characters </p></div>';
				default:
					$message = null;
			}
		
		}else{
		
			$message = null;
			
		}
?>