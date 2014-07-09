<?php
	include("scripts/check_login_status.php");
	
	include('scripts/get-next-list.php');
	require_once("scripts/artist_albums.php");
	
	include("scripts/get_bio.php");
	require_once("scripts/favorite_users.php");
	include("scripts/display_album_warnings.php");
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8" />
		<title>Rap Mashup</title>

		<link rel="stylesheet" href="css/Reset.css"  />
		<link rel="stylesheet" href="css/template.css" />
		<link rel="stylesheet" href="css/albumsCSS.css" />		
		
		<!--JQuery lib required for the Ajax calls for the Video Widget to work. -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<!--Required for the Video widget-->
		<link rel="stylesheet" href="video_Widget/video_WidgetCSS.css" />
		<script type="text/javascript" src="video_Widget/load-widgets-ajax.js" ></script>
	</head>
	
	<!-- On load run the video_request function so that the videos are displayed as soon as the page has loaded. -->
	<body  onload="video_request('youtube','<?php echo $artist_name; ?>')">
	
		<!--This wrapper is used to align content to the center of the screen. -->
		<div class="wrapper">
		
			<header>
			
				<a href="index.php"><img id="banner" src="images/Banner.png" alt="Rap Mashup banner" /></a>
				
				<!-- Top navigation bar start -->
				<nav>
					<ul>
						<li>
							<a href="index.php">Home</a>
							<div class="arrow"></div>
						</li>
						<li>
							<a href="artist.php">Artists</a>
							<div class="arrow"></div>
						</li>
						<li>
							<a href="members.php">Members</a>   
							<div class="arrow"></div>
						</li>                 
						<li>
							<a href="register.php">Register</a>
							<div class="arrow"></div>
						</li>
						<li>
							<?php echo $user_status; ?>
							<div class="arrow"></div>
						</li>
						<li>
							<div id="rss_container">
								<a href="feed/rss.php">
									<img id="rss_img" src="images/rss.png" alt="RSS feed" title="Rss feed" />
								</a>
							</div>
						</li>
					</ul>
				<!-- Top navigation bar END -->	
				</nav>
					
			</header>
			
			<!-- The bread crumbs to take the user back to the previous pages. -->
			<div id="bread_crumbs">
				<a href="index.php"><span>Home</span></a> &raquo; 
				<a href="artist.php"><span>Artist</span></a> &raquo; 
				<span><?php echo $artist_name ?></span>
			</div>		
			
			<div id="main_content">	
				
				<!--The heading of the page. Notifies the user that they need to log in to be able to favourite the artist. -->
				<section id="introduction">
					<div class="lines"></div>
					<h1 id="page_title">Albums</h1>
					<div class="lines"></div>
					<p id="information">
					<span>Note:</span> 
						To favourite an artist or track, 
						you have to be logged into this website. If you are not registered 
						to this website, then please click on the register button above.
					</p>
				</section>
				
				
				<!-- The profile information about the artist. Contains: biography, artist name, and artist image. -->
				<section id="artist_profile">
					<img id="artist_image" src="<?php echo $artist_image; ?>" alt="<?php echo $artist_name ?>" title="<?php echo $artist_name ?>" />
					<h2 id="artist_name"><?php echo $artist_name ?></h2>
					<p id="total_albums">Total albums: <?php echo $num_of_albums; ?></p>
					<p id="artist_description"><?php echo $lastfm_bio; ?></p>
				</section>
				
				
				<!-- The container which will display the usernames of the users who have favourited this artist. -->
				<div id="fav_container">
					<?php echo $favorite_button; ?>
					<div id="fav_users">
						<p id="fav_users_title">Users Who Have Favorited This Artist</p>
							<?php if($fav_users){ echo $fav_users; } ?>
					</div>
				</div>
				
				
				<!-- A warning or a success message will only be displayed when the user favourites the artist. -->
				<?php echo $message; ?>
				
				
				<!-- This section contains the list of albums being passed in from the artist_albums.php page. -->
				<section id="album_list">
					<?php echo $albums_output;?>
				</section>
				
				
				<!--Widget: recent rap videos from YouTube, Muzu, and DailyMotion. Student Number: 11020070 -->
				<section id="rapVideo_container">

					<h4 id="widget_title">Rap Videos of <?php echo $artist_name; ?></h4>
					
					<!-- These are the buttons which the user will click on to view videos from different websites.-->
					<ul id="button_container">
						<li class="buttons"><a class="tab" href="#youtube" onclick="video_request('youtube','<?php echo $artist_name; ?>')">Youtube</a></li>
						<li class="buttons"><a class="tab" href="#dailymotion" onclick="video_request('dailymotion','<?php echo $artist_name; ?>')">Dailymotion</a></li>
					</ul>
					
					<!-- The content retrieved from the Ajax call to request the PHP file containing the relvant video information will be displayed within the 
						appropriate div element below. --> 
					<div id="content_container">
						<div class="list-container" id="youtube"></div>
						<div class="list-container" id="dailymotion"></div>
					</div>
				
				<!--Widget: recent rap videos ENDS -->
				</section>
				
				<!-- The buttons which the user can use to get the next 9 albums, if the user has more than 9 albums -->
				<div id="page_select_container">
					<a class="page_select" href="albums.php?page=previous&range=<?php echo $counter; ?>&total_list=<?php echo $num_of_albums; ?>&echonest_id=<?php echo $echonest_id; ?>&deezer_id=<?php echo $deezer_id; ?>&artist_name=<?php echo $artist_name; ?>&artist_image=<?php echo $artist_image; ?>">
						&laquo; Previous Albums
					</a>
					
					<a class="page_select" href="albums.php?page=next&range=<?php echo $counter; ?>&total_list=<?php echo $num_of_albums; ?>&echonest_id=<?php echo $echonest_id; ?>&deezer_id=<?php echo $deezer_id; ?>&artist_name=<?php echo $artist_name; ?>&artist_image=<?php echo $artist_image; ?>">
						Next Albums &raquo;
					</a>
				</div>				
				
				
			<!-- Main Body Content END -->	
			</div>
			
		<!-- WRAPPER END -->	
		</div>		
		
		<footer>
				
			<div class="wrapper">	
				
				<article id="about_us">
					<h5 class="footer_titles">About This Website</h5>
					<p class="description">
						This is a website which has been built from using various
						APIs from the internet to present you with a variety of atists to choose 
						from, as well as to listen to some of their music tracks.
					</p>
				</article>
				
				<article id="footer_nav">	
					<h5 class="footer_titles">Main Links</h5>
					<a class="footer_links hover" href="index.php">Home</a>
					<a class="footer_links hover" href="artist.php">Artists</a>
					<a class="footer_links hover" href="members.php">Members</a>
					<a class="footer_links hover" href="register.php">Register</a>
					<a class="footer_links hover" href="login.php">Login</a>
				</article>				
			
				<article id="copyright">
					<h5 class="footer_titles">Created By</h5>
					<p>Samir Vora</P>
					<p>Nicholas Pruett</P>
					<p>Grant Ponter</P>
					<p>Irene Okwuka</P>
				</article>			

			<!--Wrapper END -->	
			</div>	
				
		</footer>
		
	</body>
	
</html>