<?php 
	session_start();
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
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	</head>
	<body >
		<form action="?" method="POST">
		<div class="g-recaptcha" data-sitekey="6Lcs-5QbAAAAADcrv86eJrxp_BWnN-UfOTldFSJC"></div>
		<br/>
		<input type="submit" value="Submit">
		</form>
	</body>
	<script type="text/javascript">
      var onloadCallback = function() {
        alert("grecaptcha is ready!");
      };
    </script>
</html>