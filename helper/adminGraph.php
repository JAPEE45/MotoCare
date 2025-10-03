<?php
    include_once "db.php";
    $shop_id = $_GET['shop_id'];
    $stmt = $conn->prepare("SELECT
    (SELECT COUNT(*) FROM booking WHERE status = 'pending' AND shop = ?) as pending_count,
    (SELECT COUNT(*) FROM booking WHERE status = 'progress' AND shop = ?) as progress_count,
    (SELECT COUNT(*) FROM booking WHERE status = 'completed' AND shop = ?) as completed_count,
    (SELECT COUNT(*) FROM booking WHERE status = 'cancelled' AND shop = ?) as cancelled_count
    ");
    $stmt->bind_param("iiii", $shop_id,$shop_id,$shop_id,$shop_id);
    $stmt->execute();
    $res = $stmt->get_result();
    


?>