<?php

    include_once 'db.php';
    include 'sendSms.php';
    $id = (int)$_GET['id'];
    $status = $_GET['status'];
     $phone = $_GET['phone'];
    $fullname = $_GET['fullname'];
    $shopName = $_GET['shopName'];
    $service = $_GET['serviceType'];
    $stmt = $conn->prepare('UPDATE booking SET status = ? WHERE id = ?');
    $stmt->bind_param('si', $status,$id );
    if($stmt->execute()){
        if($status == "pending"){
             $content = "Hi ".$fullname." Your Booking for ".$service." at ".$shopName." is confirmed for October 12. Thank you for us! Please Arrive 10 minutes before your appointment.";
             sendMessage($phone,$content);
        }elseif($status == "progress"){
             $content = "Your Vehicle service at ".$shopName." is Now in Progress. Our Mechanics are working on your ".$service.". You'll receive another update once it's completed.";
             sendMessage($phone,$content);
        }elseif($status == "completed"){
             $content = "Your service at ".$shopName." has been Completed. Please proceed to the shop for pickup and payment. Thank you for trusting our shop!";
             sendMessage($phone,$content);
        }
    }
$stmt->close();
$conn->close();
?>