<?php
	include("scripts/check_login_status.php");

	include('scripts/get-next-list.php');
	require_once('scripts/artistList.php');
	include('twitter_Widget/twitter_Widget.php');
?>

<!DOCTYPE html>
<html lang="en">

	<head>
	
		<meta charset="utf-8" />
		<title>Rap Mashup</title>

		<link rel="stylesheet" href="css/Reset.css"  />
		<link rel="stylesheet" href="css/template.css" />
		<link rel="stylesheet" href="css/artistCSS.css" />
		<link rel="stylesheet" href="twitter_Widget/twitter_css.css" />
		
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
			
			
			<div id="bread_crumbs">
				<a href="index.php"><span>Home</span></a> &raquo; <span>Artist</span>
			</div>			
			
			
			<div id="main_content">	

			
				<section id="introduction">
					<div class="lines"></div>
					<h1 id="page_title">Artists</h1>
					<div class="lines"></div>
					<p id="information">
						Here you are able to find Rap artists from a wide range of 
						backgrounds and time periods.If you are interested in finding
						out about a particular artist, then just click on thier picture
						or name and you will be directed to a page to view most of their 
						albums.
					</p>
				</section>
					
					
				<section id="artist_list">
				<?php
					 $artists = ($artist_output != null ? $artist_output : null);
					 echo $artists;
				?>
				</section>
				

				<article id="twitter_widget">
					<h3>Tweets About Rap Music</h3>
					<?php echo $feed; ?>
				</article>
				
				
				<div id="page_select_container">
					<a class="page_select" href="artist.php?page=previous&range=<?php echo $counter; ?>">&laquo; Previous Artists</a>
					<a class="page_select" href="artist.php?page=next&range=<?php echo $counter; ?>">Next Artists &raquo; </a>
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