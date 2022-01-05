<?php
session_start();
require_once "../Home/config.php";
require "../Spaces/space_listing_page.html";
// Get ownerID
$ownerID = isset($_SESSION['ownerID']) ? trim($_SESSION['ownerID']) : '';
// Get spaceID
$get_space_id = isset($_GET['spaceID']) ? trim($_GET['spaceID']) : '';

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
    for ($i = 0; $i < count($event_results); $i++) {
        $event_type_id = $event_results[$i];
        $event_name = $pdo->query("SELECT `type` FROM myfirstdatabase.event_type WHERE event_type_ID = $event_type_id")->fetch();

        echo '<script>
            var allSelect = document.querySelectorAll("#space-event-section select");
            var allOptions = allSelect['.$i.'].getElementsByTagName("option");
            for (var eachOption of allOptions) {
                if (eachOption.innerHTML == "'.$event_name['type'].'") {
                    eachOption.selected = "true";
                }
            }
        </script>';

        if ($i < count($event_results) - 1) {
            echo '<script>
                    document.getElementById("more-events").click();
                </script>';
        }
    }

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
    
} else {    // List Space
    echo '<script>
        document.getElementById("page-title").innerHTML = "List Your Space";
    </script>';
}

?>
