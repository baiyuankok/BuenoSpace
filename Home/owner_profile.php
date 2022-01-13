<?php
    // Connect to database
    require_once "config.php";
    session_start();
    require "../Home/account_function/owner_readProfile.php";
    require "../Home/account_function/owner_updateProfile.php";
    $owner_name = isset($_SESSION['owner_name']) ? trim($_SESSION['owner_name']) : '';
    $query_msg = isset($_GET['query_msg']) ? trim($_GET['query_msg']) : '';
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

    <!-- <br><br><br> -->
    <section id="table_layout">
        <br><br>
        <table>
            <h5 class="tableName">Space Listing <i class="fas fa-plus" style="display: inline; cursor:pointer; margin-left: 10px" onclick="addSpace()"></i><span id="query-result-msg" style="margin-left: 10px; display: inline; opacity: 0.85;"><?php echo $query_msg; ?></span></h5>
            <thead>
                <tr>
                    <th>Space</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
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
                            $name= $row->name; 
                            $space_id = $row->spaceID; ?>

                            <tr>
                                <td data-label="Name"><?php echo $name; ?></td>
                                <td data-label=""><a href="../Spaces/space_listing.php?spaceID=<?php echo $space_id; ?>">Edit</a></td>
                                <td data-label=""><a href="../Spaces/space_deleting.php?spaceID=<?php echo $space_id; ?>">Delete</a></td>
                            </tr>
                <?php }}
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
                <?php }
                ?>
            </tbody>
        </table>
        <br><br><br>
        <?php
            $sql = "SELECT * FROM space where ownerID=$ownerID";
            $pdoQuery_run= $pdo->query($sql);
            if ($pdoQuery_run) {
                $bookingDateJSONid = 1;
                $bookingDatesArray = array();
                while ($row= $pdoQuery_run->fetch(PDO::FETCH_OBJ)) {
                    $space_id = $row->spaceID;
                    $space_name = $row->name;
                    $sqlBookingDate = "SELECT eventName, eventStartDate, eventEndDate FROM booking where spaceID=$space_id";
                    $pdoQuery_run2= $pdo->query($sqlBookingDate);
                    if ($pdoQuery_run2) {
                        while ($row= $pdoQuery_run2->fetch(PDO::FETCH_OBJ)) {
                            $event_name=$row->eventName;
                            $event_start_date=$row->eventStartDate;
                            $event_end_date=$row->eventEndDate;
                            $eachArray = array(
                                "id" => $bookingDateJSONid,
                                "name" => $event_name,
                                "description" => $space_name,
                                "date" => [$event_start_date, $event_end_date],
                                "type" => "event"
                            );
                            array_push($bookingDatesArray, $eachArray);
                            $bookingDateJSONid++;
                        }
                    }
                }
            }
        ?>
        <div id="calendarSection" style="display: inline-block;">
            <div id="calendar"></div>
        </div>
        
    </section>
    <br><br><br>
    
    <footer id="footer"></footer> 

    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script> -->
    <script src="../Home/javascript/evo-calendar.min.js"></script>
    <script type="text/javascript" src="../Home/javascript/profileFunction.js"></script>

    <script>
        $(document).ready(function() {
            
            var bookingDates = <?php echo json_encode($bookingDatesArray); ?>;
            console.log(bookingDates);

            $('#calendar').evoCalendar({
                theme:"Midnight Blue",
                format: "yyyy-mm-dd",
                eventHeaderFormat: "dd MM, yyyy",
                // calendarEvents: [{
                //     id: 'bHay68s', // Event's ID (required)
                //     name: "New Year", // Event name (required)
                //     date: "January/1/2022", // Event date (required)
                //     type: "holiday", // Event type (required)
                // },
                // {
                //     id: 'bHay68d', // Event's ID (required)
                //     name: "Vacation Leave",
                //     badge: "02/13 - 02/15", // Event badge (optional)
                //     // date: ["February/13/2022", "February/15/2022"], // Date range
                //     date: ["2022-01-28", "2022-01-29"],
                //     type: "event",
                //     color: "#63d867" // Event custom color (optional)
                // }
                // ]
                calendarEvents: bookingDates
            })
        })
    </script>
</body>

</html>