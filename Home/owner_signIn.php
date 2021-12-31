<?php
    session_start();
    // Connect to database
    require_once "config.php";

    // Define variables and initialize with empty values
    $owner_email = $owner_password = "";
    $owner_email_err = $owner_password_err = "";

    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(empty(trim($_POST["owner_email"]))){
            $owner_email_err = "Please enter a valid email address.";
        }

        if(empty(trim($_POST["owner_password"]))){
            $owner_password_err = "Please enter a password.";     
        }

        $owner_password=$_POST["owner_password"];
        $owner_email=$_POST["owner_email"];

        if(empty($owner_email_err) && empty($owner_password_err)){

            $sql = "SELECT ownerID, owner_email, owner_password, owner_name FROM owner WHERE owner_email = :owner_email";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":owner_email", $owner_email, PDO::PARAM_STR);
            
            // Set parameters
            $owner_email = trim($_POST["owner_email"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if email exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $ownerID = $row["ownerID"];
                        $owner_email = $row["owner_email"];
                        $owner_name = $row["owner_name"];
                        $hashed_password = $row["owner_password"];
                        if(password_verify($owner_password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["owner_loggedin"] = true;
                            $_SESSION["ownerID"] = $ownerID;
                            $_SESSION["owner_name"] = $owner_name;
                                                      
                            
                            // Redirect user to welcome page
                            header("location: owner_profile.php");
                        } else{
                            // Display an error message if password is not valid
                            $owner_password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $owner_email_err = "No account found with that email address.";
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

    <title>BuenoSpace - Owner Sign In</title>
</head>

<body>

    <div class="background-img-gradient">
    <div class="background-img" style="height: 100vh;"></div>
   </div>
   
    <nav class="navbar navbar-expand-lg fixed-top" id="navbar-section">
    <div class="container-fluid">
        <a class="navbar-brand" href="../Home/main_page.html">
            <img src="../Home/assets/logo.png" alt="logo-image">
        </a>
    </div>
</nav>
<br><br><br>
<div class="form_container">
    <h1 class="title-form">Sign In As Space Owner</h1>
    <form class="sign_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <br><br>
            
                <label>Email</label>
                <input type="email" name="owner_email"  value="<?php echo $owner_email; ?>">
                <br>
                <span class="help-block"><?php echo $owner_email_err; ?></span>
                <br>
           
                <label for="password">Password</label>
                <input type="password" id="password" name="owner_password" value="<?php echo $owner_password; ?>">
                 <br>
                <span class="help-block"><?php echo $owner_password_err; ?></span>
                <br><br>
               
                <div class="form-btn">
                <input type="submit" class="small-btn" value="Sign In">

                <br><br>
                <p style="color:#fff;">Forget Password? <a href="owner_resetPassword.php" style="color:#fff;">Reset Password here</a>.</p>
                <p style="color:#fff;">Don't have a Customer Account? <a href="owner_signUp.php" style="color:#fff;">Sign Up here</a>.</p>
            
                </div>
        </form>
    
</div>

    
</body>