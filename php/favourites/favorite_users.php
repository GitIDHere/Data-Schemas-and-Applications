<?php

	/*
	* This script is used to display all the users who have favourite a particular artist. 
	*/
	
	// If unable to connect to database, then kill the script processing.
	if(!require_once('db_login.php')){
		die("UNABLE TO CONNECT TO DATABASE: favorite_users.php");
	}
	
	// The variable which will hold all the usernames of the users who have favourite the artist.
	$fav_users = null;
	
	/* Acquire all the usernames whose user_index from users table matches with the user_index within the fav_artist table, 
		and where the artist_deezer_id is equal to the current artists Deezer ID. */
	$user_fav_query = "SELECT users.username FROM users, fav_artist WHERE users.user_index = fav_artist.user_index AND fav_artist.artist_deezer_id = '$deezer_id'";
	
	
	//Proceed only if the query is a success.
	if($user_fav_resource = $mysqli->query($user_fav_query)){
		
		//Commit to the queries that are queued
		$mysqli->commit();
		
		//Loop through each record of the users who have favourite the artist.
		while($user_fav_array = $user_fav_resource->fetch_assoc()){
			
			//Turn username acquired from database to lowercase so that it can fairly be matched against the session username.
			$db_username = strtolower($user_fav_array['username']);
			
			if(isset($_SESSION['username'])){
				
				//Turn the $_SESSION[] username to lowercase to match against the database username.
				$session_username = strtolower($_SESSION['username']);
				
				//If session is set, then check if the current user's username matches the one taken from the database.
				if($db_username == $session_username){
					
					//Apply a span with the id of 'user' to the matched username from the database
					$fav_users .= ' <a class="hover" href="members.php"><span id="user">'.$user_fav_array['username'].'</span></a>, ';
					
				}else{
				
					//Print the username from the database normally.
					$fav_users .= ' '.$user_fav_array['username'].',';
				}
				
			}else{
			
				//If session is not set then print the user's name normally.
				$fav_users .= ' '.$user_fav_array['username'].',';
			}
			//echo "array: ".$user_fav_array['username']."</br>"."session: ".$_SESSION['username']."</br>";
		}
		
	}else{
	
		//Rollback the queries that were queued.
		$mysqli->rollback();
		
		//Close the database connection.
		$mysqli->close();
		
		// print a message to explain that the query has failed.
		$fav_users = "Error executing a query to obtain users who favorited this artist";
		
	}
?>