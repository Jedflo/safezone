<?php 
	session_start();
?>

<!DOCTYPE html>
<html dir="ltr" lang="us-en">
	<head>
		<title>Home | SafeZonePH</title>
		<link href="./images/icon.png" rel="icon" type="image/x-icon">
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" type="text/css" href="./styles/style.css">
		<script type="text/javascript" src="./scripts/javascript.js"></script>
	</head>
	<body >
	<header>
			<nav>
				<div id='dropdown' class='dropdown'>
					<button type="button" class='menu_icon'><img src="images/menu.jpg" width="50px" height="50px"/></button>
					<div id="dropdown_content" class="dropdown_content">
						<a href="index.html"   target="" style="background-color: #ddd;	color: #760001; border-bottom: 3px solid #760001;">HOME</a></li>						
						<?php if (isset($_SESSION['username'])) { ?>
							<a href="user.php" target="">ACCOUNT</a>
						<?php } else if (isset($_POST['loc_username'])) { ?>
							<a href="location.php" target="">ACCOUNT</a>
						<?php } else { ?>
							<a href="login.php" target="">LOGIN</a>
						<?php } ?>
						
						<a href="about.php"    target="">ABOUT</a>
					</div>
				</div>
				<ul>
					<li><a href="index.html"   target="" style="background-color: #ddd;	color: #760001; border-bottom: 3px solid #760001;">HOME</a></li>
						<?php if (isset($_SESSION['username'])) { ?>
							<a href="user.php" target="">ACCOUNT</a>
						<?php } else if (isset($_POST['loc_username'])) { ?>
							<a href="location.php" target="">ACCOUNT</a>
						<?php } else { ?>
							<a href="login.php" target="">LOGIN</a>
						<?php } ?>
					<li><a href="about.php"    target="">ABOUT</a></li>
				</ul>
			</nav>
			<div class="logo">
				<a href="index.html" target="">
				<img src="images/icon.png" width="50px"/>
				</a>
			</div>
		</header>

	    <!-- USER DASH -->
	    <br><br>
		<form action="" method="post">
			<div class="container">   
			<h1> Welcome to SafeZonePH </h1>
			<h4> A Web-based Contract Tracing Application </h4>
			
			<div id="fb-root"></div>
			<div class="share" id="share">
            	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v11.0" nonce="xhNd669X"></script>
            	<div id="share-btn" class="fb-share-button" data-href="http://safezoneph.000webhostapp.com/" data-layout="button_count" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fsafezoneph.000webhostapp.com%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div>
            	
            	<link rel="canonical" href="/web/tweet-button">
            	<link rel="me" href="https://twitter.com/twitterdev">
            	<a id="share-btn" class="twitter-share-button"
                  href="https://twitter.com/intent/tweet?text=Check%20out%20SafeZonePH.%20A%20web-based%20online%20contact%20tracing%20app.%20Link%20here:%20http://safezoneph.000webhostapp.com/"
                  data-size="large">Tweet</a>
                  
                <script>window.twttr = (function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0],
                        t = window.twttr || {};
                      if (d.getElementById(id)) return t;
                      js = d.createElement(s);
                      js.id = id;
                      js.src = "https://platform.twitter.com/widgets.js";
                      fjs.parentNode.insertBefore(js, fjs);
                      t._e = [];
                      t.ready = function(f) {
                        t._e.push(f);
                      };
                      return t;
                    }(document, "script", "twitter-wjs"));
                </script>
			</div>
			
			</div> 
			
			
			<div class="container">
			<script src="https://apps.elfsight.com/p/platform.js" defer></script>
            <div class="elfsight-app-80562b8e-082c-4517-959a-ce5a8b65acda"></div>
            </div>
			<!-- SHARE PLUGINS -->
			<div class='sns' id='sns'>
				<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fsafezoneph.000webhostapp.com%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore"><img src="images/icons/facebook.png" alt="Facebook" title="Facebook"></a>
				<a target="_blank" href="https://twitter.com/intent/tweet?text=Check%20out%20SafeZonePH.%20A%20web-based%20online%20contact%20tracing%20app.%20Link%20here:%20http://safezoneph.000webhostapp.com/" data-size="large"><img src="images/icons/twitter.png" alt="Twitter" title="Twitter"></a>
				<a href="https://www.instagr.am/leonelinon" target="_blank"><img src="images/icons/instagram.png" alt="Instagram" title="Instagram"></a>
				<a href="https://t.me/leo_leonel" target="_blank"><img src="images/icons/telegram.png" alt="Telegram" title="Telegram"></a>
			</div>
	</body>
	
	
	<!-- Scripts for QR -->
	<script type="text/javascript" src="./scripts/qrcode.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js" integrity="sha256-/H4YS+7aYb9kJ5OKhFYPUjSJdrtV6AeyJOtTkw6X72o=" crossorigin="anonymous"></script>
</html>