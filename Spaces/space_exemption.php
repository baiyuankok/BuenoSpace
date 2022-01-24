<?php
    session_start();
    ob_start();
    require_once "../Home/config.php";
    require "./space_exemption.html";

    $ownerID = isset($_SESSION['ownerID']) ? trim($_SESSION['ownerID']) : '';
    $space_id = isset($_GET['spaceID']) ? trim($_GET['spaceID']) : '';
    $query_msg = isset($_GET['query_msg']) ? trim($_GET['query_msg']) : '';

    // To prevent user from changing the url to view/edit other spaces
    if (!empty($space_id)) {
        $check_space_query = "SELECT spaceID FROM myfirstdatabase.space WHERE ownerID = $ownerID";
        $check_results = $pdo->query($check_space_query)->fetchAll(PDO::FETCH_COLUMN, 0);
        if (!in_array($space_id, $check_results, false)) {
            header("location: ../Home/owner_profile.php");
            ob_end_flush();
            exit();
        }
    }

    function select_details($pdo, $space) {
        $results = $pdo->query("SELECT name FROM myfirstdatabase.space WHERE spaceID = $space");
        return $results->fetch();
    }

    function createRange($start, $end, $format = 'd-m-Y') {
        $start  = new DateTime($start);
        $end    = new DateTime($end);
        $invert = $start > $end;
    
        $dates = array();
        $dates[] = $start->format($format);
        while ($start != $end) {
            $start->modify(($invert ? '-' : '+') . '1 day');
            $dates[] = $start->format($format);
        }
        return $dates;
    }

    function formatDate($date) {
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);
        return $day . "-" . $month . "-" . $year;
    }

    if (!empty($space_id)) {
        // Obtain Space Details
        $space_detail = select_details($pdo, $space_id);
        echo '<script>
            document.getElementById("space-name").value = "'.$space_detail['name'].'";
            document.getElementById("space-ID").value = "'.$space_id.'";
        </script>';

        // Obtain Space's Slot Data
        $select_slot_info = "SELECT * FROM myfirstdatabase.available_slot WHERE spaceID = $space_id";
        $slot_info_results = $pdo->query($select_slot_info)->fetch();
        if ($slot_info_results) {
            echo '<script>
                const availableSlots = [];
            </script>';
            if($slot_info_results['availableSunday'] == FALSE) {
                echo '<script>
                    availableSlots.push("0");
                </script>';
            }
            if($slot_info_results['availableMonday'] == FALSE) {
                echo '<script>
                    availableSlots.push("1");
                </script>';
            }
            if($slot_info_results['availableTuesday'] == FALSE) {
                echo '<script>
                    availableSlots.push("2");
                </script>';
            }
            if($slot_info_results['availableWednesday'] == FALSE) {
                echo '<script>
                    availableSlots.push("3");
                </script>';
            }
            if($slot_info_results['availableThursday'] == FALSE) {
                echo '<script>
                    availableSlots.push("4");
                </script>';
            }
            if($slot_info_results['availableFriday'] == FALSE) {
                echo '<script>
                    availableSlots.push("5");
                </script>';
            }
            if($slot_info_results['availableSaturday'] == FALSE) {
                echo '<script>
                    availableSlots.push("6");
                </script>';
            }
        }
       
        // Obtain Space's Booking Data
        $sqlSpaceBookedDates = "SELECT eventStartDate, eventEndDate FROM myfirstdatabase.booking WHERE spaceID = $space_id";
        $sqlSpaceBookedDates= $pdo->query($sqlSpaceBookedDates);
        if ($sqlSpaceBookedDates) {
            echo '<script>
                const bookedDates = [];
            </script>';
            while ($row= $sqlSpaceBookedDates->fetch(PDO::FETCH_OBJ)) {
                $event_start_date=$row->eventStartDate;
                $event_end_date=$row->eventEndDate;
                $space_booked_dates = createRange($event_start_date, $event_end_date);
                foreach ($space_booked_dates as $each_booked_date) {
                    echo '<script>
                        bookedDates.push("'.$each_booked_date.'");
                    </script>';
                }
            }
        }

        // Obtain Space's Exemption Date
        $sqlSpaceExemptionDates = "SELECT exemptionDate, availability FROM myfirstdatabase.exemption_slot WHERE spaceID = $space_id";
        $sqlSpaceExemptionDates= $pdo->query($sqlSpaceExemptionDates);
        if ($sqlSpaceExemptionDates) {
            echo '<script>
                const exemptionDates = [];
                const exemptionDatesAvailability = [];
            </script>';
            while ($row= $sqlSpaceExemptionDates->fetch(PDO::FETCH_OBJ)) {
                $exemption_date=$row->exemptionDate;
                $availability=$row->availability;
                $exemption_date = formatDate($exemption_date);
                echo '<script>
                    exemptionDates.push("'.$exemption_date.'");
                    exemptionDatesAvailability.push("'.$availability.'");
                </script>';
            }
        }

        if(!empty($query_msg)) {
            echo '<script>
                document.getElementById("errorMessage").innerHTML = "'.$query_msg.'";
            </script>';
        }
    }

?>
