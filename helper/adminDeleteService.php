<?php
    include_once "db.php";
    $service_id = $_GET['service_id'];
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    if($stmt->execute()){
        echo json_encode(["success"=>true]);
    }else{
        echo json_encode(["error"=>$stmt->error]);
    }
$stmt->close();
$conn->close();
?>