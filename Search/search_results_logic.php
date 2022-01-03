<?php

//Connect to database
require_once "../Home/config.php";
require "../Search/search_results_page.html";

//Test to see if search attributes were received from main page
echo $_POST["events"];
echo '<html><br></html>';
echo $_POST["location"];
echo '<html><br></html>';
echo $_POST["min_price"];
echo '<html><br></html>';
echo $_POST["max_price"];
echo '<html><br></html>';
echo $_POST["capacity"];
echo '<html><br></html>';

//Get event type, location, minimum price (if it exists), maximum price (if it exists) and capacity (if it exists)
$event_type = $_POST["events"];
$location = $_POST["location"];
if (isset($_POST["min_price"])) {
    $min_price = $_POST["min_price"];
} else $min_price = NULL;
if (isset($_POST["max_price"])) {
    $max_price = $_POST["max_price"];
} else $max_price = NULL;
if (isset($_POST["capacity"])) {
    $capacity = $_POST["capacity"] ;
} else $capacity = NULL;

//Test to see if variables were initialized
echo $event_type;
echo '<html><br></html>';
echo $location;
echo '<html><br></html>';
echo $min_price;
echo '<html><br></html>';
echo $max_price;
echo '<html><br></html>';
echo $capacity;


//Get all the space IDs
//Default: if no location or event type is selected, display all
$select_space = "SELECT spaceID FROM myfirstdatabase.space LIMIT 6";
$space_id = $pdo->query($select_space)->fetchAll(PDO::FETCH_COLUMN, 0);

//Search queries
if ($location != "all") {
    //If location is selected
    $query = "SELECT spaceID FROM myfirstdatabase.space WHERE location = '$location'";
    $space_id = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);
}
elseif ($event_type != "all-events") {
    //If event type is selected
    $query = "SELECT spaceID 
    FROM myfirstdatabase.space t1
    INNER JOIN myfirstdatabase.event_space_type t2
    ON t1.spaceID = t2.space_ID
    INNER JOIN myfirstdatabase.event_type t3
    ON t2.event_type_ID = t3.event_type_ID
    WHERE t3.type = '$event_type'";
    $space_id = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);
}
elseif (($location != "all")&&($event_type != "all-events")) {
    //If location & event type are selected
    $query = "SELECT spaceID 
    FROM myfirstdatabase.space t1
    INNER JOIN myfirstdatabase.event_space_type t2
    ON t1.spaceID = t2.space_ID
    INNER JOIN myfirstdatabase.event_type t3
    ON t2.event_type_ID = t3.event_type_ID
    WHERE t1.location = '$location'
    AND t3.type = '$event_type'";
    $space_id = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);
}
elseif (($location != "all")&&($event_type != "all-events")&&($min_price != NULL)&&($capacity != NULL)) {
    //If location, event type, price & capacity are selected
    $query = "SELECT spaceID 
    FROM myfirstdatabase.space t1
    INNER JOIN myfirstdatabase.event_space_type t2
    ON t1.spaceID = t2.space_ID
    INNER JOIN myfirstdatabase.event_type t3
    ON t2.event_type_ID = t3.event_type_ID
    WHERE t1.location = '$location'
    AND t3.type = '$event_type'
    AND t1.price BETWEEN $min_price AND $max_price
    AND t1.capacity BETWEEN $capacity-5 AND $capacity+5";
    $space_id = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);
}
elseif (($location == "all")&&($event_type == "all-events")&&($min_price != NULL)&&($capacity != NULL)) {
    //If price & capacity are selected
    $query = "SELECT spaceID 
    FROM myfirstdatabase.space
    WHERE price BETWEEN $min_price AND $max_price
    AND capacity BETWEEN $capacity-5 AND $capacity+5";
    $space_id = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);
}


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
    $img_id = select_images_id($pdo, $each_space);
    $img_counter = 1;
    foreach($img_id as $each_img) {
        echo '<script>
            var imgSec = document.getElementById("space-image-'.$counter.'");
            imgSec.classList.add("database-space-id-'.$each_space.'");
            var imgEle = document.createElement("IMG");
            imgEle.setAttribute("data-src", "../Home/image.php?id='.$each_img.'");
            imgEle.classList.add("each-img");
            imgEle.alt = "space-'.$counter.'-'.$img_counter.'";
            imgSec.appendChild(imgEle);
        </script>';
        $img_counter += 1;
    }



    $each_detail = select_details($pdo, $each_space);
    echo '<script>
            document.getElementById("space-name-'.$counter.'").innerHTML = "'.$each_detail['name'].'";
            document.getElementById("space-location-'.$counter.'").innerHTML = "'.$each_detail['location'].'";
            document.getElementById("space-price-'.$counter.'").innerHTML = "'.$each_detail['price'].'";
            document.getElementById("space-capacity-'.$counter.'").innerHTML = "'.$each_detail['capacity'].'";
        </script>';
    $counter += 1;
}

?>