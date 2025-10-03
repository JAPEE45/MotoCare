
<?php
    include_once "db.php";
    $data = json_decode(file_get_contents("php://input"), true);
    if($data){
        $name = $data['name'];
        $description = $data['description'];
        $minPrice = $data['minPrice'];
        $maxPrice = $data['maxPrice'];
        $icon = $data['icon'];
        $shop_id = $data['shop_id'];
        $stmt = $conn->prepare("INSERT INTO services (service_name, description, min_cost, max_cost, icon, shop_id) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("sssssi", $name, $description, $minPrice, $maxPrice, $icon, $shop_id);
        if($stmt->execute()){
            echo json_encode(["success"=>true]);
        }else{
            echo json_encode(["error"=>true]);

        }
    }else{
        echo json_encode(["error"=>"no data"]);
    }
$stmt->close();
$conn->close();
?>