<?php
include_once 'db.php';
header('Content-Type: application/json');

try {
    // Get total shops
    $result = $conn->query("SELECT COUNT(*) as total FROM shop");
    $total_shops = $result->fetch_assoc()['total'];
    
    // Get shops added this month
    $result = $conn->query("SELECT COUNT(*) as count FROM shop WHERE MONTH(CURRENT_DATE()) = MONTH(NOW())");
    $new_shops_this_month = $result->fetch_assoc()['count'];
    
    // Get total services across all shops
    $result = $conn->query("SELECT COUNT(*) as total FROM services");
    $total_services = $result->fetch_assoc()['total'];
    
    // Get total customers
    $result = $conn->query("SELECT COUNT(*) as total FROM user WHERE role = 'customer'");
    $total_customers = $result->fetch_assoc()['total'];
    
    // Get new customers
    $result = $conn->query("SELECT COUNT(*) as count FROM user WHERE role = 'customer' AND createdAt >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
    $new_customers = $result->fetch_assoc()['count'];
    
    // Get total revenue
    $result = $conn->query("SELECT COALESCE(SUM(total_cost), 0) as total FROM booking WHERE status = 'completed'");
    $total_revenue = $result->fetch_assoc()['total'];
    
    // Get total bookings
    $result = $conn->query("SELECT COUNT(*) as total FROM booking");
    $total_bookings = $result->fetch_assoc()['total'];
    
    // Get recent activity
    $sql = "SELECT 
        b.id,
        b.transaction_number,
        b.status,
        b.total_cost,
        b.createdAt,
        u.fullname as customer_name,
        s.name as shop_name,
        sv.service_name
    FROM booking b
    JOIN user u ON b.user_id = u.ID
    JOIN shop s ON b.shop = s.id
    LEFT JOIN services sv ON b.service_id = sv.id
    ORDER BY b.createdAt DESC
    LIMIT 10";
    
    $result = $conn->query($sql);
    $recent_activity = [];
    
    while ($row = $result->fetch_assoc()) {
        $activity = [
            'id' => $row['id'],
            'transaction_number' => $row['transaction_number'],
            'customer_name' => $row['customer_name'],
            'shop_name' => $row['shop_name'],
            'service_name' => $row['service_name'] ?? 'N/A',
            'status' => $row['status'],
            'total_cost' => $row['total_cost'],
            'created_at' => $row['createdAt']
        ];
        $recent_activity[] = $activity;
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => [
            'total_shops' => intval($total_shops),
            'new_shops_this_month' => intval($new_shops_this_month),
            'total_services' => intval($total_services),
            'total_customers' => intval($total_customers),
            'new_customers' => intval($new_customers),
            'total_revenue' => floatval($total_revenue),
            'total_bookings' => intval($total_bookings),
            'recent_activity' => $recent_activity
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>
