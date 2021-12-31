<?php

$customer_email_resetPass = $customer_email_resetPass_err = "";


// Processing form data when form is submitted
if(isset(($_POST['customer_resetPassword']))){

    // Check if email is empty
    if(empty(trim($_POST["customer_email_resetPass"]))){
        $customer_email_resetPass_err = "Please enter a valid email address.";
    } else{
        $customer_email = trim($_POST["customer_email_resetPass"]);
    }
    
    // Validate credentials
    if(empty($customer_email_resetPass_err)) {
        // Prepare a select statement
        $sql = "SELECT * FROM customer WHERE customer_email = :customer_email";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":customer_email", $param_customer_email, PDO::PARAM_STR);
            
            // Set parameters
            $param_customer_email = trim($_POST["customer_email_resetPass"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if email exists, if yes then send a link for password reset
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $userID = $row["userID"];
                        $customer_email = $row["customer_email"];
                        $customer_email_resetPass_err = "Account found. Please check your email to reset your password.";
                        $user_ID_reset=$userId;
                        include 'reset-password_findName.php';
                        
                        //Create a secure token for this forgot password request.
                        $token = openssl_random_pseudo_bytes(16);
                        $token = bin2hex($token);
                        
                        //Insert the request information
                        //into our password_reset_request table.
 
                        //The SQL statement.
                        $insertSql = "INSERT INTO password_reset_requests (user_id, date_requested, token)
                        VALUES (:user_id, :date_requested, :token)";
                        
                        //Prepare our INSERT SQL statement.
                        $statement = $pdo->prepare($insertSql);
 
                        //Execute the statement and insert the data.
                        $statement->execute(array(
                            "user_id" => $userId,
                            "date_requested" => date("Y-m-d H:i:s"),
                            "token" => $token
                        ));
                        
                        //Get the ID of the row we just inserted.
                        $passwordRequestId = $pdo->lastInsertId();
                        
                        //Create a link to the URL that will verify the
                        //forgot password request and allow the user to change their password

                        //$verifyScript = 'http://localhost/evaluV2/forgot-password.php';
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
                        $verifyScript .= "/forgot-password.php";

      
                       

 
                        //The link that we will send the user via email.
                        $linkToSend = $verifyScript . '?uid=' . $userId . '&id=' . $passwordRequestId . '&t=' . $token;
 
                        //Print out the email for the sake of this tutorial.
                        echo "here";
                        echo $linkToSend;
                        
                        //TO-DO Think of a way to send an email with this link!
                        //$template_file = "./emailTemplate.php";
                        // the message
                        $to_email = $email;
                        $subject = "Your Cybiant Ada Account Password Reset";
                        $headers= 'From: Evalu Team <xueying.tan1234@gmail.com>'."\r\n".'X-Mailer: PHP/'.phpversion()."\r\n".'Content-type: text/html; charset=iso-8859-1';
                       
                     
                        $body="Dear ".$fullname_user.","." \r\n\r\nYou are receiving this email because you have requested a password reset for your Cybiant Ada account. You can reset your password with the following link:" . "\n". $linkToSend;
                        $body .=" \r\n  \r\nPlease note that this link only remains valid for 15 minutes. If you did not request a password reset, you can safely ignore this email. Passwords can only be changed by official account holders.";
                        $body .=" \r\n  \r\n  \r\nKind regards, \r\nThe Cybiant Ada Team ";

                    


                   
                        
                        
                        
                        include"reset-password _emailSending.php";
                        
                      


                        
                        
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email address.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($pdo);
}




?>