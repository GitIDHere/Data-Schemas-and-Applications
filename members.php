<?php
	include('scripts/check_login_status.php');
	require('scripts/require_user_info.php');
	require('scripts/number_of_favorites.php');
?>

<!DOCTYPE html>
<html lang="en">

	<head>
	
		<meta charset="utf-8" />
		<title>Rap Mashup</title>

		<link rel="stylesheet" href="css/Reset.css"  />
		<link rel="stylesheet" href="css/template.css" />
		<link rel="stylesheet" href="css/membersCSS.css" />	

		<!-- Aquire Ajax library and link file to process Ajax requests. -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="scripts/js/fav_favorite_requests.js" type="text/javascript"></script>		
	</head>
	
	<body>
	
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
							<?php if($user_status){ echo $user_status; } ?>
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
			
			
			<div id="bread_crumbs">
				<a href="index.php"><span>Home</span></a> &raquo; 
				<span>Members</span>
			</div>		
			
			
			<div id="main_content">	

				<section>
					<div class="lines"></div>
					<h1 id="page_title">Members</h1>
					<div class="lines"></div>
				</section>
			
				<section id="member_profile">
					<img id="member_image" src="images/user-img.png" alt="<?php echo $username; ?>" title="<?php echo $username; ?>" />
					<h2 id="member_name"><?php echo $username; ?></h2>
					<p class="total_favs">Total Artists Favorited: <?php echo $num_artist_favorites['COUNT(artist_deezer_id)']; ?></p>
					<p class="total_favs">Total Tracks Favorited: <?php echo $num_track_favorites['COUNT(track_deezer_id)']; ?></p>
					<a class="tab_button" href="#favorite_artist" onclick="favorite_artist_request('artist')">Show Favorite Artists</a>
					<a class="tab_button" href="#favorite_track" onclick="favorite_artist_request('track')">Show Favorite Tracks</a>
				</section>
				
				
				<section id="fav_list">
					
					<section id="favorite_artist">
						<h3>Your favorite artists</h3>
						<div id="artist" class="favorites_list"></div>
					</section>
					
					<section id="favorite_track">
						<h3>Your Favorite Tracks</h3>
						<div id="track" class="favorites_list"></div>
					</section>		
				
				<!-- fav_list END -->
				</section>
				
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