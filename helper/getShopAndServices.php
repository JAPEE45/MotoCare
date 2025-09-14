<?php
include_once 'db.php';

// Check DB connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$sql = "
SELECT 
    s.name,
    s.id,
    s.icon,
    s.address,
    s.lg AS lng,
    s.lat,
    s.rating,
    s.hours AS hours,
    GROUP_CONCAT(sr.service_name) AS services
FROM shop s
LEFT JOIN services sr ON sr.shop_id = s.id
GROUP BY s.id
";

// Prepare statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    die(json_encode(["error" => "SQL prepare failed: " . $conn->error]));
}

// Execute statement
if (!$stmt->execute()) {
    http_response_code(500);
    die(json_encode(["error" => "SQL execution failed: " . $stmt->error]));
}

// Get result
$result = $stmt->get_result();
if (!$result) {
    http_response_code(500);
    die(json_encode(["error" => "Fetching result failed: " . $stmt->error]));
}

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "name" =>  $row["name"], // ⚠️ replace with real shop name column if available
        "icon" => $row["icon"],
        "address" => $row["address"],
        "coordinates" => [
            floatval($row["lat"]),
            floatval($row["lng"])
        ],
        "services" => $row["services"] ? explode(",", $row["services"]) : [],
        "rating" => $row["rating"],
        "hours" => $row["hours"]
    ];
}

// If no data
if (empty($data)) {
    http_response_code(404);
    echo json_encode(["message" => "No shops found"]);
} else {
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

$stmt->close();
$conn->close();
?>
