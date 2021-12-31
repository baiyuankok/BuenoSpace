<?php
    session_start();

   
?>


<nav class="navbar navbar-expand-lg fixed-top" id="navbar-section">
    <div class="container-fluid">
        <a class="navbar-brand" href="../Home/main_page.html">
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

                        <a href="main_page.html">Home Page</a>
                        <a href="customer_profile.php">Profile Page</a>
                        <a href="signOut.php">Sign Out</a>
                    </div>
                    </div>         
                    
                    
              <?php  }else{ //the customer not yet sign in, so show sign in sign up button ?>

                <ul class="navbar-nav ms-auto justify-content-end">
                                <li class="nav-item dropdown">
                                    <button class="dropdown-toggle small-btn" id="sign-in-btn" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Sign in 
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item dropdown-item-hover" href="customer_signIn.php">Sign in as customer</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li><a class="dropdown-item dropdown-item-hover" href="owner_signIn.php">Sign in as owner</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <button class="small-btn" id="sign-up-btn" data-bs-toggle="dropdown" aria-expanded="false">Sign Up</button>
                                    <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item dropdown-item-hover" href="customer_signUp.php">Sign Up as customer</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li><a class="dropdown-item dropdown-item-hover" href="owner_signUp.php">Sign Up as owner</a></li>
                                    </ul>
                                    
                                </li>
                            </ul>




           <?php     }
          

            ?>
            
        </div>
    </div>
</nav>


<script type="text/javascript" src="../Home/javascript/welcomebutton.js"></script>








