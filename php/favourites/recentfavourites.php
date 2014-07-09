<?php
if(!require_once('scripts/db_login.php')){
	//Need redirect them to an appropriate page to tell what has happened.
	die("UNABLE TO CONNECT TO DATABASE: process-fav-artist.php");
}


$recent_artist_fav = null;
$recent_track_favs = null;


//===========Recent Favourite Artist ===========
$recent_artist_query = "SELECT artist_deezer_id FROM fav_artist ORDER BY fav_artist_index DESC LIMIT 5 ";
$recent_artist_resource = $mysqli->query($recent_artist_query);

while($recent_artist = $recent_artist_resource->fetch_assoc()){

	$artist_query = "SELECT artist_name, artist_image FROM artist WHERE artist_deezer_id = '$recent_artist[artist_deezer_id]'";
	$artist_resource = $mysqli->query($artist_query);
	$artist = $artist_resource->fetch_assoc();
	
	$recent_artist_fav .= <<<DOC
	<div class="fav-container">
		<a href="#" ><img src="{$artist['artist_image']}" alt="{$artist['artist_name']}" /></a>
		<a class="name" href="#">{$artist['artist_name']}</a>
	</div>
DOC;
}


//=============Recent Favourite Tracks =================
$recent_track_query = "SELECT track_deezer_id FROM fav_track ORDER BY fav_track_index DESC LIMIT 5";
$recent_track_resource = $mysqli->query($recent_track_query);

while($recent_track = $recent_track_resource->fetch_assoc()){

	$track_query = "SELECT track.track_name, album.album_cover FROM track, album WHERE track.album_deezer_id = album.album_deezer_id AND track.track_deezer_id = '$recent_track[track_deezer_id]'";
	$track_resource = $mysqli->query($track_query);
	$track = $track_resource->fetch_assoc();
	
	if(strlen($track['track_name']) > 12){
		 $track['track_name'] = substr($track['track_name'], 0, 12)."...";
	}
	
	$recent_track_favs .= <<<DOC
		<div class="fav-container">
		<a href="#" ><img src="{$track['album_cover']}" alt="{$track['track_name']}" /></a>
		<a class="name" href="#">{$track['track_name']}</a>
	</div>
DOC;
}
?>