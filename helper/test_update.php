<?php
// Test file to debug shop update
include_once 'db.php';
header('Content-Type: text/html');

echo "<h2>Database Test</h2>";

// Test connection
if ($conn) {
    echo "<p style='color:green'>✓ Database connected</p>";
} else {
    echo "<p style='color:red'>✗ Database connection failed</p>";
    exit;
}

// Get current shops
echo "<h3>Current Shops in Database:</h3>";
$result = $conn->query("SELECT id, name, address, lat, lg FROM shop");
echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Address</th><th>Lat</th><th>Lng</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
    echo "<td>" . $row['lat'] . "</td>";
    echo "<td>" . $row['lg'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test update form
echo "<h3>Test Update:</h3>";
echo "<form method='POST'>";
echo "<input type='hidden' name='test_update' value='1'>";
echo "Shop ID: <input type='text' name='shop_id' value='1'><br><br>";
echo "New Name: <input type='text' name='shop_name' value='Test Update Name'><br><br>";
echo "Address: <input type='text' name='address' value='Test Address'><br><br>";
echo "Lat: <input type='text' name='lat' value='13.5875'><br><br>";
echo "Lng: <input type='text' name='lng' value='124.237'><br><br>";
echo "<button type='submit'>Test Update</button>";
echo "</form>";

// Process test update
if (isset($_POST['test_update'])) {
    echo "<h3>Update Test Results:</h3>";
    
    $shop_id = intval($_POST['shop_id']);
    $shop_name = $_POST['shop_name'];
    $address = $_POST['address'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    
    echo "<p>Updating shop ID: $shop_id</p>";
    echo "<p>New name: $shop_name</p>";
    echo "<p>New address: $address</p>";
    
    // First check if shop exists
    $check = $conn->query("SELECT * FROM shop WHERE id = $shop_id");
    if ($check->num_rows == 0) {
        echo "<p style='color:red'>✗ Shop ID $shop_id not found!</p>";
    } else {
        $oldData = $check->fetch_assoc();
        echo "<p>Current name in DB: " . htmlspecialchars($oldData['name']) . "</p>";
        
        // Try the update
        $stmt = $conn->prepare("UPDATE shop SET name = ?, address = ?, lat = ?, lg = ? WHERE id = ?");
        $stmt->bind_param('ssssi', $shop_name, $address, $lat, $lng, $shop_id);
        
        if ($stmt->execute()) {
            echo "<p style='color:green'>✓ Execute successful</p>";
            echo "<p>Affected rows: " . $stmt->affected_rows . "</p>";
            
            // Verify the update
            $verify = $conn->query("SELECT * FROM shop WHERE id = $shop_id");
            $newData = $verify->fetch_assoc();
            echo "<p>Name after update: " . htmlspecialchars($newData['name']) . "</p>";
            
            if ($newData['name'] === $shop_name) {
                echo "<p style='color:green'>✓ Update VERIFIED - Data changed in database!</p>";
            } else {
                echo "<p style='color:red'>✗ Update FAILED - Data did NOT change!</p>";
            }
        } else {
            echo "<p style='color:red'>✗ Execute failed: " . $stmt->error . "</p>";
        }
    }
    
    echo "<hr><h3>Shops After Update:</h3>";
    $result = $conn->query("SELECT id, name, address, lat, lg FROM shop");
    echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Address</th><th>Lat</th><th>Lng</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['address']) . "</td>";
        echo "<td>" . $row['lat'] . "</td>";
        echo "<td>" . $row['lg'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

$conn->close();
?>
