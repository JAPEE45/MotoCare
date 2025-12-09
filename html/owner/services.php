
<?php
  include "../../helper/checkOwner.php";

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Services - MotoCare</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="../../assets/styles/owner-services.css" />
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
        <a href="./dashboard.php" class="nav-item">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="./user-management.php" class="nav-item">
          <i class="fas fa-users-cog"></i>
          <span>User Management</span>
        </a>
        <a href="./services.php" class="nav-item active">
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
    
  <p id="shopId" style="display:none"><?php echo $row['shop_id'] ?></p>
  <p id="userId" style="display:none"><?php echo $row['id'] ?></p>
  
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
      <div class="content-wrapper">
        <div class="container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h1 class="page-title">Services</h1>
            <p class="mb-0" style="var: (--text-gray)">
              Manage your auto repair services
            </p>
          </div>
          <div class="col-md-6 text-md-end">
            <button
              class="btn btn-primary"
              data-bs-toggle="modal"
              data-bs-target="#serviceModal"
            >
              <i class="fas fa-plus me-2"></i>Add Service
            </button>
          </div>
        </div>
      </div>

      <!-- Services Grid -->
      <div id="servicesContainer" class="row">
        <!-- Services will be dynamically loaded here -->
      </div>

      <!-- Empty State (shown when no services) -->
      <div id="emptyState" class="empty-state" style="display: none">
        <i class="fas fa-tools"></i>
        <h3>No Services Yet</h3>
        <p>
          Start by adding your first auto repair service to help customers know
          what you offer.
        </p>
        <button
          class="btn btn-primary btn-lg"
          data-bs-toggle="modal"
          data-bs-target="#serviceModal"
        >
          <i class="fas fa-plus me-2"></i>Add Your First Service
        </button>
      </div>
    </div>

    <!-- Service Modal -->
    <div class="modal fade" id="serviceModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add New Service</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <form id="serviceForm">
              <input type="hidden" id="serviceId" value="" />
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="serviceName" class="form-label"
                      >Service Name *</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      id="serviceName"
                      required
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="serviceIcon" class="form-label">Icon</label>
                    <select class="form-control" id="serviceIcon">
                      <option value="fas fa-wrench">Wrench</option>
                      <option value="fas fa-oil-can">Oil Can</option>
                      <option value="fas fa-car">Car</option>
                      <option value="fas fa-cogs">Gears</option>
                      <option value="fas fa-tools">Tools</option>
                      <option value="fas fa-dharmachakra">Tire</option>
                      <option value="fas fa-battery-full">Battery</option>
                      <option value="fas fa-spray-can">Paint</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="serviceDescription" class="form-label"
                  >Description</label
                >
                <textarea
                  class="form-control"
                  id="serviceDescription"
                  rows="3"
                ></textarea>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="serviceMinPrice" class="form-label"
                      >Minimum Price (₱) *</label
                    >
                    <input
                      type="number"
                      class="form-control"
                      id="serviceMinPrice"
                      step="0.01"
                      min="0"
                      required
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="serviceMaxPrice" class="form-label"
                      >Maximum Price (₱) *</label
                    >
                    <input
                      type="number"
                      class="form-control"
                      id="serviceMaxPrice"
                      step="0.01"
                      min="0"
                      required
                    />
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-outline-light"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button type="button" class="btn btn-primary" id="saveService">
              Save Service
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
      <div id="notificationToast" class="toast" role="alert">
        <div class="toast-header">
          <i
            id="toastIcon"
            class="fas fa-info-circle text-primary"
            style="margin-right: 100px"
          ></i>
          <strong
            class="me-auto"
            id="toastTitle"
            style="color: var(--text-gray)"
            >Notification</strong
          >
          <small id="toastTime">Just now</small>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="toast"
          ></button>
        </div>
        <div class="toast-body" id="toastBody">
          Service updated successfully!
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="../../assets/scripts/owner-services.js"></script>
    <script src="../../assets/scripts/sidebar.js"></script>
  </body>
</html>
