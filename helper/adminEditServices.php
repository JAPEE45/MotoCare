<?php
    include_once "db.php";
    $data = json_decode(file_get_contents("php://input"),true);
    if($data){
        $service_name = $data['name'];
        $description = $data['description'];
        $minPrice = $data['minPrice'];
        $maxPrice = $data['maxPrice'];
        $icon = $data['icon'];
        $id = $data['id'];
        $stmt = $conn->prepare("UPDATE services SET service_name = ?, description = ?, min_cost = ?, max_cost = ?, icon = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $service_name, $description, $minPrice, $maxPrice, $icon, $id);
        if($stmt->execute()){
            echo json_encode(['success'=>"goods"]);
        }else{
            echo json_encode(['error'=>$stmt->error]);
        }
    }else{
        echo json_encode(['error'=>"no data"]);
    }
$stmt->close();
$conn->close();

?>