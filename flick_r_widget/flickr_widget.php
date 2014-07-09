<?php

	/* 
		Student Number: 11013888
	*/
	
	$key = "838a724bb9a06aafaa73bcc87f3f6dfa";

	$encoded_artist_name = urlencode($artist_name);
	
	$xml = file_get_contents("https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=$key&tags=$encoded_artist_name&text=$encoded_artist_name&per_page=8&format=rest");
	
	$flickr_xml = new SimpleXMLElement($xml);
	
	$photo_out = null;
	
	
	foreach($flickr_xml->photos->photo as $photo){
	
		$photo_out .= <<<PHOTO
			<img class="flick_r_img" src="http://farm{$photo->attributes()->farm}.staticflickr.com/{$photo->attributes()->server}/{$photo->attributes()->id}_{$photo->attributes()->secret}.jpg" alt="{$photo->attributes()->title}" title="{$photo->attributes()->title}"/>
PHOTO;
	}


?>


