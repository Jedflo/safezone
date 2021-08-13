<?php 
	session_start();
	$error = '';
	if (isset($_SESSION['username']) && isset($_SESSION['psw']))
	{
		header("location: ./user_location.php");
	}
	if (isset($_POST['submit'])) {
		if (empty($_POST['username']) || empty($_POST['psw'])) {
			$error = "Username or Password is invalid";
		} else {
			include('./php/connection.php');
			
			//echo $con ? 'connected' : 'not connected';
			
			$username = $_POST['username'];  
			$password = $_POST['psw'];  
			
			$username = stripcslashes($username);  
			$password = stripcslashes($password);  
			$username = mysqli_real_escape_string($con, $username);  
			$password = mysqli_real_escape_string($con, $password);
			
			$query = "SELECT * from LOC_LOGIN where username = '".$username."' and password = '".$password."'";  
			$result = mysqli_query($con, $query);  
			//$count = mysqli_num_rows($result);  
			  
			if(mysqli_num_rows($result)){
			    $row = mysqli_fetch_array($result);  
				$_SESSION['userid']   = $row[0];
				$_SESSION['username'] = $row[1];
				$_SESSION['psw']      = $row[7];
				$_SESSION['name']     = $row[2];
				echo "<script>window.location.href='user_location.php'</script>";
			}  
			else{  
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
		<script type="text/javascript" src="./scripts/javascript.js"></script>
	</head>
	<body>
		<br><br>
		<form action="" method="post">
			<div class="container">
			<div class='head'>
				<img class="icons" src="images/icons/profile.png"/>
				<h1>Location Login</h1>
				<p>Please fill in this form to login your account.</p>
			</div>
			<hr>
			<label for="username"><b>Username</b></label>
			<input type="text" placeholder="Enter Username" name="username" id="username" required />
			
			<label for="psw"><b>Password</b></label>
			<input type="password" placeholder="Enter Password" name="psw" id="psw" required />
			
			<input type="checkbox" onclick="showPassword()">Show Password</input>
			
			<button type="reset" id="resetbtn" class="resetbtn">Reset</button>
			
			<div id="error_message"><?php echo "<p>".$error."</p>"; ?></div>
			
			<hr>
			
			<button type="submit" name="submit" id="loginbtn" class="buttons">Login</button>
			<p>for user accounts, login  <a href="./login.php">Here</a>.</p>
			
			</div>
			
			
			<div class="container signup">
			<p>Don't have an account? <a href="./register.php">Sign up</a>.</p>
			<p>or register your business <a href="./register_location.php">Here</a>.</p>
			</div>
		
		</form>
		<br><br>
		<div class='sns' id='sns'>
			<a href="https://www.fb.me/leonelinon" target="_blank"><img src="images/icons/facebook.png" alt="Facebook" title="Facebook"></a>
			<a href="https://www.twitter.com/god" target="_blank"><img src="images/icons/twitter.png" alt="Twitter" title="Twitter"></a>
			<a href="https://www.instagr.am/leonelinon" target="_blank"><img src="images/icons/instagram.png" alt="Instagram" title="Instagram"></a>
			<a href="https://t.me/leo_leonel" target="_blank"><img src="images/icons/telegram.png" alt="Telegram" title="Telegram"></a>
		</div>
	</body>
</html>