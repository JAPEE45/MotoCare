<?php
    include "db.php";
    $bookingId = $_GET['id'];
    $status = "pending";
    $stmt = $conn->prepare("UPDATE booking SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $bookingId);
    if($stmt->execute()){
        echo json_encode(["success", "true"]);
    }else{
        echo json_encode(["error", $stmt->error]);
    }
$stmt->close();
$conn->close();
?>