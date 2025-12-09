<?php
require('../../helper/checkingUser.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Auto Repair Shop - Bookings</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    rel="stylesheet" />

  <link rel="stylesheet" href="../../assets/styles/booking-list.css">
  <link rel="stylesheet" href="../../assets/styles/navbar.css">
</head>

<body>
  <header>
    <p id="user_id" style="display:none;"><?php echo $row['ID'] ?></p>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="homepage.html">
          <i class="fa-solid fa-motorcycle"></i><span style="color: var(--primary-red)"> Auto</span>Repair Shop
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="./homepage.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../index.php">About Us</a>
            </li>
            <?php
            if (empty($row)) {
              echo '  <li class="nav-item ">
                <a class="nav-link " href="../signin.php">Sign In</a>
              </li>';
            }
            ?>

            <!-- <li class="nav-item ">
                <a class="nav-link logged-out" href="./signin.html">Sign In</a>
              </li> -->

            <!-- <li class="nav-item ">
                <a class="nav-link signup logged-out" href="./signup.html">Sign Up</a>
              </li> -->
          </ul>
          <div class="profile-dropdown">
            <button class="btn-user" id="profileBtn">
              <div class="d-flex flex-column gap-0">
                <p class="user-name mb-0 fw-bold"><?php echo $row['fullname'] ?></p>
                <p class="mb-0 text-muted">Customer</p>
              </div>
              <i
                class="fa-solid fa-angle-down"
                style="font-size: 0.8rem; color: var(--text-gray)"></i>
            </button>
            <div class="dropdown-menu-custom" id="profileDropdown">
              <a href="../account.php" class="dropdown-item-custom">
                <i class="fas fa-user-circle me-2"></i>Account
              </a>
              <a href="./booking-list.php" class="dropdown-item-custom">
                <i class="fa-solid fa-calendar-check me-2"></i>Booking
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
  <div class="container-main">
    <!-- Filter Section -->
    <div class="filter-section">
      <div class="filter-title">Filter & Search</div>
      <div class="row g-3">
        <div class="col-12 col-md-6">
          <input type="search" class="form-control" id="searchCustomer" placeholder="Search by customer name...">
        </div>
        <div class="col-12 col-md-3">
          <select class="form-select" id="filterStatus">
            <option value="All Status" selected>All Status</option>
            <option value="pending">On Queue</option>
            <option value="progress">In Progress</option>
            <option value="completed">Completed</option>
          </select>
        </div>
       
        <!-- <div class="col-12 col-md-3">
                <input type="date" class="form-control" id="filterDate" placeholder="dd/mm/yyyy">
            </div> -->
      </div>
    </div>

    <!-- Table -->
    <div class="table-container">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>BOOKING ID</th>
            <th>SHOP NAME</th>
            <th>SERVICE</th>
            <th>VEHICLE</th>
            <th>APPOINTMENT DATE</th>
            <th>STATUS</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody id="bookingsTable">

        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap Modal for Booking -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div
          class="modal-content"
          style="
            background: var(--dark-secondary);
            border: 1px solid var(--dark-tertiary);
          "
        >
          <div
            class="modal-header text-white"
            style="
              background: var(--dark-secondary);
              border-bottom: 1px solid var(--dark-tertiary);
            "
          >
            <h5 class="modal-title" style="color: var(--text-light)">
              <i
                class="fas fa-calendar-check me-2"
                style="color: var(--red-primary)"
              ></i>
              Book Your Service
            </h5>
            <button
              type="button"
              class="btn-close btn-close-white"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div
            class="modal-body"
            style="background: var(--dark-secondary); color: var(--text-light)"
          >
            <form id="bookingForm">
              <div class="mb-3">
                <label class="form-label"
                  ><strong >Selected Shop: <span id="selectedShop" style="color: white"></span></strong></label
                >
                <p
                  id="selectedShop"
                  class="fw-bold"
                  style="color: var(--red-primary)"
                  
                ></p>
              </div>
              <div class="row">
                <input id="shop_id" name="shop_id" type="hidden"/>
              </div>
              <div class="mb-3">
                <label class="form-label"  style="color: var(--text-light)"
                  >Vehicle Brand</label
                >
                <input
                  type="text"
                  class="form-control"
                  required
                  id="vehicle_name"
                  name="vehicle_name"
                />
              </div>
              <div class="mb-3">
                <label class="form-label" style="color: var(--text-light)"
                  >Vehicle Model</label
                >
                <input
                  type="text"
                  class="form-control"
                  required
                  id="vehicle_model"
                  name="vehicle_model"
                />
              </div>
              <div class="mb-3">
                <label class="form-label" style="color: var(--text-light)"
                  >Vehicle Plate Number</label
                >
                <input
                  type="text"
                  class="form-control"
                  required
                  id="vehicle_plate_number"
                  name="vehicle_plate_number"
                />
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label" style="color: var(--text-light)"
                    >Preferred Date</label
                  >
                  <input
                    type="date"
                    class="form-control"
                    required
                    id = "preferred_date"
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label" style="color: var(--text-light)"
                    >Preferred Time</label
                  >
                  <select
                    class="form-control"
                    required
                    id="time"
                  >
                    <option value="" disabled selected>Select Time</option>
                    <option value="08:00">8:00 AM</option>
                    <option value="08:30">8:30 AM</option>
                    <option value="09:00">9:00 AM</option>
                    <option value="09:30">9:30 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="10:30">10:30 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="11:30">11:30 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="12:30">12:30 PM</option>
                    <option value="13:00">1:00 PM</option>
                    <option value="13:30">1:30 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="14:30">2:30 PM</option>
                    <option value="15:00">3:00 PM</option>
                    <option value="15:30">3:30 PM</option>
                    <option value="16:00">4:00 PM</option>
                    <option value="16:30">4:30 PM</option>
                    <option value="17:00">5:00 PM</option>
                    <option value="17:30">5:30 PM</option>
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label" style="color: var(--text-light); font-weight: 600;">
                  <i class="fas fa-tools me-2" style="color: var(--red-primary);"></i>
                  Select Services <span style="color: var(--text-gray); font-size: 0.85rem;">(Choose 1-5 services)</span>
                </label>
                <div id="servicesCheckboxContainer" class="services-checkbox-container" style="max-height: 250px; overflow-y: auto; background: var(--dark-tertiary); border-radius: 8px; padding: 12px;">
                  <!-- Services checkboxes will be dynamically loaded -->
                </div>
                <small class="text-muted">
                  <i class="fas fa-info-circle me-1"></i>
                  You can select multiple services for one booking. Check all that apply.
                </small>
              </div>
              <div class="mb-3">
                <label class="form-label" style="color: var(--text-light)"
                  >Notes</label
                >
                <textarea
                  class="form-control"
                  rows="3"
                  placeholder="Describe the service you need..."
                  name="notes"
                  id="notes"
                ></textarea>
              </div>
            </form>
          </div>
          <div
            class="modal-footer"
            style="
              background: var(--dark-secondary);
              border-top: 1px solid var(--dark-tertiary);
            "
          >
            <button
              type="button"
              class="btn"
              style="
                background: var(--gray-card);
                color: var(--text-light);
                border: 1px solid var(--dark-tertiary);
              "
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-book"
            >
              <i class="fas fa-check me-2"></i>Save
            </button>
          </div>
        </div>
      </div>
    </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <script src="../../assets/scripts/booking-list.js"></script>
  <script src="../../assets/scripts/navbar.js"></script>
</body>

</html>