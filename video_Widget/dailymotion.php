<?php
	
	/*
		Widget: Recent Videos From YouTube, and DailyMotion APIs.
		Student Number: 11020070
	*/
	
	//Get the artist name to be passed into the URI to retrieve video information about that artist.
	$artist_name = urlencode($_GET['artist_name']);
	
	//Go through the UWE proxy and aquire the contents of the URI.
	$json_uri = file_get_contents("https://api.dailymotion.com/videos?fields=channel.name,duration%2Cowner.screenname%2Cthumbnail_medium_url%2Ctitle%2Curl&search=$artist_name&tags=$artist_name&page=1&limit=5");
	
	//Convert the Json contents into a PHP variable.
	$dailymotion_json = json_decode($json_uri, true);
	
	//The variable which will hold the HTML containing the videos.
	$DM_output = null;

	//Loop through each item in the list array.
	foreach($dailymotion_json['list'] as $video){
		
		//The video uploader's name.
		$author = $video['owner.screenname'];
		
		//The video title.
		$title = $video['title'];
		
		//If the string length is over 20 then shorten it down to just 20 characters.
		if(strlen($title) > 15){
			$video_title = substr($title, 0, 15)."...";
		}else{
			$video_title = $title;
		}
		
		//Video thumbnail
		$thumbnail = $video['thumbnail_medium_url'];
		
		//The duration of the video.
		$video_duration = $video['duration'];
		
		//If the video duration is over 3600 seconds (1 hour), then format it in hours, minutes, seconds.
		if($video_duration > 3600){
			$duration = gmdate("H:i:s", (double)$video_duration);
		}else{
			//else shor the duration in minutes and seconds.
			$duration = gmdate("i:s", (double)$video_duration);
		}
		
		//The URL of the video.
		$video_url = $video['url'];
		
		//The HTML code for all of the videos are contactonated into $DM_output.
		$DM_output .= <<<DOC
		
			<div class="video_container">
				<a href="{$video_url}" class="prettyPhoto" target="_blank"><img class="thumbnail" src="{$thumbnail}" alt="{$video_title}" title="{$video_title}" /></a>
				<a class="video_title hover" href="{$video_url}" class="prettyPhoto" target="_blank">{$video_title}</a>
				<p class="text">Duration: {$duration}</p>
				<a class="text hover" href="{$author}" target="_blank">{$author}</a>
			</div>
DOC;
	}
	
	//Echo out the variables which will then be aquired by an AJAX request to this script and inserted into a div within the widget.
	echo '<p class="container_title">Dailymotion Rap Videos</p>';
	echo $DM_output;

?>