<?php

session_start();
$ownerID = isset($_SESSION['ownerID']) ? trim($_SESSION['ownerID']) : '';
require_once "../Home/config.php";
// To prevent the shutdown function from executing if no error occurred
$global_shutdown = true;

function onShutDown() {
    global $global_shutdown;
    // If the error "max_execution_time" is catched, redirect to the owner profile page
    if ($global_shutdown) {
        displayMsg("Space details are updated, but images are not uploaded. Please try again.");
    }
}
 
function displayMsg($msg) {
    header('Location: ../Home/owner_profile.php?query_msg="' . $msg . '"');
    exit();
}

function insertNewImage($s_id, $pdo) {
    global $global_shutdown;
    $query_result_msg = "";
    if (count($_FILES["images"]) > 0) {
        // Insert new images
        $insert_space_image = "";
        foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
            if (is_uploaded_file($_FILES["images"]["tmp_name"][$key])) {
                // Get image data and properties
                $imgData = addslashes(file_get_contents($_FILES["images"]["tmp_name"][$key]));
                $imageProperties = getimagesize($_FILES["images"]["tmp_name"][$key]);

                $insert_space_image .= "INSERT INTO myfirstdatabase.space_image(`image`, image_type, spaceID) VALUES ('{$imgData}', '{$imageProperties['mime']}', {$s_id});";
            }
        }
        if (!empty($insert_space_image)) {
            $global_shutdown = true;
            // The shutdown function will run either an error is caught or finish executing the function
            register_shutdown_function("onShutDown");
            // Set the maximum execution time to 120 seconds
            ini_set("max_execution_time", 120);

            $insert_space_image_exec = $pdo->prepare($insert_space_image)->execute();

            if ($insert_space_image_exec) {
                $global_shutdown = false;
                $query_result_msg .= " and images are updated successfully.";
            } else {
                $query_result_msg .= " are updated, but images are not uploaded. Please try again.";
            }
        } else {
            $query_result_msg .= " are updated successfully.";
        } 
    }
    return $query_result_msg;
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

    if (empty($_POST["slot"])) {
        $space_slot_err = "Please select at least one slot for the space.";
    }

    $space_id = $_POST["space-id"];
    $space_name = $_POST["space-name"];
    $space_location = $_POST["space-location"];
    $space_price = $_POST["space-price"];
    $space_capacity = $_POST["space-capacity"];
    $space_event = $_POST['event'];
    $space_slots = $_POST['slot'];

    // To get the ID of existing images that are being removed during space editing
    $remove_img = isset($_POST['removeImage']) ? $_POST['removeImage'] : '';

    // Convert to aa array of boolean
    if (empty($space_slot_err)) {
        $slot_bool_array = array(false, false, false, false, false, false, false);
        $input_slot_counter = 0;
        for ($i = 0; $i < count($slot_bool_array); $i++) {
            if ($i == $space_slots[$input_slot_counter]) {
                $slot_bool_array[$i] = true;
                $input_slot_counter += 1;
            } else {
                $slot_bool_array[$i] = false;
            }
        }
    }

    // If spaceID is set, it indicates updating database
    if (empty($space_id_err)) {
        if (empty($space_name_err) && empty($space_location_err) && empty($space_price_err) && empty($space_capacity_err) && empty($space_event_err) && empty($space_slot_err)) {
            // Update to particular row according to spaceID and ownerID
            $space_location = ucwords($space_location);
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

            // Update space slot according to spaceID
            $update_slot_query = "UPDATE myfirstdatabase.available_slot
                SET
                    `availableMonday` = :space_slot_1,
                    `availableTuesday` = :space_slot_2,
                    `availableWednesday` = :space_slot_3,
                    `availableThursday` = :space_slot_4,
                    `availableFriday` = :space_slot_5,
                    `availableSaturday` = :space_slot_6,
                    `availableSunday` = :space_slot_0
                WHERE `spaceID` = :space_id";
            
            $query_array = array(
                ":space_slot_1" => $slot_bool_array[1],
                ":space_slot_2" => $slot_bool_array[2],
                ":space_slot_3" => $slot_bool_array[3],
                ":space_slot_4" => $slot_bool_array[4],
                ":space_slot_5" => $slot_bool_array[5],
                ":space_slot_6" => $slot_bool_array[6],
                ":space_slot_0" => $slot_bool_array[0],
                ":space_id" => $space_id
            );
            $update_slot_exec = $pdo->prepare($update_slot_query)->execute($query_array);

            $query_result_msg = "";
            if ($update_query_exec && $insert_event_exec && $update_slot_exec) {
                $query_result_msg .= "Space details";

                // Delete the existing space images
                if (!empty($remove_img)) {
                    foreach ($remove_img as $each_remove_img) {
                        $delete_images = $pdo->query('DELETE FROM myfirstdatabase.space_image WHERE imgID = ' . $each_remove_img);
                    }
                }
                // Insert new images
                $query_result_msg .= insertNewImage($space_id, $pdo);
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
            $space_location = ucwords($space_location);
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

            // Insert new space slot according to spaceID
            $insert_slot_query = "INSERT INTO myfirstdatabase.available_slot
                (`spaceID`,
                `availableMonday`,
                `availableTuesday`,
                `availableWednesday`,
                `availableThursday`,
                `availableFriday`,
                `availableSaturday`,
                `availableSunday`)
                VALUES
                (:space_id,
                :space_slot_1,
                :space_slot_2,
                :space_slot_3,
                :space_slot_4,
                :space_slot_5,
                :space_slot_6,
                :space_slot_0) ";
            
            $insert_query_array = array(
                ":space_id" => $space_id,
                ":space_slot_1" => $slot_bool_array[1],
                ":space_slot_2" => $slot_bool_array[2],
                ":space_slot_3" => $slot_bool_array[3],
                ":space_slot_4" => $slot_bool_array[4],
                ":space_slot_5" => $slot_bool_array[5],
                ":space_slot_6" => $slot_bool_array[6],
                ":space_slot_0" => $slot_bool_array[0]
            );
            $insert_slot_exec = $pdo->prepare($insert_slot_query)->execute($insert_query_array);

            // Insert space images
            $insert_query_result_msg = "";
            if ($insert_details_exec && $insert_space_event_exec && $insert_slot_exec) {
                $insert_query_result_msg .= "Space details";
                // Insert new images
                $insert_query_result_msg .= insertNewImage($space_id, $pdo);
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