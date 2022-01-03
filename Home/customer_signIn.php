<?php
    session_start();
    // Connect to database
    require_once "config.php";

    // Define variables and initialize with empty values
    $customer_email = $customer_password = "";
    $customer_email_err = $customer_password_err = "";

    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(empty(trim($_POST["customer_email"]))){
            $customer_email_err = "Please enter a valid email address.";
        }

        if(empty(trim($_POST["customer_password"]))){
            $customer_password_err = "Please enter a password.";     
        }

        $customer_password=$_POST["customer_password"];
        $customer_email=$_POST["customer_email"];

        if(empty($customer_email_err) && empty($customer_password_err)){

            $sql = "SELECT userID, customer_email, customer_password, customer_name FROM customer WHERE customer_email = :customer_email";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":customer_email", $customer_email, PDO::PARAM_STR);
            
            // Set parameters
            $customer_email = trim($_POST["customer_email"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if email exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $userID = $row["userID"];
                        $customer_email = $row["customer_email"];
                        $customer_name = $row["customer_name"];
                        $hashed_password = $row["customer_password"];
                        if(password_verify($customer_password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["customer_loggedin"] = true;
                            $_SESSION["userID"] = $userID;
                            $_SESSION["customer_name"] = $customer_name;
                                                      
                            
                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $customer_password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $customer_email_err = "No account found with that email address.";
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

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/FortAwesome/Font-Awesome@5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main_page.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/signInsignUp.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <title>BuenoSpace - Customer Sign In</title>
</head>

<body>

    <div class="background-img-gradient">
    <div class="background-img" style="height: 100vh;"></div>
   </div>
   
    <nav class="navbar navbar-expand-lg fixed-top" id="navbar-section">
    <div class="container-fluid">
        <a class="navbar-brand" href="../Home/index.php">
            <img src="../Home/assets/logo.png" alt="logo-image">
        </a>
    </div>
</nav>
<br><br><br>
<div class="form_container">
    <h1 class="title-form">Sign In As Customer</h1>
    <form class="sign_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <br><br>
            
                <label>Email</label>
                <input type="email" name="customer_email"  value="<?php echo $customer_email; ?>">
                <br>
                <span class="help-block"><?php echo $customer_email_err; ?></span>
                <br>
           
                <label for="password">Password</label>
                <input type="password" id="password" name="customer_password" value="<?php echo $customer_password; ?>">
                 <br>
                <span class="help-block"><?php echo $customer_password_err; ?></span>
                <br>
               
                <div class="form-btn">
                <input type="submit" class="small-btn" value="Sign In">
            
                
                <p style="color:#fff;">Forget Password? <a href="customer_resetPassword.php" style="color:#fff;">Reset Password here</a>.</p>
            <p style="color:#fff;">Don't have a Customer Account? <a href="customer_signUp.php" style="color:#fff;">Sign Up here</a>.</p>
            <br>
                </div>
        </form>
    
</div>

    
</body>