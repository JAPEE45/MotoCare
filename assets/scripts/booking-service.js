// Sample booking
const bookings = [
  {
    id: "BK001",
    customerName: "John Smith",
    phone: "+1 (555) 123-4567",
    email: "john.smith@email.com",
    serviceType: "Oil Change",
    vehicleInfo: "2020 Honda Civic",
    licensePlate: "ABC-123",
    scheduledDate: "2025-09-05",
    scheduledTime: "10:00 AM",
    status: "pending",
    notes:
      "Regular maintenance checkup needed. Customer mentioned unusual engine noise.",
    createdAt: "2025-09-01T08:30:00Z",
    estimatedDuration: "45 minutes",
    totalCost: "$75.00",
  },
  {
    id: "BK002",
    customerName: "Sarah Johnson",
    phone: "+1 (555) 987-6543",
    email: "sarah.j@email.com",
    serviceType: "Brake Repair",
    vehicleInfo: "2018 Toyota Camry",
    licensePlate: "XYZ-789",
    scheduledDate: "2025-09-03",
    scheduledTime: "2:30 PM",
    status: "in-progress",
    notes:
      "Squeaking brakes, urgent repair needed. Customer reports grinding noise when braking.",
    createdAt: "2025-08-30T14:15:00Z",
    estimatedDuration: "2 hours",
    totalCost: "$350.00",
  },
  {
    id: "BK003",
    customerName: "Mike Davis",
    phone: "+1 (555) 456-7890",
    email: "mike.davis@email.com",
    serviceType: "Engine Diagnostic",
    vehicleInfo: "2019 Ford F-150",
    licensePlate: "DEF-456",
    scheduledDate: "2025-09-02",
    scheduledTime: "9:00 AM",
    status: "completed",
    notes:
      "Check engine light diagnosis completed. Issue resolved - faulty O2 sensor replaced.",
    createdAt: "2025-08-29T11:20:00Z",
    estimatedDuration: "1 hour",
    totalCost: "$120.00",
  },
  {
    id: "BK004",
    customerName: "Emily Wilson",
    phone: "+1 (555) 321-9876",
    email: "emily.w@email.com",
    serviceType: "Tire Service",
    vehicleInfo: "2021 Nissan Altima",
    licensePlate: "GHI-012",
    scheduledDate: "2025-09-04",
    scheduledTime: "11:30 AM",
    status: "pending",
    notes:
      "Tire rotation and alignment check. Customer mentioned car pulling to the right.",
    createdAt: "2025-08-31T16:45:00Z",
    estimatedDuration: "1.5 hours",
    totalCost: "$150.00",
  },
  {
    id: "BK005",
    customerName: "David Brown",
    phone: "+1 (555) 654-3210",
    email: "david.brown@email.com",
    serviceType: "Transmission Service",
    vehicleInfo: "2017 Chevrolet Malibu",
    licensePlate: "JKL-345",
    scheduledDate: "2025-09-06",
    scheduledTime: "1:00 PM",
    status: "in-progress",
    notes:
      "Transmission fluid change and inspection. Customer reports slipping gears.",
    createdAt: "2025-09-01T09:10:00Z",
    estimatedDuration: "2.5 hours",
    totalCost: "$280.00",
  },
  {
    id: "BK006",
    customerName: "Lisa Anderson",
    phone: "+1 (555) 789-0123",
    email: "lisa.anderson@email.com",
    serviceType: "Oil Change",
    vehicleInfo: "2022 BMW 3 Series",
    licensePlate: "MNO-678",
    scheduledDate: "2025-09-07",
    scheduledTime: "3:00 PM",
    status: "pending",
    notes: "Synthetic oil change, first service for new vehicle.",
    createdAt: "2025-09-01T10:20:00Z",
    estimatedDuration: "30 minutes",
    totalCost: "$95.00",
  },
  {
    id: "BK007",
    customerName: "Robert Taylor",
    phone: "+1 (555) 234-5678",
    email: "robert.taylor@email.com",
    serviceType: "Brake Repair",
    vehicleInfo: "2016 Mercedes-Benz C-Class",
    licensePlate: "PQR-901",
    scheduledDate: "2025-09-08",
    scheduledTime: "8:00 AM",
    status: "cancelled",
    notes: "Customer cancelled - found alternative service provider.",
    createdAt: "2025-08-28T13:30:00Z",
    estimatedDuration: "1.5 hours",
    totalCost: "$420.00",
  },
];

document.addEventListener("DOMContentLoaded", function () {
  renderTable(bookings);
  updateStats();
  setupEventListeners();
});

