<?php

    $sqlfindname = "SELECT * FROM customer where userID =$user_ID_reset";
    //run the sql query
    $sqlfindname= $pdo->query($sqlfindname);
    //if query run
    if ($sqlfindname) {
    //fetch pdo object
    while ($row= $sqlfindname->fetch(PDO::FETCH_OBJ)) {
        $customer_name=$row->customer_name;

    }}

?>