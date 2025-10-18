<?php
require_once 'db.php';
session_start();

header('Content-Type: application/json');

// if (empty($_SESSION['user'])) {
//     echo json_encode(['error' => 'Not logged in']);
//     exit();
// }

try {
    $user_id = $_GET['user_id']; // Get user ID from session
    
    $sql = "SELECT b.*, 
            s.name, 
            s.address,
            sv.service_name
            FROM booking b
            JOIN shop s ON b.shop = s.id
            JOIN services sv ON b.service_id = sv.id
            WHERE b.user_id = ?
            ORDER BY b.createdAt DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    $result = $stmt->get_result();
    
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = [
            'id' => $row['id'],
            'shopName' => $row['name'],
            'service' => $row['service_name'],
            'status' => $row['status'],
            'dateTime' => $row['preferred_time'],
            'vehicle' => $row['vehicle_name'],
            'vehicle_model' => $row['vehicle_model'],
            'vehicle_plate_number' => $row['vehicle_plate_number'],
            'address' => $row['address'],
            'notes' => $row['notes'],
        ];
    }
    
    echo json_encode([
        'status' => 'success',
        'bookings' => $bookings
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

$conn = null;
?>
