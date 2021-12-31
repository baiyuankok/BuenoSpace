<?php

$ownerID = isset($_SESSION['ownerID']) ? trim($_SESSION['ownerID']) : '';


        // Define variables and initialize with empty values
        $owner_name = $owner_email = $owner_contact = $updatedMsg= "";
        $owner_name_err = $owner_email_err = $owner_contact_err ="";

        //read the profile information
        //get institute details from table institute as DEFAULT
       $sql = "SELECT * FROM owner where ownerID=$ownerID";
       //run the sql query
       $pdoQuery_run= $pdo->query($sql);
       //if query run
       if ($pdoQuery_run) {
        //fetch pdo object
        while ($row= $pdoQuery_run->fetch(PDO::FETCH_OBJ)) {
            
            ////found user record in institute table
           $owner_name= $row->owner_name;
           $owner_email= $row->owner_email;
           $owner_contact= $row->owner_contact;
       }}
       else{
            echo 'not found data';
       }

?>