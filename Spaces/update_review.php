<?php

require_once "../Home/config.php";

$updateType = $_POST['updateType'];
$spaceID = $_POST['spaceID'];
$userID = $_POST['userID'];
$comment = $_POST['comment'];

function add_review($pdo, $space, $user, $comment) {
    // Check if user already added favourite
    $query_result = "";
    $select_review = $pdo->query('SELECT reviewID FROM myfirstdatabase.review WHERE spaceID = ' . $space . ' AND userID = ' . $user)->fetchAll(PDO::FETCH_COLUMN, 0);
    if (count($select_review) == 0) {
        // Insert new review
        $add_review = 'INSERT INTO myfirstdatabase.review(comment, spaceID, userID) VALUES (' . $comment . ', ' . $space . ', ' . $user . ')';
        $add_exec = $pdo->prepare($add_review)->execute();
        $query_result .= $add_exec ? "Executed successfully." : "Failed to execute.";
    } else {
        // Update existing review
        $update_review = 'UPDATE myfirstdatabase.review SET comment =:comment WHERE reviewID =:reviewID ';
        $update_exec = $pdo->prepare($update_review)->execute([":comment" => $comment, ":reviewID" => $select_review[0]]);
        $query_result .= $update_exec ? "Executed successfully." : "Failed to execute.";
    }
    return $query_result;
}

function remove_review($pdo, $space, $user) {
    // Check if user already removed favourite
    $query_result = "";
    $select_review = $pdo->query('SELECT reviewID FROM myfirstdatabase.review WHERE spaceID = ' . $space . ' AND userID = ' . $user)->fetchAll(PDO::FETCH_COLUMN, 0);
    if (count($select_review) != 0) {
        $remove_review = 'DELETE FROM myfirstdatabase.review WHERE spaceID = ' . $space . ' AND userID = ' . $user;
        $remove_exec = $pdo->prepare($remove_review)->execute();
        $query_result .= $remove_exec ? "Executed successfully." : "Failed to execute.";
    }
    return $query_result;
}

if ($updateType == -1) {
    echo remove_review($pdo, $spaceID, $userID);
} elseif ($updateType == 1) {
    echo add_review($pdo, $spaceID, $userID, $comment);
}

?>