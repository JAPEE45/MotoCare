<?php
include_once 'db.php';
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

try {
    // Log received data for debugging
    error_log("Update Shop - Received POST data: " . print_r($_POST, true));
    
    // Validate required fields
    if (empty($_POST['shop_id'])) {
        throw new Exception("Missing shop ID");
    }
    
    $shop_id = intval($_POST['shop_id']);
    $shop_name = $_POST['shop_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $lat = $_POST['lat'] ?? '';
    $lng = $_POST['lng'] ?? '';
    $owner_id = !empty($_POST['owner_id']) ? intval($_POST['owner_id']) : null;
    $owner_name = $_POST['owner_name'] ?? null;
    $contact = $_POST['contact'] ?? null;
    
    // Validate required fields
    if (empty($shop_name) || empty($address)) {
        throw new Exception("Shop name and address are required");
    }
    
    if (empty($lat) || empty($lng)) {
        throw new Exception("Location coordinates are required");
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    // Update shop
    $stmt = $conn->prepare("UPDATE shop SET name = ?, address = ?, lat = ?, lg = ? WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param('ssssi',
        $shop_name,
        $address,
        $lat,
        $lng,
        $shop_id
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update shop: " . $stmt->error);
    }
    
    $rows_affected = $stmt->affected_rows;
    error_log("Shop update affected rows: " . $rows_affected);
    $rows_affected = $stmt->affected_rows;
    error_log("Shop update affected rows: " . $rows_affected);
    
    // Update owner info if provided
    if ($owner_id && ($owner_name || $contact)) {
        $stmt = $conn->prepare("UPDATE user SET fullname = COALESCE(NULLIF(?, ''), fullname), contact = COALESCE(NULLIF(?, ''), contact), address = ? WHERE ID = ?");
        if (!$stmt) {
            throw new Exception("Prepare owner update failed: " . $conn->error);
        }
        
        $stmt->bind_param('sssi',
            $owner_name,
            $contact,
            $address,
            $owner_id
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to update owner: " . $stmt->error);
        }
        
        error_log("Owner update affected rows: " . $stmt->affected_rows);
    }
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Shop updated successfully',
        'shop_id' => $shop_id,
        'rows_affected' => $rows_affected
    ]);
    
} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollback();
    }
    error_log("Update shop error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

if (isset($stmt)) {
    $stmt->close();
}
if (isset($conn)) {
    $conn->close();
}
?>
