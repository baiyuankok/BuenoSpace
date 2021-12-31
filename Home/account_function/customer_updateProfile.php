<?php


if(isset(($_POST['edit_customer_profile']))){

    if(empty(trim($_POST["customer_name"]))){
        $customer_name_err = "Please enter a valid name.";
    }

    if(empty(trim($_POST["customer_email"]))){
        $customer_email_err = "Please enter a valid email address.";
    }

    if(empty(trim($_POST["customer_contact"]))){
        $customer_contact_err = "Please enter a valid contact number.";
    }

    $customer_name=$_POST["customer_name"];
    $customer_contact=$_POST["customer_contact"];
    $customer_email=$_POST["customer_email"];

    if(empty($customer_email_err) && empty($customer_contact_err) && empty($customer_name_err)){

        //update to particular user row
        $pdoQuery="UPDATE customer SET customer_email=:customer_email, customer_contact=:customer_contact, customer_name=:customer_name WHERE userID=$userID";
        //run the query
        //prepare the query
        $pdoQuery_run=$pdo->prepare($pdoQuery);
        //execute the query
        $pdoQuery_exec= $pdoQuery_run->execute(array(":customer_email"=>$customer_email,":customer_contact"=>$customer_contact,":customer_name"=>$customer_name));
        if ($pdoQuery_exec) {
                $updatedMsg="Successfully Updated";
            }  
        else{
                $updatedMsg="Data Not Updated. Please Try Again";
            }    


    }



    
}



?>