let sampleBookings = [
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

let map;
let shp = [];
let current_services = [];
let allBookings = []; // global storage for filtering

// 🧠 Open booking modal and load shop services
async function bookService(shopName) {
  const ress = await fetch("../../helper/getShopAndServices.php");
  const json = await ress.json();
  shp = json;

  document.getElementById("selectedShop").textContent = shopName;
  const modal = new bootstrap.Modal(document.getElementById("bookingModal"));

  const serv = shp.filter((e) => e.name === shopName);
  if (serv.length === 0) {
    console.error("Shop not found:", shopName);
    return;
  }

  const res = await fetch(`../../helper/checkBookService.php?ddd=${serv[0].id}`);
  const j = await res.json();

  if (j.success) {
    window.location.href = "./booking-status.php";
    return;
  }

  const sel = document.getElementById("services");
  sel.innerHTML = "<option value='' disabled selected>Select Service</option>";

  document.getElementById("shop_id").value = serv[0].shop_id;

  serv[0].services.forEach((e) => {
    const node = document.createElement("option");
    node.value = e.id;
    node.textContent = e.service_name.toUpperCase();
    sel.appendChild(node);
  });

  modal.show();
}

// 🏷️ Get badge style per booking status
function getStatusBadge(status) {
  const statusMap = {
    pending: { class: "badge-pending", text: "PENDING" },
    progress: { class: "badge-progress", text: "IN PROGRESS" },
    completed: { class: "badge-completed", text: "COMPLETED" },
    "not accepted": { class: "badge-pending", text: "WAITING" },
    cancelled: { class: "badge-pending", text: "CANCELLED" },
  };
  const statusInfo = statusMap[status] || statusMap["pending"];
  return `<span class="badge ${statusInfo.class}">${statusInfo.text}</span>`;
}

// 🧾 Render booking table
async function renderBookings(bookings = null) {
  const tbody = document.getElementById("bookingsTable");

  if (!tbody) {
    console.error("❌ Element #bookingsTable not found in DOM.");
    return;
  }

  // Fetch from API only if no bookings passed
  if (bookings === null) {
    const res = await fetch(
      `../../helper/customerGetBooking.php?user_id=${document.getElementById("user_id").textContent}`
    );
    const json = await res.json();
    if (json.status === "success") {
      bookings = json.bookings;
    } else {
      console.error("Error fetching bookings:", json.message);
      return;
    }
  }

  if (!bookings || bookings.length === 0) {
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
          <td>${booking.vehicle || "N/A"}</td>
          <td>${booking.dateTime}</td>
          <td>${getStatusBadge(booking.status)}</td>
          <td>
            <a href="./booking-status.php?buid=${booking.id}" class="btn btn-sm btn-outline-light">
              <i class="fa-regular fa-eye"></i>
            </a>
            <button style="display:${
              booking.status === "not accepted" ? "block" : "none"
            }" class="btn btn-sm btn-outline-light" onclick="editBooking('${booking.id}')">
              <i class="fa-regular fa-pen-to-square"></i>
            </button>
          </td>
        </tr>`
    )
    .join("");
}

// ✏️ Edit booking info
async function editBooking(id) {
  const bookingModal = document.getElementById("bookingModal");
  const modal = new bootstrap.Modal(bookingModal);
  const res = await fetch(`../../helper/bookingStatus.php?buid=${id}`);
  const j = await res.json();

  await bookService(j.shop_name); // Wait for shop services to load

  document.getElementById("vehicle_name").value = j.vehicle_name;
  document.getElementById("vehicle_model").value = j.vehicle_model;
  document.getElementById("vehicle_plate_number").value = j.vehicle_plate_number;
  document.getElementById("preferred_date").value = j.preferred_time;
  document.getElementById("time").value = j.time;
  document.getElementById("notes").value = j.notes;
  document.getElementById("selectedShop").textContent = j.shop_name;

  const sers = document.getElementById("services");
  for (let option of sers.options) {
    if (option.value == j.service_id) {
      option.selected = true;
      break;
    }
  }

  // Attach save handler for update
  const saveBtn = bookingModal.querySelector(".btn-book");
  saveBtn.onclick = async function () {
    // Collect updated data
    const updatedData = {
      id: id,
      vehicle_name: document.getElementById("vehicle_name").value,
      vehicle_model: document.getElementById("vehicle_model").value,
      vehicle_plate_number: document.getElementById("vehicle_plate_number").value,
      preferred_time: document.getElementById("preferred_date").value,
      time: document.getElementById("time").value,
      service_id: document.getElementById("services").value,
      notes: document.getElementById("notes").value,
    };
    // Send update to backend
    const resp = await fetch("../../helper/updateBooking.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(updatedData),
    });
    const result = await resp.json();
    if (result.status === "success") {
      modal.hide();
      // Remove backdrop if still present
      document.body.classList.remove("modal-open");
      const backdrops = document.querySelectorAll(".modal-backdrop");
      backdrops.forEach((bd) => bd.parentNode.removeChild(bd));
      // Refresh bookings
      initializeBookings();
    } else {
      alert("Failed to update booking: " + (result.message || "Unknown error"));
    }
  };

  // Fix backdrop issue on close
  bookingModal.addEventListener("hidden.bs.modal", function () {
    document.body.classList.remove("modal-open");
    const backdrops = document.querySelectorAll(".modal-backdrop");
    backdrops.forEach((bd) => bd.parentNode.removeChild(bd));
  }, { once: true });

  modal.show();
}

// 📋 Initialize bookings from backend
async function initializeBookings() {
  const res = await fetch(
    `../../helper/customerGetBooking.php?user_id=${document.getElementById("user_id").textContent}`
  );
  const json = await res.json();

  if (json.status === "success") {
    allBookings = json.bookings;
    renderBookings(allBookings);
  } else {
    console.warn("⚠️ Backend unavailable — showing sample data.");
    renderBookings(sampleBookings);
  }
}

// 🔍 Filter bookings by name or status
function filterBookings() {
  const searchTerm = document.getElementById("searchCustomer").value.toLowerCase().trim();
  const statusFilter = document.getElementById("filterStatus").value;

  let filtered;
  if (statusFilter === "All Status" || statusFilter === "All Statuses" || statusFilter === "") {
    // Show all bookings if 'All Status' is selected, regardless of search
    if (searchTerm === "") {
      filtered = allBookings;
    } else {
      filtered = allBookings.filter((booking) => {
        const shopName = (booking.shopName || "").toLowerCase();
        const serviceName = (booking.service || "").toLowerCase();
        const vehicle = (booking.vehicle || "").toLowerCase();
        return shopName.includes(searchTerm) || serviceName.includes(searchTerm) || vehicle.includes(searchTerm);
      });
    }
  } else {
    filtered = allBookings.filter((booking) => {
      const shopName = (booking.shopName || "").toLowerCase();
      const serviceName = (booking.service || "").toLowerCase();
      const vehicle = (booking.vehicle || "").toLowerCase();
      const status = (booking.status || "").toLowerCase();
      const matchesSearch =
        searchTerm === "" ||
        shopName.includes(searchTerm) ||
        serviceName.includes(searchTerm) ||
        vehicle.includes(searchTerm);
      const matchesStatus = status === statusFilter.toLowerCase();
      return matchesSearch && matchesStatus;
    });
  }
  renderBookings(filtered);
}

// 🚀 Load after DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  initializeBookings();

  document
    .getElementById("searchCustomer")
    .addEventListener("input", filterBookings);
  document
    .getElementById("filterStatus")
    .addEventListener("change", filterBookings);

  // For offline/local test mode
  if (typeof allBookings === "undefined" || allBookings.length === 0) {
    renderBookings(sampleBookings);
  }
});
