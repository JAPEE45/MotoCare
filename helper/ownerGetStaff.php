<?php
    include_once "db.php";
    session_start();
    $shop_id = $_GET['shop_id'];

    $stmt = $conn->prepare("SELECT * from user WHERE shop_id = ? ");
    $stmt->bind_param("i", $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $res = [];
    while($r = $result->fetch_assoc()){
        $res[] = [
            "fullname"=>$r['fullname'],
            "id"=>$r['ID'],
            "email"=>$r['email'],
            "phone"=>$r['contact'],
            "role"=>$r['role'],
            "address"=>$r['address'],
            "joinDate"=>$r['createdAt'],
            "order"=> 1,
            "status"=>$r['status'],
            "lastLogin"=>"dati pa"
        ];
    }
    session_abort();
    echo json_encode($res);
    $stmt->close();
    $conn->close();


?>