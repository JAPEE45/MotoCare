<?php

    include_once 'db.php';
    include 'sendSms.php';
    $id = (int)$_GET['id'];
    $status = $_GET['status'];
    $phone = $_GET['phone'];
    $fullname = $_GET['fullname'];
    $shopName = $_GET['shopName'];
    $service = $_GET['serviceType'];
    $total_cost = isset($_GET['total_cost']) ? floatval($_GET['total_cost']) : null;
    
    // If status is completed and no cost provided, return error
    if ($status == "completed" && ($total_cost === null || $total_cost <= 0)) {
        echo json_encode(['success' => false, 'message' => 'Total cost is required for completed bookings']);
        exit;
    }
    
    // Update query - include total_cost if provided
    if ($total_cost !== null) {
        $stmt = $conn->prepare('UPDATE booking SET status = ?, total_cost = ? WHERE id = ?');
        $stmt->bind_param('sdi', $status, $total_cost, $id);
    } else {
        $stmt = $conn->prepare('UPDATE booking SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $status, $id);
    }
    
    if($stmt->execute()){
        if($status == "pending"){
             $content = "Hi ".$fullname." Your Booking for ".$service." at ".$shopName." is confirmed for October 12. Thank you for us! Please Arrive 10 minutes before your appointment.";
             sendMessage($phone,$content);
        }elseif($status == "progress"){
             $content = "Your Vehicle service at ".$shopName." is Now in Progress. Our Mechanics are working on your ".$service.". You'll receive another update once it's completed.";
             sendMessage($phone,$content);
        }elseif($status == "completed"){
             $costText = $total_cost ? " Total cost: ₱".number_format($total_cost, 2) : "";
             $content = "Your service at ".$shopName." has been Completed.".$costText." Please proceed to the shop for pickup and payment. Thank you for trusting our shop!";
             sendMessage($phone,$content);
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }
    
$stmt->close();
$conn->close();
?>