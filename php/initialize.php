<?php
	$error = '';
    $host = "localhost";  
    $user = "id17146855_safezoneph";  
    $password = '(UPr6+&s)7TaR5^9';  
    $db_name = "id17146855_safezoneph_db";  
	
   // Check if database already exist
    $con = mysqli_connect($host, $user);
		if (mysqli_connect_error()) {
			die('Could not connect to database');
		}else {
		   $isDBExist = mysqli_select_db($con, $db_name);
		}
		mysqli_close($con);
    
    // create database if not exist
    if ($isDBExist == false) {
        $con = mysqli_connect($host, $user);
        if (mysqli_connect_error()) {
            die('Could not connect to database');
        }else {
            $query = 'CREATE DATABASE id17146855_safezoneph_db';
            mysqli_query($con, $query);   
        }
        mysqli_close($con);
    }

	// check if table Login already existing
    $con = mysqli_connect($host, $user, $password, $db_name);
        if (mysqli_connect_error()) {
            die('Could not connect to database');
        }else {
            $query = 'SELECT UserID from LOGIN';
            $isTableLoginExist = mysqli_query($con, $query);
        }
		mysqli_close($con);

    // create table Login if not existing
    if ($isTableLoginExist == false) {
        $con = mysqli_connect($host, $user, $password, $db_name);

        if (mysqli_connect_error()) {
            die('Could not connect to database');
        }else {
            $query = 'CREATE TABLE LOGIN(
			UserID VARCHAR(10),
			UserName VARCHAR(20) PRIMARY KEY,
			Password VARCHAR(20),
			Name VARCHAR(30))';
			mysqli_query($con, $query);
        }
        mysqli_close($con);
    }
?>