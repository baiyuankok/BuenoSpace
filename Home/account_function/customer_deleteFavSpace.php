<?php
require_once "../config.php";
$userID = isset($_SESSION['userID']) ? trim($_SESSION['userID']) : '';

$favouriteID=$_GET['favouriteID'];
    echo $favouriteID;
    $pdoQuery = "DELETE FROM favourite WHERE favouriteID=:favouriteID";
            $pdoQuery_run= $pdo->prepare($pdoQuery);
            $pdoQuery_exec = $pdoQuery_run->execute(array(":favouriteID"=>$favouriteID));
            if ($pdoQuery_exec) {
                header("location:../customer_profile.php");
            }else{
                $deleteMsg= "Fail To Delete Instructor, Please Try Again";

            }

     
    
?>