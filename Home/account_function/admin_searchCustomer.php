<?php

  require_once 'config.php';
  $searchCustomerEmail='';
  $searchCustomerName='';
  $searchCustomerContact='';

  if (isset($_POST['searchBox'])) {


    $searchCustomerEmail=$_POST['searchCustomerEmail'];
    if(empty(trim($_POST["searchCustomerEmail"]))){
      $searchCustomerEmail='';
    }

    $searchCustomerName=$_POST['searchCustomerName'];
    if(empty(trim($_POST["searchCustomerName"]))){
      $searchCustomerName='';
    }

    $searchCustomerContact=$_POST['searchCustomerContact'];
    if(empty(trim($_POST["searchCustomerContact"]))){
      $searchCustomerContact='';
    }

  }


?>

<br>