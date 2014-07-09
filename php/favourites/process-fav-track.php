<?php
	
/*
	Because this script is over 500 lines long, here is the breakdown of the processes which take place:
		
		1) Check if all the required information is passed into this script. 
			else redirect to the homepage.
			
		2) Check whether the user has already favourited this track. 
			If they have favourites the track, then redirect the user back to tracks.php page and show warning.
			If they have NOT favourited the track, then insert the track's information into fav_track and continue to step 3.
			
		3) Check if the track is in the track table. 
			If track is not in the track table, then insert it in the track table and continue to step 4.
			If track IS in the track table then continue to step 4.
			
		4) Check if the track's artist is in the artist table.
			If artist is not in the artist table, then insert it in the artist table and continue to step 5.
			If artist IS in the artist table then continue to step 5.
			
		5) Check if the track's album is in the album table.
			If album is not in the album table, then insert it in the album table and redirect the user back to tracks.php page and show success message.
			If album IS in the album table then redirect the user back to tracks.php page and show success message.

*/
	
	session_start();

	// Only allow for favouriting of artists when sessions are set.
	if(!empty($_SESSION['username']) && !empty($_SESSION['password'])){


		// If unable to connect to database, then kill the script processing.
		if(!require_once('db_login.php')){
			die("UNABLE TO CONNECT TO DATABASE: process-fav-track.php");
		}
		
		$username = $_SESSION['username'];
		$password = $_SESSION['password'];
		
		// Check for if the right queries have been passed.
		if(!empty($_GET['track_id']) && !empty($_GET['echonest_id']) && !empty($_GET['deezer_id'])&& !empty($_GET['album_id']) && !empty($_GET['album_title']) &&!empty($_GET['album_cover']) && !empty($_GET['artist_name']) && !empty($_GET['artist_image'])){
			
			$echo_artist_id = $_GET['echonest_id'];
			$deezer_id = $_GET['deezer_id'];
			$deezer_album_id = $_GET['album_id'];
			$deezer_track_id = $_GET['track_id'];

			$artist_name_unchecked = $_GET['artist_name'];
			// The sanitise() function is a custom function which escapes certain characters to prevent SQL injection. 
			$artist_name = sanitise($artist_name_unchecked, $mysqli);
					
			$album_title_unchecked = $_GET['album_title'];
			$album_title = sanitise($album_title_unchecked, $mysqli);

			$album_cover_unchecked = $_GET['album_cover'];
			$album_cover = sanitise($album_cover_unchecked, $mysqli);
			
			$artist_image_unchecked = $_GET['artist_image'];
			$artist_image = sanitise($artist_image_unchecked, $mysqli);
			
			//Mysqli's Autocommit is turned off to prevent execution of queries when an error arises.
			$mysqli->autocommit(FALSE);
			
			// Aquire the user's ID from the users tables.
			$user_query = "SELECT user_index FROM users WHERE username = '$username'";
			$user_resource = $mysqli->query($user_query);
			$user_fav_entry = $user_resource->fetch_assoc();

			// Query which checks if the user has already favorited the track they are favoriting currently favoriting.
			$fav_track_query = "SELECT COUNT(user_index), COUNT(track_deezer_id) FROM fav_track WHERE user_index = '$user_fav_entry[user_index]' AND track_deezer_id = '$deezer_track_id'";
			$fav_track_resource = $mysqli->query($fav_track_query);
			$fav_track_entry = $fav_track_resource->fetch_assoc();
			
			
			// Proceed only if the user has not favourited the track.
			if($fav_track_entry['COUNT(user_index)'] == 0 && $fav_track_entry['COUNT(track_deezer_id)'] == 0){
				
				//Commit to the queries that are currently queued
				$mysqli->commit();
				
				//Insert the user_index and track_deezer_id into the fav_track table.
				$fav_track_query = "INSERT INTO fav_track (user_index, track_deezer_id) VALUES ('$user_fav_entry[user_index]', '$deezer_track_id')";
				$fav_track_resource = $mysqli->query($fav_track_query);
				
				
				// Only proceed if the track was inserted into the fav_track table. 
				if($fav_track_resource){
					
					//Commit to the queries that are currently queued
					$mysqli->commit();
					
					
					//We now check if the track is in the track table.
					
					
					// Query checks if the track that the user is favoriting is in the track table.
					$track_check_query = "SELECT COUNT(track_deezer_id) FROM track WHERE track_deezer_id = '$deezer_track_id'";
					$track_check_resource = $mysqli->query($track_check_query);
					$track_check_entry = $track_check_resource->fetch_assoc();
					
					
					//Proceed only if the track is NOT in the tracks table.
					if($track_check_entry['COUNT(track_deezer_id)'] == 0){
						
						//Commit to the queries that are currently queued
						$mysqli->commit();
						
						// Access the Deezer API to acquire the track's credentials.
						$deezer_api = file_get_contents("http://api.deezer.com/2.0/track/$deezer_track_id&output=xml");
						$deezer_xml = new SimpleXMLElement($deezer_api);
						
						// Acquire track name, preview, and album release date.
						$track_name_unchecked = (string)$deezer_xml->title;
						// The sanitise() function is a custom function which escapes certain characters to prevent SQL injection. 
						$track_name = sanitise($track_name_unchecked, $mysqli);
						
						$preview_unchecked = (string)$deezer_xml->preview;
						$preview = sanitise($preview_unchecked, $mysqli);
						
						$album_release_date =  $deezer_xml->album->release_date;
						
						// Insert the track's credentials into the track table.
						$insert_track_query = "INSERT INTO track (track_deezer_id, album_deezer_id, track_name, preview) VALUES ('$deezer_track_id', '$deezer_album_id', '$track_name', '$preview')";
						$insert_track_resource = $mysqli->query($insert_track_query);
						
						
						// Only proceed if the inserting the track was a success.
						if($insert_track_resource){
							
							
							// Check if the artist is in the artist table.
							$artist_check_query = "SELECT COUNT(artist_deezer_id) FROM artist WHERE artist_deezer_id = '$deezer_id'";
							$artist_check_resource = $mysqli->query($artist_check_query);
							$artist_check = $artist_check_resource->fetch_assoc();
							
							//Commit to the queries that are currently queued
							$mysqli->commit();
							
							if($artist_check['COUNT(artist_deezer_id)'] == 0){
								
								// Insert the artist's credentials into the artist table.
								$insert_artist_query = "INSERT INTO artist (artist_deezer_id, artist_echonest_id, artist_name, artist_image) VALUES ('$deezer_id', '$echo_artist_id', '$artist_name', '$artist_image')";
								$insert_artist_resource = $mysqli->query($insert_artist_query);
								
								//Proceed only if the query was a success.		
								if($insert_artist_resource){
								
								
									// Check if the album is in the album table.
									$album_check_query = "SELECT COUNT(album_deezer_id) FROM album WHERE album_deezer_id = '$deezer_album_id'";
									$album_check_resource = $mysqli->query($album_check_query);
									$album_check = $album_check_resource->fetch_assoc();
										
									//Commit to the queries that are currently queued
									$mysqli->commit();							

									// Proceed only if the album is not in the album table.
									if($album_check['COUNT(album_deezer_id)'] == 0){
										
										// Insert the album's credentials into the album table.
										$insert_album_query = "INSERT INTO album (album_deezer_id, artist_deezer_id, album_name, album_cover, published) VALUES ('$deezer_album_id', '$deezer_id', '$album_title', '$album_cover', '$album_release_date')";
										$insert_album_resource = $mysqli->query($insert_album_query);
											
										//Proceed only if the query was a success.		
										if($insert_album_resource){
										
										
											//Remove any slashes from $album_title that have been placed.
											$album_title = stripslashes($album_title);
										
											//Commit to the queries that are currently queued
											$mysqli->commit();
											
											//Close the database connection.
											$mysqli->close();
											
											//Redirect the user back to the tracks.php page and notify them that track was favorited successfully.
											header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=SUCCESS");
											
										}else{
											//Rollback the queries that were queued.
											$mysqli->rollback();
											
											//Close the database connection.
											$mysqli->close();
											
											// Redirect the user back to the albums.php page and notify them that an error occured.
											header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=ERROR");
										}
										
									}else{
										
										//The user successfully favourited the track, but the artist and album were all existent in their own individual tables.
										
										//Remove any slashes from $album_title that have been placed.
										$album_title = stripslashes($album_title);
										
										//Rollback the queries that were queued.
										$mysqli->rollback();
										
										//Close the database connection.
										$mysqli->close();								
										
										// Redirect the user back to the albums.php page and notify them that they successfully favorited the track.
										header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=SUCCESS");
										
									}
								
								}else{
										//Rollback the queries that were queued.
										$mysqli->rollback();
										
										//Close the database connection.
										$mysqli->close();
										
										// Redirect the user back to the albums.php page and notify them that an error occured.
										header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=ERROR");
								}
								
							}else{
								
								
								//If the artist was in the artist table, then we will now check if the album is in the album table.
								
								
								// Check if the album is in the album table.
								$album_check_query = "SELECT COUNT(album_deezer_id) FROM album WHERE album_deezer_id = '$deezer_album_id'";
								$album_check_resource = $mysqli->query($album_check_query);
								$album_check = $album_check_resource->fetch_assoc();
									
								//Commit to the queries that are currently queued
								$mysqli->commit();							

								// Proceed only if the album is not in the album table.
								if($album_check['COUNT(album_deezer_id)'] == 0){
									
									// Insert the album's credentials into the album table.
									$insert_album_query = "INSERT INTO album (album_deezer_id, artist_deezer_id, album_name, album_cover, published) VALUES ('$deezer_album_id', '$deezer_id', '$album_title', '$album_cover', '$album_release_date')";
									$insert_album_resource = $mysqli->query($insert_album_query);
										
										
									//Proceed only if the query was a success.		
									if($insert_album_resource){
									
										//Remove any slashes from $album_title that have been placed.
										$album_title = stripslashes($album_title);
									
										//Commit to the queries that are currently queued
										$mysqli->commit();
										
										//Close the database connection.
										$mysqli->close();
										
										//Redirect the user back to the tracks.php page and notify them that track was favorited successfully.
										header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=SUCCESS");
										
									}else{
										//Rollback the queries that were queued.
										$mysqli->rollback();
										
										//Close the database connection.
										$mysqli->close();
										
										// Redirect the user back to the albums.php page and notify them that an error occured.
										header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=ERROR");
									}
									
								}else{
									//Remove any slashes from $album_title that have been placed.
									$album_title = stripslashes($album_title);
									
									//Rollback the queries that were queued.
									$mysqli->rollback();
									
									//Close the database connection.
									$mysqli->close();								
									
									// Redirect the user back to the albums.php page and notify them that they successfully favorited the track.
									header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=SUCCESS");
									
								}
							}
								
						}else{
							// Rollback previous queries when the inserting the track into the track table fails.
							$mysqli->rollback();
							
							//Close the database connection.
							$mysqli->close();
							
							// Redirect the user back to the albums.php page and notify them that an error occured.
							header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=ERROR");
						}
						
					}else{
						
						
						//The track was already in the track table, so we will now check if the track's artist is in the artist table.
						
						
						// Check if the artist is in the artist table.
						$artist_check_query = "SELECT COUNT(artist_deezer_id) FROM artist WHERE artist_deezer_id = '$deezer_id'";
						$artist_check_resource = $mysqli->query($artist_check_query);
						$artist_check = $artist_check_resource->fetch_assoc();
						
						//Commit to the queries that are currently queued
						$mysqli->commit();
						
						if($artist_check['COUNT(artist_deezer_id)'] == 0){
							
							// Insert the artist's credentials into the artist table.
							$insert_artist_query = "INSERT INTO artist (artist_deezer_id, artist_echonest_id, artist_name, artist_image) VALUES ('$deezer_id', '$echo_artist_id', '$artist_name', '$artist_image')";
							$insert_artist_resource = $mysqli->query($insert_artist_query);
							
							//Proceed only if the query was a success.		
							if($insert_artist_resource){
								
								//Commit to the queries that are currently queued
								$mysqli->commit();
								
								
								// The next step is to check if the album exists in the album table.
								
								
								// Access the Deezer API to aquire the album's credentials.
								$deezer_api = file_get_contents("http://api.deezer.com/2.0/track/$deezer_track_id&output=xml");
								$deezer_xml = new SimpleXMLElement($deezer_api);
								
								// Get the album's release date.
								$album_release_date = $deezer_xml->album->release_date;
								
								// Check to see if the album is already in the album table.
								$album_check_query = "SELECT COUNT(album_deezer_id) FROM album WHERE album_deezer_id = '$deezer_album_id'";
								$album_check_resource = $mysqli->query($album_check_query);
								$album_check = $album_check_resource->fetch_assoc();
								
								//Commit to the queries that are currently queued
								$mysqli->commit();
								
								
								// Proceed only if the album is not in the album table.
								if($album_check['COUNT(album_deezer_id)'] == 0){
									
									//Remove any slashes from $album_title that have been placed.
									$album_title = stripslashes($album_title);
									
									// Insert the album's credentials into the album table.
									$insert_album_query = "INSERT INTO album (album_deezer_id, artist_deezer_id, album_name, album_cover, published) VALUES ('$deezer_album_id', '$deezer_id', '$album_title', '$album_cover','$album_release_date')";
									$insert_album_resource = $mysqli->query($insert_album_query);
									
									
									//Proceed only is the album was inserted into the album table successfully.
									if($insert_album_resource){
									
										//Remove any slashes from $album_title that have been placed.
										$album_title = stripslashes($album_title);
										
										//Commit to the queries that are currently queued
										$mysqli->commit();
										
										//Close the database connection.
										$mysqli->close();							
										
										// Reidrect the user back to tracks.php page and notify them that the track was favorited successfully.
										header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=SUCCESS");
									
									}else{
										//Rollback the queries that were queued.
										$mysqli->rollback();
										
										//Close the database connection.
										$mysqli->close();		
										
										// Redirect the user back to the albums.php page and notify them that an error occured.
										header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=ERROR");
									}
									
								}else{
									//Remove any slashes from $album_title that have been placed.
									$album_title = stripslashes($album_title);
									
									//Rollback the queries that were queued.
									$mysqli->rollback();
									
									//Close the database connection.
									$mysqli->close();							
									
									// Reidrect the user back to tracks.php page and notify them that the track was favorited successfully.
									header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=SUCCESS");
								}

							}else{
							
								//An error occured whilst inserting the artist in the artist table, so an error message will be shown to the user.
								
								//Rollback the queries that were queued.
								$mysqli->rollback();
								
								//Close the database connection.
								$mysqli->close();		
								
								// Redirect the user back to the albums.php page and notify them that an error occured.
								header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=ERROR");
							}

						}else{
						
							// The artist already exists in the artist table, so now we check if the album exists in the album table.
							
							
							// Access the Deezer API to aquire the album's credentials.
							$deezer_api = file_get_contents("http://api.deezer.com/2.0/track/$deezer_track_id&output=xml");
							$deezer_xml = new SimpleXMLElement($deezer_api);
							
							// Get the album's release date.
							$album_release_date = $deezer_xml->album->release_date;
							
							// Check to see if the album is already in the album table.
							$album_check_query = "SELECT COUNT(album_deezer_id) FROM album WHERE album_deezer_id = '$deezer_album_id'";
							$album_check_resource = $mysqli->query($album_check_query);
							$album_check = $album_check_resource->fetch_assoc();
							
							//Commit to the queries that are currently queued
							$mysqli->commit();
							
							
							// Proceed only if the album is not in the album table.
							if($album_check['COUNT(album_deezer_id)'] == 0){
								
								//Remove any slashes from $album_title that have been placed.
								$album_title = stripslashes($album_title);
								
								// Insert the album's credentials into the album table.
								$insert_album_query = "INSERT INTO album (album_deezer_id, artist_deezer_id, album_name, album_cover, published) VALUES ('$deezer_album_id', '$deezer_id', '$album_title', '$album_cover','$album_release_date')";
								$insert_album_resource = $mysqli->query($insert_album_query);
								
								
								//Proceed only is the album was inserted into the album table successfully.
								if($insert_album_resource){
								
									//Remove any slashes from $album_title that have been placed.
									$album_title = stripslashes($album_title);
									
									//Commit to the queries that are currently queued
									$mysqli->commit();
									
									//Close the database connection.
									$mysqli->close();							
									
									// Reidrect the user back to tracks.php page and notify them that the track was favorited successfully.
									header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=SUCCESS");
								
								}else{
									//Rollback the queries that were queued.
									$mysqli->rollback();
									
									//Close the database connection.
									$mysqli->close();		
									
									// Redirect the user back to the albums.php page and notify them that an error occured.
									header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=ERROR");
								}
								
							}else{
								
								/* All tables associated with the track has been checked and the track has been added to the fav_track table. We will now redirect 
									user back to tracks.php page and show success message. */
							
								//Remove any slashes from $album_title that have been placed.
								$album_title = stripslashes($album_title);
								
								//Rollback the queries that were queued.
								$mysqli->rollback();
								
								//Close the database connection.
								$mysqli->close();							
								
								// Reidrect the user back to tracks.php page and notify them that the track was favorited successfully.
								header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=SUCCESS");
							}
						}
						
					}
					
				}else{
					
					//There was a problem inserting the track into the fav_track table.
					
					//Rollback the queries that were queued.
					$mysqli->rollback();
					
					//Close the database connection.
					$mysqli->close();					
					
					// Redirect the user back to the albums.php page and notify them that an error occured.
					header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=ERROR");
				}
				
			}else{
				
				//The user has already favorited the track. Redirect them back to tracks.php page and show unsuccessful message.
				
				//Remove any slashes from $album_title that have been placed.
				$album_title = stripslashes($album_title);
				
				//Rollback the queries that were queued.
				$mysqli->rollback();
				
				//Close the database connection.
				$mysqli->close();

				// Redirect the user back to the albums.php page and notify them that they have already favorited the track.
				header("LOCATION: ../tracks.php?echonest_id=$echo_artist_id&deezer_id=$deezer_id&album_id=$deezer_album_id&artist_image=$artist_image&warning=FAILED");
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