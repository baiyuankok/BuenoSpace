<?php

session_start();
$ownerID = isset($_SESSION['ownerID']) ? trim($_SESSION['ownerID']) : '';
require_once "../Home/config.php";

function displayMsg($msg) {
    header('Location: ../Home/owner_profile.php?query_msg="' . $msg . '"');
    exit();
}

if (isset(($_POST['edit_space_detail']))) {

    if (empty(trim($_POST["space-id"]))) {
        $space_id_err = "Do not have space ID.";
    }

    if (empty(trim($_POST["space-name"]))) {
        $space_name_err = "Please enter a valid space name.";
    }

    if (empty(trim($_POST["space-location"]))) {
        $space_location_err = "Please enter a valid space location.";
    }

    if (empty(trim($_POST["space-price"]))) {
        $space_price_err = "Please enter a valid space price.";
    }

    if (empty(trim($_POST["space-capacity"]))) {
        $space_capacity_err = "Please enter a valid space capacity.";
    }

    if (empty($_POST["event"])) {
        $space_event_err = "Please select at least one event for the space.";
    }

    $space_id = $_POST["space-id"];
    $space_name = $_POST["space-name"];
    $space_location = $_POST["space-location"];
    $space_price = $_POST["space-price"];
    $space_capacity = $_POST["space-capacity"];
    $space_event = $_POST['event'];

    // To get the ID of existing images that are being removed during space editing
    $remove_img = isset($_POST['removeImage']) ? $_POST['removeImage'] : '';

    // If spaceID is set, it indicates updating database
    if (empty($space_id_err)) {
        if (empty($space_name_err) && empty($space_location_err) && empty($space_price_err) && empty($space_capacity_err) && empty($space_event_err)) {
            // Update to particular row according to spaceID and ownerID
            $update_query = "UPDATE myfirstdatabase.space SET name=:space_name, location=:space_location, price=:space_price, capacity=:space_capacity WHERE ownerID = $ownerID AND spaceID = $space_id";
            $update_query_run = $pdo->prepare($update_query);
            $update_query_exec = $update_query_run->execute(array(":space_name" => $space_name, ":space_location" => $space_location, ":space_price" => $space_price, ":space_capacity" => $space_capacity));

            // Delete the rows in event table
            $delete_events = $pdo->query("DELETE FROM myfirstdatabase.event_space_type WHERE space_ID = $space_id");
            // Insert new rows to event table
            $insert_new_events = "";
            foreach ($space_event as $each_event) {
                $select_event_id = $pdo->query('SELECT event_type_ID FROM myfirstdatabase.event_type WHERE type = "' . $each_event . '"')->fetch();
                $insert_new_events .= 'INSERT INTO myfirstdatabase.event_space_type(event_type_ID, space_ID) VALUES (' . $select_event_id['event_type_ID'] . ', ' . $space_id . ');';
            }
            $insert_event_exec = $pdo->prepare($insert_new_events)->execute();

            $query_result_msg = "";
            if ($update_query_exec && $insert_event_exec) {
                $query_result_msg .= "Space details";

                // Delete the existing space images
                if (!empty($remove_img)) {
                    foreach ($remove_img as $each_remove_img) {
                        $delete_images = $pdo->query('DELETE FROM myfirstdatabase.space_image WHERE imgID = ' . $each_remove_img);
                    }
                }
                if (count($_FILES["images"]) > 0) {
                    // Insert new images
                    $insert_image_sql = "";
                    foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
                        if (is_uploaded_file($_FILES["images"]["tmp_name"][$key])) {
                            // Get image data and properties
                            $imgData = addslashes(file_get_contents($_FILES["images"]["tmp_name"][$key]));
                            $imageProperties = getimagesize($_FILES["images"]["tmp_name"][$key]);
                            $insert_image_sql .= "INSERT INTO myfirstdatabase.space_image(`image`, image_type, spaceID) VALUES ('{$imgData}', '{$imageProperties['mime']}', {$space_id});";
                        }
                    }
                    if (!empty($insert_image_sql)) {
                        $insert_image_exec = $pdo->prepare($insert_image_sql)->execute();
                        
                        if ($insert_event_exec) {
                            $query_result_msg .= " and images are updated successfully.";
                        } else {
                            $query_result_msg .= " are updated, but images are not uploaded. Please try again.";
                        }
                    } else {
                        $query_result_msg .= " are updated successfully.";
                    }
                }
            } else {
                $query_result_msg .= "Space details and images are not updated. Please try again.";
            }

            displayMsg($query_result_msg);
        } else {
            displayMsg("Space details and images are not updated. Please try again.");
        }
    } else { // List new spaces
        if (empty($space_name_err) && empty($space_location_err) && empty($space_price_err) && empty($space_capacity_err) && empty($space_event_err)) {
            // Insert basic space details
            $insert_space_details = 'INSERT INTO myfirstdatabase.space(name, location, price, capacity, ownerID) VALUES ("' . $space_name . '", "' . $space_location . '", ' . $space_price . ', ' . $space_capacity . ', ' . $ownerID . ');';
            $insert_details_exec = $pdo->prepare($insert_space_details)->execute();

            $select_new_space_id = 'SELECT MAX(spaceID) AS new_spaceID FROM myfirstdatabase.space WHERE ownerID = ' . $ownerID;
            $space_id = $pdo->query($select_new_space_id)->fetch()['new_spaceID'];
            // Insert space event type
            $insert_space_event = '';
            foreach ($space_event as $each_event) {
                $select_event_id = $pdo->query('SELECT event_type_ID FROM myfirstdatabase.event_type WHERE type = "' . $each_event . '"')->fetch();
                $insert_space_event .= 'INSERT INTO myfirstdatabase.event_space_type(event_type_ID, space_ID) VALUES (' . $select_event_id['event_type_ID'] . ', ' . $space_id . ');';
            }
            $insert_space_event_exec = $pdo->prepare($insert_space_event)->execute();

            // Insert space images
            $insert_query_result_msg = "";
            if ($insert_details_exec && $insert_space_event_exec) {
                $insert_query_result_msg .= "Space details";

                if (count($_FILES["images"]) > 0) {
                    // Insert new images
                    $insert_space_image = "";
                    foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
                        if (is_uploaded_file($_FILES["images"]["tmp_name"][$key])) {
                            // Get image data and properties
                            $imgData = addslashes(file_get_contents($_FILES["images"]["tmp_name"][$key]));
                            $imageProperties = getimagesize($_FILES["images"]["tmp_name"][$key]);

                            $insert_space_image .= "INSERT INTO myfirstdatabase.space_image(`image`, image_type, spaceID) VALUES ('{$imgData}', '{$imageProperties['mime']}', {$space_id});";
                        }
                    }
                    if (!empty($insert_space_image)) {
                        $insert_space_image_exec = $pdo->prepare($insert_space_image)->execute();

                        if ($insert_space_image_exec) {
                            $insert_query_result_msg .= " and images are listed successfully.";
                        } else {
                            $insert_query_result_msg .= " are listed, but images are not uploaded. Please try again.";
                        }
                    } else {
                        $insert_query_result_msg .= " are listed successfully.";
                    } 
                }
            } else {
                $insert_query_result_msg .= "Space details and images are not listed. Please try again.";
            }
            displayMsg($insert_query_result_msg);
        } else {
            displayMsg("Space details and images are not listed. Please try again.");
        }
    }
}

?>