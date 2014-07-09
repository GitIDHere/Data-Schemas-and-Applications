<?php
	
	// Only proceed if the user is logged in.
	if(isset($_SESSION['username']) && isset($_SESSION['password'])){
		
		// If unable to connect to database, then kill the script processing.
		if(!require_once('db_login.php')){
			die("UNABLE TO CONNECT TO DATABASE: require_user_info.php");
		}	
		
		//Store the user's username in a variable which will be used to aquire the user's user_index.
		$unchecked_username = $_SESSION['username'];
		
		// Escape $unchecked_username just incase it has any special characters.
		$username = $mysqli->real_escape_string($unchecked_username);

		//Turn autocommit for transaction off so queries do not executed when an error arises.
		$mysqli->autocommit(FALSE);
		
		// Aquire the user's user_index so that it can be used to aquire the user's favorite tracks and artists. 
		$user_index_query = "SELECT user_index FROM users WHERE username = '$username'";
		$user_index_resource = $mysqli->query($user_index_query);
		$user_index = $user_index_resource->fetch_assoc();
		
	}else{
		//Redirect the user to the homepage if they are not logged in.
		header("LOCATION: index.php");
	}
?>