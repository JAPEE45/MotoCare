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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AutoRepair Shop - Admin Dashboard</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="../../assets/styles/admin-dashboard.css">
    <link rel="stylesheet" href="../../assets/styles/sidebar.css">
  </head>
  <body>
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <!-- <div class="sidebar-logo">
          <img src="../../assets/images/logo.svg" alt="MotoCare Logo" onerror="this.style.display='none'">
        </div> -->
        <span class="sidebar-title">AutoRepair Shop</span>
      </div>
      
      <nav class="sidebar-nav">
        <a href="./dashboard.php" class="nav-item active">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="./shop-management.php" class="nav-item">
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
      <span class="mobile-title">AutoRepair Shop</span>
      <div class="mobile-user">
        <?php echo strtoupper(substr($row['fullname'], 0, 1)); ?>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
      <div class="content-wrapper">
      <div class="mb-4">
        <h1 class="h3 fw-bold">Dashboard Overview</h1>
        <p class="text-muted">
          Welcome back! Here's what's happening with your auto repair shops today.
        </p>
      </div>

      <!-- Stats Cards -->
      <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="dashboard-card">
            <div class="card-icon red">
              <i class="fas fa-store"></i>
            </div>
            <div class="card-title">Total Shops</div>
            <div class="card-value" id="totalShops">0</div>
            <div class="card-trend up">
              <i class="fas fa-arrow-up"></i> <span id="newShopsThisMonth">0</span>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
          <div class="dashboard-card">
            <div class="card-icon blue">
              <i class="fas fa-wrench"></i>
            </div>
            <div class="card-title">Active Services</div>
            <div class="card-value" id="totalServices">0</div>
          </div>
        </div>

        
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="dashboard-card">
            <div class="card-icon orange">
              <i class="fas fa-users"></i>
            </div>
            <div class="card-title">Total Customers</div>
            <div class="card-value" id="totalCustomers">0</div>
            <div class="card-trend up">
              <i class="fas fa-arrow-up"></i> <span id="newCustomers">0</span>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
          <div class="dashboard-card">
            <div class="card-icon green">
              <i class="fas fa-peso-sign"></i>
            </div>
            <div class="card-title">Total Revenue</div>
            <div class="card-value" id="totalRevenue">₱0</div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
          <div class="dashboard-card">
            <div class="card-icon purple">
              <i class="fas fa-calendar-check"></i>
            </div>
            <div class="card-title">Total Bookings</div>
            <div class="card-value" id="totalBookings">0</div>
          </div>
        </div>
      </div>

      <!-- Charts and Activity -->
      <div class="row g-4">
        <div class="col-lg-12">
          <div class="chart-card">
            <div class="chart-header">
              <h2 class="chart-title">Recent Activity</h2>
            </div>
            <div id="recentActivity">
              <p class="text-muted">Loading...</p>
            </div>
          </div>
        </div>
      </div>
      </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script src="../../assets/scripts/sidebar.js"></script>
    <script src="../../assets/scripts/admin-dashboard.js"></script>
  </body>
</html>
