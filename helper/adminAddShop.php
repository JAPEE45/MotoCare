<?php
include_once 'db.php';
include_once 'mailer.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Function to generate a secure random password
function generatePassword($length = 10) {
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $numbers = '0123456789';
    $special = '!@#$%&*';
    
    // Ensure at least one of each type
    $password = '';
    $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
    $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
    $password .= $numbers[random_int(0, strlen($numbers) - 1)];
    $password .= $special[random_int(0, strlen($special) - 1)];
    
    // Fill the rest with random characters
    $allChars = $uppercase . $lowercase . $numbers . $special;
    for ($i = 4; $i < $length; $i++) {
        $password .= $allChars[random_int(0, strlen($allChars) - 1)];
    }
    
    // Shuffle the password
    return str_shuffle($password);
}

try {
    // Validate required fields (password no longer required - will be auto-generated)
    $required = ['shop_name', 'owner_name', 'contact', 'address', 'lat', 'lng', 'email'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    $shop_name = trim($_POST['shop_name']);
    $owner_name = trim($_POST['owner_name']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $email = trim(strtolower($_POST['email']));
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }
    
    // Check if email already exists in the system (check both email and email_id columns)
    $checkEmail = $conn->prepare("SELECT ID, email, email_id FROM user WHERE LOWER(email) = LOWER(?) OR LOWER(email_id) = LOWER(?)");
    if (!$checkEmail) {
        throw new Exception("Database error: " . $conn->error);
    }
    $checkEmail->bind_param('ss', $email, $email);
    $checkEmail->execute();
    $emailResult = $checkEmail->get_result();
    
    if ($emailResult->num_rows > 0) {
        $existingUser = $emailResult->fetch_assoc();
        $checkEmail->close();
        throw new Exception("This email address (" . $email . ") is already registered in the system. Please use a different email.");
    }
    $checkEmail->close();
    
    // Generate a secure random password
    $plainPassword = generatePassword(10);
    
    // Start transaction
    $conn->begin_transaction();
    
    // First, create owner user account
    $stmt = $conn->prepare("INSERT INTO user (fullname, email, contact, address, password, email_id, role, shop_id) VALUES (?, ?, ?, ?, ?, ?, 'owner', 0)");
    $email_id = $email;
    $stmt->bind_param('ssssss', 
        $owner_name,
        $email,
        $contact,
        $address,
        $plainPassword,
        $email_id
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to create owner account: " . $stmt->error);
    }
    
    $owner_id = $conn->insert_id;
    
    // Then create shop
    $icon = isset($_POST['icon']) ? $_POST['icon'] : 'fas fa-wrench';
    $hours = isset($_POST['hours']) ? $_POST['hours'] : '8';
    $rating = 0;
    
    $stmt = $conn->prepare("INSERT INTO shop (name, address, lat, lg, icon, hours, rating, owner_id, service_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param('ssssssii',
        $shop_name,
        $address,
        $lat,
        $lng,
        $icon,
        $hours,
        $rating,
        $owner_id
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to create shop: " . $stmt->error);
    }
    
    $shop_id = $conn->insert_id;
    
    // Update owner's shop_id
    $stmt = $conn->prepare("UPDATE user SET shop_id = ? WHERE ID = ?");
    $stmt->bind_param('ii', $shop_id, $owner_id);
    $stmt->execute();
    
    // Commit transaction
    $conn->commit();
    
    // Send email to the owner with login credentials
    $emailSubject = "Welcome to MotoCare - Your Shop Owner Account";
    $emailBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            .credentials { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea; }
            .credentials p { margin: 10px 0; }
            .label { color: #666; font-size: 14px; }
            .value { font-weight: bold; color: #333; font-size: 16px; }
            .password-box { background: #fff3cd; padding: 15px; border-radius: 5px; margin-top: 15px; }
            .warning { color: #856404; font-size: 14px; }
            .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
            .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🏍️ Welcome to MotoCare!</h1>
                <p>Your Shop Owner Account is Ready</p>
            </div>
            <div class='content'>
                <p>Hello <strong>{$owner_name}</strong>,</p>
                <p>Congratulations! Your shop <strong>\"{$shop_name}\"</strong> has been successfully registered on MotoCare.</p>
                
                <div class='credentials'>
                    <h3>🔐 Your Login Credentials</h3>
                    <p><span class='label'>Email:</span><br><span class='value'>{$email}</span></p>
                    <p><span class='label'>Password:</span><br><span class='value'>{$plainPassword}</span></p>
                </div>
                
                <div class='password-box'>
                    <p class='warning'>⚠️ <strong>Important:</strong> For security reasons, please change your password after your first login.</p>
                </div>
                
                <p>You can now log in to your owner dashboard to:</p>
                <ul>
                    <li>Manage your shop services</li>
                    <li>View and manage bookings</li>
                    <li>Track your revenue</li>
                    <li>Manage your staff</li>
                </ul>
                
                <div class='footer'>
                    <p>If you didn't expect this email, please contact our support team.</p>
                    <p>&copy; " . date('Y') . " MotoCare. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Try to send email
    $emailSent = false;
    $emailError = null;
    try {
        $emailSent = sendEmail($email, $emailSubject, $emailBody);
    } catch (Exception $e) {
        // Log email error but don't fail the whole operation
        $emailError = $e->getMessage();
        error_log("Failed to send email to $email: " . $emailError);
    }
    
    echo json_encode([
        'status' => 'success',
        'message' => $emailSent ? 'Shop added successfully! Credentials sent to owner email.' : 'Shop added but email failed to send. Generated password: ' . $plainPassword,
        'shop_id' => $shop_id,
        'owner_id' => $owner_id,
        'email_sent' => $emailSent,
        'email_error' => $emailError,
        'generated_password' => $emailSent ? null : $plainPassword
    ]);
    
} catch (Exception $e) {
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
