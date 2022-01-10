<?php

require_once "../Home/config.php";

$updateType = $_POST['updateType'];
$spaceID = $_POST['spaceID'];
$userID = $_POST['userID'];

function add_favourite($pdo, $space, $user) {
    // Check if user already added favourite
    $query_result = "";
    $select_favourite = $pdo->query('SELECT favouriteID FROM myfirstdatabase.favourite WHERE spaceID = ' . $space . ' AND userID = ' . $user)->fetchAll(PDO::FETCH_COLUMN, 0);
    if (count($select_favourite) == 0) {
        $add_favourite_query = 'INSERT INTO myfirstdatabase.favourite(spaceID, userID) VALUES (' . $space . ', ' . $user . ')';
        $add_exec = $pdo->prepare($add_favourite_query)->execute();
        $query_result .= $add_exec ? "Executed successfully." : "Failed to execute.";
    }
    return $query_result;
}

function remove_favourite($pdo, $space, $user) {
    // Check if user already removed favourite
    $query_result = "";
    $select_favourite = $pdo->query('SELECT favouriteID FROM myfirstdatabase.favourite WHERE spaceID = ' . $space . ' AND userID = ' . $user)->fetchAll(PDO::FETCH_COLUMN, 0);
    if (count($select_favourite) != 0) {
        $remove_favourite_query = 'DELETE FROM myfirstdatabase.favourite WHERE spaceID = ' . $space . ' AND userID = ' . $user;
        $remove_exec = $pdo->prepare($remove_favourite_query)->execute();
        $query_result .= $remove_exec ? "Executed successfully." : "Failed to execute.";
    }
    return $query_result;
}

if ($updateType == -1) {
    echo remove_favourite($pdo, $spaceID, $userID);
} elseif ($updateType == 1) {
    echo add_favourite($pdo, $spaceID, $userID);
}

?>