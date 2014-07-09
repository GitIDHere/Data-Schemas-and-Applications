<?php

	/*
		Widget: Recent Videos From YouTube, and DailyMotion APIs.
		Student Number: 11020070
	*/
	
	//Get the artist name to be passed into the YouTube URI to acquire the artist's videos.
	$artist_name = urlencode($_GET['artist_name']);
	
	//Go through the UWE proxy and acquire the contents of the URI.
	$youtube_uri = file_get_contents("http://gdata.youtube.com/feeds/api/videos?q=$artist_name&start-index=1&max-results=5&v=2");
	
	//Represents the content acquired from the UWE proxy as an XML element making it readable to PHP.
	$youtube_xml = new SimpleXMLElement($youtube_uri);
	
	//The variable which will hold the HTML elements of the Muzu videos.
	$youtube_output = null;
	
	//Loop through each entry element in the XML file.
	foreach($youtube_xml->entry as $video){
		
		//The uploader's username.
		$author = $video->author->name;
		
		//The video title.
		$title = $video->title;
		
		//If the string length is over 20 then shorten it down to just 20 characters.
		if(strlen($title) > 15){
			$video_title = substr($title, 0, 15)."...";
		}else{
			$video_title = $title;
		}
		
		//Access the namespace 'media' to gain access to specific elements in the XML.
		$media = $video->children("http://search.yahoo.com/mrss/")->group;
		
		//Acquire the thumbnail.
		$thumbnail = $media->thumbnail[1]->attributes();
		
		//Gain access to the 'yt' namespace.
		$yt = $media->children("http://gdata.youtube.com/schemas/2007");
		
		//Get the video id.
		$id = $yt->videoid;
		
		//Get the duration of the video.
		$video_duration= $yt->duration->attributes();
		
		//If the video duration is over 3600 seconds (1 hour), then format it in hours, minutes, seconds.
		if($video_duration > 3600){
			$duration = gmdate("H:i:s", (double)$video_duration);
		}else{
			//else sort the duration in minutes and seconds.
			$duration = gmdate("i:s", (double)$video_duration);
		}
		
		//The HTML code for all of the videos are concatenated into $youtube_output.
		$youtube_output .= <<<DOC
			<div class="video_container">
				<a class="prettyPhoto" href="http://www.youtube.com/watch?v={$id}" target="_blank"><img class="thumbnail" src="{$thumbnail}" alt="{$video_title}" title="{$video_title}" /></a>
				<a class="video_title hover prettyPhoto" href="http://www.youtube.com/watch?v={$id}?rel=0" target="_blank">{$video_title}</a>
				<p class="text">Duration: {$duration}</p>
				<a class="text hover" href="http://www.youtube.com/{$author}" target="_blank">{$author}</a>
			</div>
DOC;
	}
	
	//Echo out the variables which will then be acquired by an AJAX request to this script and inserted into a div within the widget.
	echo '<p class="container_title">YouTube Rap Videos</p>';
	echo $youtube_output;
	
?>