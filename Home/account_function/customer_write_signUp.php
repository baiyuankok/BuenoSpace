<?php
// Connect to database
require_once "config.php";

        // Define variables and initialize with empty values
        $customer_email = $customer_password = $confirm_password = $customer_name = $customer_contact = "";
        $customer_email_err = $customer_password_err = $confirm_password_err = $customer_name_err = $customer_contact_err = "";

        // Processing form data when form is submitted
        if($_SERVER["REQUEST_METHOD"] == "POST"){

            if(empty(trim($_POST["customer_name"]))){
                $customer_name_err = "Please enter a name.";
            }

            if(empty(trim($_POST["customer_contact"]))){
                $customer_contact_err = "Please enter a valid contact number.";
            }

            if(empty(trim($_POST["customer_email"]))){
                $customer_email_err = "Please enter a valid email address.";
            } else{
                // Prepare a select statement
                $sql = "SELECT userID FROM customer WHERE customer_email = :customer_email";
                
                if($stmt = $pdo->prepare($sql)){
                    // Bind variables to the prepared statement as parameters
                    $stmt->bindParam(":customer_email", $param_customer_email, PDO::PARAM_STR);
                    
                    // Set parameters
                    $param_customer_email = trim($_POST["customer_email"]);
                    
                    // Attempt to execute the prepared statement
                    if($stmt->execute()){
                        if($stmt->rowCount() == 1){
                            $customer_email_err = "This email address is already taken.";
                        } else{
                            $customer_email = trim($_POST["customer_email"]);
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
        
                    // Close statement
                    unset($stmt);
                }
            }

            // Validate password
            if(empty(trim($_POST["customer_password"]))){
                $customer_password_err = "Please enter a password.";     
            } elseif(strlen(trim($_POST["customer_password"])) < 6){
                $customer_password_err = "Password must have atleast 6 characters.";
            } else{
                $customer_password = trim($_POST["customer_password"]);
            }
            
            // Validate confirm password
            if(empty(trim($_POST["confirm_password"]))){
                $confirm_password_err = "Please confirm password.";     
            } else{
                $confirm_password = trim($_POST["confirm_password"]);
                if(empty($customer_password_err) && ($customer_password != $confirm_password)){
                    $confirm_password_err = "Password did not match.";
                }
            }


            $customer_name=$_POST["customer_name"];
            $customer_contact=$_POST["customer_contact"];
            $customer_password=$_POST["customer_password"];
            $customer_email=$_POST["customer_email"];

            $encryp_password = password_hash($customer_password, PASSWORD_DEFAULT); // Creates a password hash
            


             // Check input errors before inserting in database
            if(empty($customer_email_err) && empty($customer_password_err) && empty($confirm_password_err) && empty($customer_contact_err) && empty($customer_name_err)){

                 //insert new customer into TABLE customer
                $pdoQuery="INSERT INTO customer(customer_email, customer_password, customer_contact, customer_name) VALUES (:customer_email, :encryp_password, :customer_contact, :customer_name)";
                $pdoQuery_run= $pdo->prepare($pdoQuery);
                $pdoQuery_exec= $pdoQuery_run->execute(array(":customer_email"=>$customer_email,":encryp_password"=>$encryp_password, ":customer_contact"=>$customer_contact, ":customer_name"=>$customer_name));

            

            if ($pdoQuery_exec){
                //if sucessfully inserted the new customer information, redirect to customer login page
                header("location: customer_signIn.php");

            }



            }
            
        }
?>