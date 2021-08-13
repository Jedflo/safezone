<?php 
	session_start();
	$error = '';
	$tableName = '';
	if (isset($_SESSION['username']) || isset($_SESSION['loc_username']))
	{
		if(isset($_SESSION['username'])){
			header("location: ./user.php");
		}
		if(isset($_SESSION['loc_username'])){
			header("location: ./location.php");
		}
	}
	
	if (isset($_POST['submit'])) {
		if (empty($_POST['username']) || empty($_POST['psw'])) {
			$error = "Username or Password is invalid";
		} else {
			include('./php/connection.php');
			$username = $_POST['username'];  
			$password = $_POST['psw'];  
			
			$tableName = $_POST['submit'];
			$username = stripcslashes($username);  
			$password = stripcslashes($password);  
			$username = mysqli_real_escape_string($con, $username);  
			$password = mysqli_real_escape_string($con, $password);
			
			$query = "SELECT * from ".$tableName." where UserName = '".$username."' and Password = '".$password."'";  
			$result = mysqli_query($con, $query);  
			  
			if(mysqli_num_rows($result)){
			    $row = mysqli_fetch_array($result);  
				if($tableName == 'login'){
					$_SESSION['userid']   = $row[0];
					$_SESSION['username'] = $row[1];
					$_SESSION['psw']      = $row[2];
					$_SESSION['name']     = $row[4];
					echo "<script>window.location.href='user.php'</script>";
				} else {
					$_SESSION['loc_userid']   = $row[0];
					$_SESSION['loc_username'] = $row[1];
					$_SESSION['loc_psw']      = $row[7];
					$_SESSION['loc_name']     = $row[2];
					echo "<script>window.location.href='location.php'</script>";					
				}
			} else { 
				$error = "Login failed. Invalid username or password.";
			}
			mysqli_close($con);
		}
	}
?>

<!DOCTYPE html>
<html dir="ltr" lang="us-en">
	<head>
		<title>Login | SafeZonePH</title>
		<link href="./images/icon.png" rel="icon" type="image/x-icon">
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" type="text/css" href="./styles/style.css">
		<!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

		<script type="text/javascript" src="./scripts/javascript.js"></script>
	</head>
	<body>
	<!-- Navbar-->
		
		
	
		<div class="container login-container">
			<div class='head'>
				<img class="icons" src="images/icons/profile.png"/>
				<h1>Login</h1>
				<p>Please fill in this form to login your account.</p>
			</div>

			<hr>
			
			<!-- Tab Buttons -->
			<div id="tab-btn">
				<a href="#" class="user-tab active">USER</a>
				<a href="#" class="location-tab">LOCATION</a>
			</div>

			<!-- User Form -->
			<div class="user-box">
				<h2>USER LOGIN</h2>
				<p>If you're using an individual account, please login here.</p>
				<hr>
				<form action="" method="post">
				<label for="username"><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="username" id="username" required />
				<label for="psw"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="psw" id="psw" required />
				<input type="checkbox" onclick="showPassword(this, 'user')" >Show Password</input>
				<div id="error_message"><?php echo "<p>".$error."</p>"; ?></div>
				<p>By clicking <b>Login</b>, you agree with <a href="" target="">Terms of Use and Privacy Notice</a>.</p>
				<button type="submit" name="submit" value="login" id="loginbtn" class="buttons">Login</button>
				<button type="reset" id="loginbtn" class="buttons" onclick="clearErrors('user')" >Reset</button>
				</form>
			</div>

			<!-- Location Form -->
			<div class="location-box">
				<h2>LOCATION LOGIN</h2>
				<p>If you're using a location/establishment account, please login here.</p>
				<hr>
				<form action="" method="post">
				<label for="username"><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="username" id="loc_username" required />
				<label for="psw"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="psw" id="loc_psw" required />
				<input type="checkbox" onclick="showPassword(this, 'loc')" >Show Password</input>
				<div id="loc_error_message"><?php echo "<p>".$error."</p>"; ?></div>
				
				<p>By clicking <b>Login</b>, you agree with <a href="" target="">Terms of Use and Privacy Notice</a>.</p>
				<button type="submit" name="submit" value="loc_login" id="loginbtn" class="buttons">Login</button>
				<button type="reset" id="loginbtn" class="buttons" onclick="clearErrors('loc')" >Reset</button>
				</form>
			</div>
		</div>
		
		<div class="container signup">
			<p>Don't have an account? <a href="./register.php">Sign up</a>.</p>
		</div>
		
		<div class='sns' id='sns'>
			<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fsafezoneph.000webhostapp.com%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore"><img src="images/icons/facebook.png" alt="Facebook" title="Facebook"></a>
			<a target="_blank" class="twitter-share-button" href="https://twitter.com/intent/tweet?text=Check%20out%20SafeZonePH.%20A%20web-based%20online%20contact%20tracing%20app.%20Link%20here:%20http://safezoneph.000webhostapp.com/" data-size="large"><img src="images/icons/twitter.png" alt="Twitter" title="Twitter"></a>
			<a href="https://www.instagr.am/leonelinon" target="_blank"><img src="images/icons/instagram.png" alt="Instagram" title="Instagram"></a>
			<a href="https://t.me/leo_leonel" target="_blank"><img src="images/icons/telegram.png" alt="Telegram" title="Telegram"></a>
		</div>
		<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v11.0" nonce="xhNd669X"></script>
		<link rel="canonical" href="/web/tweet-button">
		<link rel="me" href="https://twitter.com/twitterdev">
		
		<foooter>
            <img src="images/icon" alt="safezoneph_logo" width="140"/>
            <p>
                © 2020 SafeZone Philippines.
                <br>
                v1.0.0. All rights reserved.
                <br>
                Polytechnic University of the Philippines
                <br>
                College of Computer and Information Science
            </p>
    	</foooter>

		<?php if($tableName == 'loc_login') { ?>
		<script>jQuery(function (){
				$(".location-box").show();
				$(".user-box").hide();
				$(".location-tab").addClass("active");
				$(".user-tab").removeClass("active");
		});</script>
		<?php } ?>
	
	</body>
</html>