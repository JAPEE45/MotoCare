<?php
include_once 'db.php';
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

try {
    // Get POST data
    $shop_id = isset($_POST['shop_id']) ? intval($_POST['shop_id']) : 0;
    $shop_name = isset($_POST['shop_name']) ? trim($_POST['shop_name']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $lat = isset($_POST['lat']) ? $_POST['lat'] : '';
    $lng = isset($_POST['lng']) ? $_POST['lng'] : '';
    $owner_id = isset($_POST['owner_id']) && $_POST['owner_id'] !== '' ? intval($_POST['owner_id']) : null;
    $owner_name = isset($_POST['owner_name']) ? trim($_POST['owner_name']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
    
    // Validate
    if ($shop_id <= 0) {
        throw new Exception("Invalid shop ID: " . $shop_id);
    }
    
    if (empty($shop_name)) {
        throw new Exception("Shop name is required");
    }
    
    if (empty($address)) {
        throw new Exception("Address is required");
    }
    
    // Check if shop exists
    $check = $conn->prepare("SELECT * FROM shop WHERE id = ?");
    $check->bind_param('i', $shop_id);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Shop with ID $shop_id not found");
    }
    
    $oldShop = $result->fetch_assoc();
    
    // Update shop - use direct query for debugging
    $updateSQL = "UPDATE shop SET name = ?, address = ?, lat = ?, lg = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSQL);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param('ssssi', $shop_name, $address, $lat, $lng, $shop_id);
    
    $executeResult = $stmt->execute();
    
    if (!$executeResult) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $affectedRows = $stmt->affected_rows;
    
    // Verify the update by selecting the data again
    $verify = $conn->prepare("SELECT * FROM shop WHERE id = ?");
    $verify->bind_param('i', $shop_id);
    $verify->execute();
    $newResult = $verify->get_result();
    $newShop = $newResult->fetch_assoc();
    
    // Update owner if provided
    $ownerUpdated = false;
    $ownerAffectedRows = 0;
    $ownerError = null;
    $oldOwnerData = null;
    $newOwnerData = null;
    
    if ($owner_id && $owner_id > 0) {
        // Get old owner data
        $oldOwnerQuery = $conn->prepare("SELECT ID, fullname, contact FROM user WHERE ID = ?");
        $oldOwnerQuery->bind_param('i', $owner_id);
        $oldOwnerQuery->execute();
        $oldOwnerResult = $oldOwnerQuery->get_result();
        $oldOwnerData = $oldOwnerResult->fetch_assoc();
        
        // Update owner
        $ownerStmt = $conn->prepare("UPDATE user SET fullname = ?, contact = ? WHERE ID = ?");
        if ($ownerStmt) {
            $ownerStmt->bind_param('ssi', $owner_name, $contact, $owner_id);
            $ownerStmt->execute();
            $ownerAffectedRows = $ownerStmt->affected_rows;
            $ownerUpdated = $ownerAffectedRows >= 0; // 0 means no change but query ran
            $ownerError = $ownerStmt->error;
            
            // Get new owner data
            $newOwnerQuery = $conn->prepare("SELECT ID, fullname, contact FROM user WHERE ID = ?");
            $newOwnerQuery->bind_param('i', $owner_id);
            $newOwnerQuery->execute();
            $newOwnerResult = $newOwnerQuery->get_result();
            $newOwnerData = $newOwnerResult->fetch_assoc();
        } else {
            $ownerError = $conn->error;
        }
    }
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Shop updated successfully',
        'debug' => [
            'shop_id' => $shop_id,
            'shop_affected_rows' => $affectedRows,
            'old_shop_name' => $oldShop['name'],
            'new_shop_name' => $newShop['name'],
            'shop_name_changed' => $oldShop['name'] !== $newShop['name'],
            'owner_id_received' => $owner_id,
            'owner_name_received' => $owner_name,
            'contact_received' => $contact,
            'owner_affected_rows' => $ownerAffectedRows,
            'owner_updated' => $ownerUpdated,
            'owner_error' => $ownerError,
            'old_owner_data' => $oldOwnerData,
            'new_owner_data' => $newOwnerData
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'received_post' => $_POST
    ]);
}
?>
