<?php
	
	/*
		This script was created from following a tutorial on: http://www.carronmedia.com/create-an-rss-feed-with-php/
	*/
	
	
	/* This script was unable to require() db_login.php from the scripts folder because relevant permissions had not been granted.
	   However, when manually connect to the database without acquiring the db_login.php script, the UWE server lets me connect to 
	   my database. */
	$host1="localhost";
	$username1="root";
	$password1="";
	$db_name1="musicapi";

	$mysqli = new mysqli("$host1", "$username1", "$password1", "$db_name1");
	
	//The variable which will store the RSS feed in string format, and echo it out at the end of the script.
	$rssfeed = null;
	
	//The current date used to list the lastBuildDate.
	$build_date = date('r', time());
	
	//Acquire the artist_deezer_id which will be used to retrieve the artist's information from the database.
	$recent_artist_query = "SELECT artist_deezer_id FROM fav_artist ORDER BY fav_artist_index DESC LIMIT 2";
	$recent_artist_resource = $mysqli->query($recent_artist_query) or die("Could not execute Favorite Artist query");
	
	//Acquire the track_deezer_id which will be used to acquire the track's information from the database.
	$recent_track_query = "SELECT track_deezer_id FROM fav_track ORDER BY fav_track_index DESC LIMIT 2";
	$recent_track_resource = $mysqli->query($recent_track_query) or die("Count not execute favorite Track query");
	
	
	//Send a header to the browser to format the RSS in the browser.
	header('Content-Type: text/xml');
	
	$rssfeed .= '<?xml version="1.0" encoding="UTF-8" ?>';
    $rssfeed .= '<rss version="2.0" xmlns:favorites="http://www.cems.uwe.ac.uk/~s2-vora/dsa/file.html/">';
    $rssfeed .= '<channel>';
    $rssfeed .= '<title>Rap City Mashup</title>';
    $rssfeed .= '<link>http://www.cems.uwe.ac.uk/~s2-vora/dsa/rapmusic/</link>';
    $rssfeed .= '<description>An application which mashes various API data together to give you a list of Rap artists, albums, and tracks to favorite</description>';
    $rssfeed .= '<language>en-us</language>';
	$rssfeed .= '<lastBuildDate>'.$build_date.'</lastBuildDate>';
	
	while($recent_artist = $recent_artist_resource->fetch_assoc()){
	
		//Acquire all the information of the recently favorited artist from the database.
		$artist_query = "SELECT * FROM artist WHERE artist_deezer_id = '$recent_artist[artist_deezer_id]'";
		$artist_resource = $mysqli->query($artist_query);
		$artist = $artist_resource->fetch_assoc();
		
		//Create the item element which will contain the most recently favorited artist.
		$rssfeed .= '<!-- Recently favorited artists.-->';
		$rssfeed .= '<item>';
		$rssfeed .= '<title>Most Recently Favorited Artist</title>';
		$rssfeed .= '<description>The most recently favorited artist by one of our users: '.$artist['artist_name'].'</description>';
		$rssfeed .= '<link>http://www.cems.uwe.ac.uk/~s2-vora/dsa/albums.php?echonest_id='.urlencode($artist['artist_echonest_id']).'&amp;deezer_id='.urlencode($artist['artist_deezer_id']).'&amp;artist_name='.urlencode($artist['artist_name']).'&amp;artist_image='.urlencode($artist['artist_image']).'</link>';
		$rssfeed .= '<favorites:artistInformation>';
		$rssfeed .= '<favorites:artistDeezerID>'.$artist['artist_deezer_id'].'</favorites:artistDeezerID>';
		$rssfeed .= '<favorites:artistEchonestID>'.$artist['artist_echonest_id'].'</favorites:artistEchonestID>';
		$rssfeed .= '<favorites:artistName>'.$artist['artist_name'].'</favorites:artistName>';
		$rssfeed .= '<favorites:artistImage>'.$artist['artist_image'].'</favorites:artistImage>';
		$rssfeed .= '</favorites:artistInformation>';
		$rssfeed .= '</item>';
		
	}
	
	//Loop through the track table and acquire each track's information from the table.
	while($recent_track = $recent_track_resource->fetch_assoc()){
	
		//Acquire all the information of the recently favourite track and its album cover from the album table.
		$track_query = "SELECT track.*, album.album_cover FROM track, album WHERE track.album_deezer_id = album.album_deezer_id AND track.track_deezer_id = '$recent_track[track_deezer_id]'";
		$track_resource = $mysqli->query($track_query);
		$track = $track_resource->fetch_assoc();
		
		//Create the Item element which will contain the recently favorited track.
		$rssfeed .= '<!--Recently favorited track. -->';
		$rssfeed .= '<item>';
		$rssfeed .= '<title>Recent Favorited Track</title>';
		$rssfeed .= '<description>The most recently favorited track by one of our users: '.$track['track_name'].'</description>';
		$rssfeed .= '<link>http://www.cems.uwe.ac.uk/~s2-vora/dsa/tracks.php?album_id='.urlencode($track['album_deezer_id']).'&amp;echonest_id='.urlencode($artist['artist_echonest_id']).'&amp;deezer_id='.urlencode($artist['artist_deezer_id']).'&amp;artist_image='.urlencode($artist['artist_image']).'</link>';
		$rssfeed .= '<favorites:trackInformation>';
		$rssfeed .= '<favorites:trackDeezerID>'.$track['track_deezer_id'].'</favorites:trackDeezerID>';
		$rssfeed .= '<favorites:trackName>'.$track['track_name'].'</favorites:trackName>';
		$rssfeed .= '<favorites:trackPreview>'.$track['preview'].'</favorites:trackPreview>';
		$rssfeed .= '</favorites:trackInformation>';
		$rssfeed .= '<favorites:albumInformation>';
		$rssfeed .= '<favorites:albumDeezerID>'.$track['album_deezer_id'].'</favorites:albumDeezerID>';
		$rssfeed .= '<favorites:albumImage>'.$track['album_cover'].'</favorites:albumImage>';
		$rssfeed .= '</favorites:albumInformation>';
		$rssfeed .= '</item>';			
		
	}
	
	//Concatenate the ending channel and rss tags.
	$rssfeed .= '</channel>';
    $rssfeed .= '</rss>';
	
	//Display the RSS feed which we have concatenated within $rssfeed;
	echo $rssfeed;
	
	
?>