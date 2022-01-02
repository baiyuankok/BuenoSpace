<?php

require '../Home/account_function/customer_write_signUp.php';

 

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

    <title>BuenoSpace - Customer Sign Up</title>
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
    <h1 class="title-form">Sign Up As Customer</h1>
    <form class="sign_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <label>Name</label>
                <input type="text" name="customer_name"  value="<?php echo $customer_name; ?>">
                <br>
                <span class="help-block"><?php echo $customer_name_err; ?></span>
                <br>

                <label>Contact Number:</label>
                <input type="text" name="customer_contact"  value="<?php echo $customer_contact; ?>">
                <br>
                <span class="help-block"><?php echo $customer_contact_err; ?></span>
                <br>
            
                <label>Email</label>
                <input type="email" name="customer_email"  value="<?php echo $customer_email; ?>">
                <br>
                <span class="help-block"><?php echo $customer_email_err; ?></span>
                <br>
           
                <label for="password">Password</label>
                <input type="password" id="password" name="customer_password"
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required 
                 value="<?php echo $customer_password; ?>">
                 <br>
                <span class="help-block"><?php echo $customer_password_err; ?></span>
                <br>
            
           
                <label>Confirm Password</label>
                <input type="password" name="confirm_password"  value="<?php echo $confirm_password; ?>">
                <br>
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
                <br>

                <div id="message">
                    <label style="width:100%;">Password must contain the following:</label><br><br>
                    <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                    <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                    <p id="number" class="invalid">A <b>number</b></p>
                    <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                    </div>
         
            
            
           
                <div class="form-btn">
                <input type="submit" class="small-btn" value="Sign Up">
            
            <p style="color:#fff;">Already have a Customer Account? <a href="customer_signIn.php" style="color:#fff;">Sign In here</a>.</p>
                </div>
        </form>
    
</div>
<script type="text/javascript" src="../Home/javascript/strongpass.js"></script>
    
</body>