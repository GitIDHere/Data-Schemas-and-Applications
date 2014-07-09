/*
	Widget: Recent Videos From YouTube, and DailyMotion APIs.
	Student Number: 11020070
*/


/*
	The following code for changing the colours of the widget buttons 
	has been adapted from code used on this website:
	
	http://stackoverflow.com/questions/6265139/how-to-change-background-color-on-click-with-jquery
*/

/* Allows the content currently shown to be hidden and new content 
to be shown upon pressing one of the buttons at the top of the widget. */
$(document).ready(function(){
	
	//Select the first child of a.tab_button and make its colour grey.
	$('ul#button_container a.tab:first').css('background','#EBEBEB');
	
	$('ul#button_container .tab').click(function() {
		$('#content_container div').hide();
		$($(this).attr('href')).show();
		
		/*Upon clicking any anchor element with the class of tab_button, that element's colour should be 
		changed to grey, and the other button should be white. */
		$(this).parent().parent().find('.tab').css('background','#ffffff');
		$(this).css('background','#EBEBEB');
	});
	
});

function ajaxRequest(){
	try{
		var request = new XMLHttpRequest()
	}catch(e1){
		try{
			request = new ActiveXObject("Msxml2.XMLHTTP")
		}catch(e2){
			try{
				request = new ActiveXObject("Microsoft.XMLHTTP")
			}catch(e3){
				request = false;
				alert("request is false");
			}//Third catch end
		}//Second catch end
	}//first catch end
	return request
}

//The variable which will store the current API name, and the current artist name.
var currentfeed;
var current_artist; /*CHANGED */

/*
	This function is ran everytime the user clicks on one of the top buttons on the widget.
	A string is passed to this function which will then be used in a switch statment to compare against
	a predefined string term. If the terms match then then the appropriate string term is placed in the 
	feed variable which will then be used to access the PHP file of the same name and get the API data 
	from that PHP file and insetrted into a predefined div element on the index.php page.
*/

function video_request(str, ArtistName){
	
	// The switch statment compares the term passed into the function with a predefined string.
	// If the terms match then the appropriate string is placed inside the feed variable.
	switch(str){
		case 'youtube':
		   var feed = 'youtube';
		   var artist_name = ArtistName;
		   break;
		case 'dailymotion':
		   var feed = 'dailymotion';
		   var artist_name = ArtistName;
		   break;
	}
	
	request = new ajaxRequest();
	
	//Get the contents from the PHP file which is named the same as the variable feed.
	request.open("GET", 'video_Widget/'+feed+'.php?artist_name='+artist_name, true) 

	//Check if the Ajax requests are sent and recived correctly.
	request.onreadystatechange = function(){
		if(this.readyState == 4){
			if(this.status == 200){
				if(this.responseText != null){
					if(request.responseText){
						// Insert the content retrieved from the PHP file and insert it into
						// an HTML element named the same as the string in feed.
						document.getElementById(feed).innerHTML = request.responseText;
						
					}else{
						alert("no responce");
					}
				}else alert("No data recieved");
			}
		}
	}

	request.send(null);	
	
	//Store the feed and the artist name in a global variable so that they can be used again when re-running the function. 
	currentfeed = feed;
	current_artist = artist_name;
}

//Re-run the function every 10 seconds to get any new video updates.
setInterval(function(){video_request(currentfeed,current_artist)}, 10000);