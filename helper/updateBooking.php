<?php
include_once 'db.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing booking ID']);
    exit;
}

// Handle multiple services - receives an array
$service_ids = isset($data['service_ids']) ? $data['service_ids'] : [];

// For backward compatibility, also check for single service_id
if (empty($service_ids) && isset($data['service_id'])) {
    $service_ids = [$data['service_id']];
}

// Convert service_ids array to comma-separated string for storage
$service_ids_str = implode(',', $service_ids);

// Use first service ID for the main service_id field (for backward compatibility)
$primary_service_id = !empty($service_ids) ? $service_ids[0] : 0;

// Prepare update query - removed vehicle_plate_number, added service_ids
$sql = "UPDATE booking SET vehicle_name=?, vehicle_model=?, preferred_time=?, time=?, service_id=?, service_ids=?, notes=? WHERE id=? AND status='not accepted'";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
    exit;
}
$stmt->bind_param(
    'ssssissi',
    $data['vehicle_name'],
    $data['vehicle_model'],
    $data['preferred_time'],
    $data['time'],
    $primary_service_id,
    $service_ids_str,
    $data['notes'],
    $id
);
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Booking updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No booking found or booking cannot be edited (already accepted)']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
$stmt->close();
$conn->close();
?>
