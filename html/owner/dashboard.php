<?php
  include "../../helper/checkOwner.php";
  include_once "../../helper/db.php";
  $sid = $row['shop_id'];
  $stmt = $conn->prepare("SELECT
  (SELECT COUNT(*) FROM user WHERE role = 'staff' and shop_id =?) as staff_count,
  (SELECT COUNT(*) FROM services WHERE shop_id = ?) as services_count,
  (SELECT COUNT(*) FROM booking WHERE shop = ? AND status = 'not accepted') as appointment,
  (SELECT COUNT(distinct u.id) FROM user u RIGHT JOIN booking b ON u.id = b.user_id AND shop = ?) as customer_count
  ");
  $stmt->bind_param("iiii",$sid,$sid,$sid,$sid);
  $stmt->execute();
  $result = $stmt->get_result();
  $res = $result->fetch_assoc();
  


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Owner Dashboard - MotoCare</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <link rel="stylesheet" href="../../assets/styles/owner-dashboard.css">
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
        </div>
        <span class="sidebar-title">MotoCare</span>
      </div>
      
      <nav class="sidebar-nav">
        <a href="./dashboard.php" class="nav-item active">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="./user-management.php" class="nav-item">
          <i class="fas fa-users-cog"></i>
          <span>User Management</span>
        </a>
        <a href="./services.php" class="nav-item">
          <i class="fas fa-tools"></i>
          <span>Services</span>
        </a>
      </nav>
      
      <div class="sidebar-footer">
        <div class="user-info">
          <div class="user-avatar">
            <?php echo strtoupper(substr($row['fullname'], 0, 1)); ?>
          </div>
          <div class="user-details">
            <span class="user-name"><?php echo $row['fullname'] ?></span>
            <span class="user-role">Shop Owner</span>
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

    <main class="main-content" id="mainContent">
      <div class="content-wrapper">
      <div class="top-bar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <h2 class="gradient-text mb-0">Dashboard</h2>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="stats-card">
            <div class="row align-items-center">
              <div class="col-8">
                <h3 class="stats-number" id="totalCustomers"><?php echo $res['customer_count'] ?></h3>
                <p class="stats-label">Total Customers</p>
              </div>
              <div class="col-4 text-end">
                <i class="fas fa-users stats-icon"></i>
              </div>
            </div>
          </div>
        </div>
      <p id="shopId" style="display:none"><?php echo $sid ?></p>
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="stats-card">
            <div class="row align-items-center">
              <div class="col-8">
                <h3 class="stats-number" id="totalStaff"><?php echo $res['staff_count'] ?></h3>
                <p class="stats-label">Staff Members</p>
              </div>
              <div class="col-4 text-end">
                <i class="fas fa-user-tie stats-icon"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="stats-card">
            <div class="row align-items-center">
              <div class="col-8">
                <h3 class="stats-number" id="totalAppointments"><?php echo $row['shop_name'] ?></h3>
                <p class="stats-label">Shop</p>
              </div>
              <div class="col-4 text-end">
                <!-- <i class="fas fa-calendar-alt stats-icon"></i> -->
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="stats-card">
            <div class="row align-items-center">
              <div class="col-8">
                <h3 class="stats-number" id="totalRevenue"><?php echo $res['services_count'] ?></h3>
                <p class="stats-label">Services</p>
              </div>
              <div class="col-4 text-end">
                <i class="fa-solid fa-screwdriver-wrench stats-icon"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-8">
          <div class="chart-container">
            <h5 class="chart-title">Monthly Performance Overview</h5>
            <canvas id="performanceChart"></canvas>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="chart-container">
            <h5 class="chart-title">Appointment Status Distribution</h5>
            <canvas id="statusChart"></canvas>
          </div>
        </div>
      </div>      
      </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/scripts/owner-dashboard.js"></script>
    <script src="../../assets/scripts/sidebar.js"></script>
  </body>
</html>
