<?php
$dsn = 'mysql:host=35.224.143.106;port=3306;dbname=myfirstdatabase';
$username = "root";
$password = "thisisthepassword";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

?>
