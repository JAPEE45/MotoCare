let appointments = [];
let bookingsByDate = {};

let currentDate = new Date();
let selectedDate = new Date();

async function init() {
  updateCurrentDate();
  await fetchBookings();
  updateStatistics();
  generateCalendar();
  showAppointments();
}

async function fetchBookings() {
  // Get shop_id from a hidden input or session (adjust as needed)
  const shopIdInput = document.getElementById('shopId');
  if (!shopIdInput || !shopIdInput.value) {
    appointments = [];
    bookingsByDate = {};
    return;
  }
  const shopId = shopIdInput.value;
  const res = await fetch(`../../helper/staffGetAllBooking.php?shop_id=${shopId}`);
  const data = await res.json();
  appointments = data.map(b => ({
    id: b.id,
    date: b.scheduleDate,
    time: b.scheduledTime,
    customer: b.customerName,
    vehicle: `${b.vehicleInfo} (${b.vehicle_model})`,
    service: b.serviceType,
    status: b.status,
    phone: b.phone,
    price: b.totalCost,
    notes: b.notes,
    shop: b.shop_name,
    plate: b.vehicle_plate_number
  }));
    // duration removed
  bookingsByDate = {};
  appointments.forEach(b => {
    if (!bookingsByDate[b.date]) bookingsByDate[b.date] = [];
    bookingsByDate[b.date].push(b);
  });
}

function updateCurrentDate() {
  const today = new Date();
  const dateString = today.toLocaleDateString("en-PH", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });
  document.getElementById("currentDate").textContent = `Today is ${dateString}`;
}

function updateStatistics() {
  const today = formatDate(new Date());
  const todayAppointments = appointments.filter((apt) => apt.date === today);

  const stats = {
    today: todayAppointments.length,
    ongoing: appointments.filter((apt) => apt.status === "ongoing").length,
    confirmed: appointments.filter((apt) => apt.status === "confirmed").length,
    total: appointments.length,
  };

  document.getElementById("todayCount").textContent = stats.today;
  document.getElementById("ongoingCount").textContent = stats.ongoing;
  document.getElementById("confirmedCount").textContent = stats.confirmed;
  document.getElementById("totalCount").textContent = stats.total;
}

function formatDate(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}

function navigateMonth(direction) {
  currentDate.setMonth(currentDate.getMonth() + direction);
  generateCalendar();
// month number by clicked direction
}
  
