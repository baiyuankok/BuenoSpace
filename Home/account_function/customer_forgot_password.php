<?php

session_start();
require_once "../config.php";
// Check if the user is already logged in, if yes then redirect him to index page
if(isset($_SESSION["customer_loggedin"]) && $_SESSION["customer_loggedin"] === true){
    header("location: ../Home/index.php");
    exit;
}


//The user's id, which should be present in the GET variable "uid"
$userID = isset($_GET['uid']) ? trim($_GET['uid']) : '';
//The token for the request, which should be present in the GET variable "t"
$token = isset($_GET['t']) ? trim($_GET['t']) : '';
//The id for the request, which should be present in the GET variable "id"
$passwordRequestId = isset($_GET['id']) ? trim($_GET['id']) : '';
 
 
//Now, we need to query our password_reset_request table and
//make sure that the GET variables we received belong to
//a valid forgot password request.
 
$sql = "
      SELECT * 
      FROM customer_password_reset_requests
      WHERE 
      userID = :userID AND 
        token = :token AND 
        customer_request_ID = :customer_request_ID
";
 
//Prepare our statement.
$statement = $pdo->prepare($sql);
 
//Execute the statement using the variables we received.
$statement->execute(array(
    "userID" => $userID,
    "customer_request_ID" => $passwordRequestId,
    "token" => $token
));
 
//Fetch our result as an associative array.
$requestInfo = $statement->fetch(PDO::FETCH_ASSOC);
 
//If $requestInfo is empty, it means that this
//is not a valid forgot password request. i.e. Somebody could be
//changing GET values and trying to hack our
//forgot password system.
if(empty($requestInfo)){
    echo 'Invalid request! You will be redirected to the home page';
    header("location: ../index.php");
    exit;
    
}
 
//The request is valid, so give them a session variable
//that gives them access to the reset password form.
$_SESSION['user_id_reset_pass'] = $userID;
 
//Redirect them to your reset password form.
header('Location: customer_create_new_password.php');
exit;


?>