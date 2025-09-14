<?php
include_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $email = $data['email'];
    $fullname = $data['fullname'];
    $password = $data['password'];
    $contact = $data['contact'];  
    $email_id = $data['email_id'];
    $picture = $data['picture'];
    $address = $data['address'];
    
    $check = $conn->prepare("SELECT id FROM user WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode([
            "status" => "error", 
            "message" => "Email already exists"
        ]);
        $check->close();
        $conn->close();
        exit();
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO user (fullname, email, password, contact, email_id, picture, address) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $fullname, $email, $password, $contact, $email_id, $picture, $address);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['user'] = $email_id;
        echo json_encode([
            "status" => "success", 
            "message" => "User added successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "No data received"
    ]);
}

$conn->close();
?>
