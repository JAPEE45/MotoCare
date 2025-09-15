<?php
include_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $user_id = $data['user_id'];
    $shop_id = $data['shop_id'];
    $preferred_date = $data['preferred_date']; 
    $time = $data['time'];
    $service_id = $data['service_id'];
    $vehicle_name = $data['vehicle_name'];

    $stmt = $conn->prepare("INSERT INTO booking (user_id, shop, preferred_time, time, repair_status, vehicle_name, service_id) VALUES (?, ?, ?, ?, ?,?,?)");

    $status = "pending";

    $stmt->bind_param("isssssi", $user_id, $shop_id, $preferred_date, $time, $status, $vehicle_name, $service_id);

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
