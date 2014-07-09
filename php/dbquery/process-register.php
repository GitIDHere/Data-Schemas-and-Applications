<?php
	
	session_start();
	
	// instantiate the username, password and repassword into thier own variables.
	$entered_username = $_POST['username']; 
	$entered_password = $_POST['password']; 
	$entered_repassword = $_POST['repassword']; 
	
	//Check if the fields were set
	if(isset($entered_username) && isset($entered_password) && isset($entered_repassword) ){
		
		//Check if the fields were not empty.
		if(!empty($entered_username) && !empty($entered_password) && !empty($entered_repassword)){
			
			// If unable to connect to database, then kill the script processing.
			if(!require_once('db_login.php')){
				die("UNABLE TO CONNECT TO DATABASE: process-register.php");
			}
			
			//Only proceed if the entered username does not contain all numbers.
			if(!is_numeric($entered_username)){
				
				//Check if the username contains any special characters such as apostrophes.
				if(preg_match("/^[a-zA-Z]/i", $entered_username)){

					$password = md5($entered_password);
					$repassword = md5($entered_repassword);
					
					//Turn autocommit for transaction off so queries do not executed when an error arises.
					$mysqli->autocommit(FALSE);
					
					// Query selects a username from the database if it matches the one the user has entered.
					$user_query = "SELECT username FROM users WHERE username = '$entered_username'";
					$user_result = $mysqli->query($user_query);
					$user_arr = $user_result->fetch_assoc();
					
					// Turn both the aquired username and the entered username to lowercase since capital letters will be accepted as a miss match.
					$db_username = strtolower($user_arr['username']);
					$username = strtolower($entered_username);
					
					//Proceed only if the entered username does not exist in the table.
					if($username != $db_username){
						
						//Proceed if only the password and repassword matches.
						if($password === $repassword){
							
							//Create a query string which will insert the entered username and password into the database.
							$insert_query = "INSERT INTO users(username, password) VALUES ('$entered_username', '$password')";
							
							//Proceed only if the username and password have been inserted into the database.
							if($mysqli->query($insert_query)){
							
								//Commit to the queries that are currently queued
								$mysqli->commit();
								
								//Close the database connection.
								$mysqli->close();
								
								//Start a new session.
								session_start();
								
								// Set the username and password in their own $_SESSION variables.
								$_SESSION['username'] = $username;
								$_SESSION['password'] = $password;
								
								//Redirect the user to the members page.
								header('LOCATION: ../members.php');
								
							}else{
								//rollback all the queries which have not been committed.
								$mysqli->rollback();
								
								//Close the database connection.
								$mysqli->close();
								
								header("LOCATION: ../register.php?&warning=PASS_NO_MATCH");
							}
							
						}else{
							//rollback all the queries which have not been commited.
							$mysqli->rollback();
							
							//Close the database connection.
							$mysqli->close();
							
							// Redirect the user back to the register.php page and notify them that the password and repassword did not match.
							header("LOCATION: ../register.php?&warning=PASS_NO_MATCH");
						}
						
					}else{
						//rollback all the queries
						$mysqli->rollback();
						
						//Close the database connection.
						$mysqli->close();
						
						// Redirect the user back to the register.php page and notify them that the username already exists.
						header("LOCATION: ../register.php?&warning=EXISTS");
					}
				
				}else{
					//Redirect the user back to the register.php page and notify them that their username should contain only letters and numbers.
					header("LOCATION: ../register.php?&warning=SPECIALS");
				}
				
			}else{
				//Redirect the user back to the register.php page and notify them that the username cannot containe all numbers.
				header("LOCATION: ../register.php?&warning=NUM_INPUT");
			}
			
		}else{
			//Redirect the user back to the register.php page and notify them that one or more fields were empty.
			header("LOCATION: ../register.php?&warning=EMPTY_FIELDS");
		}
		
	}else{
		//Redirect the user back to the register.php page and notify them that one or more fields were empty.
		header("LOCATION: ../register.php?&warning=EMPTY_FIELDS");
	}

?>