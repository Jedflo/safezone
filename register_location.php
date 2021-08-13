<?php 
	session_start();
	$error = '';
	if (isset($_SESSION['username']) && isset($_SESSION['psw']))
	{
		header("location: ./index.php");
	}
	
	if (isset($_POST['submit'])) {
		if (empty($_POST['loc_username']) || empty($_POST['loc_psw']) || empty($_POST['loc_psw-repeat'])) {
			$error = "Username or Password is invalid.";
		}
		else if ($_POST['loc_psw'] != $_POST['loc_psw-repeat']){
			$error = "Passwords do not match."; 	
		}else {
			$username = $_POST['loc_username'];
			$name = $_POST['loc_name'];
			$email = $_POST['loc_email'];
			$contnum = $_POST['loc_number'];
			$street = $_POST['loc_street_address'];
			$city = $_POST['loc_city_address'];
			$pword = $_POST['loc_psw']; 
			echo $username;
			echo $email;
			echo $pword;
			echo $name;
			
			
			include('./php/connection.php');
			
            $query = "SELECT * FROM LOC_LOGIN ORDER BY loc_ID DESC LIMIT 1";  
			$result = mysqli_query($con, $query);
			$row = mysqli_fetch_array($result);  
			$count = (int) str_replace('C', '', $row[0]);

			$userID   = sprintf('%05s', $count+1);
			
			//looking for username matches
			$query = "SELECT * from LOC_LOGIN where username = '$username'";  
			$result = mysqli_query($con, $query);
			$count = mysqli_num_rows($result);  
		 
			if($count == 0){
				$query = "INSERT INTO LOC_LOGIN (`loc_ID`, `username`, `name`, `email`, `contact_num`, `street_add`, `city_add`, `password`)
				  VALUES('".$userID."', '".$username."', '".$name."', '".$email."','".$contnum."','".$street."','".$city."','".$pword."');";
				if (mysqli_query($con, $query)) {
				    echo "New record created successfully";
					$_SESSION['username'] = $username;
					$_SESSION['psw'] = $pword;
					$_SESSION['name'] = $name;
					mysqli_close($con);
					//echo "<meta http-equiv='refresh' content='0'>";
				} else {
					$error = "Registration Failed. Try again"; 
				}			
			}
			else{  
				$error = "Username already exists."; 
				mysqli_close($con);
			}
		}
	}
?>

<!DOCTYPE html>
<html dir="ltr" lang="us-en">
	<head>
		<title>Register</title>
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
				<h1>Register a Location</h1>
				<p>Please fill in this form to register an account.</p> 
			</div>
			<hr>
			<label for="loc_username"><b>Username</b></label>
			<input type="text" placeholder="Enter Username" name="loc_username" id="loc_username" pattern="[A-Za-z0-9_]{3,15}" title="At least four (4) alphanumeric characters and underscore only." onchange="checkUsername()" required />
			
			<label for="loc_name"><b>Name</b></label>
			<input type="text" placeholder="Enter Location name" name="loc_name" id="loc_name" pattern="[A-Za-z.,' ]{3,20}" title="At least four (4) alphabetic characters only." onchange="capitalizeName()" required />
			
			<label for="loc_email"><b>E-mail Address:</b></label>
			<input type="email" placeholder="Enter E-mail" name="loc_email" id="loc_email" required/>
			
			<label for="loc_number"><b>Contact Number</b></label>
			<input type="number" placeholder="Enter Contact Number" name="loc_number" id="loc_number" required/>
			
			<label for="loc_street_address"><b>Street Address</b></label>
			<input type="text" placeholder="Enter Street Address" name="loc_street_address" id="loc_street_address" required/>
			
			<label for="loc_city_address"><b>City Address</b></label>
			<input type="text" placeholder="Enter City Addressr" name="loc_city_address" id="loc_city_address" required/>
			
			<label for="loc_psw"><b>Password</b></label>
			<input type="password" placeholder="Enter Password" name="loc_psw" id="loc_psw" pattern="[A-Za-z0-9]{3,15}" title="At least four (4) alphanumeric characters only." onchange="checkPasswordMatch()" required />
			
			<label for="loc_psw"><b>Confirm Password</b></label>
			<input type="password" placeholder="Enter Password" name="loc_psw-repeat" id="loc_psw-repeat" pattern="[A-Za-z0-9]{3,15}" title="At least four (4) alphanumeric characters only." onchange="checkPasswordMatch()" required />
		
			<input type="checkbox" onclick="showPassword()">Show Password</input>
			<button type="reset" id="resetbtn" class="resetbtn">Reset</button>
			
			<div id="usernameExist">
			<?php echo "<p style='color: red'>".$error."</p>"; ?>
			</div>
			
			<div id="error_message">
			<?php echo "<p style='color: red'>".$error."</p>"; ?>
			</div>
			<hr>
			<button type="submit" name="submit" id="registerbtn" class="buttons">Sign up</button>
			</div>
			
			<div class="container signup">
			<p>Already have an account? <a href="./login.php">Log in</a>.</p>
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