<?php
session_start();
// Check if the owner is already logged in, if yes then redirect owner to owner profile page
if (isset($_SESSION["owner_loggedin"]) && $_SESSION["owner_loggedin"] === true) {
    header("Location: ../Home/owner_profile.php");
    exit();
}
// Connect to database
require_once "config.php";
require "../Home/main_page.html";

function select_images_id($pdo, $space) {
    $results = $pdo->query("SELECT imgID FROM myfirstdatabase.space_image WHERE spaceID = $space");
    return $results->fetchAll(PDO::FETCH_COLUMN, 0);
}

function select_details($pdo, $space) {
    $results = $pdo->query("SELECT `name`, `location`, `price` FROM myfirstdatabase.space WHERE spaceID = $space");
    return $results->fetch();
}

function select_events($pdo) {
    $results = $pdo->query("SELECT `type` FROM myfirstdatabase.event_type");
    return $results->fetchAll(PDO::FETCH_COLUMN, 0);
}

function select_locations($pdo) {
    $results = $pdo->query("SELECT DISTINCT location FROM myfirstdatabase.space");
    return $results->fetchAll(PDO::FETCH_COLUMN, 0);
}

function appendOption($element_id, $each_option) {
    echo '<script>
        var optionEle = document.createElement("option");
        optionEle.value = "' . $each_option . '";
        optionEle.text = "' . $each_option . '";
        document.getElementById("' . $element_id . '").add(optionEle);
    </script>';
}

// Select popular spaces
$select_space = "SELECT spaceID, count(*) FROM myfirstdatabase.booking GROUP BY spaceID ORDER BY count(*) DESC LIMIT 3";
$space_id = $pdo->query($select_space)->fetchAll(PDO::FETCH_COLUMN, 0);

// If the space from booking table is less than 3
if (count($space_id) == 0) {
    $none_space_result = $pdo->query("SELECT spaceID FROM myfirstdatabase.space LIMIT 3")->fetchAll(PDO::FETCH_COLUMN, 0);
    $space_id = array_merge($space_id, $none_space_result);
} else {
    if (count($space_id) < 3) {
        $more_space_id = "SELECT spaceID FROM myfirstdatabase.space WHERE spaceID NOT IN (";
        foreach ($space_id as $each_more_space) {
            $more_space_id .= $each_more_space . ",";
        }
        $more_space_id = substr_replace($more_space_id, ")", -1);
        // Calculate the number of space left to be rendered
        $left_space = 3 - count($space_id);
        $more_space_id .= " LIMIT " . $left_space;
        // Get the remaining spaces to be rendered
        $more_space_result = $pdo->query($more_space_id)->fetchAll(PDO::FETCH_COLUMN, 0);
        $space_id = array_merge($space_id, $more_space_result);
    }
}
// Select space images and details
$counter = 1;
foreach ($space_id as $each_space) {
    $img_id = select_images_id($pdo, $each_space);
    echo '<script>
            var imgSec = document.getElementById("space-image-' . $counter . '");
            imgSec.classList.add("database-space-id-' . $each_space . '");
            var imgEle = document.createElement("IMG");
            imgEle.setAttribute("data-src", "image.php?id=' . $img_id[0] . '");
            imgEle.classList.add("each-img");
            imgEle.alt = "space-' . $counter . '-1";
            imgSec.appendChild(imgEle);
        </script>';
    
    $each_detail = select_details($pdo, $each_space);
    echo '<script>
            document.getElementById("space-name-' . $counter . '").innerHTML = "' . $each_detail['name'] . '";
            document.getElementById("space-location-' . $counter . '").innerHTML = "' . $each_detail['location'] . '";
            document.getElementById("space-price-' . $counter . '").innerHTML = "' . $each_detail['price'] . '";
        </script>';
    $counter += 1;
}

// Select event types and location
$all_events = select_events($pdo);
$all_locations = select_locations($pdo);
foreach ($all_events as $each_event) {
    appendOption("events-select", $each_event);
}

foreach ($all_locations as $each_location) {
    appendOption("location-select", $each_location);
}

?>