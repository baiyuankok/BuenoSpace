<?php
require_once "config.php";

$get_id = $_GET['id'];
if(!empty($get_id)) {
    $query = $pdo->query("SELECT `image`, image_type FROM myfirstdatabase.space_image WHERE imgID = $get_id");
    $data = $query->fetch();
    $image_type = $data['image_type'];

    if(empty($data)) {
        header("HTTP/1.0 404 Not Found");
    } else {
        header("Content-type: ".$image_type);
        echo $data['image'];
    }
}

?>