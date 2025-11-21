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
// Prepare update query
$sql = "UPDATE booking SET vehicle_name=?, vehicle_model=?, vehicle_plate_number=?, preferred_time=?, time=?, service_id=?, notes=? WHERE id=? AND status='not accepted'";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
    exit;
}
$stmt->bind_param(
    'sssssssi',
    $data['vehicle_name'],
    $data['vehicle_model'],
    $data['vehicle_plate_number'],
    $data['preferred_time'],
    $data['time'],
    $data['service_id'],
    $data['notes'],
    $id
);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
$stmt->close();
$conn->close();
?>
