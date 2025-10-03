<?php
    include_once "db.php";

    $data = json_decode(file_get_contents("php://input"),true);
    if($data){
        $fullname = $data['fullname'];
        $email = $data['email'];
        $contact = $data['phone'];
        $status = $data['status'];
        $address = $data['address'];
        $user_id = $data['user_id'];
        $stmt = $conn->prepare("UPDATE user SET fullname = ?, email = ?, contact = ?, status = ?, address = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $fullname, $email, $contact, $status, $address, $user_id);
        if($stmt->execute()){
            echo json_encode(['success'=> true]);    
        }else{
            echo json_encode(['error'=> $stmt->error]);   
        }

    }else{
        echo json_encode(['error'=> "no data"]);
    }
    $stmt->close();
    $conn->close();
?>