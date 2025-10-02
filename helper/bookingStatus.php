<?php
include_once 'db.php';
session_start();
$id = $_SESSION['user'];

$error = "";
$obj = "";
$sql = "
SELECT 
    b.id AS booking_id,
    b.preferred_time,
    b.time,
    u.id AS user_id,
    u.fullname,
    u.email_id,
    s.id AS service_id,
    s.service_name,
    b.vehicle_name,
    b.total_cost,
    b.status,
    b.notes
FROM booking b
INNER JOIN user u ON b.user_id = u.id
INNER JOIN services s ON b.service_id = s.id
WHERE u.email_id = ? AND b.status <> 'done'
ORDER BY b.id DESC
LIMIT 1;

";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL prepare failed: " . $conn->error);
}

if ($stmt->execute([$id])) {
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) { 
        echo json_encode($row);

    }else {
        echo json_encode([
            "error"=>$stmt->error
        ]);
    }
} else {
    echo "There's an error: " . $stmt->error;
    $error = $stmt->error;
}

$stmt->close();
$conn->close();

?>