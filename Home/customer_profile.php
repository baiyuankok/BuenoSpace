<?php
    // Connect to database
    require_once "config.php";
    session_start();
    require"../Home/account_function/customer_readProfile.php";
    require"../Home/account_function/customer_updateProfile.php";
    $customer_name = isset($_SESSION['customer_name']) ? trim($_SESSION['customer_name']) : '';

    function get_space_details($pdo, $space) {
        $results = $pdo->query("SELECT name FROM myfirstdatabase.space WHERE spaceID = $space");
        return $results->fetch();
    }
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

    <section id="table_layout">
        <table>
            <h5 class="tableName">Space Booking Record</h5>
            <thead>
                <tr>
                    <th>Booked Space</th>
                    <th>Event Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * FROM booking where userID=$userID";
                    $pdoQuery_run= $pdo->query($sql);
                    if ($pdoQuery_run) {
                        while ($row= $pdoQuery_run->fetch(PDO::FETCH_OBJ)) {
                            $booking_id = $row->bookingID;
                            $space_id = $row->spaceID;
                            $event_name= $row->eventName;
                            $event_start_date= $row->eventStartDate;
                            $event_end_date= $row->eventEndDate;
                            $space_details = get_space_details($pdo, $space_id);
                            $space_name = $space_details['name']; ?>

                            <tr>
                                <td data-label="Booked Space"><?php echo $space_name; ?></td>
                                <td data-label="Event Name"><?php echo $event_name; ?></td>
                                <td data-label="Start Date"><?php echo $event_start_date; ?></td>
                                <td data-label="End Date"><?php echo $event_end_date; ?></td>
                                <td data-label=""><a href="../Booking/booking_details.php?bookingID=<?php echo $booking_id; ?>">Edit</a></td>
                            </tr>
                <?php }}
                    else {
                        echo 'not found data';
                    }

                    //check if there is space booked by user
                    $countRecord="SELECT * FROM booking WHERE userID=$userID";
                    $countRecord= $pdo->query($countRecord);
                    $countRecord=$countRecord->rowCount();
                    
                    if($countRecord<1) { ?>
                        <tr>
                            <td colspan="6">No booked space.</td>
                        </tr>
                <?php }
                ?>
            </tbody>
        </table>

        <br><br><br>
        <table>
            <h5 class="tableName">Favourite Space Record</h5>
            <thead>
                <tr>
                    <th>Favourite Space</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT s.spaceID, userID, favouriteID, name 
                    FROM space s, favourite f 
                    WHERE s.spaceID = f.spaceID AND f.userID = $userID 
                    ORDER BY favouriteID DESC;";
                    $pdoQuery_run= $pdo->query($sql);
                    if ($pdoQuery_run) {
                        while ($row= $pdoQuery_run->fetch(PDO::FETCH_OBJ)) {                    
                            $name= $row->name;
                            $userID= $row->userID;
                            $favouriteID = $row->favouriteID; 
                            $space_id = $row->spaceID; ?>

                            <tr>
                                <td><a href="../Spaces/space_detail_logic.php?spaceID=<?php echo $space_id; ?>"><?php echo $name; ?></a></td>
                                <td id="icon_td" onclick="deleteFav(<?php echo $favouriteID; ?>)"><i class="fa fa-trash" ></i></td>
                            </tr>
                <?php }}
                    else{
                        echo 'not found data';
                    }

                    //check if there is favourite space added by customer
                    $countRecordFav="SELECT * FROM favourite WHERE userID=$userID";
                    $countRecordFav= $pdo->query($countRecordFav);
                    $countRecordFav=$countRecordFav->rowCount();
                
                    if($countRecordFav<1){ ?>
                        <tr>
                            <td colspan="3">No Favourite Space Added</td>
                        </tr>
                <?php }
                ?>
            </tbody>
        </table>
    </section>
    <br><br><br>

    <footer id="footer"></footer> 

    <script type="text/javascript" src="../Home/javascript/profileFunction.js"></script>
</body>

</html>