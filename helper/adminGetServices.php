<?php
    include_once "db.php";
    $shop_id = $_GET['shop_id'];
    $stmt = $conn->prepare("SELECT * from services WHERE shop_id = ?");
    $stmt->bind_param("s", $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $res = [];
    while($r = $result->fetch_assoc()){
        $res[] = [
            "id"=> $r['id'],
            "name"=> $r['service_name'],
            "description"=> $r['description'],
            "minPrice"=> $r['min_cost'],
            "maxPrice"=> $r['max_cost'],
            "icon"=> $r['icon'],
        ];
    }
    echo json_encode($res);
    $stmt->close();
    $conn->close();

?>