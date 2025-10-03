<?php
    include_once 'db.php';
    session_start();
    $user_id = $_GET['ddd'];
    $user = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * 
FROM booking 
WHERE shop = ? AND user_id = ?
  AND not status = 'cancelled' 
  AND not status = 'done'
  or status = 'pending'
  AND status = 'not accepted';
");
    $stmt->bind_param("ii",$user_id, $user);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        echo json_encode(["success"=> $row]);
    }else{
        echo json_encode(['error'=> $stmt->error]);
    }
    $stmt->close();
    $conn->close();
?>