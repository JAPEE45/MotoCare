<?php
  // include '../../helper/checkStaff.php'; 
  include '../../helper/db.php';  
  session_start();
    // if(empty($_SESSION['user'])){
    //     header("Location: ./ss/html/signin.php");
    //  exit();
    // }
  $ID = $_SESSION['user_id'];
$smtp = $conn->prepare("SELECT * FROM user WHERE ID = ?");
$smtp->bind_param("i",$ID);
$smtp->execute();
$result = $smtp->get_result();
if($result->num_rows > 0){
  $row = $result->fetch_assoc();
  $shop = $conn->prepare("SELECT * FROM shop WHERE id = ?");
  $shop->bind_param("i",$row['shop_id']);
  $shop->execute();
  $shopResult = $shop->get_result();
  if($shopResult->num_rows > 0){
    $shopRow = $shopResult->fetch_assoc();
  }
}else{
  //  header("Location: /uu/html/signin.php");
  //    exit();
  
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Staff Dashboard - MotoCare</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../../assets/styles/staff-dashboard.css" />
    <link rel="stylesheet" href="../../assets/styles/sidebar.css" />
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
          <span>Dashboard</span>
        </a>
        <a href="./booking-service.php" class="nav-item">
          <span>Bookings</span>
        </a>
        <a href="./reports.php" class="nav-item">
          <span>Reports</span>
        </a>
      </nav>
      
      <div class="sidebar-footer">
        <div class="user-info">
          <div class="user-avatar">
            <?php echo strtoupper(substr($row['fullname'], 0, 1)); ?>
          </div>
          <div class="user-details">
            <span class="user-name"><?php echo $row['fullname'] ?></span>
            <span class="user-role">Staff</span>
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
    <input type="hidden" id="shopId" value="<?php echo isset($shopRow['id']) ? $shopRow['id'] : '' ?>">
      <!-- Welcome Section -->
      <div class="row mb-4">
        <div class="col-12">
          <h2 class="mb-1">Welcome to <?php echo $shopRow['name'] ?>!</h2>
          <p class="text-muted" id="currentDate"></p>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
              <div class="me-3">
                <i
                  class="fas fa-calendar-day fa-2x"
                  style="color: var(--red-primary)"
                ></i>
              </div>
              <div>
                <h3 class="mb-0" id="todayCount">0</h3>
                <p class="text-muted mb-0">Today's Appointments</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
              <div class="me-3">
                <i class="fas fa-tools fa-2x text-warning"></i>
              </div>
              <div>
                <h3 class="mb-0" id="ongoingCount">0</h3>
                <p class="text-muted mb-0">Ongoing Repairs</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
              <div class="me-3">
                <i class="fas fa-check-circle fa-2x text-success"></i>
              </div>
              <div>
                <h3 class="mb-0" id="confirmedCount">0</h3>
                <p class="text-muted mb-0">Confirmed</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center">
              <div class="me-3">
                <i
                  class="fas fa-chart-line fa-2x"
                  style="color: var(--sky-blue)"
                ></i>
              </div>
              <div>
                <h3 class="mb-0" id="totalCount">0</h3>
                <p class="text-muted mb-0">Total Bookings</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="row">
        <!-- Calendar Section -->
        <div class="col-lg-8 mb-4">
          <div class="card">
            <div
              class="card-header calendar-header d-flex justify-content-between align-items-center"
            >
              <h5 class="mb-0">Appointment Calendar</h5>
              <div class="d-flex align-items-center">
                <button
                  class="calendar-nav-btn me-2"
                  onclick="navigateMonth(-1)"
                >
                  <i class="fas fa-chevron-left"></i>
                </button>
                <span id="currentMonth" class="mx-3 fw-bold"></span>
                <button
                  class="calendar-nav-btn ms-2"
                  onclick="navigateMonth(1)"
                >
                  <i class="fas fa-chevron-right"></i>
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <!-- Calendar Header Days -->
              <div
                class="row g-0 border-bottom"
                style="border-color: var(--gray-card)"
              >
                <div class="col text-center py-2 text-muted fw-bold">Sun</div>
                <div class="col text-center py-2 text-muted fw-bold">Mon</div>
                <div class="col text-center py-2 text-muted fw-bold">Tue</div>
                <div class="col text-center py-2 text-muted fw-bold">Wed</div>
                <div class="col text-center py-2 text-muted fw-bold">Thu</div>
                <div class="col text-center py-2 text-muted fw-bold">Fri</div>
                <div class="col text-center py-2 text-muted fw-bold">Sat</div>
              </div>
              <!-- Calendar Grid -->
              <div id="calendarGrid"></div>
            </div>
          </div>
        </div>

        <!-- Appointments Section -->
        <div class="col-lg-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0" id="appointmentsTitle">Today's Appointments</h5>
              <small class="text-muted" id="selectedDateText"></small>
            </div>
            <div class="card-body appointment-card" id="appointmentsList">
              <!-- Appointments will be populated by JavaScript -->
            </div>
          </div>
        </div>
      </div>
        </div>
      </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/scripts/staff-dashboard.js"></script>
    <script src="../../assets/scripts/sidebar.js"></script>
      <!-- Booking Details Modal -->
      <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="bookingModalLabel">Bookings for Selected Date</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookingModalBody">
              <!-- Booking details will be injected here -->
            </div>
          </div>
        </div>
      </div>
  </body>
</html>
