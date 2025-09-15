<?php

    include_once 'db.php';

    $id = (int)$_GET['id'];
    $status = $_GET['status'];
    $stmt = $conn->prepare('UPDATE booking SET status = ? WHERE id = ?');
    $stmt->bind_param('si', $status,$id );
    if($stmt->execute()){
        echo json_encode(["success"=>True]);
    }else{
        echo json_encode(["success"=>False]);
    }
$stmt->close();
$conn->close();
?>