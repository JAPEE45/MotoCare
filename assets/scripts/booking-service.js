// Global variable to hold all booking data once fetched
let bookings = [];

// Main async function to fetch data and initialize the application
const initializeApp = async () => {
  try {
    const response = await fetch("../../helper/staffGetAllBooking.php");
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();
    bookings = data; // Assign fetched data to the global variable

    // --- KEY FIX: Initialize everything AFTER data is loaded ---
    renderTable(bookings);
    updateStats();
    setupEventListeners();
    // -----------------------------------------------------------

  } catch (error) {
    console.error("Failed to fetch bookings:", error);
    // Show an error to the user on the page
    document.getElementById("bookingsTableBody").innerHTML = 
      `<tr><td colspan="7" class="text-center text-danger">Error loading bookings. Please try refreshing.</td></tr>`;
  }
};

// --- CLEANUP: Call the main initialization function when the page is ready ---
document.addEventListener("DOMContentLoaded", initializeApp);


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
  
  // Setup for reschedule modal form
  const confirmButton = document.getElementById("confirmReschedule");
  if (confirmButton) {
     confirmButton.addEventListener("click", handleReschedule);
  }
  document.getElementById("newDate").addEventListener("change", validateForm);
  document.getElementById("newTime").addEventListener("change", validateForm);
}

