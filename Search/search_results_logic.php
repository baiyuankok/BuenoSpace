<?php

//Connect to database
require_once "../Home/config.php"
require "../Search/search_results_page.html";

// $get_space_id = $_GET['spaceID'];

// TOD0: modify the below line to become a search query
$select_space = "SELECT spaceID FROM myfirstdatabase.space LIMIT 6";
$space_id = $pdo->query($select_space)->fetchAll(PDO::FETCH_COLUMN, 0);


function select_images_id($pdo, $space) {
    $results = $pdo->query("SELECT imgID FROM myfirstdatabase.space_image WHERE spaceID = $space");
    return $results->fetchAll(PDO::FETCH_COLUMN, 0);
}

function select_details($pdo, $space) {
    $results = $pdo->query("SELECT `name`, `location`, `price`, `capacity` FROM myfirstdatabase.space WHERE spaceID = $space");
    return $results->fetch();
}

$counter = 1;
foreach($space_id as $each_space) {
    // $img_id = select_images_id($pdo, $each_space);
    // $img_counter = 1;
    // foreach()
    $each_detail = select_details($pdo, $each_space);
    echo '<script>
            document.getElementById("space-name-'.$counter.'").innerHTML = "'.$each_detail['name'].'";
            document.getElementById("space-location-'.$counter.'").innerHTML = "'.$each_detail['location'].'";
            document.getElementById("space-price-'.$counter.'").innerHTML = "'.$each_detail['price'].'";
            document.getElementById("space-capacity-'.$counter.'").innerHTML = "'.$each_detail['capacity'].'";
        </script>';
    $counter +=1;
}

?>