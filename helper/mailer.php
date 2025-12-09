<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';
require __DIR__ . '/PHPMailer/Exception.php';

function sendEmail($rcv, $title, $content){
    
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'repairh411@gmail.com';   
    $mail->Password   = 'acit qsfl ydsx ynlo';   
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->setFrom('repairh411@gmail.com', 'MotoCare');
    $mail->addAddress($rcv);
    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body    = $content;
    $mail->send();
    return true;
} catch (Exception $e) {
    error_log("Email send failed to $rcv: " . $mail->ErrorInfo);
    throw new Exception("Email failed: " . $mail->ErrorInfo);
}

}