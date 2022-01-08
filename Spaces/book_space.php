<?php

session_start();
require_once "../Home/config.php";

function select_slot_id($pdo, $space) {
    $results = $pdo->query("SELECT availableSlotID FROM myfirstdatabase.available_slot WHERE spaceID = $space");
    return $results->fetch();
}

function formatDate($date) {
    $day = substr($date, 0, 2);
    $month = substr($date, 3, 2);
    $year = substr($date, 6, 4);
    return $year . "-" . $month . "-" . $day;
}

$booking_date = date("Y-m-d H:i:s");
$space_id = $_POST["spaceId"];
$user_id = isset($_SESSION['userID']) ? trim($_SESSION['userID']) : '';
$slot_detail = select_slot_id($pdo, $space_id);
$slot_id = $slot_detail['availableSlotID'];
$event_name = $_POST["eventName"];
$start_date = $_POST["startDate"];
$event_start_date = formatDate($start_date);
$end_date = $_POST["endDate"];
$event_end_date = formatDate($end_date);
$guest_number = $_POST["guestNumber"];
$event_type_id = "1"; // still under development phase
$total_price = $_POST["eventTotalPrice"];
$card_number = $_POST["cardNumber"];
$card_expiry_date = $_POST["cardExpiryDate"];
$card_CVV = $_POST["cardCVVNumber"];
$card_holder_name = $_POST["cardHolderName"];

echo $event_start_date;

$pdoQuery="INSERT INTO booking(bookingDate, spaceID, userID, slotID, eventName, eventStartDate, eventEndDate, guestNumber, eventTypeID, totalPrice, cardNumber, cardExpiryDate, cardCVV, cardHolderName) VALUES (:booking_date, :space_id, :user_id, :slot_id, :event_name, :event_start_date, :event_end_date, :guest_number, :event_type_id, :total_price, :card_number, :card_expiry_date, :card_CVV, :card_holder_name)";
$pdoQuery_run= $pdo->prepare($pdoQuery);
$pdoQuery_exec= $pdoQuery_run->execute(array(":booking_date"=>$booking_date,":space_id"=>$space_id, ":user_id"=>$user_id, ":slot_id"=>$slot_id, ":event_name"=>$event_name, ":event_start_date"=>$event_start_date, ":event_end_date"=>$event_end_date, ":guest_number"=>$guest_number, ":event_type_id"=>$event_type_id, ":total_price"=>$total_price, ":card_number"=>$card_number, ":card_expiry_date"=>$card_expiry_date, ":card_CVV"=>$card_CVV, ":card_holder_name"=>$card_holder_name));

if ($pdoQuery_exec){
        header("location: /Home/customer_profile.php");
}
