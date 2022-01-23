<?php
    // Connect to database
    require_once "config.php";
   
    
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
    <link rel="stylesheet" href="css/evo-calendar.min.css">
    <link rel="stylesheet" href="css/evo-calendar.royal-navy.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/FortAwesome/Font-Awesome@5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <title>BuenoSpace - Admin</title>
</head>

<body>
    <header id="header"></header>
    <section id="profile_page_layout">
        <div class="profile_infor">
            <div id="information_edit">
                
               
            </div>
        </div>   
    </section>

   
    <section id="table_layout">
       
        <table>
        <h5 class="tableName">Owner Account / <a href="../Home/admin_platform.php">Customer Account</a></h5>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Owner Email</th>
                    <th>Name</th>
                    <th>Contact Number</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                     <tr>
                        <form action='' method="POST">
                            <td></td>
                            <td data-label="Name"><input type="text" id="searchbar" name="searchOwnerEmail" placeholder="Search Email" value=""></td>
                                <td data-label="Name">  <input type="text" id="searchbar" name="searchOwnerName" placeholder="Search Name" value=""></td>
                                <td data-label="Name"><input type="text" id="searchbar" name="searchOwnerContact" placeholder="Search Contact" value=""></td>
                                <td data-label="Name"> <input type="submit" name="searchBoxOwner" value="Search" id="search-btn"></td>
                        </form>
                               
                        </tr>


                <?php
                require "../Home/account_function/admin_searchOwner.php";
                    $x=0;
                    //find the owner space listing and put it in table view
                    $sql = "SELECT * FROM owner WHERE owner_email LIKE '%$searchOwnerEmail%' 
                    AND owner_name LIKE '%$searchOwnerName%'
                    AND owner_contact LIKE '%$searchOwnerContact%'";
                    //run the sql query
                    $pdoQuery_run= $pdo->query($sql);
                    //if query run
                    if ($pdoQuery_run) {
                        //fetch pdo object
                        while ($row= $pdoQuery_run->fetch(PDO::FETCH_OBJ)) {
                            $x++;
                            ////found owner record in space table
                            $ownerID= $row->ownerID; 
                            $owner_email= $row->owner_email; 
                            $owner_name= $row->owner_name; 
                            $owner_contact= $row->owner_contact; 
                            //$space_id = $row->spaceID; 
                            ?>

                            <tr>
                            <td data-label="Name"><?php echo $x; ?></td>
                                <td data-label="Name"><?php echo $owner_email; ?></td>
                                <td data-label="Name"><?php echo $owner_name; ?></td>
                                <td data-label="Name"><?php echo $owner_contact; ?></td>
                                <td data-label="Name" onclick="deleteOwner(<?php echo $ownerID; ?>)"><a href="">Delete</a></td>
                               
                            </tr>
                <?php }}
                    else{
                        echo 'not found data';
                    }

                    ?>
                        
            </tbody>
        </table>
        <br><br><br>
        

    </section>

    <footer id="footer"></footer> 

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
    <script src="../Home/javascript/evo-calendar.min.js"></script>
    <script type="text/javascript" src="../Home/javascript/profileFunction.js"></script>

    
</body>

</html>