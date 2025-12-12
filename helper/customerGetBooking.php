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
            LEFT JOIN services sv ON b.service_id = sv.id
            WHERE b.user_id = ?
            ORDER BY b.createdAt DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    $result = $stmt->get_result();
    
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        // Get all services if multiple
        $services_display = $row['service_name'] ?? "N/A";
        if (!empty($row['service_ids'])) {
            $service_ids = explode(',', $row['service_ids']);
            if (count($service_ids) > 0) {
                $services_query = $conn->prepare("SELECT service_name FROM services WHERE id IN (" . implode(',', array_map('intval', $service_ids)) . ")");
                $services_query->execute();
                $services_result = $services_query->get_result();
                $service_names = [];
                while ($srv = $services_result->fetch_assoc()) {
                    $service_names[] = $srv['service_name'];
                }
                if (count($service_names) > 0) {
                    $services_display = implode(', ', $service_names);
                }
            }
        }
        
        // Generate transaction number if it doesn't exist
        $transaction_number = $row['transaction_number'];
        if (empty($transaction_number)) {
            $transaction_number = 'TRANS-' . str_pad($row['id'], 11, '0', STR_PAD_LEFT);
        }
        
        $bookings[] = [
            'id' => $row['id'],
            'transaction_number' => $transaction_number,
            'shopName' => $row['name'],
            'service' => $services_display,
            'service_id' => $row['service_id'],
            'service_ids' => $row['service_ids'],
            'status' => $row['status'],
            'dateTime' => $row['preferred_time'],
            'vehicle' => $row['vehicle_name'],
            'vehicle_model' => $row['vehicle_model'],
            'vehicle_plate_number' => $row['vehicle_plate_number'],
            'address' => $row['address'],
            'notes' => $row['notes'],
            'time' => $row['time'],
            'shop_name' => $row['name'],
            'shopName' => $row['name'],
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
