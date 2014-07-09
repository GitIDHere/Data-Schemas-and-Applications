<?Php
	
	// API key for Last.fm.
	$api_key = 'a95d5730dc7edf4c12e20e4978073ddf';
	
	//Urlencode the artist's forename and surname, so that the name could be passed through the URI.
	$html_artist_name = urlencode($artist_name);
	
	//Urlencode the album's title.
	$html_album_title = urlencode($display_album_title);
	
	$buyAlbum = null;
	
	// Probe the Last.fm API to retrieve the information of purchasing the album.
	$buyAlbum_uri = file_get_contents("http://ws.audioscrobbler.com/2.0/?method=album.getbuylinks&artist=$html_artist_name&album=$html_album_title&country=united%20kingdom&api_key=$api_key");
	$buyAlbum_xml = new SimpleXMLElement($buyAlbum_uri);
	
	//The name of seller
	$supplierName = $buyAlbum_xml->affiliations->downloads->affiliation[2]->supplierName;
	
	//album price
	$albumPrice = $buyAlbum_xml->affiliations->downloads->affiliation[2]->price->formatted;
	
	//Link to the seller's site.
	$buy_link = $buyAlbum_xml->affiliations->downloads->affiliation[2]->buyLink;
	
	//Check if the name price is variable is set, since some API requests don't have prices set for the albums.
	if(isset($albumPrice)){
		$price = $albumPrice;
	}else{
		$price = 'Price unavailable';
	}
	
	//Create the HTML to be shown on the tracks.php page.
	$buyAlbum = <<<BUY
					<a href="{$buy_link}" target="_blank">
						<p id="sort_track_button">Buy album from {$supplierName}: {$price}</p>
					</a>
BUY;

?>