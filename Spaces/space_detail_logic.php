<?php
session_start();
require_once "../Home/config.php";
require "../Spaces/space_detail_page.html";

$userID = isset($_SESSION['userID']) ? trim($_SESSION['userID']) : '';
$customer_name = isset($_SESSION['customer_name']) ? trim($_SESSION['customer_name']) : ''; 
$query_msg = isset($_GET['query_msg']) ? trim($_GET['query_msg']) : '';
$get_space_id = isset($_GET['spaceID']) ? trim($_GET['spaceID']) : '';
$_SESSION["recentURL"] = htmlspecialchars($_SERVER['REQUEST_URI']);
// To keep track the existing review posted by current user
$existing_review = False;

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

function add_review_content($element_id, $user_name, $comment) {
    echo '<script>
            var reviewSec = document.getElementById("' . $element_id . '");
            var pNameEle = document.createElement("P");
            pNameEle.innerHTML = "' . $user_name . '";
            pNameEle.classList.add("user-name");
            reviewSec.appendChild(pNameEle);

            var pReviewEle = document.createElement("P");
            pReviewEle.innerHTML = "' . $comment . '";
            pReviewEle.classList.add("review-content");
            reviewSec.appendChild(pReviewEle);
        </script>';
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
        setFavReview(true);
        document.getElementById("current-user").innerHTML = "' . $customer_name . '";

        window.addEventListener("beforeunload", function (event) {
            delete event["returnValue"];
            var favI = document.getElementById("favourite-icon");
            var spaceID = ' . $get_space_id . ';
            var userID = ' . $userID . ';
            var updateType = 0;
            if (favI.classList.contains("fas")) {
                updateType = 1;
            } else {
                updateType = -1;
            }
            fetch("../Spaces/update_favourite.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body:  `updateType=${updateType}&spaceID=${spaceID}&userID=${userID}`
            });

            var comment = "";
            if (document.getElementById("posted-review-section").style.display != "none") {
                var reviewText = document.getElementById("current-review").innerHTML;
                if (reviewText != "") {
                    updateType = 1;
                    comment = reviewText;
                } else {
                    updateType = -1;
                }
            } else {
                updateType = -1;
            }
            fetch("../Spaces/update_review.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body:  `updateType=${updateType}&spaceID=${spaceID}&userID=${userID}&comment=${comment}`
            });
        });
    </script>';
    
    // Set the favourite icon according to the user favourite table if user has logged in
    $set_favourite = $pdo->query('SELECT favouriteID FROM myfirstdatabase.favourite WHERE spaceID = ' .$get_space_id. ' AND userID = ' .$userID)->fetchAll(PDO::FETCH_COLUMN, 0);
    if (count($set_favourite) != 0) {
        echo '<script>
            var fI = document.getElementById("favourite-icon");
            fI.classList.remove("far");
            fI.classList.add("fas");
        </script>';
    }

    $set_review = $pdo->query('SELECT comment FROM myfirstdatabase.review WHERE spaceID = ' . $get_space_id . ' AND userID = ' . $userID)->fetchAll(PDO::FETCH_COLUMN, 0);
    if (count($set_review) != 0) {
        $existing_review = True;
        echo '<script>
            document.getElementById("space-review").value = "' . $set_review[0] . '";
            postReview();
        </script>';
    }
}
else {
    echo '<script>
        document.getElementById("rentButton").innerText = "Sign In & Rent";
        setFavReview(false);
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
$select_total_favourite = $pdo->query('SELECT COUNT(*) AS total_favourite FROM myfirstdatabase.favourite WHERE spaceID = ' .$get_space_id)->fetch();
echo '<script>
    document.getElementById("name").innerHTML = "'.$each_detail['name'].'";
    document.getElementById("location").innerHTML = "'.$each_detail['location'].'";
    document.getElementById("price").innerHTML = "'.$each_detail['price'].'";
    document.getElementById("capacity").innerHTML = "'.$each_detail['capacity'].'";
    document.getElementById("staticBackdropLabel").innerHTML = "'.$each_detail['name'].'";
    document.getElementById("space-id").value = '.$get_space_id.';
    document.getElementById("total-favourite").innerHTML = '.$select_total_favourite['total_favourite'].';
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
    if (!$existing_review || $user_id != $userID) {
        $user_name = $pdo->query("SELECT customer_name FROM myfirstdatabase.customer WHERE userID = $user_id")->fetch()['customer_name'];
        $comment = $row['comment'];
        $element_id = "more-review-content";
        add_review_content($element_id, $user_name, $comment);
    }
}
echo '<script>
    document.getElementById("review-num").innerHTML = "'.$total_count.'";
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

if(!empty($query_msg)) {
    echo '<script>
        document.getElementById("errorMessage").innerHTML = "'.$query_msg.'";
    </script>';
}

?>