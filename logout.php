<?php 

session_destroy();
echo "<meta http-equiv='refresh' content='0'>";
header("location: ./register.php");

?>