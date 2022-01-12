<?php
    session_start();
    require_once "../Home/config.php";
    require "./booking_details_page.html";

    $userID = isset($_SESSION['userID']) ? trim($_SESSION['userID']) : '';
    $booking_id = isset($_GET['bookingID']) ? trim($_GET['bookingID']) : '';
    $query_msg = isset($_GET['query_msg']) ? trim($_GET['query_msg']) : '';

    function select_details($pdo, $space) {
        $results = $pdo->query("SELECT price, capacity FROM myfirstdatabase.space WHERE spaceID = $space");
        return $results->fetch();
    }

    function formatDate($date) {
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);
        return $day . "-" . $month . "-" . $year;
    }

    // Obtain Booking Data
    if (!empty($booking_id)) {
        $select_info = "SELECT * FROM myfirstdatabase.booking WHERE bookingID = $booking_id";
        $info_results = $pdo->query($select_info)->fetch();
        if ($userID == $info_results['userID']) {
            $spaceID = $info_results['spaceID'];
            $bookingStartDate = $info_results['eventStartDate'];
            $bookingEndDate = $info_results['eventEndDate'];
            echo '<script>
                document.getElementById("page-title").innerHTML = "Update Booking Info";
                document.getElementById("booking-ID").value = '.$booking_id.';
                document.getElementById("event-name").value = "'.$info_results['eventName'].'";
                const eventTypeValue = "'.$info_results['eventTypeID'].'"
                document.getElementById("start-date").value = "'.formatDate($bookingStartDate).'";
                document.getElementById("end-date").value = "'.formatDate($bookingEndDate).'";
                document.getElementById("guest-number").value = "'.$info_results['guestNumber'].'";
            </script>';
    
            // Space Details
            $space_detail = select_details($pdo, $spaceID);
             echo '<script>
                const spaceCapacity = "'.$space_detail['capacity'].'";
            </script>';
    
            // Event Type
            $sqlAllEventType = "SELECT * FROM myfirstdatabase.event_type";
            $sqlAllEventType= $pdo->query($sqlAllEventType);
            if ($sqlAllEventType) {
                echo '<script>
                    const allEventTypes = {};
                </script>';
                while ($row= $sqlAllEventType->fetch(PDO::FETCH_OBJ)) {
                    $event_type_id=$row->event_type_ID;
                    $event_type_name=$row->type;
                    echo '<script>
                        allEventTypes["'.$event_type_id.'"] = "'.$event_type_name.'";
                    </script>';
                }
            }
    
            if(!empty($query_msg)) {
                echo '<script>
                    document.getElementById("errorMessage").innerHTML = "'.$query_msg.'";
                </script>';
            }
        }
        else {
            header("location: ../Home/customer_profile.php");
            die();
        }
    }

?>
