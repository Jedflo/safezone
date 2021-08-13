<?php 
	session_start();
	$error = '';
	$tableName = '';
	$id = '';
	if (!isset($_SESSION['admin'])) {
		header("location: ./admin_login.php");
	}
	
	if (isset($_POST['logout'])) {
		session_destroy();
		echo "<script>window.location.href='admin_login.php'</script>";
	}
	
    if (isset($_POST['search'])) {
        $tableName = $_POST['search'];
        $id = $_POST['searchID'];
        unset($_POST['search']);
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
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script type="text/javascript" src="./scripts/javascript.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"> </script>	
	</head>
	<body >
	    <div id="userList" class="modal">
			<div class="container">
			    <span class="close" onclick="closeOverlay()">&times;</span>
			    <h1>Users</h1>
			    <form action="" method="post">
    			    <label for="username"><b>Username</b></label>
    				<input type="text" placeholder="Search by ID only..." name="searchID" id="searchID" pattern="[A-Za-z0-9_]{1,15}" title="At least four (4) alphanumeric characters and underscore only." required />
    				<button type="submit" name="search"  id="buttons" value="login" class="buttons" >Search</button>
				</form>
			    <table>
			        <thead>
			            <th>UserID</th>
			            <th>UserName</th>
			            <th>Name</th>
			        </thead>
			        <tbody>
			            <?php 
			                include('./php/connection.php');
			                if ($tableName == "login") {
			                    $query  = "SELECT * FROM login WHERE UserID = '".$id."'"; 
			                } else { 
			                    $query  = "SELECT * FROM login ORDER BY UserID"; 
			                }
			                $result = mysqli_query($con, $query);
			                while($row = mysqli_fetch_assoc($result)){
			                    echo "<tr>
			                            <td>".$row['UserID']."</td>
			                            <td>".$row['UserName']."</td>
			                            <td>".$row['Name']."</td>
			                          </tr>";
			                }
			            ?>
			        </tbody>
			    </table>
			</div>
		</div>
		
	    <div id="locationList" class="modal">
			<div class="container">
			    <span class="close" onclick="closeOverlay()">&times;</span>
			    <h1>Locations</h1>
			    <form action="" method="post">
    			    <label for="username"><b>Username</b></label>
    				<input type="text" placeholder="Search by ID only..." name="searchID" id="searchID" pattern="[A-Za-z0-9_]{1,15}" title="At least four (4) alphanumeric characters and underscore only." required />
    				<button type="submit" name="search"  id="buttons" value="loc_login" class="buttons" >Search</button>
				</form>
			    <table>
		            <thead>
			            <th>UserID</th>
			            <th>UserName</th>
			            <th>Name</th>
			        </thead>
			        <tbody>
			            <?php 
			                include('./php/connection.php');
			                if ($tableName == "loc_login") {
			                    $query  = "SELECT * FROM loc_login WHERE Loc_ID = '".$id."'"; 
			                } else { 
			                    $query  = "SELECT * FROM loc_login ORDER BY Loc_ID"; 
			                }
			                $result = mysqli_query($con, $query);
			                while($row = mysqli_fetch_assoc($result)){
			                    echo "<tr>
			                            <td>".$row['Loc_ID']."</td>
			                            <td>".$row['Username']."</td>
			                            <td>".$row['Name']."</td>
			                          </tr>";
			                }
			            ?>
			        </tbody>
			    </table>
			</div>
		</div>

	    <div id="visitList" class="modal">
			<div class="container">
			    <span class="close" onclick="closeOverlay()">&times;</span>
			    <h1>Visits</h1>
			    <form action="" method="post">
    			    <label for="username"><b>Username</b></label>
    				<input type="text" placeholder="Search by ID only..." name="searchID" id="searchID" pattern="[A-Za-z0-9_]{1,15}" title="At least four (4) alphanumeric characters and underscore only." required />
    				<button type="submit" name="search"  id="buttons" value="visit_list" class="buttons" >Search</button>
				</form>
			    <table>
		            <thead>
			            <th>Location ID</th>
			            <th>User ID</th>
			            <th>Time of Visit</th>
			        </thead>
			        <tbody>
			            <?php 
			                include('./php/connection.php');
			                if ($tableName == "visit_list") {
			                    $query  = "SELECT * FROM visit_list WHERE loc_ID = '".$id."'"; 
			                } else { 
			                    $query  = "SELECT * FROM visit_list ORDER BY loc_ID"; 
			                }
			                $result = mysqli_query($con, $query);
			                while($row = mysqli_fetch_assoc($result)){
			                    echo "<tr>
			                            <td>".$row['loc_ID']."</td>
			                            <td>".$row['UserID']."</td>
			                            <td>".$row['time_of_visit']."</td>
			                          </tr>";
			                }
			            ?>
			        </tbody>
			    </table>
			</div>
		</div>


		<!-- Contact Tracing -->

	    <div id="tracingList" class="modal">
			<div class="container">
			    <span class="close" onclick="closeOverlay()">&times;</span>
			    <h1>Contact Tracing</h1>
			    <form action="" method="post">
    			    <label for="username"><b>Username</b></label>
    				<input type="text" placeholder="Search by ID only..." name="searchID" id="searchID" pattern="[A-Za-z0-9_]{1,15}" title="At least four (4) alphanumeric characters and underscore only." required />
    				<button type="submit" name="search"  id="buttons" value="tracing" class="buttons" >Search</button>
				</form>
			</div>


				<?php
				$location_query=mysqli_query($con,"SELECT * FROM loc_login");
				while($row=mysqli_fetch_assoc($location_query)){
					
					$skip_container=mysqli_query($con,"SELECT * FROM tracing WHERE transmission_location = '".$row['Loc_ID']."' ");

					if(mysqli_num_rows($skip_container)==0){

					}

					else{

						$location_origin_count_query=mysqli_query($con,"SELECT DISTINCT origin FROM tracing WHERE transmission_location = '".$row['Loc_ID']."'");
						$location_origin_count=mysqli_num_rows($location_origin_count_query);
						$location_possible_infected_count_query=mysqli_query($con,"SELECT * FROM tracing WHERE transmission_location = '".$row['Loc_ID']."'");
						$location_possible_infected_count=mysqli_num_rows($location_possible_infected_count_query);
						echo"
						<div class=\"container\">
						Location ID: ".$row['Loc_ID']."<br>
						Location Name: ".$row['Name']."<br>
						Total infection origins: ".$location_origin_count."<br>
						Total Possible Infected: ".$location_possible_infected_count." 
						";

						$query_all_tracing=
					    "
					    SELECT *
					    FROM tracing 
					    WHERE transmission_location = '".$row['Loc_ID']."'
					    ";

					    $origin=0;
					    $total_infected=0;
					    $total_covid_positive=0;
					    $tracing_result=mysqli_query($con,$query_all_tracing);

					    
					    while($row = mysqli_fetch_assoc($tracing_result)){

					    	if ($row['origin']!=$origin) {

					    		//$total_infected=0;
					    		if ($total_infected==0) {
					    			echo "</table><br><br><table>";
					    		}
					    		else{
					    			echo "
					    			<tr>
					    				<td>
					    					Total Number of possible infected individuals:  ".$total_infected."
					    				</td>
					    				<td>
					    					Total Number of Positive Cases: ".$total_covid_positive."
					    				</td>
					    			</tr>
					    			</table><br><br><table>
					    			";
					    			$total_infected=0;
					    			$total_covid_positive=0;
					    		}
					    		$origin=$row['origin'];
					    		$when=$row['transmission_date'];
					    		echo "
					    		<tr>
					    			<th colspan=\"2\">
					    				<b>ORIGIN User: </b>".$origin." <br>
					    				<b>Date of Transmission: </b>".$when." <br>
					    			 </th>
					    		</tr>
					    		<tr>
					    			<th>
					    			 Possible Infected Individuals
					    			</th> 
					    			<th>
					    			 Covid-19 Positve
					    			</th> 
					    		</tr>
					    		";
					    		$covid_query="
					    		SELECT * 
					    		FROM login 
					    		WHERE UserID = '".$row['transmitted_to']."'
					    		";
					    		$covid_query_result=mysqli_query($con,$covid_query);
					    		$user_covid_pos=mysqli_fetch_assoc($covid_query_result);
					    		echo "
					    		<tr>
					    			<td>
					    				".$row['transmitted_to']."
					    			</td>
					    			<td>
					    				".$user_covid_pos['Covid_Positive']."
					    			</td>
					    		</tr>";

					    		if (strcasecmp($user_covid_pos['Covid_Positive'], "yes")==0) {
					    			$total_covid_positive++;
					    		}

					    		$total_infected++;
					    	}//if end
					    	else{
					    		$covid_query="
					    		SELECT * 
					    		FROM login 
					    		WHERE UserID = '".$row['transmitted_to']."'
					    		";
					    		$covid_query_result=mysqli_query($con,$covid_query);
					    		$user_covid_pos=mysqli_fetch_assoc($covid_query_result);
					    		echo "
					    		<tr>
					    			<td>
					    				".$row['transmitted_to']."
					    			</td>
					    			<td>
					    				".$user_covid_pos['Covid_Positive']."
					    			</td>
					    		</tr>";
					    		if (strcasecmp($user_covid_pos['Covid_Positive'], "yes")==0) {
					    			$total_covid_positive++;
					    		}
					    		$total_infected++;
					    	}//else end
					    	
					    }//while end
					    echo "
					    <tr>
					    	<td>
					    		Total Number of possible infected individuals: ".$total_infected."
					    	<td>
					    		Total Number of Positive Cases: ".$total_covid_positive."
					    	</td>
					    </tr>
					    </table>

					    ";

						echo"</div>";
					}

				}//biggest while end


				?>

		</div>
		
	    <!-- ADMIN DASH -->
		<div class="container">   
		<h1> Admin Page </h1>
		<h4> SafeZonePH Administrator Page </h4>
		</div>
		<div class="container">
		    <h2>Number of Users:
		    <?php include('./php/connection.php');
		        $query  = "SELECT * FROM login";
		        $result = mysqli_query($con, $query);
		        $count  = mysqli_num_rows($result);
		        echo $count;
		        mysqli_close($con);
		    ?>
		    </h2>
		    <h2>Number of Locations:
		    <?php include('./php/connection.php');
		        $query  = "SELECT * FROM loc_login";
		        $result = mysqli_query($con, $query);
		        $count  = mysqli_num_rows($result);
		        echo $count;
		        mysqli_close($con);
		    ?>
		    </h2>
		</div>
		<div class="container">
	    <form method="post" action="">
		    <button type="button" name="userAccounts" id="userAccounts" class="buttons" onclick="openUsers()" ><i class="fa fa-fw fa-user"></i> User Accounts</button>
			<button type="button" name="locationAccounts" id="locationAccounts" class="buttons" onclick="openLocations()" ><i class="fa fa-fw fa-building"></i> Establishment Accounts</button>
			<button type="button" name="visitsList" id="visitsList" class="buttons" onclick="openVisits()" ><i class="fa fa-fw fa-users"></i> List of Visits</button>
			<button type="button" name="tracingList" id="tracingList" class="buttons" onclick="openTracing()" ><i class="fa fa-fw fa-list"></i> Contact Tracing</button>
			<button type="submit" name="logout" class="buttons">Logout</button>
	    </form>
		</div>
		<footer>
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
    	</footer>
	</body>
	<script>
		var modalUser = document.getElementById("userList");
		var modalLocation  = document.getElementById("locationList");
		var modalVisit  = document.getElementById("visitList");
		var modalTrace = document.getElementById("tracingList");
		const body = document.querySelector("body");

		function openUsers() {
			modalUser.style.display = "block";
			body.style.overflow = "hidden";
		}
		
		function openLocations() {
			modalLocation.style.display = "block";
			body.style.overflow = "hidden";
		}
		
		function openVisits() {
			modalVisit.style.display = "block";
			body.style.overflow = "hidden";
		}

		function openTracing() {
			modalTrace.style.display = "block";
			body.style.overflow = "hidden";
		}

		function closeOverlay() {
			modalUser.style.display = "none";
			modalLocation.style.display = "none";
			modalVisit.style.display = "none";
			modalTrace.style.display = "none";
			body.style.overflow = "auto";
			//location.reload();
		}
		
		window.onclick = function(event) {
			if (event.target == modalUser || 
			    event.target == modalLocation || 
			    event.target == modalVisit ||
			    event.target == modalTrace) 
			{
				modalUser.style.display = "none";
				modalLocation.style.display = "none";
				modalVisit.style.display = "none";
				modalTrace.style.display = "none";
				body.style.overflow = "auto";
				//location.reload();
			}
		}
	</script>
	
	<?php if($tableName == 'login') { ?>
	    <script> openUsers(); </script>
	<?php } else if($tableName == 'loc_login') { ?>
	    <script> openLocations();</script>
    <?php } else if($tableName == 'visit_list') { ?>
        <script>openVisits();</script>
    <?php } else if($tableName == 'tracing') { ?>
        <script>openTracing();</script>
	<?php } ?>
	
</html>