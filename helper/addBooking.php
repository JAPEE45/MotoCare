<?php
include_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $user_id = $data['user_id'];
    $shop_id = $data['shop_id'];
    $preferred_date = $data['preferred_date']; 
    $time = $data['time'];
    $vehicle_name = $data['vehicle_name'];
    $notes = $data['notes'];
    $vehicle_model = $data['vehicle_model'];
    
    // Handle multiple services - now receives an array
    $service_ids = isset($data['service_ids']) ? $data['service_ids'] : [];
    
    // For backward compatibility, also check for single service_id
    if (empty($service_ids) && isset($data['service_id'])) {
        $service_ids = [$data['service_id']];
    }
    
    // Convert service_ids array to comma-separated string for storage
    $service_ids_str = implode(',', $service_ids);
    
    // Use first service ID for the main service_id field (for backward compatibility)
    $primary_service_id = !empty($service_ids) ? $service_ids[0] : 0;

    // Generate transaction number: TRANS-XXXXXXXXXXX (11 digits, padded)
    // Get the next ID that will be inserted
    $result = $conn->query("SELECT MAX(id) as max_id FROM booking");
    $row = $result->fetch_assoc();
    $next_id = ($row['max_id'] ?? 0) + 1;
    $transaction_number = 'TRANS-' . str_pad($next_id, 11, '0', STR_PAD_LEFT);

    $stmt = $conn->prepare("INSERT INTO booking (user_id, shop, preferred_time, time, status, vehicle_name, service_id, service_ids, transaction_number, notes, vehicle_model) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $status = "not accepted";

    $stmt->bind_param("isssssissss", $user_id, $shop_id, $preferred_date, $time, $status, $vehicle_name, $primary_service_id, $service_ids_str, $transaction_number, $notes, $vehicle_model);

    if ($stmt->execute()) {
        session_start();
        echo json_encode([
            "status" => "success", 
            "message" => "Booking added successfully",
            "transaction_number" => $transaction_number,
            "booking_id" => $conn->insert_id
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
