<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'bhuvaneshb546@gmail.com';
$mail->Password = '';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('your-email@gmail.com', 'Test');
$mail->addAddress('bhuvaneshb546@gmail.com');
$mail->Subject = 'Test Mail';
$mail->Body = 'Hello OTP test';

$mail->send();
echo "Mail sent";
