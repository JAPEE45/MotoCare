// Mock data for appointments
const appointments = [
  {
    id: 1,
    date: "2025-08-27",
    time: "09:00",
    customer: "John Smith",
    vehicle: "2020 Honda Civic",
    service: "Oil Change & Filter",
    status: "confirmed",
    phone: "+63 912 345 6789",
    duration: "1 hour",
    price: "₱1,500",
  },
  {
    id: 2,
    date: "2025-08-27",
    time: "10:30",
    customer: "Maria Garcia",
    vehicle: "2019 Toyota Vios",
    service: "Brake Inspection",
    status: "ongoing",
    phone: "+63 923 456 7890",
    duration: "2 hours",
    price: "₱2,800",
  },
  {
    id: 3,
    date: "2025-08-27",
    time: "14:00",
    customer: "Pedro Santos",
    vehicle: "2021 Mitsubishi Mirage",
    service: "Engine Repair",
    status: "confirmed",
    phone: "+63 934 567 8901",
    duration: "3 hours",
    price: "₱4,500",
  },
  {
    id: 4,
    date: "2025-08-27",
    time: "15:30",
    customer: "Ana Reyes",
    vehicle: "2018 Hyundai Accent",
    service: "Tire Replacement",
    status: "canceled",
    phone: "+63 945 678 9012",
    duration: "1.5 hours",
    price: "₱3,200",
  },
  {
    id: 5,
    date: "2025-08-28",
    time: "09:00",
    customer: "Carlos Lopez",
    vehicle: "2020 Nissan Navara",
    service: "Transmission Check",
    status: "confirmed",
    phone: "+63 956 789 0123",
    duration: "2.5 hours",
    price: "₱3,800",
  },
  {
    id: 6,
    date: "2025-08-28",
    time: "11:00",
    customer: "Rosa Dela Cruz",
    vehicle: "2019 Suzuki Swift",
    service: "AC Repair",
    status: "ongoing",
    phone: "+63 967 890 1234",
    duration: "2 hours",
    price: "₱2,500",
  },
  {
    id: 7,
    date: "2025-08-29",
    time: "08:30",
    customer: "Miguel Torres",
    vehicle: "2022 Ford Ranger",
    service: "Battery Replacement",
    status: "confirmed",
    phone: "+63 978 901 2345",
    duration: "1 hour",
    price: "₱4,000",
  },
  {
    id: 8,
    date: "2025-09-19",
    time: "08:30",
    customer: "Miguel Torres",
    vehicle: "2022 Ford Ranger",
    service: "Battery Replacement",
    status: "confirmed",
    phone: "+63 978 901 2345",
    duration: "1 hour",
    price: "₱4,000",
  },
  {
    id: 9,
    date: "2025-09-22",
    time: "08:30",
    customer: "Miguel Torres",
    vehicle: "2022 Ford Ranger",
    service: "Battery Replacement",
    status: "ongoing",
    phone: "+63 978 901 2345",
    duration: "1 hour",
    price: "₱4,000",
  },
  {
    id: 10,
    date: "2025-09-22",
    time: "09:30",
    customer: "Miguel Torres",
    vehicle: "2022 Ford Ranger",
    service: "Battery Replacement",
    status: "confirmed",
    phone: "+63 978 901 2345",
    duration: "1 hour",
    price: "₱4,000",
  }
];

let currentDate = new Date();
let selectedDate = new Date();

// Initialize the dashboard
function init() {
  updateCurrentDate();
  updateStatistics();
  generateCalendar();
  showAppointments();
}

// Update current time

// Update current date
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

// Update statistics
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

// Format date to YYYY-MM-DD (avoid timezone issues)
function formatDate(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}

// Navigate month
function navigateMonth(direction) {
  currentDate.setMonth(currentDate.getMonth() + direction);
  generateCalendar();
}

// Generate calendar
function generateCalendar() {
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();

  // Update month display
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

  // Calculate total weeks needed
  const totalCells = Math.ceil((daysInMonth + startingDayOfWeek) / 7) * 7;

  for (let i = 0; i < totalCells; i++) {
    if (i % 7 === 0) {
      calendarHTML += '<div class="row g-0">';
    }

    if (i < startingDayOfWeek || dayCount > daysInMonth) {
      calendarHTML += '<div class="col"><div class="calendar-day"></div></div>';
    } else {
      const currentDateStr = `${year}-${String(month + 1).padStart(
        2,
        "0"
      )}-${String(dayCount).padStart(2, "0")}`;
      const dayAppointments = appointments.filter(
        (apt) => apt.date === currentDateStr
      );

      let dayClasses = "calendar-day";
      if (currentDateStr === today) dayClasses += " today";
      if (currentDateStr === selectedDateStr) dayClasses += " selected";

      // Create appointment badges HTML
      let appointmentBadgesHTML = "";
      dayAppointments.forEach((apt, index) => {
        if (index < 3) {
          appointmentBadgesHTML += `<span class="appointment-badge ${apt.status}">${apt.time}</span>`;
        }
      });

      if (dayAppointments.length > 3) {
        appointmentBadgesHTML += `<small class="text-muted d-block">+${
          dayAppointments.length - 3
        } more</small>`;
      }

      calendarHTML += `
                        <div class="col">
                            <div class="${dayClasses}" onclick="selectDate('${currentDateStr}')">
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
}

// Select date
function selectDate(dateString) {
  // Parse the date string correctly to avoid timezone issues
  const parts = dateString.split("-");
  selectedDate = new Date(
    parseInt(parts[0]),
    parseInt(parts[1]) - 1,
    parseInt(parts[2])
  );
  generateCalendar();
  showAppointments();
}

// Show appointments for selected date
function showAppointments() {
  const dateString = formatDate(selectedDate);
  const today = formatDate(new Date());
  const selectedAppointments = appointments.filter(
    (apt) => apt.date === dateString
  );

  // Update title
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

  // Generate appointments HTML
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
      };

      appointmentsHTML += `
                        <div class="appointment-card rounded p-3 mb-3 border-start">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock me-2 text-muted"></i>
                                    <span class="fw-bold">${
                                      appointment.time
                                    }</span>
                                </div>
                                <span class="badge ${
                                  appointment.status === "confirmed"
                                    ? "bg-success"
                                    : appointment.status === "ongoing"
                                    ? "bg-warning"
                                    : "bg-danger"
                                }">
                                    <i class="fas ${statusIcon[
                                      appointment.status
                                    ]
                                      .split(" ")[0]
                                      .replace("fa-", "")} me-1"></i>
                                    ${
                                      appointment.status
                                        .charAt(0)
                                        .toUpperCase() +
                                      appointment.status.slice(1)
                                    }
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
                                    <span class="brand-text">${
                                      appointment.service
                                    }</span>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas fa-phone me-2 text-muted"></i>
                                    <span class="text-muted">${
                                      appointment.phone
                                    }</span>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Duration: ${appointment.duration}
                                    </small>
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

// Initialize when page loads
document.addEventListener("DOMContentLoaded", init);
