<?php
/**
 * Public API endpoint to get all shops and their services
 * No authentication required - for public browsing
 */
include_once 'db.php';

$sql = "
SELECT 
    s.id AS shop_id,
    s.name,
    s.icon,
    s.address,
    s.lg AS lng,
    s.lat,
    s.rating,
    s.hours,
    sr.id AS service_id,
    sr.service_name,
    sr.min_cost,
    sr.max_cost,
    sr.description,
    u.contact AS owner_contact
FROM shop s
LEFT JOIN services sr ON sr.shop_id = s.id
LEFT JOIN user u ON u.shop_id = s.id AND u.role = 'owner'
ORDER BY s.id, sr.service_name
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $shop_id = $row["shop_id"];

    // If shop not added yet, initialize it
    if (!isset($data[$shop_id])) {
        $data[$shop_id] = [
            "name" => $row["name"],
            "shop_id" => $shop_id,
            "icon" => $row["icon"],
            "address" => $row["address"],
            "coordinates" => [
                floatval($row["lat"]),
                floatval($row["lng"])
            ],
            "services" => [],
            "rating" => intval($row["rating"]),
            "hours" => $row["hours"],
            "contact" => $row["owner_contact"]
        ];
    }

    // If there's a service, push it into the services array
    if (!empty($row["service_id"])) {
        $data[$shop_id]["services"][] = [
            "id" => intval($row["service_id"]),
            "service_name" => $row["service_name"],
            "min_cost" => intval($row["min_cost"]),
            "max_cost" => intval($row["max_cost"]),
            "description" => $row["description"] ?? ""
        ];
    }
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode(array_values($data), JSON_PRETTY_PRINT);

$stmt->close();
$conn->close();
?>
