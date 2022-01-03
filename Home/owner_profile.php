<?php
    // Connect to database
    require_once "config.php";
    session_start();
    require"../Home/account_function/owner_readProfile.php";
    require"../Home/account_function/owner_updateProfile.php";
    $owner_name = isset($_SESSION['owner_name']) ? trim($_SESSION['owner_name']) : '';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/main_page.css">
    <link rel="stylesheet" href="css/signInsignUp.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/FortAwesome/Font-Awesome@5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <title>BuenoSpace - Profile</title>
</head>

<body>
<header id="owner_header"></header>
    


    <section id="profile_page_layout">

        <div class="profile_infor">
           
            <div id="information_edit">
                
            <h5>WELCOME - <?php echo $owner_name; ?></h5>

            <br>
            <form class="profile_form" action="" method="post">

                <label>Name</label>
                <input type="text" name="owner_name"  value="<?php echo $owner_name; ?>">
                <br>
                <span class="help-block"><?php echo $owner_name_err; ?></span>
                <br>

                <label for="password">Email</label>
                <input type="text" id="password" name="owner_email" value="<?php echo $owner_email; ?>">
                <br>
                <span class="help-block"><?php echo $owner_email_err; ?></span>
                <br>

                <label for="password">Contact Number</label>
                <input type="text" id="password" name="owner_contact" value="<?php echo $owner_contact; ?>">
                <br>
                <span class="help-block"><?php echo $owner_contact_err; ?></span>
                <br><br>

                <div class="form-btn">
                    <input type="submit" class="small-btn" name="edit_owner_profile" value="Save" onclick="fade_out()">
                    <p id="success_msg"><?php echo $updatedMsg; ?></p>
                </div>
                </form>
                <br><br>
                            </div>
        </div>
           
        </section>

    
    
        <br><br><br>

        <section id="profile_page_layout">
        <h5 style="display: inline;">Space Listing </h5> <i class="fas fa-plus" style="display: inline; cursor:pointer;" onclick="addSpace()"></i>
        <br><br>

            <table>
        <tr>
            <th>Space</th>
            <th></th>
           
        </tr>

        <?php
       
            //find the owner space listing and put it in table view
             $sql = "SELECT * FROM space where ownerID=$ownerID";
             //run the sql query
             $pdoQuery_run= $pdo->query($sql);
             //if query run
             if ($pdoQuery_run) {
              //fetch pdo object
              while ($row= $pdoQuery_run->fetch(PDO::FETCH_OBJ)) {
                  ////found owner record in space table
                 $name= $row->name; ?>

                    <tr>
                        <td><?php echo $name; ?></td>
                        <td>action</td>
                    
                    </tr>

           <?php  }}
             else{
                  echo 'not found data';
             }


             //check if there is space listed by owner. if no then echo 'no space listed' in table row
                $countRecord="SELECT * FROM space WHERE ownerID=$ownerID";
                $countRecord= $pdo->query($countRecord);
                $countRecord=$countRecord->rowCount();
                
                if($countRecord<1){ ?>

                    <tr>
                        <td colspan="2">No listed space.</td>
                        
                    
                    </tr>

            <?php    }
        ?>
       
        
      
        </table>

        <br><br><br>

        <h1>Add Calander Here</h1>
        </section>

        

        
    

    <footer id="footer"></footer> 

    <script type="text/javascript" src="../Home/javascript/profileFunction.js"></script>
</body>

</html>