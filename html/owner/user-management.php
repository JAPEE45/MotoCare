<?php
  include "../../helper/checkOwner.php";

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Auto Repair Hub - User Management</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />

    <style></style>

    <link rel="stylesheet" href="../../assets/styles/user-management.css" />
    <link rel="stylesheet" href="../../assets/styles/navbar.css" />
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
            <p id="shopId" style="display:none"><?php echo $row['shop_id'] ?></p>
            <p id="userId" style="display:none"><?php echo $row['id'] ?></p>
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
                <a href="../../logout.php" class="dropdown-item-custom">
                  <i class="fas fa-sign-out-alt me-2"></i>Sign Out
                </a>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <div class="container-fluid py-4 mt-5">
      <div class="main-header">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1><i class="fas fa-users-cog me-3"></i>User Management</h1>
            <p>Manage all staff members, and customer accounts</p>
          </div>
          <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-lg" onclick="addNewUser()">
              <i class="fas fa-user-plus me-2"></i>Add New Staff
            </button>
          </div>
        </div>
      </div>

      <div class="filter-section">
        <h5><i class="fas fa-filter"></i>Filter Users</h5>
        <div class="row">
          <div class="col-md-4 mb-3">
            <input
              type="text"
              class="form-control"
              id="searchInput"
              placeholder="Search by name, email, or phone..."
            />
          </div>
          <div class="col-md-2 mb-3">
            <select class="form-select" id="statusFilter">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="col-md-2 mb-3">
            <button class="btn btn-primary w-100" onclick="applyFilters()">
              <i class="fas fa-search me-2"></i>Filter
            </button>
          </div>
          <div class="col-md-2 mb-3">
            <button class="btn btn-secondary w-100" onclick="resetFilters()">
              <i class="fas fa-undo me-2"></i>Reset
            </button>
          </div>
        </div>
      </div>

      <div class="user-table">
        <div class="loading">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-3">Loading users...</p>
        </div>
        <div class="table-responsive">
          <table class="table table-hover" id="usersTable">
            <thead>
              <tr>
                <th>User</th>
                <th>Contact</th>
                <th>Role</th>
                <th>Status</th>
                <th>Last Login</th>
              
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="usersTableBody">
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade" id="viewProfileModal" tabindex="-1">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="fas fa-user me-2"></i>User Profile Details
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4 text-center">
                <div
                  class="user-avatar mx-auto mb-3"
                  style="width: 120px; height: 120px; font-size: 3rem"
                  id="profileAvatar"
                >
                  JD
                </div>
                <h4 id="profileName">John Doe</h4>
                <span class="role-badge role-customer" id="profileRole"
                  >Customer</span
                >
              </div>
              <div class="col-md-8">
                <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button
                      class="nav-link active"
                      id="details-tab"
                      data-bs-toggle="tab"
                      data-bs-target="#details"
                      type="button"
                      role="tab"
                    >
                      <i class="fas fa-info-circle me-2"></i>Account Details
                    </button>
                  </li>
                  <li
                    class="nav-item"
                    role="presentation"
                    id="bookingHistoryTab"
                    style="display: none"
                  >
                    <button
                      class="nav-link"
                      id="bookings-tab"
                      data-bs-toggle="tab"
                      data-bs-target="#bookings"
                      type="button"
                      role="tab"
                    >
                      <i class="fas fa-calendar-alt me-2"></i>Booking History
                    </button>
                  </li>
                </ul>

                <div class="tab-content" id="profileTabContent">
                  <div
                    class="tab-pane fade show active"
                    id="details"
                    role="tabpanel"
                  >
                    <div class="row">
                      <div
                        class="col-6 mb-3"
                        id="profileIdSection"
                        style="display: none"
                      >
                        <strong>ID:</strong>
                        <p id="profileId">#001</p>
                      </div>
                      <div class="col-6 mb-3">
                        <strong>Email:</strong>
                        <p id="profileEmail">john@example.com</p>
                      </div>
                      <div class="col-6 mb-3">
                        <strong>Phone:</strong>
                        <p id="profilePhone">+1234567890</p>
                      </div>
                      <div class="col-6 mb-3">
                        <strong>Status:</strong>
                        <p>
                          <span
                            class="status-badge status-active"
                            id="profileStatus"
                            >Active</span
                          >
                        </p>
                      </div>
                      <div class="col-6 mb-3">
                        <strong>Last Login:</strong>
                        <p id="profileLastLogin">2024-01-15 10:30 AM</p>
                      </div>
                      <div class="col-6 mb-3">
                        <strong>Join Date:</strong>
                        <p id="profileJoinDate">2023-12-01</p>
                      </div>
                      <div class="col-12 mb-3">
                        <strong>Address:</strong>
                        <p id="profileAddress">123 Main Street, City, State</p>
                      </div>
                      <div
                        class="col-6"
                        id="profileOrdersSection"
                        style="display: none"
                      >
                        <strong>Total Orders:</strong>
                        <p id="profileOrders">15</p>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="bookings" role="tabpanel">
                    <div
                      id="bookingHistoryContainer"
                      class="booking-history-cont"
                    >
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="fas fa-edit me-2"></i>Edit User Details
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <form id="editUserForm">
              <input type="hidden" id="editUserId" />
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">First Name</label>
                  <input
                    type="text"
                    class="form-control"
                    id="editFullname"
                    required
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Email</label>
                  <input
                    type="email"
                    class="form-control"
                    id="editEmail"
                    required
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Phone</label>
                  <input
                    type="text"
                    class="form-control"
                    id="editPhone"
                    required
                  />
                </div>
              
                <div class="col-md-6 mb-3">
                  <label class="form-label">Status</label>
                  <select class="form-select" id="editStatus" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
                <div class="col-12 mb-3">
                  <label class="form-label">Address</label>
                  <textarea
                    class="form-control"
                    id="editAddress"
                    rows="2"
                  ></textarea>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-primary"
              onclick="saveUserChanges()"
            >
              </i>Save Changes
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="resetPasswordModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="fas fa-key me-2"></i>Reset Password
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle me-2"></i>
              A new temporary password will be sent to the user's email address.
            </div>
            <p>
              Are you sure you want to reset the password for
              <strong id="resetUserName">John Doe</strong>?
            </p>
            <input type="hidden" id="resetUserId" />
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-primary"
              onclick="confirmPasswordReset()"
            >
              <i class="fas fa-paper-plane me-2"></i>Send Reset Email
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="fas fa-user-plus me-2"></i>Add New Staff
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <form id="addUserForm">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Fullname</label>
                  <input
                    type="text"
                    class="form-control"
                    id="addFullname"
                    required
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Email</label>
                  <input
                    type="email"
                    class="form-control"
                    id="addEmail"
                    required
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Phone</label>
                  <input
                    type="tel"
                    class="form-control"
                    id="addPhone"
                    required
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Initial Password</label>
                  <input
                    type="password"
                    class="form-control"
                    id="addPassword"
                    required
                  />
                </div>
                <div class="col-12 mb-3">
                  <label class="form-label">Address</label>
                  <textarea
                    class="form-control"
                    id="addAddress"
                    rows="2"
                  ></textarea>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-primary"
              onclick="createNewUser()"
              id="createBtn"
            >
              Add Staff
            </button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/scripts/user-management.js"></script>
    <script src="../../assets/scripts/navbar.js"></script>
  </body>
</html>
