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

$space_id = $_POST["spaceID"];
$selected_date = $_POST["exemptionDate"];
$exemption_date = formatDate($selected_date);
if (isset($_POST['checkOffDay'])) {
    $check_off_day = false;
}
else {
    $check_off_day = true;
}

$sqlExemptionSlot = "SELECT exemptionSlotID, exemptionDate FROM exemption_slot WHERE spaceID = $space_id";
$sqlExemptionSlot= $pdo->query($sqlExemptionSlot);
if ($sqlExemptionSlot) {
    $isExemptionDateFound = false;
    while ($row= $sqlExemptionSlot->fetch(PDO::FETCH_OBJ)) {
        $exemption_slot_id = $row->exemptionSlotID;
        $existing_exemption_date = $row->exemptionDate;

        // Have exisiting exemption date
        if ($exemption_date == $existing_exemption_date) {
            $pdoQuery="UPDATE exemption_slot SET availability=:check_off_day WHERE exemptionSlotID=$exemption_slot_id";
            $pdoQuery_run= $pdo->prepare($pdoQuery);
            $pdoQuery_exec= $pdoQuery_run->execute(array(":check_off_day"=>$check_off_day));
            $isExemptionDateFound = true;

            if ($pdoQuery_exec){
                header("location: ../Home/owner_profile.php");
            }
            else {
                $query_result_msg = "Server error...";
                displayMsg($space_id, $query_result_msg);
            }
        }
    }

    // No exisiting exemption date
    if (!$isExemptionDateFound) {
        $pdoQuery="INSERT INTO exemption_slot(spaceID, exemptionDate, availability) VALUES (:space_id, :exemption_date, :check_off_day)";
        $pdoQuery_run= $pdo->prepare($pdoQuery);
        $pdoQuery_exec= $pdoQuery_run->execute(array(":space_id"=>$space_id, ":exemption_date"=>$exemption_date, ":check_off_day"=>$check_off_day));

        if ($pdoQuery_exec){
                header("location: ../Home/owner_profile.php");
        }
        else {
            $query_result_msg = "Server error...";
            displayMsg($space_id, $query_result_msg);
        }
    }
}
else {
    $query_result_msg = "Server error...";
    displayMsg($space_id, $query_result_msg);
}
