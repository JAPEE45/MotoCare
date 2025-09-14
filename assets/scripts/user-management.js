// Sample booking data
let bookings = [
  {
    id: 1,
    customerId: 3,
    serviceType: "Oil Change",
    vehicleInfo: "2020 Honda Civic",
    bookingDate: "2024-01-10",
    status: "completed",
    amount: 45.0,
    notes: "Regular maintenance service",
  },
  {
    id: 2,
    customerId: 3,
    serviceType: "Brake Inspection",
    vehicleInfo: "2020 Honda Civic",
    bookingDate: "2024-01-08",
    status: "completed",
    amount: 85.0,
    notes: "Brake pads replaced",
  },
  {
    id: 3,
    customerId: 4,
    serviceType: "Engine Diagnostic",
    vehicleInfo: "2018 Toyota Camry",
    bookingDate: "2023-12-28",
    status: "cancelled",
    amount: 120.0,
    notes: "Customer cancelled - engine noise resolved",
  },
  {
    id: 4,
    customerId: 6,
    serviceType: "Tire Rotation",
    vehicleInfo: "2019 Ford Explorer",
    bookingDate: "2024-01-05",
    status: "completed",
    amount: 35.0,
    notes: "All tires rotated and balanced",
  },
  {
    id: 5,
    customerId: 6,
    serviceType: "Air Filter Replacement",
    vehicleInfo: "2019 Ford Explorer",
    bookingDate: "2024-01-03",
    status: "completed",
    amount: 25.0,
    notes: "Engine air filter replaced",
  },
  {
    id: 6,
    customerId: 7,
    serviceType: "Full Service",
    vehicleInfo: "2021 BMW X3",
    bookingDate: "2024-01-12",
    status: "in-progress",
    amount: 200.0,
    notes: "Comprehensive vehicle inspection and service",
  },
  {
    id: 7,
    customerId: 7,
    serviceType: "Battery Replacement",
    vehicleInfo: "2021 BMW X3",
    bookingDate: "2023-12-15",
    status: "completed",
    amount: 150.0,
    notes: "Premium battery installed",
  },
  {
    id: 8,
    customerId: 3,
    serviceType: "AC Service",
    vehicleInfo: "2020 Honda Civic",
    bookingDate: "2024-01-14",
    status: "pending",
    amount: 95.0,
    notes: "AC refrigerant refill scheduled",
  },
  {
    id: 9,
    customerId: 3,
    serviceType: "AC Service",
    vehicleInfo: "2020 Honda Civic",
    bookingDate: "2024-01-14",
    status: "pending",
    amount: 95.0,
    notes: "AC refrigerant refill scheduled",
  },
];

// Sample user data with enhanced details
let users = [
  {
    id: 1,
    firstName: "John",
    lastName: "Doe",
    email: "john.doe@email.com",
    phone: "+1 (555) 123-4567",
    role: "staff",
    status: "active",
    lastLogin: "2024-01-15 10:30 AM",
    address: "123 Main Street, New York, NY 10001",
    joinDate: "2023-12-01",
    orders: 0,
  },
  {
    id: 2,
    firstName: "Jane",
    lastName: "Smith",
    email: "jane.smith@email.com",
    phone: "+1 (555) 234-5678",
    role: "staff",
    status: "active",
    lastLogin: "2024-01-14 2:15 PM",
    address: "456 Oak Avenue, Los Angeles, CA 90210",
    joinDate: "2023-11-15",
    orders: 0,
  },
  {
    id: 3,
    firstName: "Mike",
    lastName: "Johnson",
    email: "mike.johnson@email.com",
    phone: "+1 (555) 345-6789",
    role: "customer",
    status: "active",
    lastLogin: "2024-01-13 9:45 AM",
    address: "789 Pine Street, Chicago, IL 60601",
    joinDate: "2023-10-20",
    orders: 15,
  },
  {
    id: 4,
    firstName: "Sarah",
    lastName: "Williams",
    email: "sarah.williams@email.com",
    phone: "+1 (555) 456-7890",
    role: "customer",
    status: "inactive",
    lastLogin: "2024-01-01 11:20 AM",
    address: "321 Cedar Road, Houston, TX 77001",
    joinDate: "2023-09-10",
    orders: 3,
  },
  {
    id: 5,
    firstName: "David",
    lastName: "Brown",
    email: "david.brown@email.com",
    phone: "+1 (555) 567-8901",
    role: "staff",
    status: "active",
    lastLogin: "2024-01-12 4:30 PM",
    address: "654 Elm Street, Phoenix, AZ 85001",
    joinDate: "2023-08-05",
    orders: 0,
  },
  {
    id: 6,
    firstName: "Lisa",
    lastName: "Davis",
    email: "lisa.davis@email.com",
    phone: "+1 (555) 678-9012",
    role: "customer",
    status: "active",
    lastLogin: "2024-01-11 7:15 AM",
    address: "987 Maple Drive, Miami, FL 33101",
    joinDate: "2023-07-22",
    orders: 7,
  },
  {
    id: 7,
    firstName: "Robert",
    lastName: "Wilson",
    email: "robert.wilson@email.com",
    phone: "+1 (555) 789-0123",
    role: "customer",
    status: "active",
    lastLogin: "2024-01-10 1:45 PM",
    address: "147 Birch Lane, Seattle, WA 98101",
    joinDate: "2023-06-18",
    orders: 22,
  },
  {
    id: 8,
    firstName: "Emily",
    lastName: "Anderson",
    email: "emily.anderson@email.com",
    phone: "+1 (555) 890-1234",
    role: "staff",
    status: "active",
    lastLogin: "2024-01-09 8:00 AM",
    address: "258 Spruce Court, Denver, CO 80201",
    joinDate: "2023-05-12",
    orders: 0,
  },
];

