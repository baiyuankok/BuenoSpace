<?php

$userID = isset($_SESSION['userID']) ? trim($_SESSION['userID']) : '';


        // Define variables and initialize with empty values
        $customer_name = $customer_email = $customer_contact = $updatedMsg= "";
        $customer_name_err = $customer_email_err = $customer_contact_err ="";

        //read the profile information
        //get institute details from table institute as DEFAULT
       $sql = "SELECT * FROM customer where userID=$userID";
       //run the sql query
       $pdoQuery_run= $pdo->query($sql);
       //if query run
       if ($pdoQuery_run) {
        //fetch pdo object
        while ($row= $pdoQuery_run->fetch(PDO::FETCH_OBJ)) {
            
            ////found user record in institute table
           $customer_name= $row->customer_name;
           $customer_email= $row->customer_email;
           $customer_contact= $row->customer_contact;
       }}
       else{
            echo 'not found data';
       }

?>