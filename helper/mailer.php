<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';
require __DIR__ . '/PHPMailer/Exception.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'repairh411@gmail.com';   
    $mail->Password   = 'acit qsfl ydsx ynlo';   
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // or STARTTLS
    $mail->Port       = 465; // or 587
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port       = 587;
    $mail->setFrom('repairh411@gmail.com', 'Repair Hub');
    $mail->addAddress('fernandezmayma@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'Hello from PHPMailer!';
    $mail->Body    = 'This is a test email via Gmail SMTP on XAMPP.';
    $mail->send();
} catch (Exception $e) {
    echo "❌ Error: {$mail->ErrorInfo}";
}
