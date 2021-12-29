<?php
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

// Select popular spaces
$select_space = "SELECT spaceID, count(*) FROM myfirstdatabase.favourite GROUP BY spaceID ORDER BY count(*) DESC LIMIT 3";
$space_id = $pdo->query($select_space)->fetchAll(PDO::FETCH_COLUMN, 0);
// Select space images and details
$counter = 1;
foreach($space_id as $each_space) {
    $img_id = select_images_id($pdo, $each_space);
    $img_counter = 1;
    foreach ($img_id as $each_img) {
        echo '<script>
                var imgSec = document.getElementById("space-image-'.$counter.'");
                var imgEle = document.createElement("IMG");
                imgEle.src = "image.php?id='.$each_img.'";
                imgEle.classList.add("each-img");
                imgEle.alt = "space-'.$counter.'-'.$img_counter.'";
                imgEle.setAttribute("loading", "lazy");
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