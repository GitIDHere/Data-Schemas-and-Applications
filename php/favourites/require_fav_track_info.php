<?php

	session_start();

	// Only proceed if the user is logged in.
	if(isset($_SESSION['username']) && isset($_SESSION['password'])){
		
		// If unable to connect to database, then kill the script processing.
		if(!require_once('db_login.php')){
			die("UNABLE TO CONNECT TO DATABASE: require_fav_track_info.php");
		}
		
		// Require the require_user_info.php file to get its content.
		require('require_user_info.php');
		
		//The varible which will hold the HTML to display the user's favorite tracks on the Members page.
		$track_output = null;
		
		/* Aquire all the track_deezer_id of the user's favorite tracks which will be used to retrieve information 
			from the database of all the user's favorite tracks. */
		$favourite_track_query = "SELECT track_deezer_id FROM fav_track WHERE user_index = '$user_index[user_index]' ORDER BY fav_track_index DESC";
		$favourite_track_resource = $mysqli->query($favourite_track_query);
		
		// Loop through each row from the retrieved favourite_track_query.
		while($favourite_track = $favourite_track_resource->fetch_assoc()){
			
			// Select the track's credentials and album_deezer_id from the track table.
			$track_data_query = "SELECT album_deezer_id, track_name, preview from track WHERE track_deezer_id = '$favourite_track[track_deezer_id]'";
			$track_data_resource = $mysqli->query($track_data_query);
			$track_data = $track_data_resource->fetch_assoc();
			
			
			//Select the album_cover and album_name from the album table.
			$album_query = "SELECT artist_deezer_id, album_cover, album_name, album_deezer_id from album WHERE album_deezer_id = '$track_data[album_deezer_id]'";
			$album_resource = $mysqli->query($album_query);
			$album = $album_resource->fetch_assoc();
			
		
			//Select the artist's credentials from the database to be used to link the tracks to the artist page.
			$artist_query = "SELECT * FROM artist WHERE artist_deezer_id = '$album[artist_deezer_id]'";
			$artist_resource = $mysqli->query($artist_query);
			$artist = $artist_resource->fetch_assoc();
		
			// insert the aquired details of the favorite track into the HTML code and concatenate it into the $track_output variable.
			$track_output .= <<<DOC
				
					<div class="track_container">
						
						<a class="hover" href="tracks.php?echonest_id={$artist['artist_echonest_id']}&deezer_id={$artist['artist_deezer_id']}&album_id={$album['album_deezer_id']}&artist_image={$artist['artist_image']}">
							<img class="album_cover" src="{$album['album_cover']}" alt="{$album['album_name']}" title="{$album['album_name']}"/>	
							
							<p class="track_name">{$track_data['track_name']}</p>
						</a>
						
						<audio class="audio_player"  preload="none" controls="controls">
							<source src="{$track_data['preview']}" />
							Browser do not support the Audio tag
						</audio>
					</div>
DOC;
		
		}
		
		// Echo the contents of $track_output which will then be retrieved by an Ajax cal.
		echo $track_output;
		
		//Close the connection to the database which was started from require_user_info.php.
		$mysqli->close();
		
	}else{
		//Redirect the user to the homepage if they are not logged in.
		header("LOCATION: index.php");
	}
?>