function setupEventListeners() {
  document
    .getElementById("searchInput")
    .addEventListener("input", filterBookings);
  document
    .getElementById("statusFilter")
    .addEventListener("change", filterBookings);
  document
    .getElementById("serviceFilter")
    .addEventListener("change", filterBookings);
  document
    .getElementById("dateFilter")
    .addEventListener("change", filterBookings);
}

function renderTable(bookingData) {
  const tbody = document.getElementById("bookingsTableBody");
  const emptyState = document.getElementById("emptyState");
  const tableContainer = document.querySelector(".table-container");

  if (bookingData.length === 0) {
    tableContainer.style.display = "none";
    emptyState.style.display = "block";
    return;
  }

  tableContainer.style.display = "block";
  emptyState.style.display = "none";

  tbody.innerHTML = bookingData
    .map(
      (booking) => `
                <tr class="">
                    <td>
                        <strong>${booking.id}</strong>
                    </td>
                    <td>
                        <div class="fw-bold">${booking.customerName}</div>
                        <small class="sub-text">${booking.phone}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="service-icon">
                                <i class="fas fa-${getServiceIcon(
                                  booking.serviceType
                                )}"></i>
                            </span>
                            ${booking.serviceType}
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold">${booking.vehicleInfo}</div>
                        <small class="sub-text">${booking.licensePlate}</small>
                    </td>
                    <td>
                        <div class="fw-bold">${formatDate(
                          booking.scheduledDate
                        )}</div>
                        <small class="sub-text">${booking.scheduledTime}</small>
                    </td>
                    <td>
                        <span class="status-badge status-${booking.status}">
                            <i class="fas fa-${getStatusIcon(
                              booking.status
                            )}"></i>
                            ${booking.status.replace("-", " ")}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-view" onclick="viewBookingDetails('${
                          booking.id
                        }')">
                            <i class="fas fa-eye"></i>
                            View Details
                        </button>
                    </td>
                </tr>
            `
    )
    .join("");
}

function updateStats() {
  const total = bookings.length;
  const pending = bookings.filter((b) => b.status === "pending").length;
  const inProgress = bookings.filter((b) => b.status === "in-progress").length;
  const completed = bookings.filter((b) => b.status === "completed").length;

  document.getElementById("totalBookings").textContent = total;
  document.getElementById("pendingBookings").textContent = pending;
  document.getElementById("inProgressBookings").textContent = inProgress;
  document.getElementById("completedBookings").textContent = completed;
}

function filterBookings() {
  const search = document.getElementById("searchInput").value.toLowerCase();
  const statusFilter = document.getElementById("statusFilter").value;
  const serviceFilter = document.getElementById("serviceFilter").value;
  const dateFilter = document.getElementById("dateFilter").value;

  let filtered = bookings.filter((booking) => {
    const matchesSearch = booking.customerName.toLowerCase().includes(search);
    const matchesStatus = !statusFilter || booking.status === statusFilter;
    const matchesService =
      !serviceFilter ||
      booking.serviceType.toLowerCase().replace(" ", "-") === serviceFilter;
    const matchesDate = !dateFilter || booking.scheduledDate === dateFilter;

    return matchesSearch && matchesStatus && matchesService && matchesDate;
  });

  renderTable(filtered);
}

