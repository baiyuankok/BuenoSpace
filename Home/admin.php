<?php
    session_start();
    // Connect to database
    require_once "config.php";

    // Define variables and initialize with empty values
    $admin_email = $admin_password = "";
    $admin_email_err = $admin_password_err = "";

    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(empty(trim($_POST["admin_email"]))){
            $admin_email_err = "Please enter a valid email address.";
        }

        if(empty(trim($_POST["admin_password"]))){
            $admin_password_err = "Please enter a password.";     
        }

        $admin_password=$_POST["admin_password"];
        $admin_email=$_POST["admin_email"];

        if(empty($admin_email_err) && empty($admin_password_err)){

            $sql = "SELECT adminID, admin_email, admin_password FROM admin WHERE admin_email = :admin_email";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":admin_email", $admin_email, PDO::PARAM_STR);
            
            // Set parameters
            $admin_email = trim($_POST["admin_email"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if email exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        
                        $admin_email = $row["admin_email"];
                        $admin_password_database = $row["admin_password"];
                        if($admin_password==$admin_password_database){
                            // Password is correct, so start a new session
                            $_SESSION["owner_loggedin"] = true;
                    
                            
                            // Redirect user to welcome page
                            header("location: admin_platform.php");
                        } else{
                            // Display an error message if password is not valid
                            $admin_password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $admin_email_err = "No account found with that email address.";
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

    <title>BuenoSpace - Welcome Admin</title>
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
    <h1 class="title-form">Sign In As Admin</h1>
    <form class="sign_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <br><br>
            
                <label>Email</label>
                <input type="email" name="admin_email"  value="<?php echo $admin_email; ?>">
                <br>
                <span class="help-block"><?php echo $admin_email_err; ?></span>
                <br>
           
                <label for="password">Password</label>
                <input type="password" id="password" name="admin_password" value="<?php echo $admin_password; ?>">
                 <br>
                <span class="help-block"><?php echo $admin_password_err; ?></span>
                <br><br>
               
                <div class="form-btn">
                <input type="submit" class="small-btn" value="Sign In">

                <br>
               <br>
                </div>
        </form>
    
</div>

    
</body>