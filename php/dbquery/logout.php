<?php
	session_start();

	if(isset($_SESSION['username']) && isset($_SESSION['password'])){
		session_destroy();
		header('LOCATION: ../index.php');
	} else{
		session_destroy();
		header('LOCATION: ../index.php');
	}
?>