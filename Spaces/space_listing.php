<?php
session_start();
ob_start();
require_once "../Home/config.php";
require "../Spaces/space_listing_page.html";
// Get ownerID
$ownerID = isset($_SESSION['ownerID']) ? trim($_SESSION['ownerID']) : '';
// Get spaceID
$get_space_id = isset($_GET['spaceID']) ? trim($_GET['spaceID']) : '';

// To prevent user from changing the url to view/edit other spaces
if (!empty($get_space_id)) {
    $check_space_query = "SELECT spaceID FROM myfirstdatabase.space WHERE ownerID = $ownerID";
    $check_results = $pdo->query($check_space_query)->fetchAll(PDO::FETCH_COLUMN, 0);
    if (!in_array($get_space_id, $check_results, false)) {
        header("location: ../Home/owner_profile.php");
        ob_end_flush();
        exit();
    }
}

function setEventDropdown($pdo) {
    $all_event_type = $pdo->query("SELECT * FROM myfirstdatabase.event_type");
    while ($each_event_type = $all_event_type->fetch()) {
        $each_event = trim($each_event_type['type']);
        echo '<script>
            var eachOption = document.createElement("option");
            eachOption.value = "'.$each_event.'";
            eachOption.text = "'.$each_event.'";
            document.getElementById("one-event").add(eachOption);
        </script>';
    }
}
setEventDropdown($pdo);

// If spaceID exists, edit the space
if (!empty($get_space_id)) {
    $select_info = "SELECT * FROM myfirstdatabase.space WHERE ownerID = $ownerID AND spaceID = $get_space_id";
    $info_results = $pdo->query($select_info)->fetch();

    echo '<script>
        document.getElementById("page-title").innerHTML = "Update Your Space";
        document.getElementById("space-id").value = '.$get_space_id.';
        document.getElementById("space-name").value = "'.$info_results['name'].'";
        document.getElementById("space-location").value = "'.$info_results['location'].'";
        document.getElementById("space-price").value = '.$info_results['price'].';
        document.getElementById("space-capacity").value = '.$info_results['capacity'].';
    </script>';

    $select_event = "SELECT event_type_ID FROM myfirstdatabase.event_space_type WHERE space_ID = $get_space_id";
    $event_results = $pdo->query($select_event)->fetchAll(PDO::FETCH_COLUMN, 0);
    $all_event_str = "";
    for ($i = 0; $i < count($event_results); $i++) {
        $event_type_id = $event_results[$i];
        $event_name = $pdo->query("SELECT `type` FROM myfirstdatabase.event_type WHERE event_type_ID = $event_type_id")->fetch();

        $all_event_str .= "\"" . $event_name['type'] . "\",";
    }
    echo '<script>
        $("#one-event").val(['.$all_event_str.']);
        $("#one-event").trigger("change");
    </script>';

    $select_image = "SELECT imgID FROM myfirstdatabase.space_image WHERE spaceID = $get_space_id";
    $image_results = $pdo->query($select_image)->fetchAll(PDO::FETCH_COLUMN, 0);
    $image_counter = 0;
    foreach ($image_results as $image_row) {
        echo '<script>
            var allImagePreview = document.querySelectorAll(".image-preview");
            allImagePreview['.$image_counter.'].childNodes[0].setAttribute("data-src", "../Home/image.php?id='.$image_row.'");
            allImagePreview['.$image_counter.'].childNodes[0].alt = "image-'.$image_counter.'";

            removeUploadBtn(allImagePreview['.$image_counter.']);
            addImagePreview();
        </script>';
        $image_counter += 1;
    }

    $select_available_slot = "SELECT * FROM myfirstdatabase.available_slot WHERE spaceID = $get_space_id";
    $available_slot_results = $pdo->query($select_available_slot)->fetch();
    echo '<script>
        document.getElementById("sunday-box").checked = ' . $available_slot_results['availableSunday'] . ';
        document.getElementById("monday-box").checked = ' . $available_slot_results['availableMonday'] . ';
        document.getElementById("tuesday-box").checked = ' . $available_slot_results['availableTuesday'] . ';
        document.getElementById("wednesday-box").checked = ' . $available_slot_results['availableWednesday'] . ';
        document.getElementById("thursday-box").checked = ' . $available_slot_results['availableThursday'] . ';
        document.getElementById("friday-box").checked = ' . $available_slot_results['availableFriday'] . ';
        document.getElementById("saturday-box").checked = ' . $available_slot_results['availableSaturday'] . ';
        ifAllChecked();
    </script>';
    
} else {    // List Space
    echo '<script>
        document.getElementById("page-title").innerHTML = "List Your Space";
    </script>';
}

?>