let filteredUsers = [...users];
let currentEditingUser = null;

// Initialize page
document.addEventListener("DOMContentLoaded", function () {
  renderUsers();
  setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
  // Search functionality
  document
    .getElementById("searchInput")
    .addEventListener("input", debounce(applyFilters, 300));

  // Filter changes
  document
    .getElementById("roleFilter")
    .addEventListener("change", applyFilters);
  document
    .getElementById("statusFilter")
    .addEventListener("change", applyFilters);
}

// Debounce function for search
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Get user bookings
function getUserBookings(userId) {
  return bookings.filter((booking) => booking.customerId === userId);
}

// Get total bookings count
function getTotalBookings() {
  return bookings.length;
}


// Render users table
function renderUsers() {
  const tbody = document.getElementById("usersTableBody");
  tbody.innerHTML = "";

  if (filteredUsers.length === 0) {
    tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-users fa-3x mb-3" style="color: var(--text-gray);"></i>
                            <p>No users found matching your criteria</p>
                        </td>
                    </tr>
                `;
    return;
  }

  filteredUsers.forEach((user) => {
    const userBookings = getUserBookings(user.id);
    const totalBookings = userBookings.length;

    const row = document.createElement("tr");
    row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3">
                                ${user.firstName.charAt(
                                  0
                                )}${user.lastName.charAt(0)}
                            </div>
                            <div>
                                <strong>${user.firstName} ${
      user.lastName
    }</strong><br>
                                <small class="text-muted">ID: #${String(
                                  user.id
                                ).padStart(3, "0")}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <i class="fas fa-envelope me-2"></i>${
                              user.email
                            }<br>
                            <i class="fas fa-phone me-2"></i>${user.phone}
                        </div>
                    </td>
                    <td>
                        <span class="role-badge role-${user.role}">${
      user.role
    }</span>
                    </td>
                    <td>
                        <span class="status-badge status-${user.status}">${
      user.status
    }</span>
                    </td>
                    <td>
                        <small>${user.lastLogin}</small>
                    </td>
                    <td>
                        <span class="badge ${
                          totalBookings > 0 ? "bg-success" : "bg-secondary"
                        }">${totalBookings}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-outline-info btn-sm" onclick="viewProfile(${
                              user.id
                            })" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-warning btn-sm" onclick="editUser(${
                              user.id
                            })" title="Edit User">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="resetPassword(${
                              user.id
                            })" title="Reset Password">
                                <i class="fas fa-key"></i>
                            </button>
                        </div>
                    </td>
                `;
    tbody.appendChild(row);
  });
}

