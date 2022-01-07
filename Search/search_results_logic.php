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
if (isset($_POST["events"])) {
    $event_type = $_POST["events"];
} else $event_type = "all-events";

if (isset($_POST["location"])) {
    $location = $_POST["location"];
} else $location = "all";

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

//Put a "tick" in the checkboxes
if (is_array($event_type)) {
    foreach (range(0, count($event_type)-1) as $i) {
        echo '<script>
            for (let j=0; j < document.getElementsByName("events[]").length; j++) {
                if(document.getElementsByName("events[]")[j].value == "'.$event_type[$i].'") {
                    document.getElementsByName("events[]")[j].checked = true;
                }
            }
        </script>';
    }
}
else {
    echo '<script>
            for (let j=0; j < document.getElementsByName("events[]").length; j++) {
                if(document.getElementsByName("events[]")[j].value == "'.$event_type.'") {
                    document.getElementsByName("events[]")[j].checked = true;
                }
            }
        </script>';
}

if (is_array($location)) {
    foreach (range(0, count($location)-1) as $i) {
        echo '<script>
            for (let j=0; j < document.getElementsByName("location[]").length; j++) {
                if(document.getElementsByName("location[]")[j].value == "'.$location[$i].'") {
                    document.getElementsByName("location[]")[j].checked = true;
                }
            }
        </script>';
    }
}
else {
    echo '<script>
            for (let j=0; j < document.getElementsByName("location[]").length; j++) {
                if(document.getElementsByName("location[]")[j].value == "'.$location.'") {
                    document.getElementsByName("location[]")[j].checked = true;
                }
            }
        </script>';
}



//Flag to check if a filter has been added
$one_filter_added = False;

//Search queries v2
if($event_type == "all-events") {
    $query = "SELECT spaceID FROM myfirstdatabase.space ";
    if($location != "all") {
        if($one_filter_added) { $query .= "AND "; } else {$query .= "WHERE "; }
        
        if(is_array($location)) {
            $query .= "location = '$location[0]'";
            foreach (range(1, count($location)-1) as $i) {
                $query .= "OR location = '$location[$i]'";
            }
        }
        else {$query .= "location = '$location'";}
        $one_filter_added = True;
    }
    if($min_price != NULL) {
        if($one_filter_added) { $query .= "AND "; } else {$query .= "WHERE "; }
        $query .= "price BETWEEN $min_price AND $max_price";
        $one_filter_added = True;
    }
    if($capacity != NULL) {
        if($one_filter_added) { $query .= "AND "; } else {$query .= "WHERE "; }
        $query .= "capacity BETWEEN $capacity-5 AND $capacity+5";
        $one_filter_added = True;
    }
}
elseif ($event_type != "all-events") {
    if(is_array($event_type)) {
        $query = "SELECT spaceID 
                FROM myfirstdatabase.space t1
                INNER JOIN myfirstdatabase.event_space_type t2
                ON t1.spaceID = t2.space_ID
                INNER JOIN myfirstdatabase.event_type t3
                ON t2.event_type_ID = t3.event_type_ID WHERE t3.type = '$event_type[0]' ";
        echo '<script> console.log("More than 1 event type!") </script>';
        foreach (range(1, count($event_type)-1) as $i) {
            $query .= "OR t3.type = '$event_type[$i]' ";
        }
        //BUG: when filter is clicked a second time, the system sends in an empty value plus the current value
    }
    else {
        $query = "SELECT spaceID 
                FROM myfirstdatabase.space t1
                INNER JOIN myfirstdatabase.event_space_type t2
                ON t1.spaceID = t2.space_ID
                INNER JOIN myfirstdatabase.event_type t3
                ON t2.event_type_ID = t3.event_type_ID WHERE t3.type = '$event_type' ";
    }
    if($location != "all") {
        // $query .= "AND t1.location = '$location[0]'";
        // if(is_array($location)) {
        //     echo '<script> console.log("More than 1 location!") </script>';
        //     foreach (range(1, count($location)-1) as $i) {
        //         $query .= "OR location = '$location[$i]'";
        //     }
        // }

        if(is_array($location)) {
            $query .= "AND t1.location = '$location[0]'";
            foreach (range(1, count($location)-1) as $i) {
                $query .= "OR t1.location = '$location[$i]'";
            }
        }
        else {$query .= "AND t1.location = '$location'";}
    }
    if($min_price != NULL) {
        $query .= "AND t1.price BETWEEN $min_price AND $max_price";
    }
    if($capacity != NULL) {
        $query .= "AND t1.capacity BETWEEN $capacity-5 AND $capacity+5";
    }  
}
//Get the IDs of all spaces that match the search criteria
echo '<script> console.log("'.$query.'"); </script>';
$space_id = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);


//Get the IDs of all the images for one space (from the database)
function select_images_id($pdo, $space) {
    $results = $pdo->query("SELECT imgID FROM myfirstdatabase.space_image WHERE spaceID = $space");
    return $results->fetchAll(PDO::FETCH_COLUMN, 0);
}

