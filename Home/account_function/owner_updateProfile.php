<?php



if(isset(($_POST['edit_owner_profile']))){

    if(empty(trim($_POST["owner_name"]))){
        $owner_name_err = "Please enter a valid name.";
    }

    if(empty(trim($_POST["owner_email"]))){
        $owner_email_err = "Please enter a valid email address.";
    }

    if(empty(trim($_POST["owner_contact"]))){
        $owner_contact_err = "Please enter a valid contact number.";
    }

    $owner_name=$_POST["owner_name"];
    $owner_contact=$_POST["owner_contact"];
    $owner_email=$_POST["owner_email"];

    if(empty($owner_email_err) && empty($owner_contact_err) && empty($owner_name_err)){

        //update to particular user row
        $pdoQuery="UPDATE owner SET owner_email=:owner_email, owner_contact=:owner_contact, owner_name=:owner_name WHERE ownerID=$ownerID";
        //run the query
        //prepare the query
        $pdoQuery_run=$pdo->prepare($pdoQuery);
        //execute the query
        $pdoQuery_exec= $pdoQuery_run->execute(array(":owner_email"=>$owner_email,":owner_contact"=>$owner_contact,":owner_name"=>$owner_name));
        if ($pdoQuery_exec) {
                $updatedMsg="Successfully Updated";
            }  
        else{
                $updatedMsg="Data Not Updated. Please Try Again";
            }    


    }



    
}



?>