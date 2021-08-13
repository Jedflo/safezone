<?php
	include('connection.php');

    $date_query="
    SELECT * 
    FROM login 
    WHERE contactWhen IS NOT NULL; ";

    $date_query_result=mysqli_query($con,$date_query);

    while($rows = mysqli_fetch_assoc($date_query_result)){
        $alertDate=$rows['contactWhen'];
        $alertDate_unix=strtotime($alertDate);
        $alertExpiryDate_unix=strtotime('+12 days',$alertDate_unix);
        $today_unix=time();
        $today=date("Y-m-d H:i:s",$today_unix);

        $alertExpiryDate_Timestamp=date("Y-m-d H:i:s",$alertExpiryDate_unix);

        if ($alertExpiryDate_Timestamp<=$today) {//alert has expired
            
            $clear_alert_query="
            UPDATE login 
            SET 
            hadContact = NULL, 
            contactWhere = NULL,
            contactWhen = NULL
            WHERE UserID = '".$rows['UserID']."'

            ";

            mysqli_query($con,$clear_alert_query);

        }
        else{//alert not expired yet
            
            
        }

    }


?>