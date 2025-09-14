<?php
function sendEmail($to, $subject, $message, $from) {
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if(mail($to, $subject, $message, $headers)) {
        return true; // Sent successfully
    } else {
        return false; // Failed to send
    }
}

// Example usage
if(sendEmail("fernandezjasper463@gmail.com", "Test Subject", "<h3>Hello Jasper!</h3>", "repairhubmotocare@gmail.com")) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
?>