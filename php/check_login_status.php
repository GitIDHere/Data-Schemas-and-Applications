<?php
	session_start();

	//Check if the user is logged in or not, and display navigation button acordingly.
	if(!empty($_SESSION['username']) && !empty($_SESSION['password'])){
	
		$user_status = '<a href="scripts/logout.php">Logout</a>';
		
	}else{
	
		$user_status = '<a href="login.php">Login</a>';
		
	}
?>