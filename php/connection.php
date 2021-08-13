<?php   
	$error = '';
    $host = "localhost";  
    $user = "root";  
    $password = '';  
    $db_name = "id17146855_safezoneph_db";  
      
    $con = mysqli_connect($host, $user, $password, $db_name);  
    if(mysqli_connect_errno()) {  
        die("Failed to connect with MySQL: ". mysqli_connect_error());  
    }  

?>  