<?php
header('Content-Type: application/json');
include_once 'db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'No input data']);
    exit;
}


$fullname = $input['fullname'] ?? $input['name'] ?? '';
$email = $input['email'] ?? '';
$address = $input['address'] ?? '';
$contact = $input['contact'] ?? '';

$stmt = $conn->prepare("UPDATE user SET fullname = ?, email = ?, address = ?, contact = ? WHERE ID = ?");
$stmt->bind_param("ssssi", $fullname, $email, $address, $contact, $user_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Account info updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
$stmt->close();
$conn->close();
?>

<script>
document.getElementById("fullName").value = data.name;

document.getElementById("someId").style.display = "none";
</script>