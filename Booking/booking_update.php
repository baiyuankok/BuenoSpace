<?php

session_start();
require_once "../Home/config.php";

function displayMsg($booking_id, $msg) {
    header("Location: ../Booking/booking_details.php?bookingID=".$booking_id."&query_msg=".$msg);
    exit();
}

$update_booking_date = date("Y-m-d H:i:s");
$booking_id = $_POST["bookingID"];
$event_name = $_POST["eventName"];
$guest_number = $_POST["guestNumber"];
$event_type_id = $_POST["selectedEventType"];

if (!empty($booking_id)) {
    $pdoQuery="UPDATE booking SET updateBookingDate=:update_booking_date, eventName=:event_name, guestNumber=:guest_number, eventTypeID=:event_type_id WHERE bookingID=$booking_id";
    $pdoQuery_run= $pdo->prepare($pdoQuery);
    $pdoQuery_exec= $pdoQuery_run->execute(array(":update_booking_date"=>$update_booking_date, ":event_name"=>$event_name, ":guest_number"=>$guest_number, ":event_type_id"=>$event_type_id));

    if ($pdoQuery_exec){
            header("location: ../Home/customer_profile.php");
    }
    else {
        $query_result_msg = "Server error...";
        displayMsg($booking_id, $query_result_msg);
    }
}
