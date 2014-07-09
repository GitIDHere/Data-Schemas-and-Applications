<?php
	
	// If unable to connect to database, then kill the script processing.
 	if(!require_once('db_login.php') ){
		die("UNABLE TO CONNECT TO DATABASE: getartlist.php");
	}

	//The id which will be used to identify each individual sessions.
	$sessionID = 'artist_page_'.$element_offset;
	
	//If the session is set with the ID of $sessionID, the retrieve that SESSION's content and put it in $artist_output which will echo out the 
	// list of artists stored within the session variable.
	if(isset($_SESSION[$sessionID])){
	
		$artist_output = $_SESSION[$sessionID];
		
	} else {
			
		// API key for Echonest
		$APIkey = "DID5RQHXRZFUFIEWX";

		// Acquire ten artists from the Echonest database in an XML format arranged by their popularity.
		$echonest_uri = file_get_contents("http://developer.echonest.com/api/v4/artist/search?api_key=$APIkey&style=rap&results=9&start=$element_offset&bucket=id:deezer&bucket=images&sort=familiarity-desc&format=xml");
		$echonest_xml =  new SimpleXMLElement($echonest_uri);

		//Create the variables to hold all the artist's credentials.
		$artist_name;
		$artist_image;
		$echonest_id;
		$deezer_id;
		$num_of_albums;

		//The variable which will contain the HTML to display the acquired artists.
		$artist_output = null;

		//Loop through each node of artist within the $echonest_xml to acquire every artist's credentials and embed it within predefined HTML.   
		foreach($echonest_xml->artists->artist as $artist){
			
			// Store the artist's credentials in temporary variables.
			$artist_name = $artist->name;
			$artist_image = $artist->images->image[0]->url;
			$echonest_id = $artist->id;
			
			// Acquire the just the numeric Deezer id for the current artist in the loop.
			preg_match('#deezer:artist:([0-9]+)#', $artist->foreign_ids->foreign_id->foreign_id, $id);
			$deezer_id = $id[1];
			
			// Acquire the total number of albums for the current artist in the loop.
			$deezer_uri = file_get_contents("http://api.deezer.com/2.0/artist/".$id[1]."/albums&output=xml");
			$deezer_xml =  new SimpleXMLElement($deezer_uri);
			
			$num_of_albums = $deezer_xml->total;
			
			// Get the number of users who have favourite the current artist within the loop.
			$fav_count_query = "SELECT COUNT(user_index) FROM fav_artist WHERE artist_deezer_id = '$deezer_id'";
			$fav_count_resource = $mysqli->query($fav_count_query);
			$fav_count = $fav_count_resource->fetch_assoc(); 
		
		
			// Insert the current artist's credentials within the HTML code, and concatenate it into the $artist_output variable which will be echoed on artist.php
			$artist_output .=  <<<HERE
			
						<div class="artist_container">
							<a  href="albums.php?echonest_id={$echonest_id}&deezer_id={$deezer_id}&artist_name={$artist_name}&artist_image={$artist_image}">
								<img class="artist_image" src="{$artist_image}" alt="{$artist_name}" title="{$artist_name}"/>
							</a>
							
							<a class="artist_name hover" href="albums.php?echonest_id={$echonest_id}&deezer_id={$deezer_id}&artist_name={$artist_name}&artist_image={$artist_image}">
								{$artist_name}
							</a>
							
							<a  class="album_number hover" href="albums.php?echonest_id={$echonest_id}&deezer_id={$deezer_id}&artist_name={$artist_name}&artist_image={$artist_image}">
								Albums: {$num_of_albums}
							</a>
							
							<a  class="album_number hover" href="albums.php?echonest_id={$echonest_id}&deezer_id={$deezer_id}&artist_name={$artist_name}&artist_image={$artist_image}"> 
								Favorited by: {$fav_count['COUNT(user_index)']} users
							</a>
						</div>
HERE;
		
		
		}
		
		/*Create a SESSION variable with the ID of $sessionID and store inside of it the 
			$artist_output containing the HTML elements used to display all the artists. */
		$_SESSION[$sessionID] = $artist_output;
	}

?>