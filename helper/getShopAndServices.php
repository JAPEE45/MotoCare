<?php
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
    s.hours AS hours,
    sr.id AS service_id,
    sr.service_name AS service_name
FROM shop s
LEFT JOIN services sr ON sr.shop_id = s.id
ORDER BY s.id
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
            "rating" => $row["rating"],
            "hours" => $row["hours"]
        ];
    }

    // If there’s a service, push it into the services array
    if (!empty($row["service_id"])) {
        $data[$shop_id]["services"][] = [
            "id" => intval($row["service_id"]),
            "service_name" => $row["service_name"]
        ];
    }
}

header('Content-Type: application/json');
echo json_encode(array_values($data), JSON_PRETTY_PRINT);

$stmt->close();
$conn->close();
?>
