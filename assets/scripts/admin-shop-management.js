let shops = [];
let map, marker, mapEdit, markerEdit, mapView, markerView;
let selectedLat, selectedLng;
let editLat, editLng;
let currentEditShopId, currentEditOwnerId;

// Load shops on page load
document.addEventListener('DOMContentLoaded', function() {
  loadShops();
});

// Load shops from backend
async function loadShops() {
  try {
    console.log('Fetching shops...');
    // Add cache-busting timestamp to prevent browser caching
    const timestamp = new Date().getTime();
    const response = await fetch(`../../helper/adminGetShops.php?t=${timestamp}`, {
      method: 'GET',
      cache: 'no-store',
      headers: {
        'Cache-Control': 'no-cache',
        'Pragma': 'no-cache'
      }
    });
    console.log('Response status:', response.status);
    
    const text = await response.text();
    console.log('Raw response:', text);
    
    const data = JSON.parse(text);
    console.log('Parsed data:', data);
    
    if (data.status === 'success' && data.data) {
      shops = data.data;
      console.log('Shops loaded:', shops.length);
      renderShops();
    } else {
      console.error('Failed to load shops:', data);
      showNotification(data.message || 'Failed to load shops', 'error');
    }
  } catch (error) {
    console.error('Error loading shops:', error);
    showNotification('Error loading shops: ' + error.message, 'error');
  }
}

