<?php
    include_once "db.php";
    $shop_id = $_GET['shop_id'];
    $stmt = $conn->prepare("SELECT
    (SELECT COUNT(*) FROM booking WHERE status = 'pending' AND shop = ?) as pending,
    (SELECT COUNT(*) FROM booking WHERE status = 'progress' AND shop = ?) as confirmed,
    (SELECT COUNT(*) FROM booking WHERE status = 'completed' AND shop = ?) as completed,
    (SELECT COUNT(*) FROM booking WHERE status = 'cancelled' AND shop = ?) as cancelled
    ");
    $stmt->bind_param("iiii", $shop_id,$shop_id,$shop_id,$shop_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    echo json_encode($row);


?>