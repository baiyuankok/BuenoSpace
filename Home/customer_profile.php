<?php
    // Connect to database
    require_once "config.php";
    session_start();
    require"../Home/account_function/customer_readProfile.php";
    require"../Home/account_function/customer_updateProfile.php";
    $customer_name = isset($_SESSION['customer_name']) ? trim($_SESSION['customer_name']) : ''; 
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
<header id="header"></header>
    


    <section id="profile_page_layout">

        <div class="profile_infor">
            
            <div id="information_edit">
             
            <h5>WELCOME - <?php echo $customer_name; ?></h5>

            <br>
            <form class="profile_form" action="" method="post">

                <label>Name</label>
                <input type="text" name="customer_name"  value="<?php echo $customer_name; ?>">
                <br>
                <span class="help-block"><?php echo $customer_name_err; ?></span>
                <br>

                <label for="password">Email</label>
                <input type="text" id="password" name="customer_email" value="<?php echo $customer_email; ?>">
                <br>
                <span class="help-block"><?php echo $customer_email_err; ?></span>
                <br>

                <label for="password">Contact Number</label>
                <input type="text" id="password" name="customer_contact" value="<?php echo $customer_contact; ?>">
                <br>
                <span class="help-block"><?php echo $customer_contact_err; ?></span>
                <br><br>

                <div class="form-btn">
                    <input type="submit" class="small-btn" name="edit_customer_profile" value="Save" onclick="fade_out()">
                    <p id="success_msg"><?php echo $updatedMsg; ?></p>
                </div>
                </form>
                <br><br>
                            </div>
        </div>
           
        </section>

    
    <section id="profile_page_layout">
        <h5>Space Booking Record</h5>
        <br>

            <table>
        <tr>
            <th>Booked Space</th>
            <th>Date</th>
            <th> </th>
        </tr>
       
        <tr>
            <td>Waiting for booking function done</td>
            <td>.............</td>
            <td>............</td>
        </tr>
      
        </table>

        <br><br><br>

        <h5>Favourtire Space Record</h5>
        <br>

            <table>
        <tr>
            <th>Favourite Space</th>
            <th> </th>
        </tr>

        <?php
       
            //read the user favourite space from database and show it in table view
            $sql = "SELECT s.spaceID, userID, favouriteID, name 
            FROM space s, favourite f 
            WHERE s.spaceID = f.spaceID AND f.userID = $userID 
            ORDER BY favouriteID DESC;";
            //run the sql query
            $pdoQuery_run= $pdo->query($sql);
            //if query run
            if ($pdoQuery_run) {
             //fetch pdo object
             while ($row= $pdoQuery_run->fetch(PDO::FETCH_OBJ)) {
                 
                 ////found user record in institute table
                $name= $row->name;
                $userID= $row->userID;
                $favouriteID = $row->favouriteID;
                $space_id = $row->spaceID; ?>

                <tr>
                    <td><a href="../Spaces/space_detail_logic.php?spaceID=<?php echo $space_id; ?>"><?php echo $name; ?></a></td>
                    <td id="icon_td" onclick="deleteFav(<?php echo $favouriteID; ?>)"><i class="fa fa-trash" ></i></td>
                
                </tr>

                
          <?php  }}
            else{
                 echo 'not found data';
            }


            //check if there is favourite space added by customer. 
            //If no then echo 'no favourite space added' in table row
            $countRecordFav="SELECT * FROM favourite WHERE userID=$userID";
            $countRecordFav= $pdo->query($countRecordFav);
            $countRecordFav=$countRecordFav->rowCount();
            
            if($countRecordFav<1){ ?>

                <tr>
                    <td colspan="3">No Favourite Space Added</td>
                    
                </tr>

          <?php  }
        
        ?>
       
        
      
        </table>
           
           
        </section>
        <br><br><br>

        

        
    

    <footer id="footer"></footer> 

    <script type="text/javascript" src="../Home/javascript/profileFunction.js"></script>
</body>

</html>