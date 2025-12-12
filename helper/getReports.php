<?php
/**
 * Reports API endpoint
 * Generates various reports for staff/admin dashboards
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'db.php';

// Start output buffering to catch any errors
ob_start();

header('Content-Type: application/json');

$shop_id = isset($_GET['shop_id']) ? $_GET['shop_id'] : '0';
$period = isset($_GET['period']) ? $_GET['period'] : 'weekly';

// Use the provided start and end dates from the request
// If not provided, use defaults based on period
if (isset($_GET['start']) && isset($_GET['end'])) {
    $start_date = $_GET['start'];
    $end_date = $_GET['end'];
} else {
    // Only use period-based defaults if dates are not explicitly provided
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
        default:
            $start_date = date('Y-m-d', strtotime('-90 days'));
            $end_date = date('Y-m-d');
    }
}

// Debug: Log the parameters
file_put_contents('debug_reports.txt', "Shop ID: $shop_id\nStart: $start_date\nEnd: $end_date\n", FILE_APPEND);

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
        COALESCE(SUM(CASE 
            WHEN b.status = 'completed' THEN 
                COALESCE(b.total_cost, (s.min_cost + s.max_cost) / 2, 0)
            ELSE 0 
        END), 0) as total_revenue,
        SUM(CASE WHEN b.status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN b.status = 'pending' OR b.status = 'not accepted' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN b.status = 'progress' THEN 1 ELSE 0 END) as in_progress,
        SUM(CASE WHEN b.status = 'cancelled' OR b.status = 'rejected' THEN 1 ELSE 0 END) as cancelled
    FROM booking b
    LEFT JOIN services s ON b.service_id = s.id
    WHERE b.shop = ? 
    AND DATE(b.createdAt) BETWEEN ? AND ?
");

if (!$stats_query) {
    echo json_encode(['error' => 'Query preparation failed: ' . $conn->error]);
    exit;
}

$stats_query->bind_param("sss", $shop_id, $start_date, $end_date);

if (!$stats_query->execute()) {
    echo json_encode(['error' => 'Query execution failed: ' . $stats_query->error]);
    exit;
}

$stats_result = $stats_query->get_result();
$stats_data = $stats_result->fetch_assoc();

// Debug: Check if we got any data
$debug_info = [
    'shop_id' => $shop_id,
    'start_date' => $start_date,
    'end_date' => $end_date,
    'stats_data' => $stats_data,
    'query_error' => $stats_query->error
];

// Check actual bookings in database for this shop
$check_query = $conn->prepare("SELECT COUNT(*) as total FROM booking WHERE shop = ?");
$check_query->bind_param("s", $shop_id);
$check_query->execute();
$check_result = $check_query->get_result();
$check_data = $check_result->fetch_assoc();
$debug_info['total_bookings_for_shop'] = $check_data['total'];

// Add debug info to response
$response['debug'] = $debug_info;

// Debug: Log the stats data
file_put_contents('debug_reports.txt', "Stats Data: " . print_r($stats_data, true) . "\n", FILE_APPEND);

// Also check what bookings exist
$debug_query = $conn->prepare("SELECT id, shop, status, total_cost, createdAt FROM booking WHERE shop = ?");
$debug_query->bind_param("s", $shop_id);
$debug_query->execute();
$debug_result = $debug_query->get_result();
$all_bookings = [];
while ($row = $debug_result->fetch_assoc()) {
    $all_bookings[] = $row;
}
file_put_contents('debug_reports.txt', "All bookings for shop $shop_id: " . print_r($all_bookings, true) . "\n", FILE_APPEND);

// Ensure all stats are integers/floats
$response['stats'] = [
    'total_bookings' => intval($stats_data['total_bookings'] ?? 0),
    'total_revenue' => floatval($stats_data['total_revenue'] ?? 0),
    'completed' => intval($stats_data['completed'] ?? 0),
    'pending' => intval($stats_data['pending'] ?? 0),
    'in_progress' => intval($stats_data['in_progress'] ?? 0),
    'cancelled' => intval($stats_data['cancelled'] ?? 0)
];

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
        DATE(b.createdAt) as date,
        COUNT(*) as booking_count,
        COALESCE(SUM(CASE 
            WHEN b.status = 'completed' THEN 
                COALESCE(b.total_cost, (s.min_cost + s.max_cost) / 2, 0)
            ELSE 0 
        END), 0) as revenue
    FROM booking b
    LEFT JOIN services s ON b.service_id = s.id
    WHERE b.shop = ? 
    AND DATE(b.createdAt) BETWEEN ? AND ?
    GROUP BY DATE(b.createdAt)
    ORDER BY date ASC
");
$trend_query->bind_param("sss", $shop_id, $start_date, $end_date);
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
        COALESCE(SUM(CASE 
            WHEN b.status = 'completed' THEN 
                COALESCE(b.total_cost, (s.min_cost + s.max_cost) / 2, 0)
            ELSE 0 
        END), 0) as total_revenue
    FROM booking b
    LEFT JOIN services s ON b.service_id = s.id
    WHERE b.shop = ? 
    AND DATE(b.createdAt) BETWEEN ? AND ?
    AND s.service_name IS NOT NULL
    GROUP BY s.id, s.service_name
    ORDER BY booking_count DESC
    LIMIT 5
");
$services_query->bind_param("sss", $shop_id, $start_date, $end_date);
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
        COALESCE(b.total_cost, (s.min_cost + s.max_cost) / 2, 0) as total_cost,
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
$recent_query->bind_param("sss", $shop_id, $start_date, $end_date);
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

// Clear any buffered output
$errors = ob_get_clean();
if (!empty($errors)) {
    echo json_encode(['error' => 'PHP Errors: ' . $errors]);
    exit;
}

echo json_encode($response);

$conn->close();
?>
