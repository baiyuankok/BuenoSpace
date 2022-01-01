<?php
// Connect to database
<<<<<<< HEAD
require_once "config.php";

session_start();
 // Check if the owner is already logged in, if yes then redirect owner to owner profile page
 if(isset($_SESSION["owner_loggedin"]) && $_SESSION["owner_loggedin"] === true){
            
    header("location: owner_profile.php");
    exit;
}
=======
require_once "../Home/config.php";
>>>>>>> 91f74b1cd71a6f7a35ae1f39652b7d69450a32b0
require "../Home/main_page.html";

function select_images_id($pdo, $space) {
    $results = $pdo->query("SELECT imgID FROM myfirstdatabase.space_image WHERE spaceID = $space");
    return $results->fetchAll(PDO::FETCH_COLUMN, 0);
}

function select_details($pdo, $space) {
    $results = $pdo->query("SELECT `name`, `location`, `price` FROM myfirstdatabase.space WHERE spaceID = $space");
    return $results->fetch();
}

// Select popular spaces
$select_space = "SELECT spaceID, count(*) FROM myfirstdatabase.booking GROUP BY spaceID ORDER BY count(*) DESC LIMIT 3";
$space_id = $pdo->query($select_space)->fetchAll(PDO::FETCH_COLUMN, 0);

// If the space from booking table is less than 3
if (count($space_id) < 3) {
    $more_space_id = "SELECT spaceID FROM myfirstdatabase.space WHERE spaceID NOT IN (";
    foreach ($space_id as $each_more_space) {
        $more_space_id .= $each_more_space.",";
    }
    $more_space_id = substr_replace($more_space_id, ")", -1);
    // Calculate the number of space left to be rendered
    $left_space = 3 - count($space_id);
    $more_space_id .= " LIMIT ".$left_space;
    // Get the remaining spaces to be rendered
    $more_space_result = $pdo->query($more_space_id)->fetchAll(PDO::FETCH_COLUMN, 0);
    $space_id = array_merge($space_id, $more_space_result);
}
// Select space images and details
$counter = 1;
foreach($space_id as $each_space) {
    $img_id = select_images_id($pdo, $each_space);
    $img_counter = 1;
    foreach ($img_id as $each_img) {
        echo '<script>
                var imgSec = document.getElementById("space-image-'.$counter.'");
                imgSec.classList.add("database-space-id-'.$each_space.'");
                var imgEle = document.createElement("IMG");
                imgEle.setAttribute("data-src", "image.php?id='.$each_img.'");
                imgEle.classList.add("each-img");
                imgEle.alt = "space-'.$counter.'-'.$img_counter.'";
                imgSec.appendChild(imgEle);
            </script>';
        $img_counter += 1;
    }
    echo '<script>showDivs("'.$counter.'-space");</script>';
    $each_detail = select_details($pdo, $each_space);
    echo '<script>
            document.getElementById("space-name-'.$counter.'").innerHTML = "'.$each_detail['name'].'";
            document.getElementById("space-location-'.$counter.'").innerHTML = "'.$each_detail['location'].'";
            document.getElementById("space-price-'.$counter.'").innerHTML = "'.$each_detail['price'].'";
        </script>';
    $counter += 1;
}

?>