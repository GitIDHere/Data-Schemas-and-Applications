<?php
	
	// API key for Last.fm.
	$api_key = 'a95d5730dc7edf4c12e20e4978073ddf';
	
	//URL encode the artist name passed in so that the name could be passed through the URI.
	$encoded_artist_name = urlencode($artist_name);

	// Probe the Last.fm API to retrieve the artist's biography.
	$lastfm_uri = file_get_contents("http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=$encoded_artist_name&api_key=$api_key");
	$lastfm_xml = new SimpleXMLElement($lastfm_uri);
	
	// Get the artists biography.
	$lastfm_bio = $lastfm_xml->artist->bio->summary;
?>