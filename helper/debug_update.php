<?php
include_once 'db.php';
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Shop Update</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; }
        table { border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        form { margin: 20px 0; padding: 20px; background: #e8e8e8; }
        input, button { margin: 5px; padding: 8px; }
    </style>
</head>
<body>
    <h1>Debug Shop Update</h1>
    
    <?php
    // Show current shops
    echo "<h2>Current Shops in Database</h2>";
    $result = $conn->query("SELECT id, name, address, lat, lg FROM shop ORDER BY id");
    
    if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Address</th><th>Lat</th><th>Lg</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
            echo "<td>" . htmlspecialchars($row['lat']) . "</td>";
            echo "<td>" . htmlspecialchars($row['lg']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>No shops found or error: " . $conn->error . "</p>";
    }
    ?>
    
    <h2>Test Manual Update</h2>
    <form method="POST">
        <label>Shop ID: <input type="number" name="test_shop_id" value="1"></label><br>
        <label>New Name: <input type="text" name="test_name" value="TEST NAME <?php echo time(); ?>"></label><br>
        <label>New Address: <input type="text" name="test_address" value="TEST ADDRESS"></label><br>
        <label>Lat: <input type="text" name="test_lat" value="14.5995"></label><br>
        <label>Lng (lg column): <input type="text" name="test_lng" value="120.9842"></label><br>
        <button type="submit" name="do_update">Test Update</button>
    </form>
    
    <?php
    if (isset($_POST['do_update'])) {
        echo "<h2>Update Test Results</h2>";
        
        $shop_id = intval($_POST['test_shop_id']);
        $new_name = $_POST['test_name'];
        $new_address = $_POST['test_address'];
        $lat = $_POST['test_lat'];
        $lng = $_POST['test_lng'];
        
        echo "<p class='info'>Attempting update for shop ID: $shop_id</p>";
        echo "<p class='info'>New name: $new_name</p>";
        echo "<p class='info'>New address: $new_address</p>";
        echo "<p class='info'>Lat: $lat, Lng: $lng</p>";
        
        // Get current data
        $before = $conn->query("SELECT * FROM shop WHERE id = $shop_id");
        if ($before && $before->num_rows > 0) {
            $beforeData = $before->fetch_assoc();
            echo "<p class='info'>BEFORE update:</p>";
            echo "<pre>" . print_r($beforeData, true) . "</pre>";
        } else {
            echo "<p class='error'>Shop with ID $shop_id not found!</p>";
        }
        
        // Do the update
        $sql = "UPDATE shop SET name = ?, address = ?, lat = ?, lg = ? WHERE id = ?";
        echo "<p class='info'>SQL: $sql</p>";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ssssi', $new_name, $new_address, $lat, $lng, $shop_id);
            $result = $stmt->execute();
            
            echo "<p>Execute result: " . ($result ? "<span class='success'>SUCCESS</span>" : "<span class='error'>FAILED</span>") . "</p>";
            echo "<p>Affected rows: " . $stmt->affected_rows . "</p>";
            echo "<p>Error (if any): " . $stmt->error . "</p>";
            
            $stmt->close();
        } else {
            echo "<p class='error'>Prepare failed: " . $conn->error . "</p>";
        }
        
        // Get data after update
        $after = $conn->query("SELECT * FROM shop WHERE id = $shop_id");
        if ($after && $after->num_rows > 0) {
            $afterData = $after->fetch_assoc();
            echo "<p class='info'>AFTER update:</p>";
            echo "<pre>" . print_r($afterData, true) . "</pre>";
            
            // Compare
            if ($afterData['name'] === $new_name) {
                echo "<p class='success'>✓ Name updated correctly!</p>";
            } else {
                echo "<p class='error'>✗ Name NOT updated! DB has: " . $afterData['name'] . "</p>";
            }
        }
    }
    ?>
    
    <h2>Database Connection Info</h2>
    <?php
    echo "<p>Server: " . $conn->server_info . "</p>";
    echo "<p>Host info: " . $conn->host_info . "</p>";
    
    // Check for autocommit
    $autocommit = $conn->query("SELECT @@autocommit")->fetch_row()[0];
    echo "<p>Autocommit: " . ($autocommit ? 'ON' : 'OFF') . "</p>";
    ?>
</body>
</html>
