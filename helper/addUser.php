<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $username = $data["username"];
    $password = $data["password"];

    $stmt = $conn->prepare("SELECT id, username, password FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Direct plain-text comparison
        if ($password === $row["password"]) {
            echo json_encode(["success" => "hi", "user" => $row["username"]]);
        } else {
            echo json_encode(["error" => "invalid password"]);
        }
    } else {
        echo json_encode(["error" => "no user"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "no data"]);
}

$conn->close();
?>
