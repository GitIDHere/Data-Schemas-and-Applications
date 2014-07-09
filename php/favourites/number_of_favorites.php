<?php

	// If unable to connect to database, then kill the script processing.
	if(!require_once('db_login.php')){
		die("UNABLE TO CONNECT TO DATABASE: number_of_favorites.php");
	}	
	
	//Query which counts user's every favorite artists. 
	$max_artist_result = $mysqli->query("SELECT COUNT(artist_deezer_id) FROM fav_artist WHERE user_index = '$user_index[user_index]'");
	$num_artist_favorites = $max_artist_result->fetch_assoc();
	
	//Query which counts user's every favorite tracks. 
	$max_track_result = $mysqli->query("SELECT COUNT(track_deezer_id) FROM  fav_track WHERE user_index = '$user_index[user_index]'");
	$num_track_favorites = $max_track_result->fetch_assoc();
?>