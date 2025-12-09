<?php
// This is a one-time script to create an admin account
// Run this file once in your browser, then delete or comment it out

include_once 'db.php';

// Admin account details
$email = 'admin@motocare.com';
$password = password_hash('admin123', PASSWORD_DEFAULT); // Change this password!
$fullname = 'Admin User';

// Check if admin already exists
$check = $conn->prepare("SELECT ID FROM user WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "Admin account already exists!";
} else {
    // Create admin account
    $stmt = $conn->prepare("INSERT INTO user (fullname, email, password, role, createdAt) VALUES (?, ?, ?, 'admin', NOW())");
    $stmt->bind_param("sss", $fullname, $email, $password);
    
    if ($stmt->execute()) {
        echo "Admin account created successfully!<br>";
        echo "Email: " . $email . "<br>";
        echo "Password: admin123<br>";
        echo "<strong>Please change the password after first login!</strong><br>";
        echo "<br><strong>IMPORTANT: Delete this file after running it!</strong>";
    } else {
        echo "Error creating admin account: " . $conn->error;
    }
}

$conn->close();
?>
