<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require "../PHPMailer/vendor/autoload.php";

$mail = new PHPMailer();

$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Port = 587;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Host = 'smtp.gmail.com';
$mail->isHTML(true);
$mail->Username = 'nomiying1998@gmail.com';
$mail->Password = 'Yes12345';
$mail->SetFrom('nomiying1998@gmail.com');
$mail->Subject = $subject;
$mail->Body = $body;
$mail->AddAddress($owner_email);
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ),
);

if (!$mail->send()) {
    echo "Email not sent. Mailer Error: {$mail->ErrorInfo}";
} else {
    echo "Email sent.";
    header("location: ../Home/account_function/resetpassword_message.php");
}
