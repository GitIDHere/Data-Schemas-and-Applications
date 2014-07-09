<?php

	// Check if the required information is passed to this this script. If not then redirect the user to the homepage.
	if(!empty($_GET['echonest_id']) && !empty($_GET['deezer_id']) && !empty($_GET['artist_name']) && !empty($_GET['artist_image'])){


		// Instantiate the variables to hold the data passed from the URI queries.
		$echonest_id = $_GET['echonest_id'];
		$deezer_id = $_GET['deezer_id'];
		$artist_name = $_GET['artist_name'];
		$artist_image = $_GET['artist_image'];
		
		
		// Check if the user is logged in. If they are, then show them the button to favorite the artist. If they are not logged in then tell them to log in.
		if(!empty($_SESSION['username']) && !empty($_SESSION['password'])){
		
			$favorite_button = '<a href="scripts/process-fav-artist.php?deezer_id='.$deezer_id.'&echonest_id='.$echonest_id.'&artist_name='.$artist_name.'&artist_image='.$artist_image.'">
									<p id="fav_button">Favorite This Artist</p>
								</a>';
								
		}else{
		
			$favorite_button = '<a href="login.php"><p id="fav_button">Log in to favorite</p></a>';
			
		}

		// Aquire the the first ten album details of the artist.
		$deezer_album_uri = file_get_contents("http://api.deezer.com/2.0/artist/$deezer_id/albums&nb_items=12&output=xml&index=$element_offset");
		$deezer_album_xml = new SimpleXMLElement($deezer_album_uri);
		
		// Variable to hold the number of albums the artist has.
		$num_of_albums = $deezer_album_xml->total;
		
		// The variable which will hold the HTML code to display the artist's albums.
		$albums_output = null;
		
		
		// Loop through each album node in $deezer_album_xml and aquire each ones information.
		foreach($deezer_album_xml->data->album as $album_details){
		
			// Aquire the album ID, album title, and album cover.
			$album_id = $album_details->id;
			$album_title = $album_details->title;
			$album_cover = $album_details->cover;

			
			// insert the aquired details of the album into the HTML code and concatenate it into the $album_output variable.
			$albums_output .= <<<DOC
			
				<div class="album_container">
				
					<a href="tracks.php?album_id={$album_id}&echonest_id={$echonest_id}&deezer_id={$deezer_id}&artist_image={$artist_image}">
						<img class="album_cover" src="{$album_cover}" alt="{$album_title}" title="{$album_title}"/>
					</a>
					
					<a class="album_name hover" href="tracks.php?album_id={$album_id}&echonest_id={$echonest_id}&deezer_id={$deezer_id}&artist_image={$artist_image}">{$album_title}</a>
				
				</div>
DOC;
		}
			
	}else{
		//Redirect the user to the homepage if they accessed this file without passing the required URI queries.
		header("LOCATION: index.php");
	}
?>

