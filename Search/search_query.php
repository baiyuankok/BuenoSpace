<?php

//Connect to database
require_once "../Home/config.php";

//Test to see if search attributes were received from main page
console.log("search_query.php activated");
echo $_POST["events"];
echo '<html><br></html>';
echo $_POST["location"];
echo '<html><br></html>';
echo $_POST["min_price"];
echo '<html><br></html>';
echo $_POST["max_price"];
echo '<html><br></html>';
echo $_POST["capacity"];
echo '<html><br></html>';



?>
