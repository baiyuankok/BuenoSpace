<?php

    $sqlfindname = "SELECT * FROM owner where ownerID =$owner_ID_reset";
    //run the sql query
    $sqlfindname= $pdo->query($sqlfindname);
    //if query run
    if ($sqlfindname) {
    //fetch pdo object
    while ($row= $sqlfindname->fetch(PDO::FETCH_OBJ)) {
        $owner_name=$row->owner_name;

    }}

?>