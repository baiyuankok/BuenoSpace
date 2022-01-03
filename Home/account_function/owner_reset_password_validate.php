<?php

$owner_email = $owner_email_resetPass_err = " ";


// Processing form data when form is submitted
if(isset(($_POST['owner_resetPassword']))){

    // Check if email is empty if not send email reset password
    if(empty(trim($_POST["owner_email"]))){
        $owner_email_resetPass_err = "Please enter a valid email address.";
    } else{
       echo $owner_email = trim($_POST["owner_email"]);

        // Prepare a select statement
        $sql = "SELECT * FROM owner WHERE owner_email = :owner_email";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":owner_email", $param_owner_email, PDO::PARAM_STR);
            
            // Set parameters
            $param_owner_email = trim($_POST["owner_email"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if email exists, if yes then send a link for password reset
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $ownerID = $row["ownerID"];
                        $owner_email = $row["owner_email"];
                        $owner_email_resetPass_notification = "Account found. Please check your email to reset your password.";
                        $owner_ID_reset=$ownerID;
                        include 'owner_reset-password_findName.php';
                        
                        //Create a secure token for this forgot password request.
                        $token = openssl_random_pseudo_bytes(16);
                        $token = bin2hex($token);

                        //Insert the request information
                        //into our password_reset_request table.
 
                        //The SQL statement.
                        $insertSql = "INSERT INTO owner_password_reset_requests (date_requested, token, ownerID)
                        VALUES (:date_requested, :token, :ownerID)";
                        
                        //Prepare our INSERT SQL statement.
                        $statement = $pdo->prepare($insertSql);
 
                        //Execute the statement and insert the data.
                        $statement->execute(array(
                            "date_requested" => date("Y-m-d H:i:s"),
                            "token" => $token,
                            "ownerID" => $ownerID
                        ));
                        
                        //Get the ID of the row we just inserted.
                        $passwordRequestId = $pdo->lastInsertId();

                        //Create a link to the URL that will verify the
                        //forgot password request and allow the user to change their password

                        
                            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                            $verifyScript = "https";
                        else
                            $verifyScript = "http";
  
                        // Here append the common URL characters.
                        $verifyScript .= "://";
  
                        // Append the host(domain name, ip) to the URL.
                        $verifyScript .= $_SERVER['HTTP_HOST'];
  
                        // Append the requested resource location to the URL
                        $verifyScript .= dirname($_SERVER['PHP_SELF']);
                        $verifyScript .= "/account_function/owner_forgot_password.php";

      
                       

 
                        //The link that we will send the user via email.
                     $linkToSend = $verifyScript . '?uid=' . $ownerID . '&id=' . $passwordRequestId . '&t=' . $token;
 
                        
                        
                        //TO-DO Think of a way to send an email with this link!
                        //$template_file = "./emailTemplate.php";
                        // the message
                        $to_email = $owner_email;
                        $subject = "BuenoSpace - Owner Account Reset Password";
                        
                       
                        
                        $body="Dear ".$owner_name.","." <br><br> You are receiving this email because you have requested a password reset for your BuenoSpace Owner Account. You can reset your password with the following link:" . "\n". $linkToSend;
                        $body .="<br> Please note that this link only remains valid for 15 minutes. If you did not request a password reset, you can safely ignore this email. Passwords can only be changed by official account holders.";
                        $body .="<br><br><br>Kind regards, <br>The BuenoSpace Team ";
                        
                        include"owner_reset_email_sending.php";
                        



                        
                        
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $owner_email_resetPass_err = "No BuenoSpace Customer Account found with that email address.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }

        
    }
    
    
}




?>