function viewBookingDetails(bookingId) {
  const booking = bookings.find((b) => b.id === bookingId);
  if (!booking) return;

  const modal = document.getElementById("bookingModal");
  const modalTitle = document.getElementById("modalTitle");
  const modalBody = document.getElementById("modalBody");

  modalTitle.innerHTML = `
                <i class="fas fa-info-circle me-2"></i>
                Booking Details - ${booking.id}
            `;

  modalBody.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-group">
                            <div class="detail-label">
                                <i class="fas fa-user me-2"></i>Customer Information
                            </div>
                            <div class="detail-value mb-2"><strong>${
                              booking.customerName
                            }</strong></div>
                            <div class="detail-value mb-1">📧 ${
                              booking.email
                            }</div>
                            <div class="detail-value">📱 ${booking.phone}</div>
                        </div>

                        <div class="detail-group">
                            <div class="detail-label">
                                <i class="fas fa-car me-2"></i>Vehicle Information
                            </div>
                            <div class="detail-value mb-1"><strong>${
                              booking.vehicleInfo
                            }</strong></div>
                            <div class="detail-value">License: ${
                              booking.licensePlate
                            }</div>
                        </div>

                        <div class="detail-group">
                            <div class="detail-label">
                                <i class="fas fa-calendar me-2"></i>Service Details
                            </div>
                            <div class="detail-value mb-1"><strong>${
                              booking.serviceType
                            }</strong></div>
                            <div class="detail-value mb-1">📅 ${formatDate(
                              booking.scheduledDate
                            )}</div>
                            <div class="detail-value mb-1">🕐 ${
                              booking.scheduledTime
                            }</div>
                            <div class="detail-value">⏱️ Duration: ${
                              booking.estimatedDuration
                            }</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-group">
                            <div class="detail-label">
                                <i class="fas fa-info-circle me-2"></i>Status
                            </div>
                            <div class="detail-value mb-2">
                                <span class="status-badge status-${
                                  booking.status
                                }">
                                    <i class="fas fa-${getStatusIcon(
                                      booking.status
                                    )}"></i>
                                    ${booking.status.replace("-", " ")}
                                </span>
                            </div>
                        </div>

                        <div class="detail-group">
                            <div class="detail-label">
                                <i class="fas fa-dollar-sign me-2"></i>Cost Information
                            </div>
                            <div class="detail-value"><strong>${
                              booking.totalCost
                            }</strong></div>
                        </div>

                        <div class="detail-group">
                            <div class="detail-label">
                                <i class="fas fa-clock me-2"></i>Booking Created
                            </div>
                            <div class="detail-value">${new Date(
                              booking.createdAt
                            ).toLocaleString()}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">
                        <i class="fas fa-sticky-note me-2"></i>Service Notes
                    </div>
                    <div class="detail-value">${booking.notes}</div>
                </div>

                <div class="status-update-section">
                    <div class="status-update-title">
                        <i class="fas fa-edit me-2"></i>Update Status
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <select class="form-select" id="modalStatusSelect">
                                <option value="pending" ${
                                  booking.status === "pending" ? "selected" : ""
                                }>Pending</option>
                                <option value="in-progress" ${
                                  booking.status === "in-progress"
                                    ? "selected"
                                    : ""
                                }>In Progress</option>
                                <option value="completed" ${
                                  booking.status === "completed"
                                    ? "selected"
                                    : ""
                                }>Completed</option>
                                <option value="cancelled" ${
                                  booking.status === "cancelled"
                                    ? "selected"
                                    : ""
                                }>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <button class="btn btn-success-custom w-100" onclick="updateStatus('${
                              booking.id
                            }', document.getElementById('modalStatusSelect').value)">
                                <i class="fas fa-save"></i>
                                Update Status
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4 mb-2">
                        <button class="btn btn-success-custom w-100" onclick="confirmBooking('${
                          booking.id
                        }')" ${
    booking.status === "completed" || booking.status === "cancelled"
      ? "disabled"
      : ""
  }>
                            <i class="fas fa-check"></i>
                            Confirm Booking
                        </button>
                    </div>
                    <div class="col-md-4 mb-2">
                        <button class="btn btn-warning-custom w-100" onclick="rescheduleBooking('${
                          booking.id
                        }')" ${
    booking.status === "completed" || booking.status === "cancelled"
      ? "disabled"
      : ""
  }>
                            <i class="fas fa-calendar-alt"></i>
                            Reschedule
                        </button>
                    </div>
                    <div class="col-md-4 mb-2">
                        <button class="btn btn-danger-custom w-100" onclick="cancelBooking('${
                          booking.id
                        }')" ${
    booking.status === "completed" || booking.status === "cancelled"
      ? "disabled"
      : ""
  }>
                            <i class="fas fa-times"></i>
                            Cancel Booking
                        </button>
                    </div>
                </div>
            `;

  const modalInstance = new bootstrap.Modal(modal);
  modalInstance.show();
}

function updateStatus(bookingId, newStatus) {
  if (!newStatus) return;

  const booking = bookings.find((b) => b.id === bookingId);
  if (booking) {
    booking.status = newStatus;
    const tableContainer = document.getElementById("tableContainer");
    tableContainer.classList.add("table-loading");

    setTimeout(() => {
      tableContainer.classList.remove("table-loading");
      renderTable(getFilteredBookings());
      updateStats();
      showNotification(
        `Booking ${bookingId} status updated to ${newStatus.replace("-", " ")}`,
        "success"
      );

      const modal = bootstrap.Modal.getInstance(
        document.getElementById("bookingModal")
      );
      if (modal) {
        modal.hide();
      }
    }, 1000);
  }
}

function confirmBooking(bookingId) {
  updateStatus(bookingId, "in-progress");
  showNotification(
    `Booking ${bookingId} confirmed and set to In Progress`,
    "success"
  );
}

function cancelBooking(bookingId) {
  if (confirm("Are you sure you want to cancel this booking?")) {
    updateStatus(bookingId, "cancelled");
    showNotification(`Booking ${bookingId} has been cancelled`, "warning");
  }
}

function rescheduleBooking(bookingId) {
  const booking = bookings.find((b) => b.id === bookingId);
  if (booking) {
    const newDate = prompt(
      "Enter new date (YYYY-MM-DD):",
      booking.scheduledDate
    );
    const newTime = prompt("Enter new time:", booking.scheduledTime);

    if (newDate && newTime) {
      booking.scheduledDate = newDate;
      booking.scheduledTime = newTime;
      renderTable(getFilteredBookings());
      showNotification(`Booking ${bookingId} rescheduled successfully`, "info");

      const modal = bootstrap.Modal.getInstance(
        document.getElementById("bookingModal")
      );
      if (modal) {
        modal.hide();
      }
    }
  }
}

function refreshBookings() {
  const tableContainer = document.getElementById("tableContainer");
  tableContainer.classList.add("table-loading");

  setTimeout(() => {
    tableContainer.classList.remove("table-loading");
    renderTable(getFilteredBookings());
    updateStats();
    showNotification("Bookings refreshed successfully", "success");
  }, 800);
}

// Helper functions
function getFilteredBookings() {
  const search = document.getElementById("searchInput").value.toLowerCase();
  const statusFilter = document.getElementById("statusFilter").value;
  const serviceFilter = document.getElementById("serviceFilter").value;
  const dateFilter = document.getElementById("dateFilter").value;

  return bookings.filter((booking) => {
    const matchesSearch = booking.customerName.toLowerCase().includes(search);
    const matchesStatus = !statusFilter || booking.status === statusFilter;
    const matchesService =
      !serviceFilter ||
      booking.serviceType.toLowerCase().replace(" ", "-") === serviceFilter;
    const matchesDate = !dateFilter || booking.scheduledDate === dateFilter;

    return matchesSearch && matchesStatus && matchesService && matchesDate;
  });
}

function getServiceIcon(serviceType) {
  const icons = {
    "Oil Change": "oil-can",
    "Brake Repair": "stop-circle",
    "Engine Diagnostic": "cogs",
    "Tire Service": "tire",
    "Transmission Service": "gears",
  };
  return icons[serviceType] || "wrench";
}

function getStatusIcon(status) {
  const icons = {
    pending: "clock",
    "in-progress": "spinner",
    completed: "check-circle",
    cancelled: "times-circle",
  };
  return icons[status] || "info-circle";
}

function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString("en-US", {
    weekday: "short",
    year: "numeric",
    month: "short",
    day: "numeric",
  });
}

function showNotification(message, type = "info") {
  const notification = document.createElement("div");
  notification.className = `notification alert alert-${type}`;

  notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${getNotificationIcon(type)} me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;

  document.body.appendChild(notification);

  setTimeout(() => notification.classList.add("show"), 100);

  setTimeout(() => {
    notification.classList.remove("show");
    setTimeout(() => {
      if (notification.parentElement) {
        notification.remove();
      }
    }, 300);
  }, 4000);
}

function getNotificationIcon(type) {
  const icons = {
    success: "check-circle",
    warning: "exclamation-triangle",
    error: "times-circle",
    info: "info-circle",
  };
  return icons[type] || "info-circle";
}

function exportBookings() {
  const filtered = getFilteredBookings();
  const csvContent =
    "data:text/csv;charset=utf-8," +
    "ID,Customer Name,Phone,Email,Service Type,Vehicle,License Plate,Scheduled Date,Scheduled Time,Status,Cost,Duration,Notes\n" +
    filtered
      .map(
        (booking) =>
          `${booking.id},"${booking.customerName}","${booking.phone}","${booking.email}","${booking.serviceType}","${booking.vehicleInfo}","${booking.licensePlate}","${booking.scheduledDate}","${booking.scheduledTime}","${booking.status}","${booking.priority}","${booking.totalCost}","${booking.estimatedDuration}","${booking.notes}"`
      )
      .join("\n");

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute(
    "download",
    `booking_requests_${new Date().toISOString().split("T")[0]}.csv`
  );
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  showNotification("Bookings exported successfully", "success");
}

document.addEventListener("keydown", function (e) {
  if (e.ctrlKey && e.key === "r") {
    e.preventDefault();
    refreshBookings();
  }

  if (e.key === "Escape") {
    document.getElementById("searchInput").value = "";
    document.getElementById("statusFilter").value = "";
    document.getElementById("serviceFilter").value = "";
    document.getElementById("dateFilter").value = "";
    filterBookings();
  }
});

setInterval(() => {
  console.log("Auto-refreshing bookings...");
}, 30000);
