<?php
/*
	Student Number: 11013887
*/

	$json_uri = file_get_contents("https://search.twitter.com/search.json?q=%23rap&rpp=5&include_entities=true&with_twitter_user_id=true&result_type=recent&count=5");

	$twitter_json = json_decode($json_uri, true);

	$feed = null;

	foreach($twitter_json['results'] as $tweets){

		
		$text = $tweets['text'];

		
	$feed .= <<<DOC
		
				<div class="tweet">
					<p>{$text}</p>
				</div>
DOC;
	}

?>