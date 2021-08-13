<?php 
	session_start();
	
	
	//----TEST
	//if (isset($_POST['Login'])) {
	//    header("location: ./login_location.php");
	//}
	//----TEST
	
	

	$error = '';
	if (!isset($_SESSION['loc_username']) && !isset($_SESSION['loc_psw']))
	{
		header("location: login.php");
	}
	else{
	    
	}




	    
?>

<!DOCTYPE html>
<html dir="ltr" lang="us-en">
	<head>
		<title>Contact Tracing | SafeZonePH</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="./styles/style.css">
		
	</head>
	
	<body>
	        
            <div id="main" class="container">
                <a href="location.php">Back</a><br><br>
                
            </div>

            <div class="container">
            	<table style="width: 50%;">
	            	<?php
	            	include('./php/connection.php');
				    $query_all_tracing=
				    "
				    SELECT *
				    FROM tracing 
				    WHERE transmission_location = '".$_SESSION['loc_userid']."'
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

	            	?>
            	</table>
            </div>



	    
	</body>


	
</html>