<?php

    // Initialize the session
    session_start(); 
    // Include config file
    require_once "config.php";
    require "../Home/account_function/customer_reset_password_validate.php";


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

    <title>BuenoSpace - Reset Password</title>
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
<br><br><br><br><br><br>

<div class="form_container">
    <h1 class="title-form">Reset Password</h1>
    <form class="sign_form" action="" method="post">

                <br>
            
                <label>Email</label>
                <input type="email" name="customer_email"  value="<?php echo $customer_email; ?>">
                <br>
                <span class="help-block"><?php echo $customer_email_resetPass_err; ?></span>
                <br>
               
                <div class="form-btn">
                <input type="submit" class="small-btn" name="customer_resetPassword" value="Reset">

              
                
                <p style="color:#fff;">Back to Sign In page? <a href="customer_signIn.php" style="color:#fff;">Sign In</a>.</p>
                <br>
            
                </div>
        </form>
    
</div>

    
</body>