let shops = [
  {
    id: 1,
    shopName: "Quick Fix Auto",
    owner: "John Smith",
    contact: "+1 234-567-8900",
    address: "Virac Town Center, Virac, Catanduanes",
    status: "Active",
    lat: 13.5884,   // Virac
    lng: 124.2372,
  },
  {
    id: 2,
    shopName: "Speedy Repairs",
    owner: "Maria Garcia",
    contact: "+1 234-567-8901",
    address: "Bato Public Market, Bato, Catanduanes",
    status: "Active",
    lat: 13.6122,   // Bato
    lng: 124.2287,
  },
  {
    id: 3,
    shopName: "Elite Auto Service",
    owner: "Robert Johnson",
    contact: "+1 234-567-8902",
    address: "Baras Town Proper, Baras, Catanduanes",
    status: "Inactive",
    lat: 13.6510,   // Baras
    lng: 124.3315,
  },
];

let map, marker, mapEdit, markerEdit, mapView, markerView;
let selectedLat, selectedLng;
let editLat, editLng;

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
  const shop = shops.find(
    (s) => s.id === parseInt(document.getElementById("viewShopName").dataset.id)
  );
  if (shop) {
    mapView = L.map("mapView").setView([shop.lat, shop.lng], 13);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "© OpenStreetMap contributors",
    }).addTo(mapView);

    markerView = L.marker([shop.lat, shop.lng]).addTo(mapView);
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
function addShop() {
  const newShop = {
    id: shops.length > 0 ? Math.max(...shops.map((s) => s.id)) + 1 : 1,
    shopName: document.getElementById("shopName").value,
    owner: document.getElementById("ownerName").value,
    contact: document.getElementById("contactNumber").value,
    address: document.getElementById("address").value,
    status: document.getElementById("status").value,
    lat: selectedLat || 14.5995,
    lng: selectedLng || 120.9842,
  };

  shops.push(newShop);
  renderTable();
  bootstrap.Modal.getInstance(document.getElementById("addShopModal")).hide();
  document.getElementById("addShopForm").reset();
  document.getElementById("coordinates").textContent =
    "Latitude: - | Longitude: -";
  selectedLat = null;
  selectedLng = null;
}

// View shop
function viewShop(id) {
  const shop = shops.find((s) => s.id === id);
  if (shop) {
    document.getElementById("viewShopName").textContent = shop.shopName;
    document.getElementById("viewShopName").dataset.id = shop.id;
    document.getElementById("viewOwnerName").textContent = shop.owner;
    document.getElementById("viewContactNumber").textContent = shop.contact;
    document.getElementById("viewAddress").textContent = shop.address;
    document.getElementById("viewStatus").textContent = shop.status;
    document.getElementById(
      "viewCoordinates"
    ).textContent = `Latitude: ${shop.lat.toFixed(
      6
    )} | Longitude: ${shop.lng.toFixed(6)}`;

    new bootstrap.Modal(document.getElementById("viewShopModal")).show();
  }
}

// Edit shop
function editShop(id) {
  const shop = shops.find((s) => s.id === id);
  if (shop) {
    document.getElementById("editShopId").value = shop.id;
    document.getElementById("editShopName").value = shop.shopName;
    document.getElementById("editOwnerName").value = shop.owner;
    document.getElementById("editContactNumber").value = shop.contact;
    document.getElementById("editAddress").value = shop.address;
    document.getElementById("editStatus").value = shop.status;
    editLat = shop.lat;
    editLng = shop.lng;
    document.getElementById(
      "editCoordinates"
    ).textContent = `Latitude: ${editLat.toFixed(
      6
    )} | Longitude: ${editLng.toFixed(6)}`;

    new bootstrap.Modal(document.getElementById("editShopModal")).show();
  }
}

// Update shop
function updateShop() {
  const id = parseInt(document.getElementById("editShopId").value);
  const shop = shops.find((s) => s.id === id);

  if (shop) {
    shop.shopName = document.getElementById("editShopName").value;
    shop.owner = document.getElementById("editOwnerName").value;
    shop.contact = document.getElementById("editContactNumber").value;
    shop.address = document.getElementById("editAddress").value;
    shop.status = document.getElementById("editStatus").value;
    shop.lat = editLat;
    shop.lng = editLng;

    renderTable();
    bootstrap.Modal.getInstance(
      document.getElementById("editShopModal")
    ).hide();
  }
}

// Delete shop
function deleteShop(id) {
  const shop = shops.find((s) => s.id === id);
  if (shop) {
    document.getElementById("deleteShopId").value = shop.id;
    document.getElementById("deleteShopName").textContent = shop.shopName;
    new bootstrap.Modal(document.getElementById("deleteShopModal")).show();
  }
}

// Confirm delete
function confirmDelete() {
  const id = parseInt(document.getElementById("deleteShopId").value);
  shops = shops.filter((s) => s.id !== id);
  renderTable();
  bootstrap.Modal.getInstance(
    document.getElementById("deleteShopModal")
  ).hide();
}

// Search functionality
document.getElementById("searchInput").addEventListener("input", function (e) {
  const searchTerm = e.target.value.toLowerCase();
  const filtered = shops.filter(
    (shop) =>
      shop.shopName.toLowerCase().includes(searchTerm) ||
      shop.owner.toLowerCase().includes(searchTerm) ||
      shop.address.toLowerCase().includes(searchTerm)
  );
  renderTable(filtered);
});

// Initial render
renderTable();
