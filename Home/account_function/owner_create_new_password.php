<?php
// Initialize the session
session_start();

// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$owner_password = $confirm_password = "";
$owner_password_err = $confirm_password_err = "";

//The user's id, which should be present in the GET variable "uid"
$ownerID = $_SESSION['owner_id_reset_pass'];
//echo $userID;

// Processing form data when form is submitted
if(isset(($_POST['owner_reset_password_btn']))){

    
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
    
    // Check input errors before inserting in database
    if(empty($owner_password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "UPDATE owner SET owner_password=:owner_password WHERE ownerID=:ownerID";
         
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":ownerID", $ownerID, PDO::PARAM_STR);
            $stmt->bindParam(":owner_password", $param_owner_password, PDO::PARAM_STR);
            
            // Set parameters
            $param_owner_password = password_hash($owner_password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page

                header("location: ../owner_signIn.php");
                 

            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($pdo);
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/FortAwesome/Font-Awesome@5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main_page.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/signInsignUp.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <title>BuenoSpace - Reset Customer Account Password</title>
</head>

<body>

    <div class="background-img-gradient">
    <div class="background-img" style="height: 100vh;"></div>
   </div>
   
    <nav class="navbar navbar-expand-lg fixed-top" id="navbar-section">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">
            <img src="../assets/logo.png" alt="logo-image">
        </a>
    </div>
</nav>
<br><br><br><br><br>
<div class="form_container">
    <h1 class="title-form">Reset Customer Account Password</h1>
    <form class="sign_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
           
                <label for="password">Password</label>
                <input type="password" id="password" name="owner_password"
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required 
                 value="<?php echo $owner_password; ?>">
                 <br>
                <span class="help-block"><?php echo $owner_password_err; ?></span>
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
                <input type="submit" class="small-btn" name="owner_reset_password_btn" value="Reset">
            
            <p style="color:#fff;">Sign In to Owner Account? <a href="../owner_signIn.php" style="color:#fff;">Sign In here</a>.</p>
                </div>
        </form>
    
</div>
<script type="text/javascript" src="../javascript/strongpass.js"></script>
    
</body>