//Get the details of one space (from the database)
function select_details($pdo, $space) {
    $results = $pdo->query("SELECT `name`, `location`, `price`, `capacity` FROM myfirstdatabase.space WHERE spaceID = $space");
    return $results->fetch();
}

//TODO: Display "Sorry! We couldn't find any spaces." if no space ID is returned

if(count($space_id) == 0) {
    echo '<script>
            resultList = document.getElementById("search-result-container");
            resultList.style.justifyContent = "center";
            resultList.style.margin = "auto";
            resultList.style.height = "700px";
            resultList.insertAdjacentHTML(`beforeend`, 
                `<div>Sorry! We couldn\'t find any spaces.</div>`
            );
        </script>';
}

//For each space that is found...
$counter = 1;
foreach($space_id as $each_space) {
    $img_id = select_images_id($pdo, $each_space);
    //Create a list item
    echo '<script>
            console.log("'.$each_space.'");
            resultList = document.getElementById("search-result-container");
            resultList.insertAdjacentHTML(`beforeend`, 
            `<li class="space-box">
                <div class="space-image" id="space-image-'.$counter.'"></div>
                <p class="space-name" id="space-name-'.$counter.'">Name</p>
                <p class="space-location" id="space-location-'.$counter.'">Location</p>
                <div>
                    <span class="space-price" id="space-price-'.$counter.'">Price</span>
                    <span class="space-capacity" id="space-capacity-'.$counter.'">Capacity</span>
                </div>
            </li>`);
        </script>';

    //Load the space image
    echo '<script>
            var imgSec = document.getElementById("space-image-' . $counter . '");
            imgSec.classList.add("database-space-id-' . $each_space . '");
            var imgEle = document.createElement("IMG");
            imgEle.setAttribute("data-src", "image.php?id=' . $img_id[0] . '");
            imgEle.classList.add("each-img");
            imgEle.alt = "space-' . $counter . '-1";
            imgSec.appendChild(imgEle);
        </script>';

    //Load the space details from database
    $each_detail = select_details($pdo, $each_space);
    echo '<script>
            document.getElementById("space-name-'.$counter.'").innerHTML = "'.$each_detail['name'].'";
            document.getElementById("space-location-'.$counter.'").innerHTML = "'.$each_detail['location'].'";
            document.getElementById("space-price-'.$counter.'").innerHTML = "'.$each_detail['price'].'";
            document.getElementById("space-capacity-'.$counter.'").innerHTML = "'.$each_detail['capacity'].'";
        </script>';
    $counter += 1;
}

//TODO: Click on space image to link to selected space_details page

// Search queries v1
// if ($location != "all") {
//     //If location is selected
//     $query = "SELECT spaceID FROM myfirstdatabase.space WHERE location = '$location'";
//     $space_id = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);
// }
// elseif ($event_type != "all-events") {
//     //If event type is selected
//     $query = "SELECT spaceID 
//     FROM myfirstdatabase.space t1
//     INNER JOIN myfirstdatabase.event_space_type t2
//     ON t1.spaceID = t2.space_ID
//     INNER JOIN myfirstdatabase.event_type t3
//     ON t2.event_type_ID = t3.event_type_ID
//     WHERE t3.type = '$event_type'";
//     $space_id = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);
// }
// elseif (($location != "all")&&($event_type != "all-events")) {
//     //If location & event type are selected
//     $query = "SELECT spaceID 
//     FROM myfirstdatabase.space t1
//     INNER JOIN myfirstdatabase.event_space_type t2
//     ON t1.spaceID = t2.space_ID
//     INNER JOIN myfirstdatabase.event_type t3
//     ON t2.event_type_ID = t3.event_type_ID
//     WHERE t1.location = '$location'
//     AND t3.type = '$event_type'";
//     $space_id = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);
// }
// elseif (($location != "all")&&($event_type != "all-events")&&($min_price != NULL)&&($capacity != NULL)) {
//     //If location, event type, price & capacity are selected
//     $query = "SELECT spaceID 
//     FROM myfirstdatabase.space t1
//     INNER JOIN myfirstdatabase.event_space_type t2
//     ON t1.spaceID = t2.space_ID
//     INNER JOIN myfirstdatabase.event_type t3
//     ON t2.event_type_ID = t3.event_type_ID
//     WHERE t1.location = '$location'
//     AND t3.type = '$event_type'
//     AND t1.price BETWEEN $min_price AND $max_price
//     AND t1.capacity BETWEEN $capacity-5 AND $capacity+5";
//     $space_id = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);
// }
// elseif (($location == "all")&&($event_type == "all-events")&&($min_price != NULL)&&($capacity != NULL)) {
//     //If price & capacity are selected
//     $query = "SELECT spaceID 
//     FROM myfirstdatabase.space
//     WHERE price BETWEEN $min_price AND $max_price
//     AND capacity BETWEEN $capacity-5 AND $capacity+5";
//     $space_id = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN, 0);
// }

?>