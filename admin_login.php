<?php 
	session_start();
	$error = '';
	if (isset($_SESSION['admin'])) {
		header("location: ./admin.php");
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
			
			$query = "SELECT * from admin where UserName = '".$username."' and Password = '".$password."'";  
			$result = mysqli_query($con, $query);  
			  
			if(mysqli_num_rows($result)){
			    $row = mysqli_fetch_array($result);  
				$_SESSION['adminID']    = $row['AdminID'];
				$_SESSION['admin']      = $row['UserName'];
				$_SESSION['admin_name'] = $row['AdminName'];
				echo "<script>window.location.href='admin.php'</script>";
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
		<title>Admin | SafeZonePH</title>
		<link href="./images/icon.png" rel="icon" type="image/x-icon">
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" type="text/css" href="./styles/style.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="./scripts/javascript.js"></script>
	</head>
	<body>
	    <!-- ADMIN DASH -->
			<div class="container login-container">
			<div class='head'>
				<img class="icons" src="images/icons/profile.png"/>
				<h1>Admin Login</h1>
				<p>Please fill in this form to login to admin.</p>
			</div>
			<hr>
			<!-- User Form -->
			<form action="" method="post">
			<label for="username"><b>Username</b></label>
			<input type="text" placeholder="Enter Username" name="username" id="username" required />
			<label for="psw"><b>Password</b></label>
			<input type="password" placeholder="Enter Password" name="psw" id="psw" required />
			<input type="checkbox" onclick="showPassword(this, 'user')" >Show Password</input>
			<div id="error_message"><?php echo "<p>".$error."</p>"; ?></div>
			<button type="submit" name="submit" value="login" id="loginbtn" class="buttons">Login</button>
			<button type="reset" id="loginbtn" class="buttons" onclick="clearErrors('user')" >Reset</button>
			</form>
		<foooter>
    		<div class="copyright">
    			<center>
                <img src="images/icon.png" alt="Logo" width="140"/>
                <p>
                    <br>
                    Copyright © 2021 SafeZonePH.com™.
                    <br>
                    All rights reserved.
                    <br>
                    Polytechnic University of the Philippines
                    <br>
                    College of Computer and Information Science
                    <br>
                    BSCS 3 - 4 (S.Y. 2020-2021) 
                </p> 
    			</center>
            </div>
    	</foooter>
	</body>
	<script>
		var modalUser = document.getElementById("userList");
		var modalLocation  = document.getElementById("locationList");

		function openUsers() {
			modalUser.style.display = "block";
		}
		
		function openLocations() {
			modalLocation.style.display = "block";
		}

		function closeOverlay() {
			modalUser.style.display = "none";
			modalLocation.style.display = "none";
			//location.reload();
		}
		
		window.onclick = function(event) {
			if (event.target == modalUser || event.target == modalLocation) {
				modalAccount.style.display = "none";
				modalHealth.style.display = "none";
				//location.reload();
			}
		}
	</script>
</html>