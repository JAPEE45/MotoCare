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
    <title>Auto Repair Hub - Admin Dashboard</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <link rel="stylesheet" href="../../assets/styles/admin-dashboard.css">
    <link rel="stylesheet" href="../../assets/styles/navbar.css">
  </head>
  <body>
    <header>
      <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
          <a class="navbar-brand" href="booking-service.html">
            <i class="fa-solid fa-motorcycle"></i
            ><span style="color: var(--primary-red)"> Moto</span>Care
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link" href="./dashboard.php">Dashboard</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./user-management.php"
                  >User Management</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./services.php"
                  >Services</a
                >
              </li>
            </ul>
            <div class="profile-dropdown">
              <button class="btn-user" id="profileBtn">
                <div class="d-flex flex-column gap-0">
                  <p class="user-name mb-0 fw-bold"><?php echo $row['fullname'] ?></p>
                  <p class="mb-0 text-muted">Shop Owner</p>
                </div>
                <i
                  class="fa-solid fa-angle-down"
                  style="font-size: 0.8rem; color: var(--text-gray)"
                ></i>
              </button>
              <div class="dropdown-menu-custom" id="profileDropdown">
                <a href="../account.php" class="dropdown-item-custom">
                  <i class="fas fa-user-circle me-2"></i>Account
                </a>
                <a href="../../helper/logout.php" class="dropdown-item-custom">
                  <i class="fas fa-sign-out-alt me-2"></i>Sign Out
                </a>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <div class="main-content" id="mainContent">
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/scripts/admin-dashboard.js"></script>

    <script src="../../assets/scripts/navbar.js"></script>
  </body>
</html>
