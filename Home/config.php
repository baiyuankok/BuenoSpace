<?php
$dsn = 'mysql:host=hellodatabaseinstance.cyho2chorzov.us-east-1.rds.amazonaws.com;port=3306;dbname=myfirstdatabase';
$username = "cmt322access2";
$password = "thisisthepassword";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

/*to connect gcp
host=35.224.143.106
$username = "root";*/




?>
