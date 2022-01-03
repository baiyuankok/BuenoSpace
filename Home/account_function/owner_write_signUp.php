<?php
// Connect to database
require_once "config.php";

        // Define variables and initialize with empty values
        $owner_email = $owner_password = $confirm_password = $owner_name = $owner_contact = "";
        $owner_email_err = $owner_password_err = $confirm_password_err = $owner_name_err = $owner_contact_err = "";

        // Processing form data when form is submitted
        if($_SERVER["REQUEST_METHOD"] == "POST"){

            if(empty(trim($_POST["owner_name"]))){
                $owner_name_err = "Please enter a name.";
            }

            if(empty(trim($_POST["owner_contact"]))){
                $owner_contact_err = "Please enter a valid contact number.";
            }

            if(empty(trim($_POST["owner_email"]))){
                $owner_email_err = "Please enter a valid email address.";
            } else{
                // Prepare a select statement
                $sql = "SELECT ownerID FROM owner WHERE owner_email = :owner_email";
                
                if($stmt = $pdo->prepare($sql)){
                    // Bind variables to the prepared statement as parameters
                    $stmt->bindParam(":owner_email", $param_owner_email, PDO::PARAM_STR);
                    
                    // Set parameters
                    $param_owner_email = trim($_POST["owner_email"]);
                    
                    // Attempt to execute the prepared statement
                    if($stmt->execute()){
                        if($stmt->rowCount() == 1){
                            $owner_email_err = "This email address is already taken.";
                        } else{
                            $owner_email = trim($_POST["owner_email"]);
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
        
                    // Close statement
                    unset($stmt);
                }
            }

            // Validate password
            if(empty(trim($_POST["owner_password"]))){
                $owner_password_err = "Please enter a password.";     
            } elseif(strlen(trim($_POST["owner_password"])) < 6){
                $owner_password_err = "Password must have atleast 6 characters.";
            } else{
                $owner_password = trim($_POST["owner_password"]);
            }
            
            // Validate confirm password
            if(empty(trim($_POST["confirm_password"]))){
                $confirm_password_err = "Please confirm password.";     
            } else{
                $confirm_password = trim($_POST["confirm_password"]);
                if(empty($owner_password_err) && ($owner_password != $confirm_password)){
                    $confirm_password_err = "Password did not match.";
                }
            }


            $owner_name=$_POST["owner_name"];
            $owner_contact=$_POST["owner_contact"];
            $owner_password=$_POST["owner_password"];
            $owner_email=$_POST["owner_email"];

            $encryp_password = password_hash($owner_password, PASSWORD_DEFAULT); // Creates a password hash
            


             // Check input errors before inserting in database
            if(empty($owner_email_err) && empty($owner_password_err) && empty($confirm_password_err) && empty($owner_contact_err) && empty($owner_name_err)){

                 //insert new customer into TABLE customer
                $pdoQuery="INSERT INTO owner(owner_email, owner_password, owner_contact, owner_name) VALUES (:owner_email, :encryp_password, :owner_contact, :owner_name)";
                $pdoQuery_run= $pdo->prepare($pdoQuery);
                $pdoQuery_exec= $pdoQuery_run->execute(array(":owner_email"=>$owner_email,":encryp_password"=>$encryp_password, ":owner_contact"=>$owner_contact, ":owner_name"=>$owner_name));

            

            if ($pdoQuery_exec){
                //if sucessfully inserted the new customer information, redirect to customer login page
                header("location: owner_signIn.php");

            }



            }
            
        }
?>