<?php

session_start();
require_once "../Home/config.php";

function displayMsg($space, $msg) {
    header("Location: ../Spaces/space_exemption.php?spaceID=".$space."&query_msg=".$msg);
    exit();
}

function formatDate($date) {
    $day = substr($date, 0, 2);
    $month = substr($date, 3, 2);
    $year = substr($date, 6, 4);
    return $year . "-" . $month . "-" . $day;
}

function createRange($start, $end, $format = 'Y-m-d') {
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

function findCommonElement($array1, $array2) {
    for($i = 0; $i < count($array1); $i++) {
        for($j = 0; $j < count($array2); $j++) {
            if($array1[$i] == $array2[$j]) {
                return true;
            }
        }
    }
    return false;
}

$space_id = $_POST["spaceID"];
$booking_id = $_POST["bookingID"];
$selected_date = $_POST["exemptionDate"];
$exemption_date = formatDate($selected_date);
$check_off_day = $_POST["checkOffDay"];
echo $check_off_day;

// $sqlSpaceBookedDates = "SELECT eventStartDate, eventEndDate FROM booking WHERE spaceID = $space_id";
// $sqlSpaceBookedDates= $pdo->query($sqlSpaceBookedDates);
// if ($sqlSpaceBookedDates) {
//     while ($row= $sqlSpaceBookedDates->fetch(PDO::FETCH_OBJ)) {
//         $booking_start_date=$row->eventStartDate;
//         $booking_end_date=$row->eventEndDate;
//         $space_booked_dates = createRange($booking_start_date, $booking_end_date);
//         $event_booked_dates = createRange($event_start_date, $event_end_date);
//         if (findCommonElement($event_booked_dates, $space_booked_dates)) {
//             $query_result_msg = "The space was booked in chosen date range!";
//             displayMsg($space_id, $query_result_msg);
//         }
//     }
    
//     $pdoQuery="INSERT INTO booking(bookingDate, spaceID, userID, slotID, eventName, eventStartDate, eventEndDate, guestNumber, eventTypeID, totalPrice, cardNumber, cardExpiryDate, cardCVV, cardHolderName) VALUES (:booking_date, :space_id, :user_id, :slot_id, :event_name, :event_start_date, :event_end_date, :guest_number, :event_type_id, :total_price, :card_number, :card_expiry_date, :card_CVV, :card_holder_name)";
//     $pdoQuery_run= $pdo->prepare($pdoQuery);
//     $pdoQuery_exec= $pdoQuery_run->execute(array(":booking_date"=>$booking_date,":space_id"=>$space_id, ":user_id"=>$user_id, ":slot_id"=>$slot_id, ":event_name"=>$event_name, ":event_start_date"=>$event_start_date, ":event_end_date"=>$event_end_date, ":guest_number"=>$guest_number, ":event_type_id"=>$event_type_id, ":total_price"=>$total_price, ":card_number"=>$card_number, ":card_expiry_date"=>$card_expiry_date, ":card_CVV"=>$card_CVV, ":card_holder_name"=>$card_holder_name));

//     if ($pdoQuery_exec){
//             header("location: ../Home/customer_profile.php");
//     }
// }
// else {
//     $query_result_msg = "Server error...";
//     displayMsg($space_id, $query_result_msg);
// }
