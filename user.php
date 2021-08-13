<?php 
	session_start();
	$error = '';
	date_default_timezone_set('Asia/Brunei');
	$user_contact_condition='';
	$user_contact_message="";
	
	if (empty($_SESSION['username']) && empty($_SESSION['loc_username'])){
		header("location: ./login.php");
	} else if (isset($_SESSION['loc_username'])){
		header("location: ./location.php");
	} else if (isset($_SESSION['username'])) {
	    include('./php/connection.php');
	    @$account_query = "SELECT * FROM login WHERE UserID='".$_SESSION['userid']."'";
		$session_userid = (isset($_SESSION['userid'])) ? $_SESSION['userid'] : '';

		$user_contact = "SELECT * FROM login WHERE UserID='".$_SESSION['userid']."'";
		$user_hadContact = mysqli_query($con, $user_contact);
		while($contact = mysqli_fetch_assoc($user_hadContact)){
					$user_contact_condition = $contact['hadContact'];
					$user_covid_positive    = $contact['Covid_Positive'];
					
					if(strcasecmp($user_covid_positive,"yes")==0){
						$user_contact_message="You have reported that you have COVID-19.";
					}

					else if (strcasecmp($user_contact_condition,"yes")==0) {

						$loc_name_query = "SELECT * FROM loc_login WHERE loc_ID='".$contact['contactWhere']."'";
						$locnamequery = mysqli_query($con, $loc_name_query);
						$locnamequeryresult = mysqli_fetch_assoc($locnamequery);
						$locname = $locnamequeryresult['Name'];

						$user_contact_message="You have been in contact with a Covid-19 positive individual at {$locname} on {$contact['contactWhen']}";
					}
					else{
						$user_contact_message="";
					}
					//echo $user_contact_message;

		}
		include('./php/updateAlert.php');
	} 
	
	if (isset($_POST['logout'])) {
		session_destroy();
		echo "<script>window.location.href='login.php'</script>";
	}

	if(isset($_POST['saveAccount'])){
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
		$query = "UPDATE `login` SET `UserName` = '$username', `Name` = '$name', `Birthdate` = '$birth', `Gender` = '$gender', 
									`Region` = '$region', `Province` = '$province', `Municipality` = '$municipality', `Barangay` = '$barangay', 
									`Email` = '$email'
					WHERE `UserID` = '".$_SESSION['userid']."' AND `UserName` = '".$_SESSION['username']."';";
		if (mysqli_query($con, $query)) {
			mysqli_close($con);
			$_SESSION['username'] = $username;
			$_SESSION['name']     = $name;
			echo("<script>alert('Account Update Succesful!');</script>");
			echo("<meta http-equiv='refresh' content='1'>");
		} else {
			echo("<script>alert('Account Update Failed!');</script>");
			echo("<meta http-equiv='refresh' content='1'>");
			mysqli_close($con);
			//printf("Error: %s\n", mysqli_error($con));
			//exit();
		}
	}
	
	if(isset($_POST['saveHealth'])){
		include('./php/connection.php');
		$upd_vaccinated     = $_POST['vacine'];
	    $upd_covid_positive = $_POST['covid'];
	    $upd_comorbidity    = $_POST['comor'];
   
	    $upd_query = "UPDATE `login` SET `Vaccinated` = '".$upd_vaccinated."', 
										covid_positive='".$upd_covid_positive."', 
										comorbidity='".$upd_comorbidity."' 
						WHERE `UserID` = '".$_SESSION['userid']."' AND `UserName` = '".$_SESSION['username']."';";
						
		if (mysqli_query($con, $upd_query)) {
			mysqli_close($con);
			echo("<script>alert('Health Update Succesful!');</script>");
			echo("<meta http-equiv='refresh' content='1'>");
		} else {
			echo("<script>alert('Health Update Failed!');</script>");
			echo("<meta http-equiv='refresh' content='1'>");
			mysqli_close($con);
			//printf("Error: %s\n", mysqli_error($con));
			//exit();
		}

		$today_unix = time();
		$todayMinus12_unix = strtotime('-12 days', $today_unix);
		
		$today_timestamp = date("Y-m-d H:i:s",$today_unix);
		$todayMinus12_timestamp = date("Y-m-d H:i:s",$todayMinus12_unix);

		if(strcasecmp($upd_covid_positive,"yes") == 0){
			$visited_places = "SELECT * FROM visit_list WHERE UserID='".$_SESSION['userid']."' AND time_of_visit BETWEEN '$todayMinus12_timestamp' AND '$today_timestamp'";

			//$visited_places = "SELECT * FROM visit_list WHERE UserID='".$_SESSION['userid']."' AND time_of_visit > '".$todayMinus12_timestamp."' AND time_of_visit <'".$today_timestamp."' ";

			//$visited_places = "SELECT * FROM visit_list WHERE UserID='".$_SESSION['userid']."' AND time_of_visit BETWEEN '$todayMinus12_timestamp' AND '$today_timestamp'";
			include('./php/connection.php');
			$result1 = mysqli_query($con, $visited_places);
			while($row = mysqli_fetch_assoc($result1)){
				$location=$row['loc_ID'];

				$contact_trace_query="SELECT * FROM visit_list WHERE loc_ID='".$location."' AND time_of_visit BETWEEN '$todayMinus12_timestamp' AND '$today_timestamp'";
				$result2 = mysqli_query($con, $contact_trace_query);
				while($users = mysqli_fetch_assoc($result2)){
					$yes="yes";
					
					if (strcasecmp($users['UserID'],$_SESSION['userid']) != 0) {
						
						$alert_query="
						UPDATE login 
						SET hadContact='".$yes."', 
						contactWhere='".$location."', 
						contactWhen='".$users['time_of_visit']."' 
						WHERE UserID='".$users['UserID']."'";
						$result3 = mysqli_query($con, $alert_query);


						$query = "SELECT * FROM tracing ORDER BY trans_number DESC LIMIT 1";  
						$result = mysqli_query($con, $query);
						$row = mysqli_fetch_array($result);  
						$count = (int) str_replace('C', '', $row[0]);
						$number   = sprintf('%05s', $count+1);
						$tracing_query="
						INSERT INTO tracing 
						VALUES ('".$number."','".$_SESSION['userid']."','".$users['UserID']."','".$location."','".$users['time_of_visit']."');
						";
						$result4 = mysqli_query($con,$tracing_query);
					}
				}
			}
		}
	}
	/*
	if (isset($_POST['editHealthStatus'])){
	    $upd_vaccinated=$_POST['vacine'];
	    $upd_covid_positive=$_POST['covid'];
	    $upd_comorbidity=$_POST['comor'];
   
	    $upd_query = "UPDATE login SET vaccinated='".$upd_vaccinated."', covid_positive='".$upd_covid_positive."', comorbidity='".$upd_comorbidity."' WHERE UserID='".$_SESSION['userid']."'";
	    
	    include('./php/connection.php');
			
        $query = "SELECT * FROM login ORDER BY UserID DESC LIMIT 1";  
		$result = mysqli_query($con, $upd_query);

		$today_unix = time();
		$todayMinus12_unix = strtotime('-12 days', $today_unix);
		
		$today_timestamp = date("Y-m-d H:i:s",$today_unix);
		$todayMinus12_timestamp = date("Y-m-d H:i:s",$todayMinus12_unix);

		if(strcasecmp($upd_covid_positive,"yes")==0){
			$visited_places = "SELECT * FROM visit_list WHERE UserID='".$_SESSION['userid']."' AND time_of_visit BETWEEN '$todayMinus12_timestamp' AND '$today_timestamp'";

			//$visited_places = "SELECT * FROM visit_list WHERE UserID='".$_SESSION['userid']."' AND time_of_visit > '".$todayMinus12_timestamp."' AND time_of_visit <'".$today_timestamp."' ";

			//$visited_places = "SELECT * FROM visit_list WHERE UserID='".$_SESSION['userid']."' AND time_of_visit BETWEEN '$todayMinus12_timestamp' AND '$today_timestamp'";

			$result1 = mysqli_query($con, $visited_places);
			while($row = mysqli_fetch_assoc($result1)){
				$location=$row['loc_ID'];

				$contact_trace_query="SELECT * FROM visit_list WHERE loc_ID='".$location."' AND time_of_visit BETWEEN '$todayMinus12_timestamp' AND '$today_timestamp'";
				$result2 = mysqli_query($con, $contact_trace_query);
				while($users = mysqli_fetch_assoc($result2)){
					$yes="yes";
					
					if (strcasecmp($users['UserID'],$_SESSION['userid'])!=0) {
						
						$alert_query="
						UPDATE login 
						SET hadContact='".$yes."', 
						contactWhere='".$location."', 
						contactWhen='".$users['time_of_visit']."' 
						WHERE UserID='".$users['UserID']."'";
						$result3 = mysqli_query($con, $alert_query);


						$query = "SELECT * FROM tracing ORDER BY trans_number DESC LIMIT 1";  
						$result = mysqli_query($con, $query);
						$row = mysqli_fetch_array($result);  
						$count = (int) str_replace('C', '', $row[0]);
						$number   = sprintf('%05s', $count+1);
						$tracing_query="
						INSERT INTO tracing 
						VALUES ('".$number."','".$_SESSION['userid']."','".$users['UserID']."','".$location."','".$users['time_of_visit']."');
						";
						$result4 = mysqli_query($con,$tracing_query);
					}
				}
			}
		}
	}*/
