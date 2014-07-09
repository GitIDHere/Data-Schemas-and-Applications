<?php

$itunes_uri = file_get_contents("http://itunes.apple.com/gb/rss/topalbums/limit=40/genre=18/xml");

//change comments when in UWE
$itunes_xml = new Simplexmlelement($itunes_uri);
//$echoest_xml = $echoest_uri;

$count = 1;

$top_albums = null;

foreach($itunes_xml->entry as $artist){
	
	$s_children = $artist->children("http://itunes.apple.com/rss"); 
	$title = $s_children->name;
	$artist_name = $s_children->artist;
	$image = $s_children->image[2];
	$price = substr($s_children->price,1);

	
	$top_albums .= <<<DOC

		<div class="artist_container">
		
				<p class="rank_number">#{$count}</p>
				
				<img class="artist_image" src="{$image}" alt="{$title}" />
				
				<p class="album_name">{$title}</p>
			
		</div>
DOC;
	
	$count++;
	
	if($count == 6){
		break;
	}
}

?>