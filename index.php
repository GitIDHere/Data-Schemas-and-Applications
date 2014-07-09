<?php
	include('scripts/check_login_status.php');
	include('scripts/top_five_tracks.php');
	include('top_albums_widget/top_five_albums_widget.php');
	include('flick_r_widget/flickr_widget.php');
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8" />
		<title>Rap Mashup</title>

		<link rel="stylesheet" href="css/Reset.css"  />
		<link rel="stylesheet" href="css/template.css" />
		<link rel="stylesheet" href="css/indexCSS.css" />
		<link rel="stylesheet" href="flick_r_widget/flick_r_widget_css.css" />	
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
			
			<div id="main_content">	

				<div id="left_content">
				
				
					<!-- Widget: Top five rap albums in charts this week. Student Number: 10005183 -->
					<article class="top5">
						<h3>Top 5 Rap Albums This Week</h3>
						<?php echo $top_albums; ?>
					</article>
					<!-- Widget: Top rap five albums END. -->
					
					<article class="top5">
						<h3>Top 5 Favorited Tracks</h3>
						<?php echo $topfive_output; ?>
					</article>
					
				<!-- left_content div ENDS -->
				</div>
				
				
				<div id="right_content">
					
					<!-- Flick_r Widget. Student Number: 11013888 -->
					<article id="flickr_widget">
						<h3>Flickr images Rap Music</h3>
						<div id="flick_r_image_list">
							<?php echo $photo_out; ?>
						</div>
					</article>
					<!-- Flick R widget END -->
					
				<!-- right_content div END -->	
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