function renderShops() {
  console.log('renderShops called with', shops.length, 'shops');
  const tbody = document.getElementById('shopsTableBody');
  if (!tbody) {
    console.error('Table body element not found');
    return;
  }
  
  // Clear existing content completely
  while (tbody.firstChild) {
    tbody.removeChild(tbody.firstChild);
  }
  
  if (!shops || shops.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7" class="text-center">No shops found</td></tr>';
    return;
  }
  
  shops.forEach((shop, index) => {
    console.log(`Rendering shop ${index}:`, shop.shop_id, shop.shop_name);
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${shop.shop_name || ''}</td>
      <td>${shop.owner_name || 'N/A'}</td>
      <td>${shop.contact || 'N/A'}</td>
      <td>${shop.address || ''}</td>
      <td>
        <span class="badge bg-${shop.total_bookings > 0 ? 'success' : 'secondary'}">
          ${shop.total_bookings || 0} bookings
        </span>
      </td>
      <td>₱${parseFloat(shop.total_revenue || 0).toLocaleString()}</td>
      <td>
        <button class="btn btn-sm btn-primary" onclick="viewShop(${shop.shop_id})">
          <i class="fas fa-eye"></i> View
        </button>
        <button class="btn btn-sm btn-warning" onclick="editShop(${shop.shop_id})">
          <i class="fas fa-edit"></i> Edit
        </button>
        <button class="btn btn-sm btn-danger" onclick="deleteShop(${shop.shop_id})">
          <i class="fas fa-trash"></i> Delete
        </button>
      </td>
    `;
    tbody.appendChild(row);
  });
  console.log('renderShops complete');
}

function showNotification(message, type = 'success') {
  let alertClass = 'alert-success';
  if (type === 'error') alertClass = 'alert-danger';
  else if (type === 'warning') alertClass = 'alert-warning';
  
  const alertDiv = document.createElement('div');
  alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
  alertDiv.style.zIndex = '9999';
  alertDiv.style.maxWidth = '500px';
  alertDiv.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  document.body.appendChild(alertDiv);
  
  setTimeout(() => {
    alertDiv.remove();
  }, type === 'warning' ? 8000 : 3000);
}

// Initialize maps when modals are shown
document
  .getElementById("addShopModal")
  .addEventListener("shown.bs.modal", function () {
    initMap();
  });

document
  .getElementById("editShopModal")
  .addEventListener("shown.bs.modal", function () {
    initEditMap();
  });

document
  .getElementById("viewShopModal")
  .addEventListener("shown.bs.modal", function () {
    initViewMap();
  });

// Initialize Add Map
function initMap() {
  if (map) {
    map.remove();
  }
  map = L.map("map").setView([13.5884, 124.2372], 13);
  
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors",
  }).addTo(map);

  map.on("click", function (e) {
    selectedLat = e.latlng.lat;
    selectedLng = e.latlng.lng;

    if (marker) {
      map.removeLayer(marker);
    }

    marker = L.marker([selectedLat, selectedLng]).addTo(map);
    document.getElementById(
      "coordinates"
    ).textContent = `Latitude: ${selectedLat.toFixed(
      6
    )} | Longitude: ${selectedLng.toFixed(6)}`;
  });
}

// Initialize Edit Map
function initEditMap() {
  if (mapEdit) {
    mapEdit.remove();
  }
  mapEdit = L.map("mapEdit").setView(
    [editLat || 14.5995, editLng || 120.9842],
    13
  );
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors",
  }).addTo(mapEdit);

  if (editLat && editLng) {
    markerEdit = L.marker([editLat, editLng]).addTo(mapEdit);
  }

  mapEdit.on("click", function (e) {
    editLat = e.latlng.lat;
    editLng = e.latlng.lng;

    if (markerEdit) {
      mapEdit.removeLayer(markerEdit);
    }

    markerEdit = L.marker([editLat, editLng]).addTo(mapEdit);
    document.getElementById(
      "editCoordinates"
    ).textContent = `Latitude: ${editLat.toFixed(
      6
    )} | Longitude: ${editLng.toFixed(6)}`;
  });
}

// Initialize View Map
function initViewMap() {
  if (mapView) {
    mapView.remove();
  }
  const shopId = parseInt(document.getElementById("viewShopName").dataset.id);
  const shop = shops.find((s) => s.shop_id === shopId);
  if (shop) {
    const lat = parseFloat(shop.lat);
    const lng = parseFloat(shop.lg);
    mapView = L.map("mapView").setView([lat, lng], 13);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "© OpenStreetMap contributors",
    }).addTo(mapView);

    markerView = L.marker([lat, lng]).addTo(mapView);
  }
}

// Render table
function renderTable(data = shops) {
  const tbody = document.getElementById("shopTableBody");
  tbody.innerHTML = "";

  data.forEach((shop) => {
    const row = `
                    <tr>
                        <td>${shop.shopName}</td>
                        <td>${shop.owner}</td>
                        <td>${shop.contact}</td>
                        <td>${shop.address}</td>
                        <td><span class="badge ${
                          shop.status === "Active" ? "bg-success" : "bg-danger"
                        }">${shop.status}</span></td>
                        <td>
                            <button class="btn btn-view btn-action" onclick="viewShop(${
                              shop.id
                            })">View</button>
                            <button class="btn btn-edit btn-action" onclick="editShop(${
                              shop.id
                            })">Edit</button>
                            <button class="btn btn-delete btn-action" onclick="deleteShop(${
                              shop.id
                            })">Delete</button>
                        </td>
                    </tr>
                `;
    tbody.innerHTML += row;
  });
}

// Add shop
async function addShop() {
  const shopName = document.getElementById("shopName").value.trim();
  const ownerName = document.getElementById("ownerName").value.trim();
  const contact = document.getElementById("contactNumber").value.trim();
  const address = document.getElementById("address").value.trim();
  const email = document.getElementById("ownerEmail").value.trim();
  
  // Validate required fields
  if (!shopName || !ownerName || !contact || !address || !email) {
    showNotification('Please fill in all required fields', 'error');
    return;
  }
  
  // Validate email format
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    showNotification('Please enter a valid email address', 'error');
    return;
  }
  
  if (!selectedLat || !selectedLng) {
    showNotification('Please select a location on the map', 'error');
    return;
  }
  
  // Show loading state
  const addBtn = document.querySelector('#addShopModal .btn-primary');
  const originalText = addBtn.innerHTML;
  addBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding Shop...';
  addBtn.disabled = true;
  
  const formData = new FormData();
  formData.append('shop_name', shopName);
  formData.append('owner_name', ownerName);
  formData.append('contact', contact);
  formData.append('address', address);
  formData.append('lat', selectedLat);
  formData.append('lng', selectedLng);
  formData.append('email', email);
  
  try {
    const response = await fetch('../../helper/adminAddShop.php', {
      method: 'POST',
      body: formData
    });
    
    const text = await response.text();
    console.log('Add shop response:', text);
    
    const data = JSON.parse(text);
    
    // Reset button state
    addBtn.innerHTML = originalText;
    addBtn.disabled = false;
    
    if (data.status === 'success') {
      // Check if email was sent successfully
      if (data.email_sent) {
        showNotification('Shop added successfully! Login credentials have been sent to the owner\'s email.', 'success');
      } else {
        // Email failed - show the generated password
        const password = data.generated_password || 'Check server logs';
        showNotification(`Shop added but email failed to send. Generated password: ${password}`, 'warning');
        alert(`IMPORTANT: Email could not be sent!\\n\\nPlease provide these credentials to the owner manually:\\n\\nEmail: ${email}\\nPassword: ${password}`);
      }
      bootstrap.Modal.getInstance(document.getElementById("addShopModal")).hide();
      document.getElementById("addShopForm").reset();
      document.getElementById("coordinates").textContent = "Latitude: - | Longitude: -";
      selectedLat = null;
      selectedLng = null;
      loadShops();
    } else {
      showNotification(data.message || 'Failed to add shop', 'error');
    }
  } catch (error) {
    console.error('Error adding shop:', error);
    // Reset button state on error
    const addBtn = document.querySelector('#addShopModal .btn-primary');
    if (addBtn) {
      addBtn.innerHTML = 'Add Shop';
      addBtn.disabled = false;
    }
    showNotification('Error adding shop: ' + error.message, 'error');
  }
}

// View shop
function viewShop(id) {
  const shop = shops.find((s) => s.shop_id === id);
  if (shop) {
    document.getElementById("viewShopName").textContent = shop.shop_name;
    document.getElementById("viewShopName").dataset.id = shop.shop_id;
    document.getElementById("viewOwnerName").textContent = shop.owner_name || 'N/A';
    document.getElementById("viewContactNumber").textContent = shop.contact || 'N/A';
    document.getElementById("viewAddress").textContent = shop.address;
    document.getElementById("viewEmail").textContent = shop.email || 'N/A';
    document.getElementById("viewBookings").textContent = `Total: ${shop.total_bookings}, Completed: ${shop.completed_bookings}`;
    document.getElementById("viewRevenue").textContent = `₱${parseFloat(shop.total_revenue || 0).toLocaleString()}`;
    document.getElementById(
      "viewCoordinates"
    ).textContent = `Latitude: ${parseFloat(shop.lat).toFixed(
      6
    )} | Longitude: ${parseFloat(shop.lg).toFixed(6)}`;

    new bootstrap.Modal(document.getElementById("viewShopModal")).show();
  }
}

// Edit shop
function editShop(id) {
  const shop = shops.find((s) => s.shop_id === id);
  if (shop) {
    currentEditShopId = shop.shop_id;
    currentEditOwnerId = shop.owner_id;
    document.getElementById("editShopName").value = shop.shop_name;
    document.getElementById("editOwnerName").value = shop.owner_name || '';
    document.getElementById("editContactNumber").value = shop.contact || '';
    document.getElementById("editAddress").value = shop.address;
    editLat = parseFloat(shop.lat);
    editLng = parseFloat(shop.lg);
    document.getElementById(
      "editCoordinates"
    ).textContent = `Latitude: ${editLat.toFixed(
      6
    )} | Longitude: ${editLng.toFixed(6)}`;

    new bootstrap.Modal(document.getElementById("editShopModal")).show();
  }
}

// Update shop
async function updateShop() {
  console.log('Update shop called');
  console.log('Current edit shop ID:', currentEditShopId);
  console.log('Current edit owner ID:', currentEditOwnerId);
  
  const shopName = document.getElementById("editShopName").value;
  const ownerName = document.getElementById("editOwnerName").value;
  const contact = document.getElementById("editContactNumber").value;
  const address = document.getElementById("editAddress").value;
  
  console.log('Form values:', { shopName, ownerName, contact, address, editLat, editLng });
  
  // Validate required fields
  if (!shopName || !address) {
    showNotification('Please fill in all required fields', 'error');
    return;
  }
  
  // Validate coordinates
  if (!editLat || !editLng) {
    showNotification('Please select a location on the map', 'error');
    return;
  }
  
  // Validate IDs
  if (!currentEditShopId) {
    showNotification('Invalid shop ID', 'error');
    return;
  }
  
  const formData = new FormData();
  formData.append('shop_id', currentEditShopId);
  formData.append('owner_id', currentEditOwnerId || '');
  formData.append('shop_name', shopName);
  formData.append('owner_name', ownerName || '');
  formData.append('contact', contact || '');
  formData.append('address', address);
  formData.append('lat', editLat);
  formData.append('lng', editLng);
  
  console.log('Sending update request...');
  console.log('FormData contents:');
  for (let [key, value] of formData.entries()) {
    console.log(`  ${key}: ${value}`);
  }
  
  try {
    const response = await fetch('../../helper/adminUpdateShop2.php', {
      method: 'POST',
      body: formData
    });
    
    console.log('Response status:', response.status);
    const text = await response.text();
    console.log('Update response RAW:', text);
    
    let data;
    try {
      data = JSON.parse(text);
    } catch (parseError) {
      console.error('JSON Parse Error:', parseError);
      console.error('Raw text was:', text);
      showNotification('Server returned invalid response', 'error');
      return;
    }
    
    console.log('Parsed data:', data);
    
    if (data.debug) {
      console.log('DEBUG INFO:');
      console.log('  Old name:', data.debug.old_name);
      console.log('  New name:', data.debug.new_name);
      console.log('  Name changed:', data.debug.name_changed);
      console.log('  Affected rows:', data.debug.affected_rows);
    }
    
    if (data.status === 'success') {
      showNotification('Shop updated successfully', 'success');
      const modalElement = document.getElementById("editShopModal");
      const modalInstance = bootstrap.Modal.getInstance(modalElement);
      if (modalInstance) {
        modalInstance.hide();
      }
      // Force reload shops
      console.log('Reloading shops...');
      await loadShops();
      console.log('Shops reloaded');
    } else {
      console.error('Update failed:', data.message);
      showNotification(data.message || 'Failed to update shop', 'error');
    }
  } catch (error) {
    console.error('Error updating shop:', error);
    showNotification('Error updating shop: ' + error.message, 'error');
  }
}

// Delete shop
let deleteShopId;

function deleteShop(id) {
  const shop = shops.find((s) => s.shop_id === id);
  if (shop) {
    deleteShopId = shop.shop_id;
    document.getElementById("deleteShopName").textContent = shop.shop_name;
    new bootstrap.Modal(document.getElementById("deleteShopModal")).show();
  }
}

// Confirm delete
async function confirmDelete() {
  const formData = new FormData();
  formData.append('shop_id', deleteShopId);
  
  try {
    const response = await fetch('../../helper/adminDeleteShop.php', {
      method: 'POST',
      body: formData
    });
    
    const text = await response.text();
    console.log('Delete response:', text);
    
    const data = JSON.parse(text);
    
    if (data.status === 'success') {
      showNotification('Shop deleted successfully', 'success');
      bootstrap.Modal.getInstance(document.getElementById("deleteShopModal")).hide();
      loadShops();
    } else {
      showNotification(data.message || 'Failed to delete shop', 'error');
    }
  } catch (error) {
    console.error('Error deleting shop:', error);
    showNotification('Error deleting shop: ' + error.message, 'error');
  }
}

// Search functionality
document.getElementById("searchInput").addEventListener("input", function (e) {
  const searchTerm = e.target.value.toLowerCase();
  const filtered = shops.filter(
    (shop) =>
      shop.shop_name.toLowerCase().includes(searchTerm) ||
      (shop.owner_name && shop.owner_name.toLowerCase().includes(searchTerm)) ||
      shop.address.toLowerCase().includes(searchTerm)
  );
  renderShopsFiltered(filtered);
});

function renderShopsFiltered(filteredShops) {
  const tbody = document.getElementById('shopsTableBody');
  tbody.innerHTML = '';
  
  filteredShops.forEach(shop => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${shop.shop_name}</td>
      <td>${shop.owner_name || 'N/A'}</td>
      <td>${shop.contact || 'N/A'}</td>
      <td>${shop.address}</td>
      <td>
        <span class="badge bg-${shop.total_bookings > 0 ? 'success' : 'secondary'}">
          ${shop.total_bookings} bookings
        </span>
      </td>
      <td>₱${parseFloat(shop.total_revenue || 0).toLocaleString()}</td>
      <td>
        <button class="btn btn-sm btn-primary" onclick="viewShop(${shop.shop_id})">
          <i class="fas fa-eye"></i> View
        </button>
        <button class="btn btn-sm btn-warning" onclick="editShop(${shop.shop_id})">
          <i class="fas fa-edit"></i> Edit
        </button>
        <button class="btn btn-sm btn-danger" onclick="deleteShop(${shop.shop_id})">
          <i class="fas fa-trash"></i> Delete
        </button>
      </td>
    `;
    tbody.appendChild(row);
  });
}
