<?php
require_once "../config.php";

$ownerID=$_GET['ownerIDdelete'];
 
    $pdoQuery = "DELETE FROM owner WHERE ownerID=:ownerID";
            $pdoQuery_run= $pdo->prepare($pdoQuery);
            $pdoQuery_exec = $pdoQuery_run->execute(array(":ownerID"=>$ownerID));
            if ($pdoQuery_exec) {
                header("location:../admin_platform_owner.php");
                
            }else{
                $deleteMsg= "Fail To Delete , Please Try Again";

            }

     
    
?>
