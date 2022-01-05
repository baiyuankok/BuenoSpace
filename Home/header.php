<?php
    session_start();  
?>

<nav class="navbar navbar-expand-lg fixed-top" id="navbar-section">
    <div class="container-fluid">
        <a class="navbar-brand" href="../Home/index.php">
            <img src="../Home/assets/logo.png" alt="logo-image">
        </a>
        <button class="navbar-toggler navbar-dark toggler-custom" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation" id="triple-bar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <?php

                // Check if the customer is already logged in, if yes then hide the sign in sign up btn and show welcome msg
                if(isset($_SESSION["customer_loggedin"]) && $_SESSION["customer_loggedin"] === true){
                    
                    $customer_name = isset($_SESSION['customer_name']) ? trim($_SESSION['customer_name']) : ''; ?>

                    <div class="dropdown_welcome">
                    <button onclick="dropdownFunction()" class="dropbtn_welcome" >Welcome <?php echo $customer_name; ?> <i class="fas fa-caret-down"></i> </button>
                    <div id="myDropdown" class="dropdown-content-welcome">

                        <a href="../Home/index.php">Home Page</a>
                        <a href="../Home/customer_profile.php">Profile Page</a>
                        <a href="../Home/signOut.php">Sign Out</a>
                    </div>
                    </div>         
            <?php

                // Check if the owner is already logged in, if yes then hide the sign in sign up btn and show sign out btn
                } elseif (isset($_SESSION["owner_loggedin"]) && $_SESSION["owner_loggedin"] === true){ ?>
                    <ul class="navbar-nav ms-auto justify-content-end">
                        <li class="nav-item dropdown">
                            <button class="small-btn" id="sign-up-btn" data-bs-toggle="dropdown" aria-expanded="false" onclick="window.location.href='../Home/signOut.php'">Sign Out</button>
                        </li>
                    </ul>
                    
              <?php  }else{ //the customer not yet sign in, so show sign in sign up button ?>

                <ul class="navbar-nav ms-auto justify-content-end">
                                <li class="nav-item dropdown">
                                    <button class="dropdown-toggle small-btn" id="sign-in-btn" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Sign in 
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item dropdown-item-hover" href="../Home/customer_signIn.php">Sign in as customer</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li><a class="dropdown-item dropdown-item-hover" href="../Home/owner_signIn.php">Sign in as owner</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <button class="small-btn" id="sign-up-btn" onclick="window.location.href='../Home/customer_signUp.php'">Sign Up</button>
                                </li>
                                
                            </ul>

           <?php     }
          

            ?>
            
        </div>
    </div>
</nav>


<script type="text/javascript" src="../Home/javascript/welcomebutton.js"></script>








