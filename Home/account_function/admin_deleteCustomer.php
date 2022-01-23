<?php
require_once "../config.php";

$userID=$_GET['userIDdelete'];
 
    $pdoQuery = "DELETE FROM customer WHERE userID=:userID";
            $pdoQuery_run= $pdo->prepare($pdoQuery);
            $pdoQuery_exec = $pdoQuery_run->execute(array(":userID"=>$userID));
            if ($pdoQuery_exec) {
                header("location:../admin_platform.php");
            }else{
                $deleteMsg= "Fail To Delete Favourite Space, Please Try Again";

            }

     
    
?>