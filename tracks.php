<?php
	include("scripts/check_login_status.php");
	include('scripts/get-next-list.php');
	require_once("scripts/track_List.php");
	require_once("scripts/buyAlbum.php");
	include("scripts/display_track_warnings.php");
?>

<!DOCTYPE html>
<html lang="en">

	<head>
	
		<meta charset="utf-8"/>
		<title>Rap Mashup</title>

		<link rel="stylesheet" href="css/Reset.css"  />
		<link rel="stylesheet" href="css/template.css" />
		<link rel="stylesheet" href="css/tracksCSS.css" />
		
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
				<a href="index.php"><span>Home</span></a> &raquo; 
				<a href="artist.php"><span>Artist</span></a> &raquo; 
				<a href="albums.php?echonest_id=<?php echo $echonest_id; ?>&deezer_id=<?php echo $deezer_id; ?>&artist_name=<?php echo $artist_name; ?>&artist_image=<?php echo $artist_image; ?>"><span><?php echo $artist_name; ?></span></a> &raquo;
				<span><?php echo $display_album_title; ?></span>
			</div>
			
			<div id="main_content">	

				<section id="introduction">
					<div class="lines"></div>
					<h1 id="page_title">Tracks</h1>
					<div class="lines"></div>
					<p id="information">
						<span>Note:</span> 
						To favourite an artist or track, 
						you have to be logged into this website. If you are not registered 
						to this website, then please click on the register button above.
					</p>
				</section>
				
				<section id="album_profile">
					<img id="album_image" src="<?php echo $album_cover; ?>" alt="<?php echo $display_album_title; ?>" title="<?php echo $display_album_title; ?>" />
					<h2 id="album_name"><?php echo $display_album_title; ?></h2>
					<p class="album_info">Released: <?php echo $release_date ?></p>
					<p class="album_info">Total Tracks: <?php echo $total_tracks; ?></p>
					<?php echo $buyAlbum; ?>
				</section>
				
				<?php echo $message; ?>
				
				<section id="track_list">
					
					<?php echo $track_output; ?>

				</section>

				<div id="page_select_container">
					<a class="page_select" href="tracks.php?page=previous&range=<?php echo $counter; ?>&total_list=<?php echo $total_tracks; ?>&echonest_id=<?php echo $echonest_id; ?>&deezer_id=<?php echo $deezer_id; ?>&album_id=<?php echo $album_id; ?>&artist_image=<?php echo $artist_image; ?>">
						&laquo; Previous
					</a>
					
					<a class="page_select" href="tracks.php?page=next&range=<?php echo $counter; ?>&total_list=<?php echo $total_tracks; ?>&echonest_id=<?php echo $echonest_id; ?>&deezer_id=<?php echo $deezer_id; ?>&album_id=<?php echo $album_id; ?>&artist_image=<?php echo $artist_image; ?>">
						Next &raquo;
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