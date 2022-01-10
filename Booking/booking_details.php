<?php
    session_start();
    require_once "../Home/config.php";
    require "./booking_details_page.html";

    $userID = isset($_SESSION['userID']) ? trim($_SESSION['userID']) : '';
    $booking_id = isset($_GET['bookingID']) ? trim($_GET['bookingID']) : '';

    function select_details($pdo, $space) {
        $results = $pdo->query("SELECT price FROM myfirstdatabase.space WHERE spaceID = $space");
        return $results->fetch();
    }

    function select_available_slot($pdo, $space) {
        $results = $pdo->query("SELECT availableMonday, availableTuesday, availableWednesday, availableThursday, availableFriday, availableSaturday, availableSunday FROM myfirstdatabase.available_slot WHERE spaceID = $space");
        return $results->fetch();
    }

    function formatDate($date) {
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);
        return $day . "-" . $month . "-" . $year;
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

    // Obtain Booking Data
    if (!empty($booking_id)) {
        $select_info = "SELECT * FROM myfirstdatabase.booking WHERE bookingID = $booking_id";
        $info_results = $pdo->query($select_info)->fetch();
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
            document.getElementById("totalPaidPrice").innerHTML = "'.$info_results['totalPrice'].'";
        </script>';

        // Space Details
        $space_detail = select_details($pdo, $spaceID);
        echo '<script>
            const pricePerDay = "'.$space_detail['price'].'";
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

        // Available Slot
        $space_available_slot = select_available_slot($pdo, $spaceID);
        echo '<script>
                const availableSlots = [];
        </script>';
        if($space_available_slot['availableSunday'] == FALSE) {
            echo '<script>
                availableSlots.push("0");
            </script>';
        }
        if($space_available_slot['availableMonday'] == FALSE) {
            echo '<script>
                availableSlots.push("1");
            </script>';
        }
        if($space_available_slot['availableTuesday'] == FALSE) {
            echo '<script>
                availableSlots.push("2");
            </script>';
        }
        if($space_available_slot['availableWednesday'] == FALSE) {
            echo '<script>
                availableSlots.push("3");
            </script>';
        }
        if($space_available_slot['availableThursday'] == FALSE) {
            echo '<script>
                availableSlots.push("4");
            </script>';
        }
        if($space_available_slot['availableFriday'] == FALSE) {
            echo '<script>
                availableSlots.push("5");
            </script>';
        }
        if($space_available_slot['availableSaturday'] == FALSE) {
            echo '<script>
                availableSlots.push("6");
            </script>';
        }

        // Event Dates
        $sqlSpaceBookedDates = "SELECT eventStartDate, eventEndDate FROM myfirstdatabase.booking WHERE spaceID = $spaceID";
        $sqlSpaceBookedDates= $pdo->query($sqlSpaceBookedDates);
        if ($sqlSpaceBookedDates) {
            echo '<script>
                const disabledDates = [];
            </script>';
            while ($row= $sqlSpaceBookedDates->fetch(PDO::FETCH_OBJ)) {
                $event_start_date=$row->eventStartDate;
                $event_end_date=$row->eventEndDate;
                if ($bookingStartDate != $event_start_date && $bookingEndDate != $event_end_date) {
                    $space_booked_dates = createRange($event_start_date, $event_end_date);
                    foreach ($space_booked_dates as $each_booked_date) {
                        echo '<script>
                            disabledDates.push("'.$each_booked_date.'");
                        </script>';
                    }
                }
                else {
                    echo '<script>
                        const originalStartDate = "'.formatDate($bookingStartDate).'";
                        const originalEndDate = "'.formatDate($bookingEndDate).'";
                    </script>';
                }
            }
        }
    }

?>
