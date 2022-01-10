<?php
session_start();
require_once "../Home/config.php";
require "../Spaces/space_detail_page.html";

$userID = isset($_SESSION['userID']) ? trim($_SESSION['userID']) : '';
$get_space_id = $_GET['spaceID'];
$_SESSION["recentURL"] = htmlspecialchars($_SERVER['REQUEST_URI']);

function select_images_id($pdo, $space) {
    $results = $pdo->query("SELECT imgID FROM myfirstdatabase.space_image WHERE spaceID = $space");
    return $results->fetchAll(PDO::FETCH_COLUMN, 0);
}

function select_details($pdo, $space) {
    $results = $pdo->query("SELECT `name`, `location`, `price`, `capacity` FROM myfirstdatabase.space WHERE spaceID = $space");
    return $results->fetch();
}

function select_event_type($pdo, $space) {
    $results = $pdo->query("SELECT event_type_ID FROM myfirstdatabase.event_space_type WHERE space_ID = $space");
    $fetched_event = $results->fetchAll(PDO::FETCH_COLUMN, 0);
    $get_event_query = "SELECT `type` FROM myfirstdatabase.event_type WHERE event_type_ID IN (";
    foreach ($fetched_event as $each_event) {
        $get_event_query .= $each_event.",";
    }
    $get_event_query = substr_replace($get_event_query, ")", -1);
    $event_results = $pdo->query($get_event_query);
    return $event_results->fetchAll(PDO::FETCH_COLUMN, 0);
}

function select_available_slot($pdo, $space) {
    $results = $pdo->query("SELECT availableMonday, availableTuesday, availableWednesday, availableThursday, availableFriday, availableSaturday, availableSunday FROM myfirstdatabase.available_slot WHERE spaceID = $space");
    return $results->fetch();
}

function createRange($start, $end, $format = 'd-m-Y') {
    $start  = new DateTime($start);
    $end    = new DateTime($end);
    $invert = $start > $end;

    $dates = array();
    $dates[] = $start->format($format);
    while ($start != $end) {
        $start->modify(($invert ? '-' : '+') . '1 day');
        $dates[] = $start->format($format);
    }
    return $dates;
}


if(isset($_SESSION["customer_loggedin"]) && $_SESSION["customer_loggedin"] === true){
    echo '<script>
        document.getElementById("rentButton").innerText = "Rent";
    </script>';
}
else {
    echo '<script>
        document.getElementById("rentButton").innerText = "Sign In & Rent";
    </script>';
}

$img_id = select_images_id($pdo, $get_space_id);
$img_counter = 1;
foreach ($img_id as $each_img) {
    if ($img_counter == 1) {
        echo '<script>
            document.getElementById("images").setAttribute("src", "../Home/image.php?id='.$each_img.'");
        </script>';
    }
    echo '<script>
        var imgSec = document.getElementById("all-image-samples");
        var imgEle = document.createElement("IMG");
        imgEle.setAttribute("data-src", "../Home/image.php?id='.$each_img.'");
        imgEle.alt = "space-img-'.$img_counter.'";
        imgSec.appendChild(imgEle);
    </script>';
    $img_counter += 1;
}
echo '<script>showImg()</script>';

$each_detail = select_details($pdo, $get_space_id);
echo '<script>
    document.getElementById("name").innerHTML = "'.$each_detail['name'].'";
    document.getElementById("location").innerHTML = "'.$each_detail['location'].'";
    document.getElementById("price").innerHTML = "'.$each_detail['price'].'";
    document.getElementById("capacity").innerHTML = "'.$each_detail['capacity'].'";
    document.getElementById("staticBackdropLabel").innerHTML = "'.$each_detail['name'].'";
    document.getElementById("space-id").value = '.$get_space_id.';
</script>';

$space_event_type = select_event_type($pdo, $get_space_id);
foreach ($space_event_type as $each_space_event) {
    echo '<script>
        var pEle = document.createElement("P");
        pEle.innerHTML = "'.$each_space_event.'";
        document.getElementById("event-type").appendChild(pEle);
    </script>';
}

$user_review = $pdo->query("SELECT comment, userID FROM myfirstdatabase.review WHERE spaceID = $get_space_id");
$total_count = 0;
while ($row = $user_review->fetch()) {
    $total_count += 1;
    $user_id = $row['userID'];
    $user_name = $pdo->query("SELECT customer_name FROM myfirstdatabase.customer WHERE userID = $user_id")->fetch();
    echo '<script>
        var reviewSec = document.getElementById("review-content-section");
        var pNameEle = document.createElement("P");
        pNameEle.innerHTML = "'.$user_name['customer_name'].'";
        pNameEle.classList.add("user-name");
        reviewSec.appendChild(pNameEle);

        var pReviewEle = document.createElement("P");
        pReviewEle.innerHTML = "'.$row['comment'].'";
        pReviewEle.classList.add("review-content");
        reviewSec.appendChild(pReviewEle);
    </script>';
}
echo '<script>
    document.getElementById("review-num").innerHTML = "'.$total_count.'";
    showReviews();
</script>';


$space_available_slot = select_available_slot($pdo, $get_space_id);
echo '<script>
        const availableSlots = [];
</script>';
if($space_available_slot['availableSunday'] == FALSE) {
    echo '<script>
        availableSlots.push("0");
    </script>';
}
if($space_available_slot['availableMonday'] == FALSE) {
    echo '<script>
        availableSlots.push("1");
    </script>';
}
if($space_available_slot['availableTuesday'] == FALSE) {
    echo '<script>
        availableSlots.push("2");
    </script>';
}
if($space_available_slot['availableWednesday'] == FALSE) {
    echo '<script>
        availableSlots.push("3");
    </script>';
}
if($space_available_slot['availableThursday'] == FALSE) {
    echo '<script>
        availableSlots.push("4");
    </script>';
}
if($space_available_slot['availableFriday'] == FALSE) {
    echo '<script>
        availableSlots.push("5");
    </script>';
}
if($space_available_slot['availableSaturday'] == FALSE) {
    echo '<script>
        availableSlots.push("6");
    </script>';
}


$sqlAllEventType = "SELECT * FROM myfirstdatabase.event_type";
$sqlAllEventType= $pdo->query($sqlAllEventType);
if ($sqlAllEventType) {
    echo '<script>
        const allEventTypes = {};
    </script>';
    while ($row= $sqlAllEventType->fetch(PDO::FETCH_OBJ)) {
        $event_type_id=$row->event_type_ID;
        $event_type_name=$row->type;
        echo '<script>
            allEventTypes["'.$event_type_id.'"] = "'.$event_type_name.'";
        </script>';
    }
}

$sqlSpaceBookedDates = "SELECT eventStartDate, eventEndDate FROM myfirstdatabase.booking WHERE spaceID = $get_space_id";
$sqlSpaceBookedDates= $pdo->query($sqlSpaceBookedDates);
if ($sqlSpaceBookedDates) {
    echo '<script>
        const disabledDates = [];
    </script>';
    while ($row= $sqlSpaceBookedDates->fetch(PDO::FETCH_OBJ)) {
        $event_start_date=$row->eventStartDate;
        $event_end_date=$row->eventEndDate;
        $space_booked_dates = createRange($event_start_date, $event_end_date);
        foreach ($space_booked_dates as $each_booked_date) {
            echo '<script>
                disabledDates.push("'.$each_booked_date.'");
            </script>';
        }
    }
}

?>