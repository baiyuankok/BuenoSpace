<?php

$customer_email = $customer_email_resetPass_err = " ";


// Processing form data when form is submitted
if(isset(($_POST['customer_resetPassword']))){

    // Check if email is empty if not send email reset password
    if(empty(trim($_POST["customer_email"]))){
        $customer_email_resetPass_err = "Please enter a valid email address.";
    } else{
        $customer_email = trim($_POST["customer_email"]);

        // Prepare a select statement
        $sql = "SELECT * FROM customer WHERE customer_email = :customer_email";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":customer_email", $param_customer_email, PDO::PARAM_STR);
            
            // Set parameters
            $param_customer_email = trim($_POST["customer_email"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if email exists, if yes then send a link for password reset
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $userID = $row["userID"];
                        $customer_email = $row["customer_email"];
                        $customer_email_resetPass_notification = "Account found. Please check your email to reset your password.";
                        $user_ID_reset=$userID;
                        include 'customer_reset-password_findName.php';
                        
                        //Create a secure token for this forgot password request.
                        $token = openssl_random_pseudo_bytes(16);
                        $token = bin2hex($token);

                        //Insert the request information
                        //into our password_reset_request table.
 
                        //The SQL statement.
                        $insertSql = "INSERT INTO customer_password_reset_requests (date_requested, token, userID)
                        VALUES (:date_requested, :token, :userID)";
                        
                        //Prepare our INSERT SQL statement.
                        $statement = $pdo->prepare($insertSql);
 
                        //Execute the statement and insert the data.
                        $statement->execute(array(
                            "date_requested" => date("Y-m-d H:i:s"),
                            "token" => $token,
                            "userID" => $userID
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
                        $verifyScript .= "/account_function/customer_forgot_password.php";

      
                       

 
                        //The link that we will send the user via email.
                     $linkToSend = $verifyScript . '?uid=' . $userID . '&id=' . $passwordRequestId . '&t=' . $token;
 
                        
                        
                        //TO-DO Think of a way to send an email with this link!
                        //$template_file = "./emailTemplate.php";
                        // the message
                        $to_email = $customer_email;
                        $subject = "BuenoSpace - Customer Account Reset Password";
                        
                       
                        
                        $body="Dear ".$customer_name.","." <br><br> You are receiving this email because you have requested a password reset for your BuenoSpace Customer Account. You can reset your password with the following link:" . "\n". $linkToSend;
                        $body .="<br> Please note that this link only remains valid for 15 minutes. If you did not request a password reset, you can safely ignore this email. Passwords can only be changed by official account holders.";
                        $body .="<br><br><br>Kind regards, <br>The BuenoSpace Team ";
                        
                        include"customer_reset_email_sending.php";
                        



                        
                        
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $customer_email_resetPass_err = "No BuenoSpace Customer Account found with that email address.";
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