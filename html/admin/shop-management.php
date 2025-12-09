<?php
include '../../helper/db.php';  
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}

$ID = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user WHERE ID = ?");
$stmt->bind_param("i", $ID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['role'] !== 'admin') {
    header("Location: ../signin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoCare - Shop Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />
    
    <link rel="stylesheet" href="../../assets/styles/admin-shop-management.css">
    <link rel="stylesheet" href="../../assets/styles/sidebar.css">
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <div class="sidebar-logo">
          <img src="../../assets/images/logo.svg" alt="MotoCare Logo" onerror="this.style.display='none'">
          <i class="fa-solid fa-motorcycle" style="display:none"></i>
        </div>
        <span class="sidebar-title">MotoCare</span>
      </div>
      
      <nav class="sidebar-nav">
        <a href="./dashboard.php" class="nav-item">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="./shop-management.php" class="nav-item active">
          <i class="fas fa-store"></i>
          <span>Shop Management</span>
        </a>
      </nav>
      
      <div class="sidebar-footer">
        <div class="user-info">
          <div class="user-avatar">
            <?php echo strtoupper(substr($row['fullname'], 0, 1)); ?>
          </div>
          <div class="user-details">
            <span class="user-name"><?php echo $row['fullname'] ?></span>
            <span class="user-role">Admin</span>
          </div>
        </div>
        <a href="../../helper/logout.php" class="nav-item logout-btn">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </a>
      </div>
      
      <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-chevron-left"></i>
      </button>
    </aside>
    
    <!-- Mobile Header -->
    <header class="mobile-header" id="mobileHeader">
      <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
      </button>
      <span class="mobile-title">MotoCare</span>
      <div class="mobile-user">
        <?php echo strtoupper(substr($row['fullname'], 0, 1)); ?>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
      <div class="content-wrapper">
        <div class="container-main">
        <div class="page-header">
            <h2>Shop Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addShopModal">+ Add New Shop</button>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <input type="text" class="form-control" id="searchInput" placeholder="Search shops by name, owner, or address...">
        </div>

        <!-- Table -->
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Shop</th>
                        <th>Owner</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="shopsTableBody">
                    <!-- Data will be populated here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Shop Modal -->
    <div class="modal fade" id="addShopModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Shop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addShopForm">
                        <div class="mb-3">
                            <label class="form-label">Shop Name</label>
                            <input type="text" class="form-control" id="shopName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Owner Name</label>
                            <input type="text" class="form-control" id="ownerName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contactNumber" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Owner Email</label>
                            <input type="email" class="form-control" id="ownerEmail" required>
                            <small class="text-muted">A password will be auto-generated and sent to this email.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" id="address" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pin Location on Map</label>
                            <p class="coordinates" id="coordinates">Latitude: - | Longitude: -</p>
                            <div id="map"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addShop()">Add Shop</button>
                </div>
            </div>
        </div>
    </div>
        </div>

    <!-- View Shop Modal -->
    <div class="modal fade" id="viewShopModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Shop Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Shop Name</label>
                        <p class="form-control-plaintext" id="viewShopName"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Owner Name</label>
                        <p class="form-control-plaintext" id="viewOwnerName"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <p class="form-control-plaintext" id="viewContactNumber"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <p class="form-control-plaintext" id="viewEmail"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <p class="form-control-plaintext" id="viewAddress"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bookings</label>
                        <p class="form-control-plaintext" id="viewBookings"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Revenue</label>
                        <p class="form-control-plaintext" id="viewRevenue"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <p class="coordinates" id="viewCoordinates"></p>
                        <div id="mapView"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Shop Modal -->
    <div class="modal fade" id="editShopModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Shop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editShopForm">
                        <input type="hidden" id="editShopId">
                        <div class="mb-3">
                            <label class="form-label">Shop Name</label>
                            <input type="text" class="form-control" id="editShopName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Owner Name</label>
                            <input type="text" class="form-control" id="editOwnerName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="editContactNumber" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" id="editAddress" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Update Location on Map</label>
                            <p class="coordinates" id="editCoordinates"></p>
                            <div id="mapEdit"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateShop()">Update Shop</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Shop Modal -->
    <div class="modal fade" id="deleteShopModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Shop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteShopName"></strong>?</p>
                    <p class="text-danger">This action cannot be undone.</p>
                    <input type="hidden" id="deleteShopId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                </div>
            </div>
        </div>
    </div>
      </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script src="../../assets/scripts/sidebar.js"></script>
    <script src="../../assets/scripts/admin-shop-management.js"></script>
</body>
</html>