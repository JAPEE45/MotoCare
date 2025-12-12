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
  console.log("=== bookService called for:", shopName);
  const ress = await fetch("../../helper/getShopAndServices.php");
  const json = await ress.json();
  shp = json;

  document.getElementById("selectedShop").textContent = shopName;
  const modal = new bootstrap.Modal(document.getElementById("bookingModal"));

  const serv = shp.filter((e) => e.name === shopName);
  if (serv.length === 0) {
    console.error("Shop not found:", shopName);
    alert("Shop not found. Please try again.");
    return;
  }

  const res = await fetch(`../../helper/checkBookService.php?ddd=${serv[0].id}`);
  const j = await res.json();

  if (j.limit_reached) {
    alert(j.message || "You have reached the maximum limit of 10 bookings per day for this shop.");
    return;
  }
  
  // Display remaining bookings if allowed
  if(j.allowed && j.remaining < 10){
    console.log(`Bookings today: ${j.count}/10 - Remaining: ${j.remaining}`);
  }

  // Build checkboxes for multiple service selection
  const container = document.getElementById("servicesCheckboxContainer");
  if (!container) {
    console.error("ERROR: servicesCheckboxContainer not found in DOM!");
    alert("Error: Service container not found. Please refresh the page.");
    return;
  }

  // Clear previous content
  container.innerHTML = "";

  if (!serv[0].services || serv[0].services.length === 0) {
    console.error("ERROR: No services available for this shop");
    container.innerHTML = '<p class="text-warning">No services available for this shop.</p>';
    modal.show();
    return;
  }

  console.log("✅ Building checkboxes for", serv[0].services.length, "services:");
  document.getElementById("shop_id").value = serv[0].shop_id;

  serv[0].services.forEach((service, index) => {
    console.log(`  ${index + 1}. ${service.service_name} (ID: ${service.id}) - ₱${service.labor_price || 'N/A'}`);

    const checkboxDiv = document.createElement("div");
    checkboxDiv.className = "form-check mb-2";
    checkboxDiv.style.padding = "10px";
    checkboxDiv.style.background = "var(--dark-secondary)";
    checkboxDiv.style.borderRadius = "6px";

    const checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    checkbox.className = "form-check-input service-checkbox";
    checkbox.value = service.id;
    checkbox.id = `service_${service.id}`;
    checkbox.style.width = "20px";
    checkbox.style.height = "20px";
    checkbox.style.cursor = "pointer";

    const label = document.createElement("label");
    label.className = "form-check-label";
    label.htmlFor = `service_${service.id}`;
    label.style.color = "var(--text-light)";
    label.style.cursor = "pointer";
    label.style.marginLeft = "8px";
    label.innerHTML = `<strong>${service.service_name.toUpperCase()}</strong> ${service.labor_price ? `<span class="text-muted">(Labor: ₱${service.labor_price})</span>` : ''}`;

    checkboxDiv.appendChild(checkbox);
    checkboxDiv.appendChild(label);
    container.appendChild(checkboxDiv);
  });

  console.log("✅ Total checkboxes created:", container.querySelectorAll('.service-checkbox').length);

  // Add validation message container
  const validationMsg = document.createElement("div");
  validationMsg.id = "serviceValidationMsg";
  validationMsg.className = "text-danger mt-2";
  validationMsg.style.display = "none";
  validationMsg.style.padding = "8px 12px";
  validationMsg.style.borderRadius = "6px";
  validationMsg.style.background = "rgba(239, 68, 68, 0.1)";
  validationMsg.textContent = "Please select at least 1 service (maximum 5)";
  container.appendChild(validationMsg);

  // Add event listeners to checkboxes to clear validation message when user selects
  document.querySelectorAll('.service-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const selected = document.querySelectorAll('.service-checkbox:checked').length;
      const msg = document.getElementById('serviceValidationMsg');
      console.log("✓ Services selected:", selected);
      if (selected >= 1 && selected <= 5) {
        msg.style.display = 'none';
      }
    });
  });

  console.log("✅ Modal opening with checkboxes...");
  modal.show();
}