function renderTable(bookingData) {
  const tbody = document.getElementById("bookingsTableBody");
  const emptyState = document.getElementById("emptyState");
  const tableContainer = document.querySelector(".table-container");

  tbody.innerHTML = ""; // Clear existing table data

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
                        <i class="fas fa-${getServiceIcon(booking.serviceType)}"></i>
                    </span>
                    ${booking.serviceType}
                </div>
            </td>
            <td>
                <div class="fw-bold">${booking.vehicleInfo}</div>
                <small class="sub-text">${booking.licensePlate}</small>
            </td>
            <td>
                <div class="fw-bold">${booking.scheduledDate}</div>
                <small class="sub-text">${booking.scheduledTime}</small>
            </td>
            <td>
                <span class="status-badge status-${booking.status}">
                    <i class="fas fa-${getStatusIcon(booking.status)}"></i>
                    ${booking.status.replace("-", " ")}
                </span>
            </td>
            <td>
                <button class="btn btn-view" onclick="viewBookingDetails('${booking.id}')">
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
    // Check if properties exist before calling methods on them
    const customerName = booking.customerName || '';
    const serviceType = booking.serviceType || '';
    const scheduledDate = booking.scheduledDate || '';
    
    const matchesSearch = customerName.toLowerCase().includes(search);
    const matchesStatus = !statusFilter || booking.status === statusFilter;
    const matchesService = !serviceFilter || serviceType.toLowerCase().replace(" ", "-") === serviceFilter;
    const matchesDate = !dateFilter || scheduledDate === dateFilter;

    return matchesSearch && matchesStatus && matchesService && matchesDate;
  });

  renderTable(filtered);
}
let selectedBooking = 0
function viewBookingDetails(bookingId) {
  // Now `bookings` will always be populated when this is called
  const booking = bookings.find((b) => b.id == bookingId);
  selectedBooking = booking.id
 
  if (!booking) {
      console.error("Booking not found:", bookingId);
      return;
  }

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
                <div class="detail-label"><i class="fas fa-user me-2"></i>Customer Information</div>
                <div class="detail-value mb-2"><strong>${booking.customerName}</strong></div>
                <div class="detail-value mb-1">📧 ${booking.email}</div>
                <div class="detail-value">📱 ${booking.phone}</div>
            </div>
            <div class="detail-group">
                <div class="detail-label"><i class="fas fa-car me-2"></i>Vehicle Information</div>
                <div class="detail-value mb-1"><strong>${booking.vehicleInfo}</strong></div>
                <div class="detail-value">License: ${booking.licensePlate}</div>
            </div>
            <div class="detail-group">
                <div class="detail-label"><i class="fas fa-calendar me-2"></i>Service Details</div>
                <div class="detail-value mb-1"><strong>${booking.serviceType}</strong></div>
                <div class="detail-value mb-1">📅 ${formatDate(booking.scheduledDate)}</div>
                <div class="detail-value mb-1">🕐 ${booking.scheduledTime}</div>
                <div class="detail-value">⏱️ Duration: ${booking.estimatedDuration}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="detail-group">
                <div class="detail-label"><i class="fas fa-info-circle me-2"></i>Status</div>
                <div class="detail-value mb-2">
                    <span class="status-badge status-${booking.status}">
                        <i class="fas fa-${getStatusIcon(booking.status)}"></i>
                        ${booking.status.replace("-", " ")}
                    </span>
                </div>
            </div>
            <div class="detail-group">
                <div class="detail-label"><i class="fas fa-dollar-sign me-2"></i>Cost Information</div>
                <div class="detail-value"><strong>${booking.totalCost}</strong></div>
            </div>
            <div class="detail-group">
                <div class="detail-label"><i class="fas fa-clock me-2"></i>Booking Created</div>
                <div class="detail-value">${new Date(booking.createdAt).toLocaleString()}</div>
            </div>
        </div>
    </div>
    <div class="detail-group">
        <div class="detail-label"><i class="fas fa-sticky-note me-2"></i>Service Notes</div>
        <div class="detail-value">${booking.notes}</div>
    </div>
    <div class="status-update-section">
        <div class="status-update-title"><i class="fas fa-edit me-2"></i>Update Status</div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <select class="form-select" id="modalStatusSelect">
                    <option value="pending" ${booking.status === "pending" ? "selected" : ""}>Pending</option>
                    <option value="progress" ${booking.status === "in-progress" ? "selected" : ""}>In Progress</option>
                    <option value="completed" ${booking.status === "completed" ? "selected" : ""}>Completed</option>
                    <option value="done" ${booking.status === "cancelled" ? "selected" : ""}>Done</option>
                </select>
            </div>
            <div class="col-md-6 mb-2">
                <button class="btn btn-success-custom w-100" onclick="updateStatus('${booking.id}', document.getElementById('modalStatusSelect').value)">
                    <i class="fas fa-save"></i> Update Status
                </button>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4 mb-2">
            <button class="btn btn-success-custom w-100" onclick="confirmBooking('${booking.id}')" ${booking.status === "completed" || booking.status === "cancelled" ? "disabled" : ""}>
                <i class="fas fa-check"></i> Confirm Booking
            </button>
        </div>
        <div class="col-md-4 mb-2">
            <button class="btn btn-warning-custom w-100" onclick="rescheduleBooking('${booking.id}')" ${booking.status === "completed" || booking.status === "cancelled" ? "disabled" : ""}>
                <i class="fas fa-calendar-alt"></i> Reschedule
            </button>
        </div>
        <div class="col-md-4 mb-2">
            <button class="btn btn-danger-custom w-100" onclick="cancelBooking('${booking.id}')" ${booking.status === "completed" || booking.status === "cancelled" ? "disabled" : ""}>
                <i class="fas fa-times"></i> Cancel Booking
            </button>
        </div>
    </div>
  `;

  const modalInstance = new bootstrap.Modal(modal);
  modalInstance.show();
}

function updateStatus(bookingId, newStatus) {
  fetch(`../../helper/staffUpdateStatus.php?status=${newStatus}&id=${bookingId}`)
    .then(e=>e.json())
    .then(e=>{
      if(e.success){
        console.log("successs")
      }else{
        alert("Error")
      }
    })
  if (!newStatus) return;

  const booking = bookings.find((b) => b.id == bookingId);
  if (booking) {
    booking.status = newStatus;
    const tableContainer = document.getElementById("tableContainer");
    tableContainer.classList.add("table-loading");

    setTimeout(() => {
      tableContainer.classList.remove("table-loading");
      // --- REFACTOR: Call filterBookings to re-render the table with current filters ---
      filterBookings(); 
      updateStats();
      showNotification(
        `Booking ${bookingId} status updated to ${newStatus.replace("-", " ")}`,
        "success"
      );

      const modalEl = document.getElementById("bookingModal");
      if (modalEl) {
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
          modal.hide();
        }
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

// Keep a reference to the current booking being edited
let currentBookingId = null; 

function rescheduleBooking(bookingId) {
  currentBookingId = bookingId;
  const booking = bookings.find((b) => b.id == bookingId);

  if (booking) {
    document.getElementById("currentCustomer").textContent = booking.customerName;
    document.getElementById("currentService").textContent = booking.serviceType;
    document.getElementById("currentDate").textContent = formatDate(booking.scheduledDate);
    document.getElementById("currentTime").textContent = booking.scheduledTime;
    
    const today = new Date().toISOString().split("T")[0];
    document.getElementById("newDate").min = today;

    document.getElementById("rescheduleForm").reset();
    hideAlert();

    const modal = new bootstrap.Modal(document.getElementById("rescheduleModal"));
    modal.show();
  }
}

function refreshBookings() {
  const tableContainer = document.getElementById("tableContainer");
  tableContainer.classList.add("table-loading");

  setTimeout(() => {
    tableContainer.classList.remove("table-loading");
    // --- REFACTOR: Call filterBookings to re-render the table with current filters ---
    filterBookings();
    updateStats();
    showNotification("Bookings refreshed successfully", "success");
  }, 800);
}

// --- REMOVED getFilteredBookings() as it was redundant ---

// (The rest of your utility functions: getServiceIcon, getStatusIcon, etc. are fine and don't need changes)

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

// --- CLEANUP: Consolidated into a single, consistent formatDate function ---
function formatDate(dateString) {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    // Add a check for invalid dates
    if (isNaN(date.getTime())) {
        return "Invalid Date";
    }
    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
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
    // We need to get the currently filtered data for export
    const search = document.getElementById("searchInput").value.toLowerCase();
    const statusFilter = document.getElementById("statusFilter").value;
    const serviceFilter = document.getElementById("serviceFilter").value;
    const dateFilter = document.getElementById("dateFilter").value;

    const filtered = bookings.filter((booking) => {
        const customerName = booking.customerName || '';
        const serviceType = booking.serviceType || '';
        const scheduledDate = booking.scheduledDate || '';
        
        const matchesSearch = customerName.toLowerCase().includes(search);
        const matchesStatus = !statusFilter || booking.status == statusFilter;
        const matchesService = !serviceFilter || serviceType.toLowerCase().replace(" ", "-") === serviceFilter;
        const matchesDate = !dateFilter || scheduledDate == dateFilter;

        return matchesSearch && matchesStatus && matchesService && matchesDate;
    });

    const csvContent =
        "data:text/csv;charset=utf-8," +
        "ID,Customer Name,Phone,Email,Service Type,Vehicle,License Plate,Scheduled Date,Scheduled Time,Status,Cost,Duration,Notes\n" +
        filtered
        .map(
            (b) =>
            `"${b.id}","${b.customerName}","${b.phone}","${b.email}","${b.serviceType}","${b.vehicleInfo}","${b.licensePlate}","${b.scheduledDate}","${b.scheduledTime}","${b.status}","${b.totalCost}","${b.estimatedDuration}","${b.notes.replace(/"/g, '""')}"`
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

