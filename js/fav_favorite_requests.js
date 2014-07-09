
/*
	The following code for changing the colours of the widget buttons 
	has been adapted from code used on this website:
	
	http://stackoverflow.com/questions/6265139/how-to-change-background-color-on-click-with-jquery
*/

//Allows the user to buttons on the members.php page to hide and show content based on the button they click.
$(document).ready(function(){
	
	//Hide the favorite_track div which is currently showing.
	$('#favorite_track').hide();
	
	//Select the first child of a.tab_button and make its colour grey.
	$('section#member_profile a.tab_button:first').css('background','#EBEBEB');
	
	/* A funtion which will listen for a click on any of the buttons with the class of tab_button.
		When a click is detected, any section element within the div fav_list will be hidden, and 
		the div with the id tag the same as the href attribute of the button that had been clicked will be displayed. */
	$('section#member_profile a.tab_button').click(function() {
		$('#fav_list section').hide();
		$($(this).attr('href')).show();
		
		/*Upon clicking any anchor element with the class of tab_button, that element's colour should be 
		changed to grey, and the other button should be white. */
		$(this).parent().find('.tab_button').css('background','#ffffff');
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

var feed;

//The variable will contain the ID of the div element to display the relevant favourites acquired from the PHP scripts.
var output_element;

//Run the function upon loading this script and show the user's favourite artists.
favorite_artist_request("default");


//The AJAX function which will acquire the user's favourite artists or track depending on the str value passed to this function.
function favorite_artist_request(button_name){
	
	//A switch statement which will set the feed variable to the correct path of the PHP script to acquire the user's favvourite artists/tracks.
	switch(button_name){
		case 'artist':
			feed = 'require_fav_artist_info.php';
			offset = 0;
			output_element = 'artist';
			break;
		case 'track':
			feed = 'require_fav_track_info.php';
			offset = 0;
			output_element = 'track';
		   break;
		default:
		   feed = 'require_fav_artist_info.php';
		   offset = 0;
		   output_element = 'artist';
	}
	

	
	request = new ajaxRequest()
	
	//Prepare the AJAX request, inserting the path to the PHP script which it will run.
	request.open("GET", "scripts/"+feed+"?offset="+offset, true);
	
	//Function which will check if AJAX has executed correctly. If all the if statements are true, then the content of the PHP script will be requested.
	request.onreadystatechange = function(){
		if(this.readyState == 4){
			if(this.status == 200){
				if(this.responseText != null){
					if(request.responseText){
						//Output the acquired data from the PHP script to the element with the ID of output_element.
						document.getElementById(output_element).innerHTML = request.responseText;
					}
				}else alert("No data recieved");
			}else {
				alert("Ajax error: "+this.statusText);
			}
		}
	}

	request.send(null);	
}