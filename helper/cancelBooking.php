<?php
    include_once 'db.php';
    session_start();
    $id = $_GET['bookingId'];
    $stmt = $conn->prepare("UPDATE booking SET status = 'cancelled' WHERE  id = ?");
    $stmt->bind_param("i",$id);
    if($stmt->execute()){
        echo json_encode(["success"=> true]);
    }else{
        echo json_encode(['error'=> $stmt->error]);
    }
    $stmt->close();
    $conn->close();
?>