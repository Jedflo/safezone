<?php 
	session_start();
	$error = '';
	
	//----TEST
	/*if (isset($_POST['Login'])) {
	    header("location: ./login_location.php");
	}
	//----TEST
	*/

	if (empty($_SESSION['username']) && empty($_SESSION['loc_username'])){
		header("location: ./login.php");
	} else if (isset($_SESSION['username'])) {
	    header("location: ./user.php");
	} else if (isset($_SESSION['loc_username'])){
		include('./php/connection.php');
	    $account_query="SELECT * FROM loc_login WHERE loc_ID = '".$_SESSION['loc_userid']."'";
	}
	
	if (isset($_POST['logout'])) {
		session_destroy();
		echo "<script>window.location.href='login.php'</script>";
	}

	if(isset($_POST['scanner'])){
		echo "<script>window.location.href='scanner.php'</script>";
	}
	
	if(isset($_POST['saveAccount'])){
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
		
		$query = "UPDATE `loc_login` SET `Username` = '$username', `Name` = '$name', `Street_Add` = '$street',
									`Region` = '$region', `Province` = '$province', `Municipality` = '$municipality', `Barangay` = '$barangay', 
									`Email` = '$email', `Contact_Num` = '$contnum'
					WHERE `Loc_ID` = '".$_SESSION['loc_userid']."' AND `UserName` = '".$_SESSION['loc_username']."';";
		if (mysqli_query($con, $query)) {
			mysqli_close($con);
			$_SESSION['loc_username'] = $username;
			$_SESSION['loc_name']     = $name;
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
	
	if (isset($_POST['editInfo'])){
	    	    
	    include('./php/connection.php');  
	    $upd_username=$_POST['update_username'];
	    $upd_name=$_POST['update_name'];
	    $upd_email=$_POST['update_email'];
	    $upd_contactNum=$_POST['update_contactNum'];
	    $upd_streetAdd=$_POST['update_streetAdd'];
	    $upd_cityAdd=$_POST['update_cityAdd'];

	    $upd_query = "
	    UPDATE loc_login 
	    SET 
	    username='".$upd_username."', 
	    name='".$upd_name."', 
	    email='".$upd_email."',
	    contact_num='".$upd_contactNum."',
	    street_add='".$upd_streetAdd."',
	    city_add='".$upd_cityAdd."' 
	    WHERE loc_ID='".$_SESSION['loc_userid']."';";
		$result=mysqli_query($con, $upd_query);
	}

	if (isset($_POST['contactTracing'])) {
		header("location: ./contact_tracing.php");
	}

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
	<body>
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
				<h1> Edit Establishment Account </h1>
				<form method="post" action="" id="accountForm"> 
					<?php
						include('./php/connection.php');
						$query = "SELECT * FROM `loc_login` WHERE `Username` = '".$_SESSION['loc_username']."'";
						$result = mysqli_query($con, $query);
						$row = mysqli_fetch_array($result); ?>
					<label for="loc_username"><b>Username</b></label>
					<input type="text" placeholder="Enter Username" name="loc_username" id="loc_username" pattern="[A-Za-z0-9_]{3,15}" title="At least four (4) alphanumeric characters and underscore only."
						value="<?php echo $row['Username']; ?>" 
						required />
					<label for="loc_name"><b>Name</b></label>
					<input type="text" placeholder="Enter Location name" name="loc_name" id="loc_name" pattern="[A-Za-z.,' ]{3,20}" title="At least four (4) alphabetic characters only." onchange="capitalizeName('loc')" 
						value="<?php echo $row['Name']; ?>" 
						required />
					<label for="loc_email"><b>E-mail Address:</b></label>
					<input type="email" placeholder="Enter E-mail" name="loc_email" id="loc_email" 
						value="<?php echo $row['Email']; ?>" 
						required />
					<label for="loc_number"><b>Contact Number</b></label>
					<input type="number" placeholder="Enter Contact Number" name="loc_number" id="loc_number" 
						value="<?php echo $row['Contact_Num']; ?>" 
						required />
					<label for="loc_street_address"><b>Street Address</b></label>
					<input type="text" placeholder="Enter Street Address" name="loc_street_address" id="loc_street_address" 
						value="<?php echo $row['Street_Add']; ?>" 
						required />
									
					<label for="loc_region"><b>Region</b></label>
					<select name="loc_region" class="loc_region" name="loc_region" id="loc_region" onchange="if (this.value == 'edit') selectRegion(); loc_selectProvince(this)" required>
						<option value="edit" onselect="selectRegion()" >Edit</option>
						<option value="<?php echo $row['Region']; ?>" selected><?php echo $row['Region']; ?></option></select>
					<label for="loc_province"><b>Province</b></label>
					<select name="loc_province" class="loc_province" name="loc_province" id="loc_province" onchange="loc_selectMunicipality(this)" required>
						<option value="<?php echo $row['Province']; ?>" selected><?php echo $row['Province']; ?></option></select>
					<label for="loc_municipality"><b>Municipality</b></label>
					<select name="loc_municipality" class="loc_municipality" name="loc_municipality" id="loc_municipality" onchange="loc_selectBarangay(this)" required>
						<option value="<?php echo $row['Municipality']; ?>" selected><?php echo $row['Municipality']; ?></option></select>
					<label for="loc_barangay"><b>Barangay</b></label>
					<select name="loc_barangay" class="loc_barangay" name="loc_barangay" id="loc_barangay" required>
						<option value="<?php echo $row['Barangay']; ?>" selected><?php echo $row['Barangay']; ?></option></select>
					<button type="submit" name="saveAccount"  id="buttons" class="buttons" >Save</button>
				</form>
				<button type="button" name="closeOverlay" id="buttons" class="buttons" onclick="closeOverlay()">Close</button>
			</div>
		</div>
		
		<div id="myVisitor" class="modal">
			<div class="container">
			    <span class="close" onclick="closeOverlay()">&times;</span>
				<h1> List of Visitors of Establishment</h1>
				<table>
				<thead>
					<th>user ID</th>
					<th>time of visit</th>
				</thead>
				<tbody>
					<?php
						include('./php/connection.php'); 
						$visitorListQuery = "SELECT * FROM visit_list WHERE loc_ID = '".$_SESSION['loc_userid']."'";
						$visitorListQuery_result = mysqli_query($con, $visitorListQuery);
						while($visitor = mysqli_fetch_assoc($visitorListQuery_result)){
							echo "
							<tr>
								<td>".$visitor['UserID']."</td>
								<td>".$visitor['time_of_visit']."</td>
							</tr>
							";
						}
						?>
				</tbody>
				</table>
				<button type="button" name="closeOverlay" id="buttons" class="buttons" onclick="closeOverlay()">Close</button>
			</div>
		</div>
		
		
	    <header>
			<nav>
				<div id='dropdown' class='dropdown'>
					<button type="button" class='menu_icon'><img src="images/menu.jpg" width="50px" height="50px"/></button>
					<div id="dropdown_content" class="dropdown_content">
						<a href="index.html"   target="" style="background-color: #ddd;	color: #760001; border-bottom: 3px solid #760001;">HOME</a></li>						
						<?php if (isset($_SESSION['username']) && isset($_SESSION['psw'])) { ?>
							<a href="user.php"    target="">USER</a>
						<?php } else if (isset($_POST['loc_username']) && isset($_POST['loc_psw']) && isset($_POST['loc_psw-repeat'])) { ?>
							<a href="location.php"    target="">LOCATION</a>
						<?php } else { ?>
							<a href="login.php"    target="">LOGIN</a>
						<?php } ?>
						
						<a href="about.php"    target="">ABOUT</a>
					</div>
				</div>
				
				<ul>
					<li><a href="index.html"   target="" style="background-color: #ddd;	color: #760001; border-bottom: 3px solid #760001;">HOME</a></li>
					<?php if (isset($_SESSION['username']) && isset($_SESSION['psw'])) { ?>
							<a href="user.php"    target="">USER</a>
					<?php } else if (isset($_POST['loc_username']) && isset($_POST['loc_psw']) && isset($_POST['loc_psw-repeat'])) { ?>
						<a href="location.php"    target="">LOCATION</a>
					<?php } else { ?>
						<a href="login.php"    target="">LOGIN</a>
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
		<form action="" method="post">
			<div class="container">
				<div class='head'>
					<img class="icons" src="images/icons/profile.png"/>
					<h1><?php echo $_SESSION['loc_name']; ?></h1> 
				</div>
			<hr>

			<button type="button" name="personalInfo" id="personal_Info" class="buttons" onclick="openAccount()" ><i class="fa fa-fw fa-user"></i>Account</button>
			<button type="button" name="healthStatus" id="health_status" class="buttons" onclick="openVisitor()" ><i class="fa fa-fw fa-list"></i>Visitor List</button>
			
			<!--button type="button" name="personalInfo" id="personal_Info"  class="buttons" onclick="showAccountInfo();closeHealthStat();">Account Info</button-->
			<!--button type="button" name="healthStatus"  id="health_status" class="buttons" onclick="showHealthStat();closeAccountInfo()"><i class="fa fa-fw fa-list"></i>Visitor List</button-->
			
			<button type="submit" name="scanner" id="scanner" class="buttons">QR Scanner</button>
			<button type="submit" name="contactTracing" id="contactTracing" class="buttons">Contact Tracing</button>
			<button type="submit" name="logout" class="buttons">Logout</button>
			</div>

			<!---My Account --->
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
			                <input type=\"text\" name=\"userid\" id=\"userid\" value=\"".$rows["Loc_ID"]."\" readonly/>
			            
			                <label for=\"update_username\"><b>Username</b></label>
			                <input type=\"text\" name=\"update_username\" id=\"update_username\" value=\"".$rows["Username"]."\"/>
			                
			                <label for=\"update_name\"><b>Name</b></label>
			                <input type=\"text\" name=\"update_name\" id=\"update_name\" value=\"".$rows["Name"]."\"/>
			                
			                <label for=\"update_email\"><b>Email</b></label>
			                <input type=\"email\" name=\"update_email\" id=\"update_email\" value=\"".$rows["Email"]."\"/>

			                <label for=\"contactNum\"><b>Contact Number</b></label>
			                <input type=\"number\" name=\"update_contactNum\" id=\"contactNum\" value=\"".$rows["Contact_Num"]."\"/>

			                <label for=\"streetAdd\"><b>Street Address</b></label>
			                <input type=\"text\" name=\"update_streetAdd\" id=\"streetAdd\" value=\"".$rows["Street_Add"]."\"/>

			                <label for=\"cityAdd\"><b>City Addresss</b></label>
			                <input type=\"text\" name=\"update_cityAdd\" id=\"cityAdd\" value=\"".$rows["City_Add"]."\"/>
						";
					    }   	
				    }
				    else{
				    echo "
				        <tr>
					        <td colspan='3'>Something went wrong with the query</td>
					    </tr>
					";
				    }
			    ?>
	            </tbody>

	        </table>

			<button type="submit" name="editInfo" id="editInfo" class="buttons" >Edit Info</button>
			<button type="button" name="closeBtn" id="closeBtn" class="buttons" onclick="closeAccountInfo()">Close</button>
			</div>			

			
            <!-- Visitor List -->
			<div class="container" id="my_health_status" style="display: none;">
			<h1> Health Stat </h1>
			<table>
			<thead>
				<th>user ID</th>
				<th>time of visit</th>
			</thead>
			<tbody>
				<?php

					include('./php/connection.php'); 
					$visitorListQuery="SELECT * FROM visit_list WHERE loc_ID = '".$_SESSION['loc_userid']."'";
					$visitorListQuery_result=mysqli_query($con,$visitorListQuery);
					while($visitor = mysqli_fetch_assoc($visitorListQuery_result)){
						echo "
						<tr>
							<td>".$visitor['UserID']."</td>
							<td>".$visitor['time_of_visit']."</td>
						</tr>

						";
					}
					?>
			</tbody>
			</table>
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
	</body>
	<script>
		var modalAccount = document.getElementById("myAccount");
		var modalVisitor = document.getElementById("myVisitor");

		function openAccount() {
			modalAccount.style.display = "block";
		}
		
		function openVisitor(){
			modalVisitor.style.display = "block";
		}
		
		function closeOverlay() {
			modalAccount.style.display = "none";
			modalVisitor.style.display = "none";
			document.getElementById("accountForm").reset();
			document.getElementById("visitorForm").reset();
			//location.reload();
		}
		
		window.onclick = function(event) {
			if (event.target == modalAccount || event.target == modalVisitor) {
				modalAccount.style.display = "none";
				modalVisitor.style.display = "none";
				document.getElementById("accountForm").reset();
				document.getElementById("visitorForm").reset();
				//location.reload();
			}
		}
		
		const url = './json/philippine_provinces_cities_municipalities_and_barangays_2019v2.json';
		var loc_regionChoice;
		var loc_provinceChoice;
		var loc_municipalityChoice;
		var loc_barangayChoice;
	
		let loc_dropdownRegion = $('#loc_region');
		let loc_dropdownProvince = $('#loc_province');
		let loc_dropdownMunicipality = $('#loc_municipality');
		let loc_dropdownBarangay = $('#loc_barangay');
		
		function selectRegion() {
			loc_dropdownRegion.empty();
			loc_dropdownRegion.append('<option value="" selected disabled hidden>Choose Region</option>');
			loc_dropdownRegion.prop('selectedIndex', 0);
					
			$.getJSON(url, function (data) {
				var reg_keys = Object.keys(data).sort();
				for (let i = 0; i < reg_keys.length; i++){
					loc_dropdownRegion.append($('<option></option>').attr('value', reg_keys[i]).text(data[reg_keys[i]].region_name));
				}
			});
		}
		
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
	</script>
	
	
</html>