?>

<!DOCTYPE html>
<html dir="ltr" lang="us-en">
	<head>
		<title>Home | SafeZonePH</title>
		<link href="./images/icon.png" rel="icon" type="image/x-icon">
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" type="text/css" href="./styles/style.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script type="text/javascript" src="./scripts/javascript.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"> </script>		
	</head>
	<body  onload="generateQR()">
	    <!-- 
	    
	    <form action='' method='post'>
	    <div class="container">
	   	<h1> Hello, World! </h1>
	    <?php 
	        include('./php/connection.php');
	        echo "<p> hello </p>";
	    ?>
	    <input type='submit' name='Login' value='Login'/>
	    <input type='submit' name='Logout' value='Logout'/>
	    </form>
	    </div>
	    
	    -->
		<div id="myAccount" class="modal">
			<div class="container">
			    <span class="close" onclick="closeOverlay()">&times;</span>
				<h1> Edit User Account </h1>
				<form method="post" action="" id="accountForm"> 
					<?php
						include('./php/connection.php');
						$query = "SELECT * FROM `login` WHERE `UserName` = '".$_SESSION['username']."'";
						$result = mysqli_query($con, $query);
						$row = mysqli_fetch_array($result); ?>
					<label for="username"><b>Username</b></label>
					<input type="text" placeholder="Enter Username" name="username" id="username" pattern="[A-Za-z0-9_]{3,15}" title="At least four (4) alphanumeric characters and underscore only." 
						value="<?php echo $row['UserName']; ?>" 
						required />
					<label for="username"><b>Name</b></label>
					<input type="text" placeholder="Enter Full name" name="name" id="name" pattern="[A-Za-z.,' ]{3,40}" title="At least four (4) alphabetic characters only." onchange="capitalizeName('user')" 
						value="<?php echo($row['Name']); ?>" 
						required />
					<label for="birthdate"><b>Birthdate (mm/dd/yyyy)</b></label>
					<input type="date" placeholder="Enter birthdate" max="2012-01-01" name="birthdate" id="birthdate" 
						value="<?php echo $row['Birthdate']; ?>"
						required />
					<label for="gender"><b>Gender</b></label>
					<select name="gender" name="gender" id="gender" 
						required>
						<option value="Male" <?php if ($row['Gender'] == 'Male') echo "selected"; ?> >Male</option>
						<option value="Female" <?php if ($row['Gender'] == 'Female') echo "selected"; ?> >Female</option>
					</select>
					<label for="region"><b>Region</b></label>
					<select name="region" class="region" name="region" id="region" onchange="if (this.value == 'edit') selectRegion(); selectProvince(this);" required>
						<option value="edit" onselect="selectRegion()">Edit</option>
						<option value="<?php echo $row['Region']; ?>" selected><?php echo $row['Region']; ?></option></select>
					<label for="province"><b>Province</b></label>
					<select name="province" class="province" name="province" id="province" onchange="selectMunicipality(this)" required>
						<option value="<?php echo $row['Province']; ?>" selected><?php echo $row['Province']; ?></option></select>
					<label for="municipality"><b>Municipality</b></label>
					<select name="municipality" class="municipality" name="municipality" id="municipality" onchange="selectBarangay(this)" required>
						<option value="<?php echo $row['Municipality']; ?>" selected><?php echo $row['Municipality']; ?></option></select>
					<label for="barangay"><b>Barangay</b></label>
					<select name="barangay" class="barangay" name="barangay" id="barangay" required>
						<option value="<?php echo $row['Barangay']; ?>" selected><?php echo $row['Barangay']; ?></option></select>
					<label for="email"><b>E-mail Address</b></label>
					<input type="email" placeholder="Enter E-mail" name="email" id="email" 
						value=<?php echo $row['Email']; ?>
						required />
					<button type="submit" name="saveAccount"  id="buttons" class="buttons" >Save</button>
				</form>
				<button type="button" name="closeOverlay" id="buttons" class="buttons" onclick="closeOverlay()">Close</button>
			</div>
		</div>
		<div id="myHealth" class="modal">
			<div class="container">
			    <span class="close" onclick="closeOverlay()">&times;</span>
				<h1> Edit User Health </h1>
				<form method="post" action="" id="healthForm"> 
					<?php
						include('./php/connection.php');
						$query = "SELECT * FROM `login` WHERE `UserName` = '".$_SESSION['username']."'";
						$result = mysqli_query($con, $query);
						$row = mysqli_fetch_array($result); ?>
						
					<p>Are you vaccinated?</p>
					<input type="hidden" name="vacine" value="false" />
					<input type="checkbox" id="vacine" name="vacine" value="true" <?php if($row['Vaccinated'] == "true") echo "checked" ?>>
					<label for="vacine">I have been vaccinated</label>

					<p>Have you been diagnosed with Covid-19 recently?</p>
					<input type="hidden" name="covid" value="no" />
					<input type="checkbox" id="covid" name="covid" value="yes" <?php if($row['Covid_Positive'] == "yes") echo "checked" ?>>
					<label for="covid">I have been diagnosed with Covid-19</label>
					<br><br>
					<label for="comor"><b>Co-morbidities</b></label>
					<input type="text" name="comor" id="comor" value=
						<?php if(empty($row["Comorbidity"])) {
								echo "None"; 
							} else {
								echo $row["Comorbidity"];
							}
						?> />
					<button type="submit" name="saveHealth"  id="buttons" class="buttons" >Save</button>
				</form>
				<button type="button" name="closeOverlay" id="buttons" class="buttons" onclick="closeOverlay()">Close</button>
			</div>
		</div>
		
		<header>
			<nav>
				<div id='dropdown' class='dropdown'>
					<button type="button" class='menu_icon'><img src="images/menu.jpg" width="50px" height="50px"/></button>
					<div id="dropdown_content" class="dropdown_content">
						<a href="index.php"   target="">HOME</a></li>
						<a href="user.php"    target="" style="background-color: #ddd;	color: #760001; border-bottom: 3px solid #760001;">ACCOUNT</a>
						<a href="about.php"    target="">ABOUT</a>
					</div>
				</div>
				
				<ul>
					<li><a href="index.php"   target="" >HOME</a></li>
					<li><a href="account.php"    target="" style="background-color: #ddd;	color: #760001; border-bottom: 3px solid #760001;">ACCOUNT</a></li>
					<!--li><a href="./php/logout.php"    target="">LOGOUT</a></li-->
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
				<div class='head'>
					<!--
					<img class="icons" src="images/icons/profile.png"/><br>
					-->
					<!--
					<input type="text" id="qrdata">
					<button type="button" name="qr" id="qr"  class="buttons" onclick="generateQR()">QR</button>
					-->
					<div id="qrcode" class="qrcode" name="qrcode"></div>
					<h1><?php echo $_SESSION['name']; ?></h1> 
					<?php echo "<p style='color:red'>".$error."</p>"; ?>
					<!--covid warning if individual made contact with covid positive -->
					<div id="covidWarning" name="covidWarning" style=" color:red;">
					<?php echo $user_contact_message ?>
					</div>
				</div>
			<hr>
			<button type="button" name="personalInfo" id="personal_Info" class="buttons" onclick="openAccount()" ><i class="fa fa-fw fa-user"> </i>Account</button>
			<button type="button"name="healthStatus"  id="health_status" class="buttons" onclick="openHealth()" ><i class="fa fa-fw fa-heart"></i>Health</button>
			<!--hr>
			<button type="button" name="personalInfo" id="personal_Info"  class="buttons" onclick="showAccountInfo();closeHealthStat();">My Account</button>
			<button type="button" name="healthStatus"  id="health_status" class="buttons" onclick="showHealthStat();closeAccountInfo()">My Health Status</button-->
			<button type="submit" name="logout" class="buttons">Logout</button>
			</div>
			
			<!---My Account -->
			<div class="container" id="my_account" style="display: none;">
			<h1> My Account </h1>	
			<table border='1' align='center' class='tables'>
			    <tbody>
			        <?php
			        $SELECT = mysqli_query($con,$account_query);
			        if($SELECT != false){
			            while($rows = mysqli_fetch_array($SELECT)){
			            echo "
			                <label for=\"userid\"><b>User ID</b></label>
			                <input type=\"text\" name=\"userid\" id=\"userid\" value=\"".$rows["UserID"]."\" readonly/>
			            
			                <label for=\"update_username\"><b>Username</b></label>
			                <input type=\"text\" name=\"update_username\" id=\"update_username\" value=\"".$rows["UserName"]."\"/>
			                
			                <label for=\"update_name\"><b>Name</b></label>
			                <input type=\"text\" name=\"update_name\" id=\"update_name\" value=\"".$rows["Name"]."\"/>
			                
			                <label for=\"update_email\"><b>Email</b></label>
			                <input type=\"email\" name=\"update_email\" id=\"update_email\" value=\"".$rows["email"]."\"/>
						    
						";
						
					    }   	
				    }
				    else{
				    echo "

					";
				    }
			    ?>
	            </tbody>

	        </table>
			
			
			<button type="submit" name="editInfo" id="editInfo" class="buttons" >Edit Info</button>
			<button type="button" name="closeBtn" id="closeBtn" class="buttons" onclick="closeAccountInfo()">Close</button>
			</div>			
			
            <!-- My Health Status -->
			<div class="container" id="my_health_status" style="display: none;">
			<h1> Health Status </h1>
			<form action="" method="post">
				<?php
					$health_status_query="SELECT * FROM LOGIN WHERE UserID='".$_SESSION['userid']."'";;
			        $SELECT = mysqli_query($con,$account_query);
			        if($SELECT != false){
			            while($rows = mysqli_fetch_array($SELECT)){
			            
			            if (strcasecmp($rows['Vaccinated'], 'true')==0) {
			            	echo "
			            	<input type=\"hidden\" name=\"vacine\" value=\"false\" />
			            	<input type=\"checkbox\" id=\"vacine\" name=\"vacine\" value=\"true\" checked>
			            	<label for=\"vacine\">I have been vaccinated</label><br><br><br>";
			            }
			            else{
			            	echo "
			            	<input type=\"hidden\" name=\"vacine\" value=\"false\" />
			            	<input type=\"checkbox\" id=\"vacine\" name=\"vacine\" value=\"true\">
			            	<label for=\"vacine\">I have been vaccinated</label><br>";

			            }

			            if (strcasecmp($rows['Covid_Positive'], 'yes')==0) {
			            	echo "
			            	<input type=\"hidden\" name=\"covid\" value=\"no\" />
			            	<input type=\"checkbox\" id=\"covid\" name=\"covid\" value=\"yes\" checked>
			            	<label for=\"covid\">I have been diagnosed with Covid-19</label><br><br><br>";
			            }
			            else{
			            	echo "
			            	<input type=\"hidden\" name=\"covid\" value=\"no\" />
			            	<input type=\"checkbox\" id=\"covid\" name=\"covid\" value=\"yes\">
			            	<label for=\"covid\">I have been diagnosed with Covid-19</label><br><br><br>";
			            }
			            echo "
			            <label for=\"comor\"><b>Username</b></label>
			            <input type=\"text\" name=\"comor\" id=\"comor\" value=\"".$rows["Comorbidity"]."\"/>";

					    }   	
				    }
				    else{
				    }

			    ?>
				<button type="submit" name="editHealthStatus" id="editHealthStatus" class="buttons" >Edit Info</button>
			</form>
			<button type="button" name="closeBtn" id="closeBtn" class="buttons" onclick="closeHealthStat()">Close Table</button>
			</div>		

		<!-- SNS Share Plugins -->
		<div class='sns' id='sns'>
			<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fsafezoneph.000webhostapp.com%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore"><img src="images/icons/facebook.png" alt="Facebook" title="Facebook"></a>
			<a target="_blank" class="twitter-share-button" href="https://twitter.com/intent/tweet?text=Check%20out%20SafeZonePH.%20A%20web-based%20online%20contact%20tracing%20app.%20Link%20here:%20http://safezoneph.000webhostapp.com/" data-size="large"><img src="images/icons/twitter.png" alt="Twitter" title="Twitter"></a>
			<a href="https://www.instagr.am/leonelinon" target="_blank"><img src="images/icons/instagram.png" alt="Instagram" title="Instagram"></a>
			<a href="https://t.me/leo_leonel" target="_blank"><img src="images/icons/telegram.png" alt="Telegram" title="Telegram"></a>
		</div>
		<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v11.0" nonce="xhNd669X"></script>
		<link rel="canonical" href="/web/tweet-button">
		<link rel="me" href="https://twitter.com/twitterdev">
		
		<!--
		<br><br>
        <label>encrypted</label>
        <div id="demo1"></div>
        <br>

        <label>decrypted</label>
        <div id="demo2"></div>

        <br>
        <label>Actual Message</label>
        <div id="demo3"></div>
        -->
		


	    
	</body>
	

	<!-- Scripts for QR -->
	
	<script type="text/javascript" src="./scripts/qrcode.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js" integrity="sha256-/H4YS+7aYb9kJ5OKhFYPUjSJdrtV6AeyJOtTkw6X72o=" crossorigin="anonymous"></script>
	
	<script>
    
    //specifies where to put QR
	var qrcode = new QRCode(document.getElementById("qrcode"));
	
	//get userid and username
	var raw_userid =document.getElementById("userid");
	var raw_username =document.getElementById("update_username");
	
	function generateQR(){
	    
	    //get userid and username literal values
	    var userid_value = raw_userid.value;
	    var username_value = raw_username.value;
	    
	    //encrypt 
	    var encrypted_userid = CryptoJS.AES.encrypt(userid_value, "9240363273");
	    var encrypted_username = CryptoJS.AES.encrypt(username_value, "9240363273");
	    
	    //formatting of string to be written in QR code
	    const write2qr="###SafeZonePh###"+encrypted_userid+"###"+encrypted_username+"###";
	    
	    //write the qr code
	    qrcode.makeCode(write2qr);
	    //alert(write2qr);
	    
	    //encryption checking
        //var decrypted_userid = CryptoJS.AES.decrypt(encrypted_userid, "9240363273");
        //document.getElementById("demo1").innerHTML = encrypted_userid;
        //document.getElementById("demo2").innerHTML = decrypted_userid;
        //document.getElementById("demo3").innerHTML = decrypted_userid.toString(CryptoJS.enc.Utf8);
	    
	    
	}
	</script>
	<script>
		var modalAccount = document.getElementById("myAccount");
		var modalHealth  = document.getElementById("myHealth");

		function openAccount() {
			modalAccount.style.display = "block";
		}
		
		function openHealth() {
			modalHealth.style.display = "block";
		}

		function closeOverlay() {
			modalAccount.style.display = "none";
			modalHealth.style.display = "none";
			document.getElementById("accountForm").reset();
			document.getElementById("healthForm").reset();
			//location.reload();
		}
		
		window.onclick = function(event) {
			if (event.target == modalAccount || event.target == modalHealth) {
				modalAccount.style.display = "none";
				modalHealth.style.display = "none";
				document.getElementById("accountForm").reset();
				document.getElementById("healthForm").reset();
				//location.reload();
			}
		}
		
		const url = './json/philippine_provinces_cities_municipalities_and_barangays_2019v2.json';
		var regionChoice;
		var provinceChoice;
		var municipalityChoice;
		var barangayChoice;
	
		let dropdownRegion = $('#region');
		let dropdownProvince = $('#province');
		let dropdownMunicipality = $('#municipality');
		let dropdownBarangay = $('#barangay');
		
		function selectRegion() {
			dropdownRegion.empty();
			dropdownRegion.append('<option value="" selected disabled hidden>Choose Region</option>');
			dropdownRegion.prop('selectedIndex', 0);
			$.getJSON(url, function (data) {
				var reg_keys = Object.keys(data).sort();
				for (let i = 0; i < reg_keys.length; i++){
					dropdownRegion.append($('<option></option>').attr('value', reg_keys[i]).text(data[reg_keys[i]].region_name));
				}
			});
		}
		
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
</html>