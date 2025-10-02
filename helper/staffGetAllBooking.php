<?php


include_once 'db.php';

$sql = "
SELECT 
    b.id,
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
    u.contact
FROM booking b
INNER JOIN user u ON b.user_id = u.id
INNER JOIN services s ON b.service_id = s.id   
ORDER BY b.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
         "id"           => (int)$row["id"],
    "email"        => $row["email"],
    "address"        => $row["address"],
    "createdAt"    => $row['createdAt'],   
    "customerName" => $row["customer"],
    "vehicleInfo"  => $row["vehicle_name"] ?? "N/A",
    "licensePlate" => $row["license_plate"] ?? "N/A",  // new
    "scheduleDate" => $row["preferred_time"],
    "scheduledTime"=> $row["time"],       // separate date & time if stored separately
    "serviceType"  => $row["service"],
    "status"       => $row["status"],
    "vehicle_model"       => $row["vehicle_model"],
    "vehicle_plate_number"       => $row["vehicle_plate_number"],
    "totalCost"    => "₱" . number_format($row["total_cost"], 0, '.', ','),
    "phone"        => $row["contact"] ?? "N/A",        // new
    "estimatedDuration" => $row["duration"] ?? "N/A", // new
    "notes"        => $row["notes"] ?? "WA/A"          // new
        
    ];
}

header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);

$stmt->close();
$conn->close();
?>

