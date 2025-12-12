<?php
    include_once 'db.php';
    session_start();
    $user_id = $_GET['ddd'];
    $user = $_SESSION['user_id'];
    
    // Check how many bookings the user has for this shop today (not cancelled or completed)
    $stmt = $conn->prepare("SELECT COUNT(*) as booking_count 
FROM booking 
WHERE shop = ? AND user_id = ?
  AND DATE(createdAt) = CURDATE()
  AND status != 'cancelled' 
  AND status != 'completed'
");
    $stmt->bind_param("ii", $user_id, $user);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        $booking_count = $row['booking_count'];
        
        // Allow up to 10 bookings per day per shop
        if($booking_count >= 10){
            echo json_encode([
                "limit_reached" => true,
                "message" => "You have reached the maximum limit of 10 bookings per day for this shop.",
                "count" => $booking_count
            ]);
        } else {
            echo json_encode([
                "allowed" => true,
                "count" => $booking_count,
                "remaining" => 10 - $booking_count
            ]);
        }
    } else {
        echo json_encode(['error'=> $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
?>