<?php
  // include '../../helper/checkStaff.php'; 
  // include '../../helper/checkStaff.php'; 
  include '../../helper/db.php';  
  session_start();
    // if(empty($_SESSION['user'])){
    //     header("Location: ./ss/html/signin.php");
    //  exit();
    // }
$smtp = $conn->prepare("SELECT * FROM user WHERE ID = ?");
$smtp->bind_param("s",$_SESSION['user_id']);
$smtp->execute();
$result = $smtp->get_result();
if($result->num_rows > 0){
  $row = $result->fetch_assoc();
  
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
    <title>Bookings - MotoCare</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../../assets/styles/booking-service.css" />
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
          <i class="fa-solid fa-motorcycle" style="display:none"></i>
        </div>
        <span class="sidebar-title">MotoCare</span>
      </div>
      
      <nav class="sidebar-nav">
        <a href="./dashboard.php" class="nav-item">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="./booking-service.php" class="nav-item active">
          <i class="fas fa-calendar-check"></i>
          <span>Bookings</span>
        </a>
        <a href="./reports.php" class="nav-item">
          <i class="fas fa-chart-bar"></i>
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
        <p id="userContact" style="display:none;"><?php echo $row['contact']?></p>
        <p id="userFullname" style="display:none;"><?php echo $row['fullname']?></p>
        <p id="staffShopId" style="display:none;"><?php echo $row['shop_id']?></p>
      <!-- Stats Cards -->
      <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
          <div class="stats-card">
            <div class="stats-number" id="totalBookings">12</div>
            <div class="stats-label">Total Requests</div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
          <div class="stats-card">
            <div class="stats-number" id="pendingBookings">5</div>
            <div class="stats-label">On Queue</div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
          <div class="stats-card">
            <div class="stats-number" id="inProgressBookings">4</div>
            <div class="stats-label">In Progress</div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
          <div class="stats-card">
            <div class="stats-number" id="completedBookings">3</div>
            <div class="stats-label">Completed</div>
          </div>
        </div>
      </div>

      <!-- Filter Section -->
      

      <!-- Bookings Table -->
      <div class="table-container">
        <div class="filter-section">
        <h5 class="filter-title">
          <i class="fas fa-filter me-2"></i>
          Filter & Search
        </h5>
        <div class="row">
          <div class="col-lg-3 col-md-6 mb-3">
            <input
              type="text"
              class="form-control"
              placeholder="Search by customer name..."
              id="searchInput"
            />
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <select class="form-select" id="statusFilter">
              <option value="">All Statuses</option>
              <option value="pending">On Queue</option>
              <option value="in-progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <select class="form-select" id="serviceFilter">
              <option value="">All Services</option>
              <option value="oil-change">Oil Change</option>
              <option value="brake-repair">Brake Repair</option>
              <option value="engine-diagnostic">Engine Diagnostic</option>
              <option value="tire-service">Tire Service</option>
              <option value="transmission">Transmission Service</option>
            </select>
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <input type="date" class="form-control" id="dateFilter" />
          </div>
        </div>
      </div>
        <div class="table-responsive" id="tableContainer">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Service</th>
                <th>Vehicle</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="bookingsTableBody">
              <!-- Table rows will be populated here -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Empty State -->
      <div id="emptyState" class="empty-state" style="display: none">
        <i class="fas fa-inbox"></i>
        <h4>No bookings found</h4>
        <p>No booking requests match your current filters.</p>
      </div>
    </div>

    <!-- Booking Details Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">
              <i class="fas fa-info-circle me-2"></i>
              Booking Details
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body" id="modalBody">
            <!-- Modal content will be populated here -->
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-outline-custom"
              data-bs-dismiss="modal"
            >
              <i class="fas fa-times"></i>
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Reschedule Modal -->
    <div
      class="modal fade"
      id="rescheduleModal"
      tabindex="-1"
      aria-labelledby="rescheduleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="rescheduleModalLabel">
              <i class="fas fa-calendar-alt me-2"></i>
              Reschedule Booking
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <!-- Alert for feedback -->
            <div id="rescheduleAlert" class="alert alert-info d-none">
              <i class="fas fa-info-circle me-2"></i>
              <span id="rescheduleAlertMessage"></span>
            </div>

            <!-- Current Booking Information -->
            <div class="current-booking-info">
              <h6 class="mb-3">
                <i class="fas fa-info-circle me-2"></i>
                Current Booking Details
              </h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="info-label">Customer:</div>
                  <div class="info-value" id="currentCustomer">John Doe</div>
                </div>
                <div class="col-md-6">
                  <div class="info-label">Service:</div>
                  <div class="info-value" id="currentService">Oil Change</div>
                </div>
                <div class="col-md-6">
                  <div class="info-label">Current Date:</div>
                  <div class="info-value" id="currentDate">2024-01-15</div>
                </div>
                <div class="col-md-6">
                  <div class="info-label">Current Time:</div>
                  <div class="info-value" id="currentTime">10:00 AM</div>
                </div>
              </div>
            </div>

            <form id="rescheduleForm">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="newDate" class="form-label">
                    <i class="fas fa-calendar me-2"></i>
                    New Date
                  </label>
                  <input
                    type="date"
                    class="form-control"
                    id="newDate"
                    name="newDate"
                    required
                  />
                  <div class="invalid-feedback">
                    Please select a valid date.
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="newTime" class="form-label">
                    <i class="fas fa-clock me-2"></i>
                    New Time
                  </label>
                  <select
                    class="form-select"
                    id="newTime"
                    name="newTime"
                    required
                  >
                    <option value="">Select Time</option>
                    <option value="08:00">8:00 AM</option>
                    <option value="09:00">9:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="13:00">1:00 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="15:00">3:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                    <option value="17:00">5:00 PM</option>
                  </select>
                  <div class="invalid-feedback">Please select a time.</div>
                </div>
              </div>
              <div class="mb-3">
                <label for="rescheduleReason" class="form-label">
                  <i class="fas fa-comment me-2"></i>
                  Reason for Rescheduling (Optional)
                </label>
                <textarea
                  class="form-control"
                  id="rescheduleReason"
                  name="rescheduleReason"
                  rows="3"
                  placeholder="Please provide a reason for rescheduling..."
                ></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary-custom"
              data-bs-dismiss="modal"
            >
              <i class="fas fa-times me-2"></i>
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-primary-custom"
              id="confirmReschedule"
            >
              <i class="fas fa-check me-2"></i>
              <span id="confirmButtonText">Confirm Reschedule</span>
            </button>
          </div>
        </div>
      </div>
    </div>
      </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/scripts/booking-service.js"></script>
    <script src="../../assets/scripts/sidebar.js"></script>
  </body>
</html>
