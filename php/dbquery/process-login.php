<?php

	session_start();

	$entered_username = $_POST['username'];
	$entered_password = $_POST['password'];

	if(isset($entered_username) && isset($entered_password)){
		
		// If unable to connect to database, then kill the script processing.
		if(!require_once('db_login.php')){
			die("UNABLE TO CONNECT TO DATABASE: process-login.php");
		}
		
		// used real_escape_string to prevent SQL injection.
		$escaped_username = $mysqli->real_escape_string($entered_username);
		$escaped_password = $mysqli->real_escape_string($entered_password);
		
		//md5 the password for security protection.
		$password = md5($escaped_password);
		
		if(!empty($escaped_username) && !empty($password)){
			
			//Mysqli's Autocommit is turned off to prevent execution of queries when an error arises.
			$mysqli->autocommit(FALSE);
			
			// Get the username and password from  the database if they match the username and password supplied by this user.
			$query_user = "SELECT username, password FROM users WHERE username = '$escaped_username' AND password = '$password'";
			$user_result = 	$mysqli->query($query_user);
			$user_credential = $user_result->fetch_assoc();
			
			//Turn $escaped_username and $user_credential['username'] to lowercase so that they can be matched equally.
			$username = strtolower($escaped_username);
			$db_username = strtolower($user_credential['username']);
			
			//Proceed only if the username and password supplied matched those aquired from the database. 
			if($username == $db_username && $password == $user_credential['password']){
				
				//Commit to the queries that are queued
				$mysqli->commit();
				
				//Close the database connection.
				$mysqli->close();
				
				/*Check to see if the $_SESSION is already set because a user might already be logged in,
					and they might want to log into a different account. */
				if(isset($_SESSION['username']) && isset($_SESSION['password'])){
					
					// Check if the username and password provided are the same as the ones currently set in the $_SESSION variables.
					if($_SESSION['username'] ===  $username && $_SESSION['password'] === $password){
			
						// Redirect the user back to the homepage.
						header("LOCATION: ../index.php");
						
					}else{
						
						/*If the supplied username and passwords do not match with the $_SESSION variables, then destroy the session
							and start a new one, setting the supplied username and passwords in thier own individual $_SESSION variables*/
						
						session_destroy();
						
						session_start();
						$_SESSION['username'] = $user_credential['username'];
						$_SESSION['password'] = $user_credential['password'];
						
						//redirect the user back to the homepage.
						header("LOCATION: ../index.php");
					}
					
				}else{
				
					// If $_SESSION is not set then start a new $_SESSION and set the username and password in individual session variables.
					
					session_start();
					
					$_SESSION['username'] = $user_credential['username'];
					$_SESSION['password'] = $user_credential['password'];
					
					//Redirect the user back to the homepage.
					header("LOCATION: ../index.php");
				}
				
			}else{
				//Rollback the queries that were queued.
				$mysqli->rollback();
				
				//Close the database connection.
				$mysqli->close();
				
				/* Redirect the user back to the login page and display message to tell them that username and passwords was not recognised. */
				header("LOCATION: ../login.php?&warning=NOT_RECOG");
			}
			
		}else{
			/* Redirect the user back to the login page and display message to tell them that the fields were empty. */
			header("LOCATION: ../login.php?&warning=EMPTY_FIELDS");
		}
		
	}else{
		/* Redirect the user back to the login page and display message to tell them that the fields were empty. */
		header("LOCATION: ../login.php?&warning=EMPTY_FIELDS");
	}
?>