// The rest of the reschedule-related functions

function handleReschedule() {
  if (!validateForm()) {
    return;
  }
  
  const newDate = document.getElementById("newDate").value;
  const newTime = document.getElementById("newTime").value;
  const reason = document.getElementById("rescheduleReason").value;
  
  const confirmBtn = document.getElementById("confirmReschedule");
  const originalText = confirmBtn.innerHTML;
  confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
  confirmBtn.disabled = true;

  setTimeout(() => {
    try {
      performReschedule(currentBookingId, newDate, newTime, reason);
      showAlert("success", "Booking has been successfully rescheduled!");

      setTimeout(() => {
        const modalEl = document.getElementById("rescheduleModal");
        if(modalEl){
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        }
        
        // Use your existing refresh function
        refreshBookings();
        
      }, 2000);
    } catch (error) {
      showAlert("danger", "Failed to reschedule booking. Please try again.");
      console.error("Reschedule error:", error);
    } finally {
      confirmBtn.innerHTML = originalText;
      confirmBtn.disabled = false;
    }
  }, 1500);
}

function performReschedule(bookingId, newDate, newTime, reason) {
  const bookingToUpdate = bookings.find(b => b.id == bookingId);
  if (bookingToUpdate) {
    bookingToUpdate.scheduledDate = newDate;
    bookingToUpdate.scheduledTime = convertTo12Hour(newTime);
    if (reason) {
      bookingToUpdate.notes += `\nRescheduled: ${reason}`;
    }
    console.log("Rescheduled booking:", bookingToUpdate);
    // Here you would typically send an update to your server
  }
}

function validateForm() {
  const newDate = document.getElementById("newDate");
  const newTime = document.getElementById("newTime");
  let isValid = true;

  if (!newDate.value) {
    newDate.classList.add("is-invalid");
    isValid = false;
  } else {
    const selectedDate = new Date(newDate.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
      newDate.classList.add("is-invalid");
      showAlert("danger", "Please select a date that is today or in the future.");
      isValid = false;
    } else {
      newDate.classList.remove("is-invalid");
    }
  }

  if (!newTime.value) {
    newTime.classList.add("is-invalid");
    isValid = false;
  } else {
    newTime.classList.remove("is-invalid");
  }

  // If form is valid so far, hide any previous alerts
  if(isValid) hideAlert();

  return isValid;
}

function showAlert(type, message) {
  const alert = document.getElementById("rescheduleAlert");
  const alertMessage = document.getElementById("rescheduleAlertMessage");

  alert.className = `alert alert-${type}`;
  alertMessage.textContent = message;
  alert.classList.remove("d-none");
}

function hideAlert() {
  document.getElementById("rescheduleAlert").classList.add("d-none");
}

function convertTo12Hour(time24) {
  if(!time24) return "";
  const [hours, minutes] = time24.split(":");
  const hour = parseInt(hours);
  const ampm = hour >= 12 ? "PM" : "AM";
  const displayHour = hour % 12 || 12;
  return `${displayHour}:${minutes} ${ampm}`;
}