// Apply filters
function applyFilters() {
  const searchTerm = document.getElementById("searchInput").value.toLowerCase();
  const roleFilter = document.getElementById("roleFilter").value;
  const statusFilter = document.getElementById("statusFilter").value;

  filteredUsers = users.filter((user) => {
    const matchesSearch =
      !searchTerm ||
      user.firstName.toLowerCase().includes(searchTerm) ||
      user.lastName.toLowerCase().includes(searchTerm) ||
      user.email.toLowerCase().includes(searchTerm) ||
      user.phone.toLowerCase().includes(searchTerm);

    const matchesRole = !roleFilter || user.role === roleFilter;
    const matchesStatus = !statusFilter || user.status === statusFilter;

    return matchesSearch && matchesRole && matchesStatus;
  });

  renderUsers();
}

// Reset filters
function resetFilters() {
  document.getElementById("searchInput").value = "";
  document.getElementById("roleFilter").value = "";
  document.getElementById("statusFilter").value = "";
  filteredUsers = [...users];
  renderUsers();
}

// Render booking history
function renderBookingHistory(userId) {
  const userBookings = getUserBookings(userId);
  const container = document.getElementById("bookingHistoryContainer");

  if (userBookings.length === 0) {
    container.innerHTML = `
      <div class="text-center py-4">
        <i class="fas fa-calendar-times fa-3x mb-3" style="color: var(--text-gray);"></i>
        <p>No booking history found</p>
      </div>
    `;
    return;
  }

  // Sort bookings by date (newest first)
  userBookings.sort(
    (a, b) => new Date(b.bookingDate) - new Date(a.bookingDate)
  );

  container.innerHTML = userBookings
    .map(
      (booking) => `
    <div class="booking-item">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h6 class="mb-1">${booking.serviceType}</h6>
          <small class="text-muted">
            <i class="fas fa-car me-1"></i>${booking.vehicleInfo}
          </small>
        </div>
        <div class="col-md-3 text-center">
          <span class="booking-status booking-${booking.status}">${
        booking.status
      }</span>
        </div>
        <div class="col-md-3 text-end">
          <strong>${booking.amount.toFixed(2)}</strong><br>
          <small class="text-muted">${new Date(
            booking.bookingDate
          ).toLocaleDateString()}</small>
        </div>
      </div>
      ${
        booking.notes
          ? `<div class="mt-2"><small class="text-muted"><i class="fas fa-sticky-note me-1"></i>${booking.notes}</small></div>`
          : ""
      }
    </div>
  `
    )
    .join("");
}

// View user profile
function viewProfile(userId) {
  const user = users.find((u) => u.id === userId);
  if (!user) return;

  // Populate basic info
  document.getElementById(
    "profileAvatar"
  ).textContent = `${user.firstName.charAt(0)}${user.lastName.charAt(0)}`;
  document.getElementById(
    "profileName"
  ).textContent = `${user.firstName} ${user.lastName}`;
  document.getElementById("profileRole").textContent = user.role;
  document.getElementById(
    "profileRole"
  ).className = `role-badge role-${user.role}`;
  document.getElementById("profileEmail").textContent = user.email;
  document.getElementById("profilePhone").textContent = user.phone;
  document.getElementById("profileStatus").textContent = user.status;
  document.getElementById(
    "profileStatus"
  ).className = `status-badge status-${user.status}`;
  document.getElementById("profileLastLogin").textContent = user.lastLogin;
  document.getElementById("profileAddress").textContent = user.address;
  document.getElementById("profileJoinDate").textContent = user.joinDate;

  // Show/hide fields based on role
  const isCustomer = user.role === "customer";
  const isStaff = user.role === "staff" || user.role === "admin";

  // ID section - show for staff only
  document.getElementById("profileIdSection").style.display = isStaff
    ? "block"
    : "none";
  if (isStaff) {
    document.getElementById("profileId").textContent = `#${String(
      user.id
    ).padStart(3, "0")}`;
  }

  // Orders section - show for customers only
  document.getElementById("profileOrdersSection").style.display = isCustomer
    ? "block"
    : "none";
  if (isCustomer) {
    document.getElementById("profileOrders").textContent = user.orders;
  }

  // Booking history tab - show for customers only
  document.getElementById("bookingHistoryTab").style.display = isCustomer
    ? "block"
    : "none";

  if (isCustomer) {
    renderBookingHistory(userId);
  }

  // Reset to first tab
  const detailsTab = new bootstrap.Tab(document.getElementById("details-tab"));
  detailsTab.show();

  // Show modal
  const modal = new bootstrap.Modal(
    document.getElementById("viewProfileModal")
  );
  modal.show();
}

