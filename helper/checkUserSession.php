<?php
/**
 * Check if user has an active session
 * Returns JSON response indicating login status
 */
session_start();
header('Content-Type: application/json');

$response = [
    'loggedIn' => false,
    'user' => null
];

if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    include 'db.php';
    
    $stmt = $conn->prepare("SELECT ID, fullname, email_id, role FROM user WHERE email_id = ?");
    $stmt->bind_param("s", $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $response['loggedIn'] = true;
        $response['user'] = [
            'id' => $user['ID'],
            'fullname' => $user['fullname'],
            'email' => $user['email_id'],
            'role' => $user['role']
        ];
    }
    
    $stmt->close();
    $conn->close();
}

echo json_encode($response);
?>
