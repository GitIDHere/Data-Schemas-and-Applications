<?php

/*
	This script is initiated when the user clicks the Next or previous button which then sets the variable $element_offset 
	to the offset from which to acquire the next ten artists, albums, or tracks. 
*/


// $elemnt_num holds the offset number to display the current ten artists, albums, or tracks.
$element_offset = 0;

// $counter is used to hold the offset of the next ten artists, albums, or tracks.
$counter = 9;

// $_GET['PAGE'] is used to identify which button the user clicked so appropriate actions could be taken. 
if(isset($_GET['page'])){

	if($_GET['page'] == 'next'){
		
		// Increment the $counter and $element_offset variables to hold the offset for the next ten artists, albums, or tracks.
		$counter += $_GET['range'];
		$element_offset += $_GET['range'];
		
		// $_GET['total_list'] is used to identify the total amount of artists, albums or tracks that are available to go through.
		if(isset($_GET['total_list'])){
			
			// Check to see if the $element_offset does not go out of the range of the total artists, albums or tracks available.
			if($element_offset >= $_GET['total_list']){
			
				// If it is greater than the total amount of artists, albums or tracks available then decrement 9 from the offset.
				$element_offset -= 9;
				$counter  -= 9;
				
			}
			
		}
		
	}else if($_GET['page'] == 'previous'){
		
		// Check to see if $_GET['range'] is over 9, only then allow the previous  artists, albums or tracks to be shown.
		if($_GET['range'] > 9){
			
			// Increment the $counter and $element_offset variables to hold the offset for the next ten artists, albums, or tracks.
			$element_offset += $_GET['range'];
			$counter += $_GET['range'];
			
			/* Only allow the user to go to previous artists, albums or tracks if $element_offset and $counter is greater than 9. 
				This will stop the offset from being set to negative numbers. */
			if($element_offset > 9 && $counter > 9){
				
				/* Since we had incremented the $element_offset and counter by 9 above we need to decrement these elements by 18
					to get the previous artists, albums, and tracks. */
				$element_offset -= 18;
				$counter -= 18;

			}	
		}
	}
}

?>