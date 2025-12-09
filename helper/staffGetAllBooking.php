<?php


include_once 'db.php';
$shop_id = $_GET['shop_id'];
$sql = "
SELECT 
    b.id,
    b.transaction_number,
    b.service_ids,
    u.fullname AS customer,
    b.vehicle_name,
    s.service_name AS service,
    b.status,
    u.email,
    b.preferred_time,
    b.createdAt,
    b.total_cost,
    b.notes,
    b.vehicle_model,
    b.vehicle_plate_number,
    b.time,
    u.address,
    h.name as shop_name,
    u.contact
FROM booking b
INNER JOIN user u ON b.user_id = u.id
LEFT JOIN services s ON b.service_id = s.id
INNER JOIN shop h ON h.id = b.shop WHERE h.id = ?
ORDER BY b.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute([$shop_id]);
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    // Generate transaction number if not exists
    $transaction_number = $row["transaction_number"];
    if (empty($transaction_number)) {
        $transaction_number = 'TRANS-' . str_pad($row["id"], 11, '0', STR_PAD_LEFT);
    }
    
    // Get all services if multiple
    $services_display = $row["service"] ?? "N/A";
    if (!empty($row["service_ids"])) {
        $service_ids = explode(',', $row["service_ids"]);
        if (count($service_ids) > 1) {
            $services_query = $conn->prepare("SELECT service_name FROM services WHERE id IN (" . implode(',', array_map('intval', $service_ids)) . ")");
            $services_query->execute();
            $services_result = $services_query->get_result();
            $service_names = [];
            while ($srv = $services_result->fetch_assoc()) {
                $service_names[] = $srv['service_name'];
            }
            $services_display = implode(', ', $service_names);
        }
    }
    
    $data[] = [
        "id" => (int)$row["id"],
        "transaction_number" => $transaction_number,
        "email" => $row["email"],
        "shop_name" => $row["shop_name"],
        "address" => $row["address"],
        "createdAt" => $row['createdAt'],   
        "customerName" => $row["customer"],
        "vehicleInfo" => $row["vehicle_name"] ?? "N/A",
        "licensePlate" => $row["license_plate"] ?? "N/A",
        "scheduleDate" => $row["preferred_time"],
        "scheduledTime" => $row["time"],
        "serviceType" => $services_display,
        "status" => $row["status"],
        "vehicle_model" => $row["vehicle_model"],
        "vehicle_plate_number" => $row["vehicle_plate_number"],
        "totalCost" => "₱" . number_format($row["total_cost"], 0, '.', ','),
        "phone" => $row["contact"] ?? "N/A",
        "estimatedDuration" => $row["duration"] ?? "N/A",
        "notes" => $row["notes"] ?? "N/A"
    ];
}

header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);

$stmt->close();
$conn->close();
?>

