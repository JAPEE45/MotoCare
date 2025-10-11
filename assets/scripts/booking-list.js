const sampleBookings = [
  {
    id: "BK001",
    shopName: "Molje Lube",
    service: "Oil Change",
    vehicle: "Honda CBR 600",
    dateTime: "2024-10-15 10:00 AM",
    status: "pending",
  },
  {
    id: "BK002",
    shopName: "Jane Smith",
    service: "Brake Service",
    vehicle: "Yamaha YZF-R1",
    dateTime: "2024-10-15 02:00 PM",
    status: "progress",
  },
  {
    id: "BK003",
    shopName: "Mike Johnson",
    service: "Tire Rotation",
    vehicle: "Kawasaki Ninja",
    dateTime: "2024-10-14 11:30 AM",
    status: "completed",
  },
];

function getStatusBadge(status) {
  const statusMap = {
    pending: { class: "badge-pending", text: "PENDING" },
    progress: { class: "badge-progress", text: "IN PROGRESS" },
    completed: { class: "badge-completed", text: "COMPLETED" },
  };
  const statusInfo = statusMap[status] || statusMap["pending"];
  return `<span class="badge ${statusInfo.class}">${statusInfo.text}</span>`;
}

function renderBookings(bookings) {
  const tbody = document.getElementById("bookingsTable");

  if (bookings.length === 0) {
    tbody.innerHTML =
      '<tr><td colspan="7" class="error-message">No bookings found.</td></tr>';
    return;
  }

  tbody.innerHTML = bookings
    .map(
      (booking) => `
                <tr>
                    <td>${booking.id}</td>
                    <td>${booking.shopName}</td>
                    <td>${booking.service}</td>
                    <td>${booking.vehicle}</td>
                    <td>${booking.dateTime}</td>
                    <td>${getStatusBadge(booking.status)}</td>
                    <td>
                        <a href="./booking-status.php" class="btn btn-sm btn-outline-light">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-light" onclick="editBooking()">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    </td>
                </tr>
            `
    )
    .join("");
}

function editBooking() {
  const bookingModal = document.getElementById("bookingModal");
  
  // Show modal (Bootstrap 5 example)
  const modal = new bootstrap.Modal(bookingModal);
  modal.show();
}

// Uncomment this line to load sample data
// renderBookings(sampleBookings);

// Filter functionality
document
  .getElementById("searchCustomer")
  .addEventListener("input", filterBookings);
document
  .getElementById("filterStatus")
  .addEventListener("change", filterBookings);
document
  .getElementById("filterService")
  .addEventListener("change", filterBookings);

function filterBookings() {
  const searchTerm = document
    .getElementById("searchCustomer")
    .value.toLowerCase();
  const statusFilter = document.getElementById("filterStatus").value;
  const serviceFilter = document.getElementById("filterService").value;
  //   const dateFilter = document.getElementById("filterDate").value;
  console.log(serviceFilter);

  let filtered = sampleBookings.filter((booking) => {
    const matchesSearch = booking.shopName.toLowerCase().includes(searchTerm);
    const matchesStatus =
      statusFilter === "All Statuses" || booking.status === statusFilter;
    const matchesService =
      serviceFilter === "All Services" ||
      booking.service.toLowerCase().includes(serviceFilter.toLowerCase());

    return matchesSearch && matchesStatus && matchesService;
  });

  renderBookings(filtered);
}

renderBookings(sampleBookings);
