<?php
header("Content-Type: application/json");

// connect to DB
include_once 'db.php'; // this must contain $conn = new mysqli(...)

// get JSON request body
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

$email      = $input['email'] ?? null;
$password   = $input['password'] ?? null;
$picture    = $input['picture'] ?? null;
$fullname   = $input['fullname'] ?? null;
$email_id   = $input['email_id'] ?? null;
$address    = $input['address'] ?? null;
$contact    = $input['contact'] ?? null;

if (!$email || !$password || !$fullname) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

try {
    // check if email already exists
    $check = $conn->prepare("SELECT id FROM user WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists"]);
        exit;
    }

    // insert new user (password stored as plain text)
    $stmt = $conn->prepare("INSERT INTO user (email, password, picture, fullname, email_id, address, contact, createdAt) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssss", $email, $password, $picture, $fullname, $email_id, $address, $contact);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User registered successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to register user"]);
    }

    $stmt->close();
    $check->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