// 🏷️ Get badge style per booking status
function getStatusBadge(status) {
  const statusMap = {
    pending: { class: "badge-pending", text: "ON QUEUE" },
    progress: { class: "badge-progress", text: "IN PROGRESS" },
    completed: { class: "badge-completed", text: "COMPLETED" },
    "not accepted": { class: "badge-pending", text: "ON QUEUE" },
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
          <td>${booking.transaction_number || booking.id}</td>
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
            }" class="btn btn-sm btn-outline-light" onclick='editBooking(${JSON.stringify(booking)})'>
              <i class="fa-regular fa-pen-to-square"></i>
            </button>
          </td>
        </tr>`
    )
    .join("");
}

// ✏️ Edit booking info
async function editBooking(bookingData) {
  const bookingModal = document.getElementById("bookingModal");
  const modal = new bootstrap.Modal(bookingModal);
  
  // If only ID passed (string), fetch full booking data
  let j;
  if (typeof bookingData === 'string' || typeof bookingData === 'number') {
    const res = await fetch(`../../helper/bookingStatus.php?buid=${bookingData}`);
    j = await res.json();
  } else {
    // Full booking object passed from table
    j = bookingData;
  }

  await bookService(j.shop_name || j.shopName); // Wait for shop services to load

  // Populate form fields
  document.getElementById("vehicle_name").value = j.vehicle_name || j.vehicle;
  document.getElementById("vehicle_model").value = j.vehicle_model;
  document.getElementById("preferred_date").value = j.preferred_time || j.dateTime;
  document.getElementById("time").value = j.time;
  document.getElementById("notes").value = j.notes;
  document.getElementById("selectedShop").textContent = j.shop_name || j.shopName;

  // Check the appropriate service checkboxes
  // Parse service_ids (comma-separated string) or fallback to service_id
  let serviceIds = [];
  if (j.service_ids) {
    serviceIds = j.service_ids.split(',').map(id => parseInt(id.trim()));
  } else if (j.service_id) {
    serviceIds = [parseInt(j.service_id)];
  }

  console.log("Editing booking - Service IDs to check:", serviceIds);

  // Check the checkboxes that match the service IDs
  document.querySelectorAll('.service-checkbox').forEach(checkbox => {
    const checkboxValue = parseInt(checkbox.value);
    if (serviceIds.includes(checkboxValue)) {
      checkbox.checked = true;
      console.log("✅ Checked service:", checkboxValue);
    } else {
      checkbox.checked = false;
    }
  });

  // Store booking ID for update
  const bookingId = j.booking_id || j.id;

  // Attach save handler for update
  const saveBtn = bookingModal.querySelector(".btn-book");
  saveBtn.onclick = async function () {
    // Get selected service IDs from checkboxes
    const selectedServices = Array.from(document.querySelectorAll('.service-checkbox:checked')).map(cb => parseInt(cb.value));

    // Validate service selection (1-5 services)
    if (selectedServices.length === 0) {
      const validationMsg = document.getElementById("serviceValidationMsg");
      validationMsg.textContent = "Please select at least 1 service";
      validationMsg.style.display = "block";
      return;
    }

    if (selectedServices.length > 5) {
      const validationMsg = document.getElementById("serviceValidationMsg");
      validationMsg.textContent = "You can select a maximum of 5 services";
      validationMsg.style.display = "block";
      return;
    }

    // Hide validation message if validation passes
    document.getElementById("serviceValidationMsg").style.display = "none";

    // Collect updated data
    const updatedData = {
      id: bookingId,
      vehicle_name: document.getElementById("vehicle_name").value,
      vehicle_model: document.getElementById("vehicle_model").value,
      preferred_time: document.getElementById("preferred_date").value,
      time: document.getElementById("time").value,
      service_ids: selectedServices, // Array of service IDs
      notes: document.getElementById("notes").value,
    };

    console.log("✓ Updating booking with data:", updatedData);

    // Send update to backend
    const resp = await fetch("../../helper/updateBooking.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(updatedData),
    });
    const result = await resp.json();
    
    console.log("Update result:", result);
    
    if (result.status === "success") {
      alert("Booking updated successfully!");
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
