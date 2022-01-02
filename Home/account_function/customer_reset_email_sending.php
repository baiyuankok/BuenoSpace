<?php

  //session_start();
  //$url = isset($_SESSION['url']) ? trim($_SESSION['url']) : '';
  
  require_once ('../PHPMailer/PHPMailerAutoload.php');
 

  $mail= new PHPMailer();
  $mail->isSMTP();
  $mail->SMTPAuth = true;
  $mail->Port = 587;
$mail->SMTPSecure = 'tls';
  $mail->Host = 'smtp.gmail.com';
  $mail->isHTML();
  $mail->Username='nomiying1998@gmail.com';
  $mail->Password = 'Yes12345';
  $mail->SetFrom('nomiying1998@gmail.com');
  $mail->Subject = $subject;
  $mail->Body= $body;
  $mail->AddAddress($customer_email);

 
  //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

  if (!$mail->Send()) {
    echo "Email not sent";
    
  }else{
    echo "sent";
    header("location: ../Home/account_function/resetpassword_message.php");
  }

?>