function generateCalendar() {
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();
  document.getElementById("currentMonth").textContent =
    currentDate.toLocaleDateString("en-PH", {
      month: "long",
      year: "numeric",
    });
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const daysInMonth = lastDay.getDate();
  const startingDayOfWeek = firstDay.getDay();
  let calendarHTML = "";
  let dayCount = 1;
  const today = formatDate(new Date());
  const selectedDateStr = formatDate(selectedDate);
  const totalCells = Math.ceil((daysInMonth + startingDayOfWeek) / 7) * 7;
  for (let i = 0; i < totalCells; i++) {
    if (i % 7 === 0) {
      calendarHTML += '<div class="row g-0">';
    }
    if (i < startingDayOfWeek || dayCount > daysInMonth) {
      calendarHTML += '<div class="col"><div class="calendar-day"></div></div>';
    } else {
      const currentDateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(dayCount).padStart(2, "0")}`;
      const dayAppointments = bookingsByDate[currentDateStr] || [];
      let dayClasses = "calendar-day";
      if (currentDateStr === today) dayClasses += " today";
      if (currentDateStr === selectedDateStr) dayClasses += " selected";
      let appointmentBadgesHTML = "";
      dayAppointments.forEach((apt, index) => {
        if (index < 3) {
          appointmentBadgesHTML += `<span class="appointment-badge ${apt.status}" data-id="${apt.id}" data-date="${currentDateStr}">${apt.time}</span>`;
        }
      });
      if (dayAppointments.length > 3) {
        appointmentBadgesHTML += `<small class="text-muted d-block">+${dayAppointments.length - 3} more</small>`;
      }
      calendarHTML += `
        <div class="col">
          <div class="${dayClasses}" data-date="${currentDateStr}">
            <div class="p-2 h-100 d-flex flex-column">
              <div class="fw-bold mb-1">${dayCount}</div>
              <div class="appointment-container flex-grow-1">
                ${appointmentBadgesHTML}
              </div>
            </div>
          </div>
        </div>
      `;
      dayCount++;
    }
    if (i % 7 === 6) {
      calendarHTML += "</div>";
    }
  }
  document.getElementById("calendarGrid").innerHTML = calendarHTML;
  // Add click event for calendar days with bookings
  document.querySelectorAll('.calendar-day[data-date]').forEach(dayEl => {
    const date = dayEl.getAttribute('data-date');
    dayEl.addEventListener('click', function(e) {
      selectDate(date);
    });
  });
}
function showBookingModal(date) {
  const bookings = bookingsByDate[date] || [];
  let modalContent = '';
  if (bookings.length === 0) {
    modalContent = '<p>No bookings for this date.</p>';
  } else {
    modalContent = bookings.map(b => `
      <div class="mb-3">
        <strong>Customer:</strong> ${b.customer}<br>
        <strong>Vehicle:</strong> ${b.vehicle}<br>
        <strong>Service:</strong> ${b.service}<br>
        <strong>Status:</strong> ${b.status}<br>
        <strong>Time:</strong> ${b.time}<br>
        <strong>Plate:</strong> ${b.plate}<br>
        <strong>Notes:</strong> ${b.notes}
      </div>
      <hr>
    `).join('');
  }
  document.getElementById('bookingModalBody').innerHTML = modalContent;
  // Use Bootstrap 5 API to show modal
  const modalEl = document.getElementById('bookingModal');
  if (window.bootstrap && window.bootstrap.Modal) {
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  } else {
    // fallback for older Bootstrap
    $(modalEl).modal('show');
  }
}

function selectDate(dateString) {
  const parts = dateString.split("-");
  selectedDate = new Date(
    parseInt(parts[0]),
    parseInt(parts[1]) - 1,
    parseInt(parts[2])
  );
  generateCalendar();
  showAppointments();
}

function showAppointments() {
  const dateString = formatDate(selectedDate);
  const today = formatDate(new Date());
  const selectedAppointments = appointments.filter(
    (apt) => apt.date === dateString
  );

  const isToday = dateString === today;
  document.getElementById("appointmentsTitle").textContent = isToday
    ? "Today's Appointments"
    : "Selected Date Appointments";

  document.getElementById("selectedDateText").textContent =
    selectedDate.toLocaleDateString("en-PH", {
      weekday: "long",
      month: "long",
      day: "numeric",
    });

  let appointmentsHTML = "";

  if (selectedAppointments.length === 0) {
    appointmentsHTML = `
                    <div class="no-appointments">
                        <i class="fas fa-calendar-times fa-3x mb-3" style="color: var(--text-gray);"></i>
                        <p>No appointments for this date</p>
                    </div>
                `;
  } else {
    selectedAppointments.forEach((appointment) => {
      const statusIcon = {
        confirmed: "fa-check-circle text-success",
        ongoing: "fa-clock text-warning",
        canceled: "fa-times-circle text-danger",
        completed: "fa-check-circle text-success",
        pending: "fa-clock text-warning",
        progress: "fa-clock text-warning",
        'not accepted': "fa-clock text-warning",
        cancelled: "fa-times-circle text-danger",
        rejected: "fa-times-circle text-danger"
      };
      const iconString = statusIcon[appointment.status] || "fa-question-circle text-secondary";
      const iconClass = iconString.split(" ")[0].replace("fa-", "");
      const badgeClass = appointment.status === "confirmed" || appointment.status === "completed"
        ? "bg-success"
        : appointment.status === "ongoing" || appointment.status === "pending" || appointment.status === "progress"
        ? "bg-warning"
        : "bg-danger";

      appointmentsHTML += `
        <div class="appointment-card rounded p-3 mb-3 border-start">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="d-flex align-items-center">
              <i class="fas fa-clock me-2 text-muted"></i>
              <span class="fw-bold">${appointment.time}</span>
            </div>
            <span class="badge ${badgeClass}">
              <i class="fas ${iconClass} me-1"></i>
              ${appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}
            </span>
          </div>
          <div class="mb-2">
            <div class="d-flex align-items-center mb-1">
              <i class="fas fa-user me-2 text-muted"></i>
              <strong>${appointment.customer}</strong>
            </div>
            <div class="d-flex align-items-center mb-1">
              <i class="fas fa-car me-2 text-muted"></i>
              <span>${appointment.vehicle}</span>
            </div>
            <div class="d-flex align-items-center mb-1">
              <i class="fas fa-wrench me-2 text-muted"></i>
              <span class="brand-text">${appointment.service}</span>
            </div>
            <div class="d-flex align-items-center mb-1">
              <i class="fas fa-phone me-2 text-muted"></i>
              <span class="text-muted">${appointment.phone}</span>
            </div>
              <div class="d-flex justify-content-end mt-2">
                <small class="brand-text fw-bold">
                  ${appointment.price}
                </small>
              </div>
          </div>
        </div>
      `;
    });
  }

  document.getElementById("appointmentsList").innerHTML = appointmentsHTML;
}

document.addEventListener("DOMContentLoaded", init);
