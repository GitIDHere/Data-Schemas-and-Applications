<?php

	// Check if the required information is passed to this this script. If not then redirect the user to the homepage.
	if(!empty($_GET['echonest_id']) && !empty($_GET['deezer_id']) && !empty($_GET['album_id']) && !empty($_GET['artist_image'])){
		
		// Instantiate the variables to hold the data passed from the URI queries.
		$album_id = $_GET['album_id'];
		$echonest_id = $_GET['echonest_id'];
		$deezer_id = $_GET['deezer_id'];
		$artist_image = $_GET['artist_image'];

		
		// If unable to connect to database, then kill the script processing.
		if(!require_once('db_login.php')){
			die("UNABLE TO CONNECT TO DATABASE: process-fav-track.php");
		}
		
		
		// Check if the user is logged in. If they are, then show them the button to favorite the tracks. If they are not then tell them to log in.
		if(!empty($_SESSION['username']) && !empty($_SESSION['password'])){
			$favorite_button = '<p class="fav_button">Favorite this Track</p>';
		}else{
			$favorite_button = '<a class="hover" href="login.php"><p class="fav_button">Log in to favorite</p></a>';
		}	
		
		// Probe the Deezer API to aquire the album details.
		$deezer_album_uri = file_get_contents("http://api.deezer.com/2.0/album/$album_id&output=xml");
		$deezer_album_xml = new SimpleXMLElement($deezer_album_uri);	
		
		// Aquire the artist name, album cover, and album title.
		$artist_name = $deezer_album_xml->artist->name;
		$album_cover = $deezer_album_xml->cover;
		$display_album_title = $deezer_album_xml->title;
		
		/* Urlencode special characters such as whitespaces in $display_album_title so that the album title 
			is in the correct format to be passed via the URI. */
		$encoded_album_title = urlencode($display_album_title); 

		// Aquire the release date of the album which is not in UK date format.
		$unformated_release_date = (string)$deezer_album_xml->release_date;
		
		// Use strtotime returns a Unix timestamp of $unformated_release_date which is then converted into UK date format.
		$release_date = date("d-m-Y", strtotime($unformated_release_date));
		
		
		// Probe the Deezer API to aquire the artist's total number of tracks.
		$deezer_uri = file_get_contents("http://api.deezer.com/2.0/album/$album_id/tracks&index=$element_offset&nb_items=9&output=xml");
		$deezer_track_xml = new SimpleXMLElement($deezer_uri);	
		
		//The total amount of tracks for the album.
		$total_tracks = $deezer_track_xml->total;

		// The variable which will hold the HTML code to display the album's tracks.
		$track_output = null;
		
		// Loop through each track node in $deezer_track_xml and aquire each ones information.
		foreach($deezer_track_xml->data->track as $track){
			
			// Aquire the track ID, track preview, and track name.
			$track_id = $track->id;
			$track_Preview = $track->preview;
			$track_Name = $track->title;
	

			// Get the number of users who have favorited the current artist within the loop.
			$fav_count_query = "SELECT COUNT(user_index) FROM fav_track WHERE track_deezer_id = '$track_id'";
			$fav_count_resource = $mysqli->query($fav_count_query);
			$fav_count = $fav_count_resource->fetch_assoc(); 
			
			
			// insert the aquired details of the track into the HTML code and concatenate it into the $track_output variable.
			$track_output .= <<<TRACKS
				
					<div class="track_container">
						<div class="track_details">
							<p class="track_name">{$track_Name}</p>
							<audio class="audio_player"  preload="none" controls="controls">
								<source src="{$track_Preview}" />
								Browser do not support the Audio tag
							</audio>
							<p class="fav_count">Favorited by {$fav_count['COUNT(user_index)']} users</p>
						</div>
						<a class="favorite_button hover" href="scripts/process-fav-track.php?echonest_id={$echonest_id}&deezer_id={$deezer_id}&track_id={$track_id}&album_id={$album_id}&album_title={$encoded_album_title}&album_cover={$album_cover}&artist_name={$artist_name}&artist_image={$artist_image}">
							{$favorite_button}
						</a>
					</div>	
TRACKS;

		}

	}else{
		//Redirect the user to the homepage if they accessed this file without passing the required URI queries.
		header("LOCATION: index.php");
	}
?>