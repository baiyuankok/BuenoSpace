<?php

//Connect to database
require_once "../Home/config.php";

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

//Search queries v2
if($event_type == "all-events") {
    $query = "SELECT DISTINCT spaceID FROM myfirstdatabase.space ";
    if($location != "all") {
        if($one_filter_added) { $query .= "AND "; } else {$query .= "WHERE "; }
        
        if(is_array($location)) {
            $query .= "location = '$location[0]'";
            if(count($location) > 1) {
                foreach (range(1, count($location)-1) as $i) {
                    $query .= "OR location = '$location[$i]'";
                }
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
        $query = "SELECT DISTINCT spaceID 
                FROM myfirstdatabase.space t1
                INNER JOIN myfirstdatabase.event_space_type t2
                ON t1.spaceID = t2.space_ID
                INNER JOIN myfirstdatabase.event_type t3
                ON t2.event_type_ID = t3.event_type_ID WHERE t3.type = '$event_type[0]' ";
        
        if(count($event_type) > 1) {
            foreach (range(1, count($event_type)-1) as $i) {
                $query .= "OR t3.type = '$event_type[$i]' ";
            }
        }
    }
    else {
        $query = "SELECT DISTINCT spaceID 
                FROM myfirstdatabase.space t1
                INNER JOIN myfirstdatabase.event_space_type t2
                ON t1.spaceID = t2.space_ID
                INNER JOIN myfirstdatabase.event_type t3
                ON t2.event_type_ID = t3.event_type_ID WHERE t3.type = '$event_type' ";
    }
    if($location != "all") {
        if(is_array($location)) {
            $query .= "AND t1.location = '$location[0]'";
            if(count($location) > 1) {
                foreach (range(1, count($location)-1) as $i) {
                    $query .= "OR t1.location = '$location[$i]'";
                }
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

echo "<p>" . $min_price . "</p>";
echo "<p>" . $max_price . "</p>";



?>
