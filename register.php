<?php 
	session_start();
	$error = '';
	$loc_error = '';
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
		$tableName = $_POST['submit'];
		if($tableName == 'login'){
			if (empty($_POST['username']) || empty($_POST['psw']) || empty($_POST['psw-repeat'])) {
				$error = "Username or Password is invalid";
			} else if ($_POST['psw'] != $_POST['psw-repeat']){
				$error = "Passwords do not match."; 	
			} else {
				include('./php/connection.php');
			
				$username = $_POST['username'];
				$name     = $_POST['name']; 
				$birth    = $_POST['birthdate'];
				$gender   = $_POST['gender'];
				$region   = $_POST['region'];
				$province = $_POST['province'];
				$municipality = $_POST['municipality'];
				$barangay = $_POST['barangay'];
				$email    = $_POST['email'];
				$pword    = $_POST['psw']; 
				$vac      = $_POST['vacine'];
				$comorb   = $_POST['comor'];
				$covid    = $_POST['covid'];
				
				$query = "SELECT * FROM login ORDER BY UserID DESC LIMIT 1";  
				$result = mysqli_query($con, $query);
				$row = mysqli_fetch_array($result);  
				$count = (int) str_replace('C', '', $row[0]);
				$userID   = sprintf('%05s', $count+1);
				
				$query = "SELECT * from `login` where username = '$username'";  
				$result = mysqli_query($con, $query);
				$count = mysqli_num_rows($result);
			 
				if($count == 0){
					$query = "INSERT INTO `login` (`UserID`, `UserName`, `Email`, 
													`Password`, `Name`, `Birthdate`, 
													`Gender`, `Region`, `Province`, 
													`Municipality`, `Barangay`, 
													`Vaccinated`, `Comorbidity`, `Covid_Positive`) 
							VALUES ('$userID', '$username', '$email', 
									'$pword', '$name', '$birth', '$gender', 
									'$region', '$province', '$municipality', '$barangay', 
									'$vac', '$comorb', '$covid');";
					if (mysqli_query($con, $query)) {
						$_SESSION['userid'] = $userID;
						$_SESSION['username'] = $username;
						$_SESSION['psw'] = $pword;
						$_SESSION['name'] = $name;
						mysqli_close($con);
					    //echo("<meta http-equiv='refresh' content='1'>");
					    //header("location: ./user.php");
						echo("<script>location.href ='./user.php';</script>");
					} else {
						$error = "Registration Failed. Try again"; 
						printf("Error: %s\n", mysqli_error($con));
                        exit();
					}			
				}
				else{  
					$error = "Username already exists."; 
					mysqli_close($con);
				}
			}
		} else if ($tableName == 'loc_login'){
			if (empty($_POST['loc_username']) || empty($_POST['loc_psw']) || empty($_POST['loc_psw-repeat'])) {
				$loc_error = "Username or Password is invalid";
			} else if ($_POST['loc_psw'] != $_POST['loc_psw-repeat']){
				$loc_error = "Passwords do not match."; 	
			} else {
				include('./php/connection.php');
				
				$username = $_POST['loc_username'];
				$name     = $_POST['loc_name'];
				$email    = $_POST['loc_email'];
				$contnum  = $_POST['loc_number'];
				$street   = $_POST['loc_street_address'];
				$region   = $_POST['loc_region'];
				$province = $_POST['loc_province'];
				$municipality = $_POST['loc_municipality'];
				$barangay = $_POST['loc_barangay'];
				$pword    = $_POST['loc_psw']; 
				
				$query = "SELECT * FROM `loc_login` ORDER BY loc_ID DESC LIMIT 1";  
				$result = mysqli_query($con, $query);
				$row = mysqli_fetch_array($result);  
				$count = (int) str_replace('C', '', $row[0]);
				$userID   = sprintf('%05s', $count+1);
				
				$query = "SELECT * from `loc_login` where username = '$username'";  
				$result = mysqli_query($con, $query);
				$count = mysqli_num_rows($result);  
			 
				if($count == 0){
					
					$query = "INSERT INTO `loc_login` (`Loc_ID`, `Username`, `Name`, 
														`Password`, `Email`, `Contact_Num`, 
														`Street_Add`, `Region`, `Province`, 
														`Municipality`, `Barangay`) 
							VALUES ('$userID', '$username', '$name', 
									'$pword', '$email', '$contnum', 
									'$street', '$region', '$province', '$municipality', '$barangay');";	
					if (mysqli_query($con, $query)) {
						$_SESSION['loc_username'] = $username;
						$_SESSION['loc_userid']   = $userID;
						$_SESSION['loc_psw']      = $pword;
						$_SESSION['loc_name']     = $name;
						mysqli_close($con);
						echo("<script>location.href ='./location.php';</script>");
					} else {
						$loc_error = "Registration Failed. Try again"; 
						printf("Error: %s\n", mysqli_error($con));
                        exit();
					}			
				} else {  
					$loc_error = "Username already exists."; 
					mysqli_close($con);
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html dir="ltr" lang="us-en">
	<head>
		<title>Register | SafeZonePH</title>
		<link href="./images/icon.png" rel="icon" type="image/x-icon">
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" type="text/css" href="./styles/style.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"> </script>
		<script type="text/javascript" src="./scripts/javascript.js"></script>
	</head>
	<body>
		<div class="container">
			<div class='head'>
				<img class="icons" src="images/icons/profile.png"/>
				<h1>Register</h1>
				<p>Please fill in this form to register an account.</p> 
			</div>

			<hr>
			<!-- Tab Buttons -->
			<div id="tab-btn">
				<a href="#" class="user-tab active">USER</a>
				<a href="#" class="location-tab">LOCATION</a>
			</div>

			<!-- User Form -->
			<div class="user-box">
				<h2>USER REGISTER</h2>
				<p>If you're a new user, please register here.</p>
				<hr>
				<form action="" method="post">
					<label for="username"><b>Username</b></label>
					<input type="text" placeholder="Enter Username" name="username" id="username" pattern="[A-Za-z0-9_]{3,15}" title="At least four (4) alphanumeric characters and underscore only." onchange="checkUsername()" required />
					<label for="username"><b>Name</b></label>
					<input type="text" placeholder="Enter Full name" name="name" id="name" pattern="[A-Za-z.,' ]{3,20}" title="At least four (4) alphabetic characters only." onchange="capitalizeName('user')" required />
					
					<label for="birthdate"><b>Birthdate (mm/dd/yyyy)</b></label>
					<input type="date" placeholder="Enter birthdate" max="2012-01-01" name="birthdate" id="birthdate" required />

					<label for="gender"><b>Gender</b></label>
					<select name="gender" name="gender" id="gender" required>
						<option value="" selected disabled hidden>Choose Gender</option>
						<option value="Male">Male</option>
						<option value="Female">Female</option>
					</select>
					
					<label for="region"><b>Region</b></label>
					<select name="region" class="region" name="region" id="region" onchange="selectProvince(this)" required><option value="" selected disabled hidden>Choose Region</option></select>
					<label for="province"><b>Province</b></label>
					<select name="province" class="province" name="province" id="province" onchange="selectMunicipality(this)" disabled required><option value="" selected disabled hidden>Choose Province</option></select>
					<label for="municipality"><b>Municipality</b></label>
					<select name="municipality" class="municipality" name="municipality" id="municipality" onchange="selectBarangay(this)" disabled required><option value="" selected disabled hidden>Choose Municipality</option></select>
					<label for="barangay"><b>Barangay</b></label>
					<select name="barangay" class="barangay" name="barangay" id="barangay" disabled required><option value="" selected disabled hidden>Choose Barangay</option></select>

					<label for="email"><b>E-mail Address</b></label>
					<input type="email" placeholder="Enter E-mail" name="email" id="email" required/>
					<label for="psw"><b>Password</b></label>
					<input type="password" placeholder="Enter Password" name="psw" id="psw" pattern="[A-Za-z0-9]{3,15}" title="At least four (4) alphanumeric characters only." onchange="checkPasswordMatch('user')" required />
					<label for="psw"><b>Confirm Password</b></label>
					<input type="password" placeholder="Enter Password" name="psw-repeat" id="psw-repeat" pattern="[A-Za-z0-9]{3,15}" title="At least four (4) alphanumeric characters only." onchange="checkPasswordMatch('user')" required />
					<input type="checkbox" onclick="showPassword(this, 'user')">Show Password</input>
					
					<div id="error_message"></div>
					<div id="username_message"><?php echo "<p>".$error."</p>"; ?></div>
					
					<hr>
					<h2>USER HEALTH DECLARATION</h2>
					<p>Are you vaccinated?</p>
					<input type="hidden" name="vacine" value="false" />
					<input type="checkbox" id="vacine" name="vacine" value="true">
					<label for="vacine">I have been vaccinated</label>

					<p>Have you been diagnosed with Covid-19 recently?</p>
					<input type="hidden" name="covid" value="no" />
					<input type="checkbox" id="covid" name="covid" value="yes">
					<label for="covid">I have been diagnosed with Covid-19</label>
					<br><br>
					<label for="comor"><b>Co-morbidities</b></label>
					<input type="text" name="comor" id="comor" />
                    <p>By clicking <b>Sign up</b>, you agree with <a href="" target="">Terms of Use and Privacy Notice</a>.</p>
					<button type="submit" name="submit" value="login" id="registerbtn" class="buttons">Sign up</button>
					<button type="reset" id="loginbtn" class="buttons" onclick="clearErrors('user')" >Reset</button>
				</form>
			</div>

			<!-- Location Form -->
			<div class="location-box">
				<h2>LOCATION REGISTER</h2>
				<p>If you're a new location/establishment, please register here.</p>
				<hr>
				<form action="" method="post">
					<label for="loc_username"><b>Username</b></label>
					<input type="text" placeholder="Enter Username" name="loc_username" id="loc_username" pattern="[A-Za-z0-9_]{3,15}" title="At least four (4) alphanumeric characters and underscore only." onchange="checkUsername()" required />
					<label for="loc_name"><b>Name</b></label>
					<input type="text" placeholder="Enter Location name" name="loc_name" id="loc_name" pattern="[A-Za-z.,' ]{3,20}" title="At least four (4) alphabetic characters only." onchange="capitalizeName('loc')" required />
					<label for="loc_email"><b>E-mail Address:</b></label>
					<input type="email" placeholder="Enter E-mail" name="loc_email" id="loc_email" required />
					<label for="loc_number"><b>Contact Number</b></label>
					<input type="number" placeholder="Enter Contact Number" name="loc_number" id="loc_number" required />
					<label for="loc_street_address"><b>Street Address</b></label>
					<input type="text" placeholder="Enter Street Address" name="loc_street_address" id="loc_street_address" required />
									
					<label for="loc_region"><b>Region</b></label>
					<select name="loc_region" class="loc_region" name="loc_region" id="loc_region" onchange="loc_selectProvince(this)" required><option value="" selected disabled hidden>Choose Region</option></select>
					<label for="loc_province"><b>Province</b></label>
					<select name="loc_province" class="loc_province" name="loc_province" id="loc_province" onchange="loc_selectMunicipality(this)" disabled required><option value="" selected disabled hidden>Choose Province</option></select>
					<label for="loc_municipality"><b>Municipality</b></label>
					<select name="loc_municipality" class="loc_municipality" name="loc_municipality" id="loc_municipality" onchange="loc_selectBarangay(this)" disabled required><option value="" selected disabled hidden>Choose Municipality</option></select>
					<label for="loc_barangay"><b>Barangay</b></label>
					<select name="loc_barangay" class="loc_barangay" name="loc_barangay" id="loc_barangay" disabled required><option value="" selected disabled hidden>Choose Barangay</option></select>

					<label for="loc_psw"><b>Password</b></label>
					<input type="password" placeholder="Enter Password" name="loc_psw" id="loc_psw" pattern="[A-Za-z0-9]{3,15}" title="At least four (4) alphanumeric characters only." onchange="checkPasswordMatch('loc')" required />
					<label for="loc_psw"><b>Confirm Password</b></label>
					<input type="password" placeholder="Enter Password" name="loc_psw-repeat" id="loc_psw-repeat" pattern="[A-Za-z0-9]{3,15}" title="At least four (4) alphanumeric characters only." onchange="checkPasswordMatch('loc')" required />
					<input type="checkbox" onclick="showPassword(this, 'loc')">Show Password</input>

					<div id="loc_error_message"></div>
					<div id="loc_username_message"><?php echo "<p>".$loc_error."</p>"; ?></div>
                
                    <p>By clicking <b>Sign up</b>, you agree with <a href="" target="">Terms of Use and Privacy Notice</a>.</p>
					<button type="submit" name="submit" value="loc_login" id="registerbtn" class="buttons">Sign up</button>
					<button type="reset" id="loginbtn" class="buttons" onclick="clearErrors('loc')">Reset</button>
				</form>
			</div>
		</div>

		<div class="container signup">
		<p>Already have an account? <a href="./login.php">Log in</a>.</p>
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

		<script>
			const url = './json/philippine_provinces_cities_municipalities_and_barangays_2019v2.json';
			var loc_regionChoice;
			var loc_provinceChoice;
			var loc_municipalityChoice;
			var loc_barangayChoice;
		
			let loc_dropdownRegion = $('#loc_region');
			let loc_dropdownProvince = $('#loc_province');
			let loc_dropdownMunicipality = $('#loc_municipality');
			let loc_dropdownBarangay = $('#loc_barangay');
			
			loc_dropdownRegion.empty();
			loc_dropdownRegion.append('<option value="" selected disabled hidden>Choose Region</option>');
			loc_dropdownRegion.prop('selectedIndex', 0);
					
			$.getJSON(url, function (data) {
				var reg_keys = Object.keys(data).sort();
				for (let i = 0; i < reg_keys.length; i++){
					loc_dropdownRegion.append($('<option></option>').attr('value', reg_keys[i]).text(data[reg_keys[i]].region_name));
				}
			});
			
			function loc_selectProvince(choice){
				loc_regionChoice = choice.value;
				loc_dropdownProvince.empty();
				loc_dropdownMunicipality.empty();
				loc_dropdownMunicipality.append('<option value="" selected disabled hidden>Choose Municipality</option>');
				loc_dropdownMunicipality.attr("disabled", true, "required", true);
				
				loc_dropdownBarangay.empty();
				loc_dropdownBarangay.append('<option value="" selected disabled hidden>Choose Barangay</option>');
				loc_dropdownBarangay.attr("disabled", true);
				
				loc_dropdownProvince.attr("disabled", false, "required", true);
				loc_dropdownProvince.prop('disabled', false, "required", true);
				loc_dropdownProvince.append('<option value="" selected disabled hidden>Choose Province</option>');
				loc_dropdownProvince.prop('selectedIndex', 0);
				$.getJSON(url, function (data) {
					var province_keys = Object.keys(data[loc_regionChoice].province_list).sort();
					for (let i = 0; i < province_keys.length; i++){
						loc_dropdownProvince.append($('<option></option>').attr('value', province_keys[i]).text(province_keys[i]));
					}
				});
			}
			
			function loc_selectMunicipality(choice){
				loc_provinceChoice = choice.value;
				loc_dropdownBarangay.empty();
				loc_dropdownBarangay.append('<option value="" selected disabled hidden>Choose Barangay</option>');
				loc_dropdownBarangay.attr("disabled", true, "required", true);
				
				loc_dropdownMunicipality.empty();
				loc_dropdownMunicipality.attr("disabled", false, "required", true);
				loc_dropdownMunicipality.prop('disabled', false, "required", true);
				loc_dropdownMunicipality.append('<option value="" selected disabled hidden>Choose Municipality</option>');
				loc_dropdownMunicipality.prop('selectedIndex', 0);
				$.getJSON(url, function (data) {
					var municipality_keys = Object.keys(data[loc_regionChoice].province_list[loc_provinceChoice].municipality_list).sort();
					for (let i = 0; i < municipality_keys.length; i++){
						loc_dropdownMunicipality.append($('<option></option>').attr('value', municipality_keys[i]).text(municipality_keys[i]));
					}
				});
			}
			
			function loc_selectBarangay(choice){
				loc_municipalityChoice = choice.value;
				loc_dropdownBarangay.empty();
				loc_dropdownBarangay.attr("disabled", false, "required", true);
				loc_dropdownBarangay.prop('disabled', false, "required", true);
				loc_dropdownBarangay.append('<option value="" selected disabled hidden>Choose Barangay</option>');
				loc_dropdownBarangay.prop('selectedIndex', 0);
				$.getJSON(url, function (data) {
					var barangay_keys = Object.keys(data[loc_regionChoice].province_list[loc_provinceChoice].municipality_list[loc_municipalityChoice].barangay_list).sort();
					for (let i = 0; i < barangay_keys.length; i++){
						loc_dropdownBarangay.append($('<option></option>').attr('value', barangay_keys[i]).text(barangay_keys[i]));
					}
				});
			}
			
			var regionChoice;
			var provinceChoice;
			var municipalityChoice;
			var barangayChoice;
		
			let dropdownRegion = $('#region');
			let dropdownProvince = $('#province');
			let dropdownMunicipality = $('#municipality');
			let dropdownBarangay = $('#barangay');
			
			dropdownRegion.empty();
			dropdownRegion.append('<option value="" selected disabled hidden>Choose Region</option>');
			dropdownRegion.prop('selectedIndex', 0);
					
			$.getJSON(url, function (data) {
				var reg_keys = Object.keys(data).sort();
				for (let i = 0; i < reg_keys.length; i++){
					dropdownRegion.append($('<option></option>').attr('value', reg_keys[i]).text(data[reg_keys[i]].region_name));
				}
			});
			
			function selectProvince(choice){
				regionChoice = choice.value;
				dropdownProvince.empty();
				dropdownMunicipality.empty();
				dropdownMunicipality.append('<option value="" selected disabled hidden>Choose Municipality</option>');
				dropdownMunicipality.attr("disabled", true, "required", true);
				
				dropdownBarangay.empty();
				dropdownBarangay.append('<option value="" selected disabled hidden>Choose Barangay</option>');
				dropdownBarangay.attr("disabled", true, "required", true);
				
				dropdownProvince.attr("disabled", false, "required", true);
				dropdownProvince.prop('disabled', false, "required", true);
				dropdownProvince.append('<option value="" selected disabled hidden>Choose Province</option>');
				dropdownProvince.prop('selectedIndex', 0);
				$.getJSON(url, function (data) {
					var province_keys = Object.keys(data[regionChoice].province_list).sort();
					for (let i = 0; i < province_keys.length; i++){
						dropdownProvince.append($('<option></option>').attr('value', province_keys[i]).text(province_keys[i]));
					}
				});
			}
			
			function selectMunicipality(choice){
				provinceChoice = choice.value;
				dropdownBarangay.empty();
				dropdownBarangay.append('<option value="" selected disabled hidden>Choose Barangay</option>');
				dropdownBarangay.attr("disabled", true, "required", true);
				
				dropdownMunicipality.empty();
				dropdownMunicipality.attr("disabled", false, "required", true);
				dropdownMunicipality.prop('disabled', false, "required", true);
				dropdownMunicipality.append('<option value="" selected disabled hidden>Choose Municipality</option>');
				dropdownMunicipality.prop('selectedIndex', 0);
				$.getJSON(url, function (data) {
					var municipality_keys = Object.keys(data[regionChoice].province_list[provinceChoice].municipality_list).sort();
					for (let i = 0; i < municipality_keys.length; i++){
						dropdownMunicipality.append($('<option></option>').attr('value', municipality_keys[i]).text(municipality_keys[i]));
					}
				});
			}
			
			function selectBarangay(choice){
				municipalityChoice = choice.value;
				dropdownBarangay.empty();
				dropdownBarangay.attr("disabled", false, "required", true);
				dropdownBarangay.prop('disabled', false, "required", true);
				dropdownBarangay.append('<option value="" selected disabled hidden>Choose Barangay</option>');
				dropdownBarangay.prop('selectedIndex', 0);
				$.getJSON(url, function (data) {
					var barangay_keys = Object.keys(data[regionChoice].province_list[provinceChoice].municipality_list[municipalityChoice].barangay_list).sort();
					for (let i = 0; i < barangay_keys.length; i++){
						dropdownBarangay.append($('<option></option>').attr('value', barangay_keys[i]).text(barangay_keys[i]));
					}
				});
			}
			
		</script>	
		<?php if($tableName == 'loc_login') { ?>
    		<script>jQuery(function (){
    				$(".location-box").show();
    				$(".user-box").hide();
    				$(".location-tab").addClass("active");
    				$(".user-tab").removeClass("active");
    		});</script>
		<?php } ?>
</html>