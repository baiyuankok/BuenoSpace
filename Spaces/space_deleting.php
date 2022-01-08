<?php



require_once "../Home/config.php";

$get_space_id = isset($_GET['spaceID']) ? trim($_GET['spaceID']) : '';

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