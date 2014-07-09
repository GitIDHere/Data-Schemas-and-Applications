<?php

	session_start();
	
	// Only proceed if the user is logged in.
	if(isset($_SESSION['username']) && isset($_SESSION['password'])){
		
		// If unable to connect to database, then kill the script processing.
		if(!require_once('db_login.php')){
			die("UNABLE TO CONNECT TO DATABASE: require_fav_artist_info.php");
		}
		
		// Require the require_user_info.php file to get its content.
		require_once('require_user_info.php');
		
		//The varible which will hold the HTML to display the user's favorite artists on the Members page.
		$artist_output = null;
		
		/* Aquire all the artist_deezer_id of the user's favorite artists which will be used to retrieve information 
			from the database of all the user's favorite artists. */
		$favourite_artist_query = "SELECT artist_deezer_id FROM fav_artist WHERE user_index = '$user_index[user_index]' ORDER BY fav_artist_index DESC ";
		$favourite_artist_resource = $mysqli->query($favourite_artist_query);
		
		// Loop through each row from the retrieved favourite_artist_query.
		while($favourite_artist = $favourite_artist_resource->fetch_assoc()){
			
			//Retrieve the user's favorite artist's information from the database.
			$artist_data_query = "SELECT artist_deezer_id, artist_echonest_id, artist_name, artist_image from artist WHERE artist_deezer_id = '$favourite_artist[artist_deezer_id]'";
			$artist_data_resource = $mysqli->query($artist_data_query);
			$artist_data = $artist_data_resource->fetch_assoc();
			
			
			// insert the aquired details of the favorite artist into the HTML code and concatenate it into the $artist_output variable.
			$artist_output .= <<<DOC
					<div class="artist_container">
						<a href="albums.php?echonest_id={$artist_data['artist_echonest_id']}&deezer_id={$artist_data['artist_deezer_id']}&artist_name={$artist_data['artist_name']}&artist_image={$artist_data['artist_image']}">
							<img class="artist_image"  src="{$artist_data['artist_image']}" alt="{$artist_data['artist_name']}" title="{$artist_data['artist_name']}"/>
						</a>
						<a class="hover" href="albums.php?echonest_id={$artist_data['artist_echonest_id']}&deezer_id={$artist_data['artist_deezer_id']}&artist_name={$artist_data['artist_name']}&artist_image={$artist_data['artist_image']}">
							<p class="artist_name hover">{$artist_data['artist_name']}</p>
						</a>
					</div>
DOC;
		}
		
		// Echo the contents of $artist_output which will then be retrieved by an Ajax cal.
		echo $artist_output;
		
	}else{
		//Redirect the user to the homepage if they are not logged in.
		header("LOCATION: ../index.php");
	}
?>