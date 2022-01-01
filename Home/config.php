<?php
$dsn = 'mysql:host=hellodatabaseinstance.cj959sycwruz.us-east-1.rds.amazonaws.com;port=3306;dbname=myfirstdatabase';
$username = "adminDatabase";
$password = "thisisthepassword";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

?>
