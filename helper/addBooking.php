<?php
include_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $user_id = $data['user_id'];
    $shop = $data['shop'];
    $preferred_date = $data['preferred_date']; 
    $time = $data['time'];

    $stmt = $conn->prepare("INSERT INTO booking (user_id, shop, preferred_time, time, repair_status) VALUES (?, ?, ?, ?, ?)");

    $status = "pending";

    $stmt->bind_param("issss", $user_id, $shop, $preferred_date, $time, $status);

    if ($stmt->execute()) {
        session_start();
        echo json_encode([
            "status" => "success", 
            "message" => "Booking added successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "No data received"]);
}

$conn->close();
?>
