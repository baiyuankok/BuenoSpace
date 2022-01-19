<?php

  require_once 'config.php';
  $searchOwnerEmail='';
  $searchOwnerName='';
  $searchOwnerContact='';

  if (isset($_POST['searchBoxOwner'])) {


    $searchOwnerEmail=$_POST['searchOwnerEmail'];
    if(empty(trim($_POST["searchOwnerEmail"]))){
      $searchOwnerEmail='';
    }

    $searchOwnerName=$_POST['searchOwnerName'];
    if(empty(trim($_POST["searchOwnerName"]))){
      $searchOwnerName='';
    }

    $searchOwnerContact=$_POST['searchOwnerContact'];
    if(empty(trim($_POST["searchOwnerContact"]))){
      $searchOwnerContact='';
    }

  }


?>

<br>