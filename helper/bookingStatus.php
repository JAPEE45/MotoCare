<?php
include_once 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id = $_SESSION['user'] ?? $_SESSION['user_id'] ?? null;
$bookingId = $_GET['buid'] ?? null;

$error = "";
$obj = "";
if ($bookingId) {
    $sql = "SELECT 
        b.id AS booking_id,
        b.transaction_number,
        b.preferred_time,
        b.time,
        u.id AS user_id,
        u.fullname,
        u.email_id,
        s.id AS service_id,
        s.service_name,
        b.service_ids,
        b.vehicle_name,
        b.vehicle_model,
        b.vehicle_plate_number,
        b.total_cost,
        b.status,
        b.notes,
        h.name as shop_name,
        h.address
    FROM booking b
    INNER JOIN user u ON b.user_id = u.id
    LEFT JOIN services s ON b.service_id = s.id
    INNER JOIN shop h ON h.id = b.shop
    WHERE u.email_id = ? AND b.id = ? AND b.status <> 'finish'
    ORDER BY b.id DESC
    LIMIT 1;
    ";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL prepare failed: " . $conn->error);
    }
    if ($stmt->execute([$id, $bookingId])) {
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
} else {
    // Get most recent booking for user
    $sql = "SELECT 
        b.id AS booking_id,
        b.transaction_number,
        b.preferred_time,
        b.time,
        u.id AS user_id,
        u.fullname,
        u.email_id,
        s.id AS service_id,
        s.service_name,
        b.service_ids,
        b.vehicle_name,
        b.vehicle_model,
        b.vehicle_plate_number,
        b.total_cost,
        b.status,
        b.notes,
        h.name as shop_name,
        h.address
    FROM booking b
    INNER JOIN user u ON b.user_id = u.id
    LEFT JOIN services s ON b.service_id = s.id
    INNER JOIN shop h ON h.id = b.shop
    WHERE u.email_id = ? AND b.status <> 'finish'
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
}
$conn->close();
?>