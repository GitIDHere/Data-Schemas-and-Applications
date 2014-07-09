<?php

	session_start();

	// Only allow for favoriting of artists when sessions are set.
	if(!empty($_SESSION['username']) && !empty($_SESSION['password'])){

		// If unable to connect to database, then kill the script processing.
		if(!require_once('db_login.php')){
			die("UNABLE TO CONNECT TO DATABASE: process-fav-artist.php");
		}
	
		$username = $_SESSION['username'];
		$password = $_SESSION['password'];
		
		// Check for if the right queries have been passed.
		if(!empty($_GET['deezer_id']) && !empty($_GET['echonest_id']) && !empty($_GET['artist_name']) && !empty($_GET['artist_image'])){
			
			
			$echonest_api_key = "DID5RQHXRZFUFIEWX";
				
			$deezer_id = $_GET['deezer_id'];
			$echonest_id = $_GET['echonest_id'];
			
			$unchecked_artist_name = $_GET['artist_name'];
			// The sanitise() function is a custom function which escapes certain characters to prevent SQL injection. 
			$artist_name = sanitise($unchecked_artist_name, $mysqli);

			$unchecked_artist_image = $_GET['artist_image'];
			$artist_image = sanitise($unchecked_artist_image, $mysqli);
			
			//Mysqli's Autocommit is turned off to prevent execution of queries when an error arises.
			$mysqli->autocommit(FALSE);
					
			// Aquire the user's ID from the users tables.
			$user_query = "SELECT user_index FROM users WHERE username = '$username' AND password = '$password'";
			$user_resource = $mysqli->query($user_query);
			$user_fav_entry = $user_resource->fetch_assoc();
			
			// Query which checks if the user has already favorited the artist they are favoriting currently favoriting.
			$fav_query = "SELECT COUNT(user_index), COUNT(artist_deezer_id) FROM fav_artist WHERE user_index = '$user_fav_entry[user_index]' AND artist_deezer_id = '$deezer_id'";
			$fav_resource = $mysqli->query($fav_query);
			$fav_check = $fav_resource->fetch_assoc();

			// If fav_check['COUNT(user_index)'] and $fav_check['COUNT(artist_deezer_id)'] is 0, then the user has not favorited the artist. Else display a warning.
			if($fav_check['COUNT(user_index)'] == 0 && $fav_check['COUNT(artist_deezer_id)'] == 0){
					
					
					// Insert the user_index and artist_deezer_id into the fav_artist table.
					$insert_fav_query = "INSERT INTO fav_artist (user_index, artist_deezer_id) VALUES ('$user_fav_entry[user_index]', '$deezer_id')";
					$insert_fav_resource = $mysqli->query($insert_fav_query);
					
					// Only proceed if the artist was inserted successfully.
					if($insert_fav_resource){
						
						
						//Commit to the queries that are currently queued.
						$mysqli->commit();
						
						// This query checks to see if the artist the user is favoriting is in the artist table.
						$artist_query = "SELECT COUNT(artist_deezer_id), COUNT(artist_name) FROM artist WHERE artist_deezer_id = '$deezer_id'";
						$artist_resource = $mysqli->query($artist_query);
						$artist_check_entry = $artist_resource->fetch_assoc();
						
						// if no artist has been found, then proceed. Else redirect the user back to the albums.php page.  
						if($artist_check_entry['COUNT(artist_deezer_id)'] == 0){
							
							
							//Commit to the queries that are currently queued.
							$mysqli->commit();

							// Insert the artist into the artist table in the database.
							$insert_artist_query = "INSERT INTO artist (artist_deezer_id, artist_echonest_id, artist_name, artist_image) VALUES ('$deezer_id', '$echonest_id', '$artist_name', '$artist_image')";
							$insert_artist_resource = $mysqli->query($insert_artist_query);
							
							// Proceed only if the query was a success.
							if($insert_artist_resource){
							
								//Commit to the queries that are currently queued
								$mysqli->commit();
								
								//Close the database connection.
								$mysqli->close();
								
								//Remove any slashes from $artist_name and $artist_image that have been placed.
								$artist_name = stripslashes($artist_name);
								$artist_image = stripslashes($artist_image);
								
								//Redirect the user back to the artist page and display a message to notify them that the favoriting was a success.
								header("LOCATION: ../albums.php?echonest_id=$echonest_id&deezer_id=$deezer_id&artist_name=$artist_name&artist_image=$artist_image&warning=SUCCESS");
								
							}else{
								// If there was a problem with inserting the artist into the artist table, then rollback that query. 
								$mysqli->rollback();
								
								// Redirect the user back to the albums.php page and notify them that an error occured.
								header("LOCATION: ../albums.php?echonest_id=$echonest_id&deezer_id=$deezer_id&artist_name=$artist_name&artist_image=$artist_image&warning=ERROR");
							}
							
						}else{
							//Remove any slashes from $artist_name and $artist_image that have been placed.
							$artist_name = stripslashes($artist_name);	
							$artist_image = stripslashes($artist_image);
						
							// If the artist has already been favorited then redirect the user to the albums.php page and notify them that the favoriting was a success.
							header("LOCATION: ../albums.php?echonest_id=$echonest_id&deezer_id=$deezer_id&artist_name=$artist_name&artist_image=$artist_image&warning=SUCCESS");
						}
						
					}else{
						// Rollback all queries if the artist was not inserted successfully.
						$mysqli->rollback();
					
						//Close the database connection.
						$mysqli->close();
					
						// Redirect the user back to the albums.php page and notify them that an error occured.
						header("LOCATION: ../albums.php?echonest_id=$echonest_id&deezer_id=$deezer_id&artist_name=$artist_name&artist_image=$artist_image&warning=ERROR");
					}
					
				}else{
					//Remove any slashes from $artist_name and $artist_image that have been placed.
					$artist_name = stripslashes($artist_name);	
					$artist_image = stripslashes($artist_image);

					//Close the database connection.
					$mysqli->close();
					
					//Redirect the user back to the albums.php page and notify them that they have already favorited the artist.
					header("LOCATION: ../albums.php?echonest_id=$echonest_id&deezer_id=$deezer_id&artist_name=$artist_name&artist_image=$artist_image&warning=FAILED");
				}
				
			}else{
				//Redirect the user to the homepage if they accessed this file via its URI.
				header("LOCATION: ../index.php");
			}
			
	}else{
		//Redirect the user to the homepage if they accessed this file via its URI.
		header("LOCATION: ../index.php");
	}

	// A custom function used to escape certain characters to create a legal SQL string.
	function sanitise($str, $mysqli){
		
		$sanitised_string = $mysqli->real_escape_string($str);
		return $sanitised_string;
	}
?>