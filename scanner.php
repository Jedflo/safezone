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
	    include('./php/connection.php');
	    $account_query="SELECT * FROM loc_login WHERE loc_ID='".$_SESSION['loc_userid']."'";
	}

	if (isset($_POST['insert'])){

	    include('./php/connection.php');
	    	
	    $loc_ID=$_SESSION['loc_userid'];
	    $username=$_POST['username'];
	    $date=$_POST['date'];
	    $time=$_POST['time'];
	    
	    foreach($_POST['userid'] as $key => $value) {
	    	//echo $key, ' => ', $loc_ID, '|', $value, '|', $username[$key], '|', $date[$key], '|', $time[$key], '|', '<br />';
	    	
	    	$query="INSERT INTO visit_list (`loc_ID`, `UserID`, `date_of_visit`, `time_of_visit`) VALUES('".$loc_ID."','".$value."','".$date[$key]."', NOW());";
	    	

	    	if(mysqli_query($con, $query)){
	    		echo "Added to database successfully<br/>";
	    	}
	    	else{
	    		echo "<p> There was an error when creating the subject </p><p>". mysql_error()."</p>" ;
	    	}
	    	

	    }
	    
	    
	}


	    
?>

<!DOCTYPE html>
<html dir="ltr" lang="us-en">
	<head>
		<title>QR Scanner | SafeZonePH</title>
		<link href="./images/icon.png" rel="icon" type="image/x-icon">
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" type="text/css" href="./styles/style.css">
		<script src="./scripts/html5qrcode.min.js"></script>
	</head>
	
	<body>
	        <div id="qr-reader" class="container"></div>
            <div id="qr-reader-results" class="container">
                <a href="user_location.php">Back</a><br><br>
                
                <form action="" method="post">
                	<button type="submit" name="insert" id="insertBtn" class="buttons" >Insert to Visitor List</button>

                	<table id="scanned-qr">
                		<thead>
                			<th>User ID</th>
                			<th>Username</th>
                			<th>Date</th>
                			<th>Time</th>
                		</thead>
                		
                		<tbody>
                		
                		</tbody>
                </table>
            	</form>

            </div>

	    
	</body>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js" integrity="sha256-/H4YS+7aYb9kJ5OKhFYPUjSJdrtV6AeyJOtTkw6X72o=" crossorigin="anonymous"></script>
	<script>
	var resultContainer = document.getElementById('qr-reader-results');
	var scannedTable = document.getElementById('scanned-qr');
    var lastResult, countResults = 0;
    
    var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
	
	function splitqr(str) {
	    var res = str.slice(0, 16);
	    var userid = str.substring(16,60);
	    var username = str.substring(63,107);
	    const userinfo = [userid, username];
	    return userinfo;
	    
	}

	function getCurrentDate(){
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();
		today = yyyy + '-' + mm + '-' + dd;
		return today;
	}

	function getCurrentTime(){
		var d = new Date();
		var hh = d.getHours();
		var mm = d.getMinutes();
		d=hh+":"+mm;
		return(d);
	}
	
	function onScanSuccess(decodedText, decodedResult) {
	    if (decodedText !== lastResult) {
            ++countResults;
            lastResult = decodedText;
            console.log(`Scan result = ${decodedText}`, decodedResult);
            
            var userinfo = splitqr(decodedText);
            var decrypted_userid = CryptoJS.AES.decrypt(userinfo[0], "9240363273");
            var decrypted_username = CryptoJS.AES.decrypt(userinfo[1], "9240363273");
 
            //scannedTable.innerHTML += `[${countResults}] - ${decrypted_userid.toString(CryptoJS.enc.Utf8)}<br>`;
            //scannedTable.innerHTML += `<tr><td>`+decrypted_userid.toString(CryptoJS.enc.Utf8) + `</td><td>`+ decrypted_username.toString(CryptoJS.enc.Utf8)+`</td></tr>`;
            //scannedTable.innerHTML += `[${countResults}] - ${decrypted_username.toString(CryptoJS.enc.Utf8)}`;
            scannedTable.innerHTML +=`
            <tr>
            <td>
            <input type=\"text\" name=\"userid[]\" value=\"`+decrypted_userid.toString(CryptoJS.enc.Utf8) +`\" class="scannedInput" readonly/>
            </td>
            <td>
            <input type=\"text\" name=\"username[]\" value=\"`+decrypted_username.toString(CryptoJS.enc.Utf8) +`\" class="scannedInput" readonly/>
            </td>
            <td>
            <input type=\"text\" name=\"date[]\" value=\"`+getCurrentDate()+`\" class="scannedInput" readonly/>
            </td>
            <td>
            <input type=\"text\" name=\"time[]\" value=\"`+getCurrentTime()+`\" class="scannedInput" readonly/>
            </td>
            </tr>`;  
            
            // Optional: To close the QR code scannign after the result is found
            //html5QrcodeScanner.clear();
        }
	}
	
	function onScanError(qrCodeError) {
        // This callback would be called in case of qr code scan error or setup error.
        // You can avoid this callback completely, as it can be very verbose in nature.
    }
	
	
	html5QrcodeScanner.render(onScanSuccess);


	
	
	</script>
	
</html>