// Edit user
function editUser(userId) {
  const user = users.find((u) => u.id === userId);
  if (!user) return;

  currentEditingUser = user;

  // Populate edit form
  document.getElementById("editUserId").value = user.id;
  document.getElementById("editFirstName").value = user.firstName;
  document.getElementById("editLastName").value = user.lastName;
  document.getElementById("editEmail").value = user.email;
  document.getElementById("editPhone").value = user.phone;
  document.getElementById("editRole").value = user.role;
  document.getElementById("editStatus").value = user.status;
  document.getElementById("editAddress").value = user.address;

  // Show modal
  const modal = new bootstrap.Modal(document.getElementById("editUserModal"));
  modal.show();
}

// Save user changes
function saveUserChanges() {
  if (!currentEditingUser) return;

  const userId = parseInt(document.getElementById("editUserId").value);
  const userIndex = users.findIndex((u) => u.id === userId);

  if (userIndex === -1) return;

  // Update user data
  users[userIndex] = {
    ...users[userIndex],
    firstName: document.getElementById("editFirstName").value,
    lastName: document.getElementById("editLastName").value,
    email: document.getElementById("editEmail").value,
    phone: document.getElementById("editPhone").value,
    role: document.getElementById("editRole").value,
    status: document.getElementById("editStatus").value,
    address: document.getElementById("editAddress").value,
  };

  // Update displays
  applyFilters();

  // Close modal
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("editUserModal")
  );
  modal.hide();

  // Show success message
  showNotification("User updated successfully!", "success");
}

// Reset password
function resetPassword(userId) {
  const user = users.find((u) => u.id === userId);
  if (!user) return;

  document.getElementById(
    "resetUserName"
  ).textContent = `${user.firstName} ${user.lastName}`;
  document.getElementById("resetUserId").value = user.id;

  const modal = new bootstrap.Modal(
    document.getElementById("resetPasswordModal")
  );
  modal.show();
}

// Confirm password reset
function confirmPasswordReset() {
  const userId = document.getElementById("resetUserId").value;
  const user = users.find((u) => u.id == userId);

  if (user) {
    // Close modal
    const modal = bootstrap.Modal.getInstance(
      document.getElementById("resetPasswordModal")
    );
    modal.hide();

    // Show success message
    showNotification(`Password reset email sent to ${user.email}`, "success");
  }
}

// Add new user
function addNewUser() {
  // Clear form
  document.getElementById("addUserForm").reset();

  // Show modal
  const modal = new bootstrap.Modal(document.getElementById("addUserModal"));
  modal.show();
}

// Create new user
function createNewUser() {
  // Get form data
  const formData = {
    id: Math.max(...users.map((u) => u.id)) + 1,
    firstName: document.getElementById("addFirstName").value,
    lastName: document.getElementById("addLastName").value,
    email: document.getElementById("addEmail").value,
    phone: document.getElementById("addPhone").value,
    role: "staff",
    status: "active",
    address: document.getElementById("addAddress").value,
    lastLogin: "Never",
    joinDate: new Date().toISOString().split("T")[0],
    orders: 0,
  };

  // Add to users array
  users.push(formData);

  // Update displays
  applyFilters();

  // Close modal
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("addUserModal")
  );
  modal.hide();

  // Show success message
  showNotification("New user created successfully!", "success");
}

// Show notification
function showNotification(message, type = "info") {
  // Create notification element
  const notification = document.createElement("div");
  notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
  notification.style.cssText =
    "top: 20px; right: 20px; z-index: 9999; max-width: 300px;";
  notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

  // Add to page
  document.body.appendChild(notification);

  // Auto-remove after 3 seconds
  setTimeout(() => {
    if (notification.parentNode) {
      notification.remove();
    }
  }, 3000);
}

// Simulate loading
function showLoading() {
  document.querySelector(".loading").style.display = "block";
  document.getElementById("usersTable").style.display = "none";
}

function hideLoading() {
  document.querySelector(".loading").style.display = "none";
  document.getElementById("usersTable").style.display = "table";
}
