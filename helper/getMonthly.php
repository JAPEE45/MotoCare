<?php
include_once "db.php";

$shop_id = $_GET['shop_id'] ?? null;
if (!$shop_id) {
    echo json_encode(["error" => "shop_id is required"]);
    exit;
}

// Build dynamic 6-month range with current month in middle
$currentMonth = date("n"); // numeric 1–12
$months = [];
for ($i = -2; $i <= 3; $i++) {
    $monthNum = ($currentMonth + $i + 12) % 12;
    if ($monthNum == 0) $monthNum = 12;
    $months[] = [
        "num" => $monthNum,
        "label" => date("M", mktime(0, 0, 0, $monthNum, 1))
    ];
}

// Fetch booking counts grouped by month
$stmt = $conn->prepare("
    SELECT 
        MONTH(createdAt) AS month_num,
        COUNT(*) AS appointments,
        COUNT(DISTINCT user_id) AS customers
    FROM booking
    WHERE shop = ?
    GROUP BY month_num
");
$stmt->bind_param("s", $shop_id);
$stmt->execute();
$res = $stmt->get_result();

// Store query results in array by month number
$dataByMonth = [];
while ($row = $res->fetch_assoc()) {
    $dataByMonth[$row['month_num']] = $row;
}

// Build final arrays, filling missing months with 0
$labels = [];
$customers = [];
$appointments = [];

foreach ($months as $m) {
    $labels[] = $m['label'];
    $customers[] = isset($dataByMonth[$m['num']]) ? (int)$dataByMonth[$m['num']]['customers'] : 0;
    $appointments[] = isset($dataByMonth[$m['num']]) ? (int)$dataByMonth[$m['num']]['appointments'] : 0;
}

echo json_encode([
    "labels" => $labels,
    "customers" => $customers,
    "appointments" => $appointments
], JSON_PRETTY_PRINT);
