<?php
	if(!require_once('db_login.php')){
		die("UNABLE TO CONNECT TO DATABASE: top_favorite_tracks.php");
	}
	
	//The variable to hold the HTML output of all the top favorited tracks.
	$topfive_output = null;
	
	/*Variable to be used to indicate the rank position of the tracks. This will be incremented for 
		every iteration through the while loop. */
	$count = 1;
	
	//Selects the track_deezer_id from fav_track and orders them by the most recursive track_deezer_id.
	$topfive_query = "SELECT track_deezer_id, count(*) FROM fav_track GROUP BY track_deezer_id ORDER BY count(*) DESC LIMIT 5";
	$topfive_resource = $mysqli->query($topfive_query);
	
	//Loop through each row retrieved.
	while($track_deezer_id = $topfive_resource->fetch_assoc()){
		
		/* This query selects the track name, artist image, artist name, album cover, and artist echonest and deezer id.
			These data is needed so that the anchor tag containing the tracks can direct the user to the artist page. */
		$track_query = "SELECT track.track_name, track.album_deezer_id, album.album_cover, album.artist_deezer_id, artist.*  
							FROM track, album, artist WHERE track_deezer_id = '$track_deezer_id[track_deezer_id]' AND 
								album.album_deezer_id = track.album_deezer_id AND artist.artist_deezer_id = album.artist_deezer_id";
		$track_resource = $mysqli->query($track_query);
		$track_info = $track_resource->fetch_assoc();
		
		//Create the HTML elements to display the top 5 tracks.
		$topfive_output .= <<<DOC
	
			<div class="artist_container">
			
				<a class="link" href="tracks.php?echonest_id={$track_info['artist_echonest_id']}&deezer_id={$track_info['artist_deezer_id']}&album_id={$track_info['album_deezer_id']}&artist_image={$track_info['artist_image']}">
					
					<p class="rank_number">#{$count}</p>
					
					<p class="fav_count hover">Favorited by: {$track_deezer_id['count(*)']}</p>
					
					<img class="artist_image" src="{$track_info['album_cover']}" alt="{$track_info['album_cover']}" />
					
					<p class="artist_name hover">{$track_info['track_name']}</p>
				</a>
				
			</div>
DOC;
		$count++;		
}

?>