<?php 
	session_start();
	
	
	//----TEST
	//if (isset($_POST['Login'])) {
	//   header("location: ./login_location.php");
	//}
	//----TEST
	
	
	$error = '';
	if (!isset($_SESSION['username']) && !isset($_SESSION['psw']))
	{
		header("location: login.php");
	}
	else{
	    include('./php/connection.php');
	    $account_query="SELECT * FROM loc_login WHERE loc_ID='".$_SESSION['userid']."'";

	    
	}
	
	
	if (isset($_POST['logout'])) {
		session_destroy();
	    echo "<meta http-equiv='refresh' content='0'>";
		header("location: ./login.php");
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
		<script type="text/javascript" src="./scripts/javascript.js"></script>
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
	    
	    <!-- USER DASH -->
	    <br><br>
		<form action="" method="post">
			<div class="container">
				<div class='head'>
					<img class="icons" src="images/icons/profile.png"/>
					<h1>Login Successful!</h1>
					<p>Hello, <?php echo $_SESSION['name']; ?>.</p> 
					<?php echo "<p style='color:red'>".$error."</p>"; ?>
				</div>
			<hr>
			<button type="button" name="personalInfo" id="personal_Info"  class="buttons" onclick="showAccountInfo();closeHealthStat();">Account Info</button>
			<button type="button" name="healthStatus"  id="health_status" class="buttons" onclick="showHealthStat();closeAccountInfo()">Visitor List</button>
			
			<button name="scanner" id="scanner" class="buttons" onclick="">QR Scanner</button>
			<a href="scanner.php">Scanner</a>

			<button type="submit" name="logout" class="buttons">Logout</button>
			<button type="submit" name="save"   class="buttons">Save to JSON</button>
			<button type="submit" name="load"   class="buttons">Load from JSON</button>
			<button type="button" name="openBtn" id="openBtn" class="buttons" onclick="showTable()">Open Table (from JSON)</button>
			<button type="button" name="openBtn" id="openBtn" class="buttons" onclick="showTableSQL()">Open Table (from SQL)</button>
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
			                <tr>
			                    <td>Location ID</td>
			                    <td style = 'color:blue;' >".$rows["loc_ID"]."</td>
						    </tr>
						    
						    <tr>
			                    <td>username</td>
			                    <td>".$rows["username"]."</td>
						    </tr>
						    
						    <tr>
			                    <td>name</td>
			                    <td>".$rows["name"]."</td>
						    </tr>
						    
						    <tr>
			                    <td>email</td>
			                    <td>".$rows["email"]."</td>
						    </tr>
						    
						    <tr>
			                    <td>contact number</td>
			                    <td>".$rows["contact_num"]."</td>
						    </tr>
						    
						    <tr>
			                    <td>street address</td>
			                    <td>".$rows["street_add"]."</td>
						    </tr>
						    
						    <tr>
			                    <td>city address</td>
			                    <td>".$rows["city_add"]."</td>
						    </tr>
						    
						    	
			                    
						        
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
			
			
			<button type="button" name="editInfo" id="editInfo" class="buttons" >Edit Info</button>
			<button type="button" name="closeBtn" id="closeBtn" class="buttons" onclick="closeAccountInfo()">Close</button>
			</div>			
			
			
			
			
            <!-- Visitor List -->
			<div class="container" id="visitor_list" style="display: none;">
			<h1> Health Stat </h1>
			<table>
			<thead>
				<th>UserID</th>
				<th>Username</th>
				<th>Email</th>
				<th>Password</th>
				<th>Name</th>
			</thead>
			<tbody>
				<?php
					$filename = "./json/users.json";
					$data = file_get_contents($filename);
					$array = json_decode($data, true);
					if(count($array) != 0){
						foreach ($array as $row){
							echo "<tr>"; 
								echo "<td>".$row['UserID']."</td>";
								echo "<td>".$row['UserName']."</td>";
								echo "<td>".$row['email']."</td>";
								echo "<td>".$row['Password']."</td>"; 
								echo "<td>".$row['Name']."</td>";
							echo "</tr>";
						}
						echo "<td colspan='5'>****Nothing Follows****</td>";
					} else {
						echo "<tr>"; 
							echo "<td colspan='5'>No Users Recorded.</td>";
						echo "</tr>";
					}
					?>
			</tbody>
			</table>
			<button type="button" name="closeBtn" id="closeBtn" class="buttons" onclick="closeHealthStat()">Close Table</button>
			</div>		
			
			
			
			
            <!-- TABLE FOR JSON -->
			<div class="container" id="userTable" style="display: none;">
			<h1> Loaded from JSON </h1>
			<table>
			<thead>
				<th>UserID</th>
				<th>Username</th>
				<th>Email</th>
				<th>Password</th>
				<th>Name</th>
			</thead>
			<tbody>
				<?php
					$filename = "./json/users.json";
					$data = file_get_contents($filename);
					$array = json_decode($data, true);
					if(count($array) != 0){
						foreach ($array as $row){
							echo "<tr>"; 
								echo "<td>".$row['UserID']."</td>";
								echo "<td>".$row['UserName']."</td>";
								echo "<td>".$row['email']."</td>";
								echo "<td>".$row['Password']."</td>"; 
								echo "<td>".$row['Name']."</td>";
							echo "</tr>";
						}
						echo "<td colspan='5'>****Nothing Follows****</td>";
					} else {
						echo "<tr>"; 
							echo "<td colspan='5'>No Users Recorded.</td>";
						echo "</tr>";
					}
					?>
			</tbody>
			</table>
			<button type="button" name="closeBtn" id="closeBtn" class="buttons" onclick="closeTable()">Close Table</button>
			</div>
			

			
			
            <!-- TABLE FOR SQL -->
			<div class="container" id="userTableSQL" style="display: none;">
			<h1> Loaded from SQL Database </h1>
			<table>
			<thead>
				<th>UserID</th>
				<th>Username</th>
				<th>Email</th>
				<th>Password</th>
				<th>Name</th>
			</thead>
			<tbody>
				<?php
					include('./php/connection.php');
					$query = "SELECT * FROM LOGIN ORDER BY UserID";  
					$result = mysqli_query($con, $query);
					
					if($result == false){
					    echo "query failed";
					}
					else{
					}
					
					$count = mysqli_num_rows($result); 
					if ($count != 0){
						$c = 1;
						while($count > 0){
							$row = mysqli_fetch_row($result);
							echo "<tr>"; 
							echo "<td>$row[0]</td>";
							echo "<td>$row[1]</td>";
							echo "<td>$row[2]</td>"; 
							echo "<td>$row[3]</td>"; 
							echo "<td>$row[4]</td>";
							echo "</tr>";
							$count = $count - 1;
							$c = $c + 1;
						}
						echo "<td colspan='5'>****Nothing Follows****</td>";
					}else{
						echo "<tr>"; 
							echo "<td colspan='5'>No Users Recorded.</td>";
						echo "</tr>";
					}
					mysqli_close($con);
				?>
			</tbody>
			</table>
			<button type="button" name="closeBtn" id="closeBtn" class="buttons" onclick="closeTableSQL()">Close Table</button>
			</div>
		</form>
		<br><br>
		
		
		
		
        <!-- LEFT BLOCK LINKS -->
		<div class='sns' id='sns'>
			<a href="https://www.fb.me/leonelinon" target="_blank"><img src="images/icons/facebook.png" alt="Facebook" title="Facebook"></a>
			<a href="https://www.twitter.com/god" target="_blank"><img src="images/icons/twitter.png" alt="Twitter" title="Twitter"></a>
			<a href="https://www.instagr.am/leonelinon" target="_blank"><img src="images/icons/instagram.png" alt="Instagram" title="Instagram"></a>
			<a href="https://t.me/leo_leonel" target="_blank"><img src="images/icons/telegram.png" alt="Telegram" title="Telegram"></a>
		</div>
	    
	</body>
</html>