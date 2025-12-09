<?php
include_once 'db.php';
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

try {
    if (!$conn) {
        throw new Exception("Database connection failed");
    }
    
    $sql = "SELECT SQL_NO_CACHE
        s.id,
        s.name as shop_name,
        s.address,
        s.lat,
        s.lg as lng,
        s.hours,
        s.rating,
        s.icon,
        u.fullname as owner_name,
        u.contact,
        u.email,
        u.ID as owner_id,
        (SELECT COUNT(*) FROM booking WHERE shop = s.id) as total_bookings,
        (SELECT COUNT(*) FROM booking WHERE shop = s.id AND status = 'completed') as completed_bookings,
        (SELECT COALESCE(SUM(total_cost), 0) FROM booking WHERE shop = s.id AND status = 'completed') as total_revenue
    FROM shop s
    LEFT JOIN user u ON s.owner_id = u.ID
    ORDER BY s.id DESC";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    $shops = [];
    while ($row = $result->fetch_assoc()) {
        $shops[] = [
            'shop_id' => intval($row['id']),
            'shop_name' => $row['shop_name'],
            'owner_name' => $row['owner_name'] ?? 'N/A',
            'owner_id' => intval($row['owner_id']),
            'contact' => $row['contact'] ?? 'N/A',
            'email' => $row['email'] ?? 'N/A',
            'address' => $row['address'],
            'lat' => floatval($row['lat']),
            'lg' => floatval($row['lng']),
            'hours' => $row['hours'],
            'rating' => intval($row['rating']),
            'icon' => $row['icon'],
            'total_bookings' => intval($row['total_bookings']),
            'completed_bookings' => intval($row['completed_bookings']),
            'total_revenue' => floatval($row['total_revenue'])
        ];
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $shops
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>
