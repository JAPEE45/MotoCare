<?php
include_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

try {
    if (empty($_POST['shop_id'])) {
        throw new Exception("Missing shop ID");
    }
    
    $shop_id = intval($_POST['shop_id']);
    
    // Check if shop has bookings
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM booking WHERE shop = ?");
    $stmt->bind_param('i', $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Cannot delete shop with existing bookings. Please contact system administrator.'
        ]);
        exit;
    }
    
    // Get owner_id before deleting shop
    $stmt = $conn->prepare("SELECT owner_id FROM shop WHERE id = ?");
    $stmt->bind_param('i', $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $shop = $result->fetch_assoc();
    $owner_id = $shop['owner_id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    // Delete associated services
    $stmt = $conn->prepare("DELETE FROM services WHERE shop_id = ?");
    $stmt->bind_param('i', $shop_id);
    $stmt->execute();
    
    // Delete shop
    $stmt = $conn->prepare("DELETE FROM shop WHERE id = ?");
    $stmt->bind_param('i', $shop_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to delete shop: " . $stmt->error);
    }
    
    // Delete owner account (optional - you may want to keep the account but set shop_id to 0)
    if ($owner_id) {
        $stmt = $conn->prepare("UPDATE user SET shop_id = 0 WHERE ID = ?");
        $stmt->bind_param('i', $owner_id);
        $stmt->execute();
    }
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Shop deleted successfully'
    ]);
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

$stmt->close();
$conn->close();
?>
