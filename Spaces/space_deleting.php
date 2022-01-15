<?php
session_start();
ob_start();
$ownerID = isset($_SESSION['ownerID']) ? trim($_SESSION['ownerID']) : '';
require_once "../Home/config.php";

$get_space_id = isset($_GET['spaceID']) ? trim($_GET['spaceID']) : '';

// To prevent user from changing the url to delete other spaces
$check_space_query = "SELECT spaceID FROM myfirstdatabase.space WHERE ownerID = $ownerID";
$check_results = $pdo->query($check_space_query)->fetchAll(PDO::FETCH_COLUMN, 0);
if (!in_array($get_space_id, $check_results, false)) {
    header("location: ../Home/owner_profile.php");
    ob_end_flush();
    exit();
}

if (!empty($get_space_id)) {
    $delete_space_query = "DELETE FROM myfirstdatabase.space WHERE spaceID = $get_space_id";
    $delete_exec = $pdo->prepare($delete_space_query)->execute();

    $delete_msg = "";
    if ($delete_exec) {
        $delete_msg = "Space deleted successfully";
    } else {
        $delete_msg = "Space is not deleted. Please try again.";
    }

    header('Location: ../Home/owner_profile.php?query_msg="'.$delete_msg.'"');
    exit();
}

?>