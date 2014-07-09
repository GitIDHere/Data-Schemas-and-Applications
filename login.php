<?php
	include("scripts/check_login_status.php");
	include("scripts/display_login_warnings.php");
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8" />
		<title>Rap Mashup</title>

		<link rel="stylesheet" href="css/Reset.css"  />
		<link rel="stylesheet" href="css/template.css" />
		<link rel="stylesheet" href="css/loginCSS.css" />
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
				<a href="index.php"><span>Home</span></a> &raquo; <span>Login</span></a>
			</div>
			
			<div id="main_content">	
			
				<?php if(isset($message)){echo $message;} ?>
				
				
				<section id="note_container">
					<h2 id="note_heading">For DSA Testing Only</h2>
					<ul id="note_list">
						<li>Username: user</li>
						<li>password: password</li>
					</ul>
				</section>
				
				<section id="login_container">
					<h2 id="login_title">Login</h2>
					<form id="login_form" method="POST" action="scripts/process-login.php">
						<span class="label">Username</span>
						<input class="input" type="text" name="username" />
						<br/>
						<span class="label">Password</span>
						<input class="input" type="password" name="password" />
						<br/>
						<button id="submit" type="submit" name="submit" >Submit</button>
					</form>
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