<?php
/**
 * Reports API endpoint
 * Generates various reports for staff/admin dashboards
 */
include_once 'db.php';

header('Content-Type: application/json');

$shop_id = isset($_GET['shop_id']) ? intval($_GET['shop_id']) : 0;
$period = isset($_GET['period']) ? $_GET['period'] : 'weekly';
$start_date = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d', strtotime('-7 days'));
$end_date = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d');

// Adjust dates based on period
switch ($period) {
    case 'daily':
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        break;
    case 'weekly':
        $start_date = date('Y-m-d', strtotime('-7 days'));
        $end_date = date('Y-m-d');
        break;
    case 'monthly':
        $start_date = date('Y-m-d', strtotime('-30 days'));
        $end_date = date('Y-m-d');
        break;
    case 'yearly':
        $start_date = date('Y-01-01');
        $end_date = date('Y-m-d');
        break;
}

$response = [
    'stats' => [],
    'trend' => ['labels' => [], 'bookings' => [], 'revenue' => []],
    'status_distribution' => [],
    'top_services' => [],
    'recent_bookings' => []
];

// Get overall stats
$stats_query = $conn->prepare("
    SELECT 
        COUNT(*) as total_bookings,
        COALESCE(SUM(CASE WHEN status = 'completed' THEN total_cost ELSE 0 END), 0) as total_revenue,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'pending' OR status = 'not accepted' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'progress' THEN 1 ELSE 0 END) as in_progress,
        SUM(CASE WHEN status = 'cancelled' OR status = 'rejected' THEN 1 ELSE 0 END) as cancelled
    FROM booking 
    WHERE shop = ? 
    AND DATE(createdAt) BETWEEN ? AND ?
");
$stats_query->bind_param("iss", $shop_id, $start_date, $end_date);
$stats_query->execute();
$stats_result = $stats_query->get_result();
$response['stats'] = $stats_result->fetch_assoc();

// Get status distribution
$response['status_distribution'] = [
    'completed' => intval($response['stats']['completed']),
    'pending' => intval($response['stats']['pending']),
    'progress' => intval($response['stats']['in_progress']),
    'cancelled' => intval($response['stats']['cancelled'])
];

// Get trend data (grouped by date)
$trend_query = $conn->prepare("
    SELECT 
        DATE(createdAt) as date,
        COUNT(*) as booking_count,
        COALESCE(SUM(CASE WHEN status = 'completed' THEN total_cost ELSE 0 END), 0) as revenue
    FROM booking 
    WHERE shop = ? 
    AND DATE(createdAt) BETWEEN ? AND ?
    GROUP BY DATE(createdAt)
    ORDER BY date ASC
");
$trend_query->bind_param("iss", $shop_id, $start_date, $end_date);
$trend_query->execute();
$trend_result = $trend_query->get_result();

while ($row = $trend_result->fetch_assoc()) {
    $response['trend']['labels'][] = date('M d', strtotime($row['date']));
    $response['trend']['bookings'][] = intval($row['booking_count']);
    $response['trend']['revenue'][] = floatval($row['revenue']);
}

// If no trend data, add placeholder
if (empty($response['trend']['labels'])) {
    $current = strtotime($start_date);
    $end = strtotime($end_date);
    while ($current <= $end) {
        $response['trend']['labels'][] = date('M d', $current);
        $response['trend']['bookings'][] = 0;
        $response['trend']['revenue'][] = 0;
        $current = strtotime('+1 day', $current);
    }
}

// Get top services
$services_query = $conn->prepare("
    SELECT 
        s.service_name,
        COUNT(b.id) as booking_count,
        COALESCE(SUM(CASE WHEN b.status = 'completed' THEN b.total_cost ELSE 0 END), 0) as total_revenue
    FROM booking b
    LEFT JOIN services s ON b.service_id = s.id
    WHERE b.shop = ? 
    AND DATE(b.createdAt) BETWEEN ? AND ?
    AND s.service_name IS NOT NULL
    GROUP BY s.id, s.service_name
    ORDER BY booking_count DESC
    LIMIT 5
");
$services_query->bind_param("iss", $shop_id, $start_date, $end_date);
$services_query->execute();
$services_result = $services_query->get_result();

while ($row = $services_result->fetch_assoc()) {
    $response['top_services'][] = [
        'service_name' => $row['service_name'],
        'booking_count' => intval($row['booking_count']),
        'total_revenue' => floatval($row['total_revenue'])
    ];
}

// Get recent completed bookings
$recent_query = $conn->prepare("
    SELECT 
        b.id,
        COALESCE(b.transaction_number, CONCAT('TRANS-', LPAD(b.id, 11, '0'))) as transaction_number,
        u.fullname as customer_name,
        s.service_name,
        b.total_cost,
        b.createdAt
    FROM booking b
    LEFT JOIN user u ON b.user_id = u.id
    LEFT JOIN services s ON b.service_id = s.id
    WHERE b.shop = ? 
    AND b.status = 'completed'
    AND DATE(b.createdAt) BETWEEN ? AND ?
    ORDER BY b.createdAt DESC
    LIMIT 10
");
$recent_query->bind_param("iss", $shop_id, $start_date, $end_date);
$recent_query->execute();
$recent_result = $recent_query->get_result();

while ($row = $recent_result->fetch_assoc()) {
    $response['recent_bookings'][] = [
        'transaction_number' => $row['transaction_number'],
        'customer_name' => $row['customer_name'],
        'service_name' => $row['service_name'] ?? 'N/A',
        'total_cost' => floatval($row['total_cost'] ?? 0)
    ];
}

echo json_encode($response);

$conn->close();
?>
