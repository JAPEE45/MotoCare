let map;
let shp = []
let current_services = []
let userLocation = null; // Store user's current location
let currentRoute = null; // Store current route polyline

function initMap() {
  map = L.map("map").setView([13.586, 124.2374], 14);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors",
  }).addTo(map);

  async function getShopAndService() {
    const res = await fetch("../../helper/getShopAndServices.php");
    const json = await res.json();
    console.log(json);
    shp = json;

    json.forEach((shop) => {
      const customIcon = L.divIcon({
        html: `<div style="
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;">
            <i class="${shop.icon}" style="color: white; font-size: 16px; transform: rotate(45deg);"></i>
          </div>`,
        className: "custom-marker",
        iconSize: [40, 40],
        iconAnchor: [20, 35],
      });

      const popupContent = `
        <div class="popup-content">
          <h5><i class="${shop.icon} me-2"></i>${shop.name}</h5>
          <p><i class="fas fa-map-marker-alt me-2"></i>${shop.address}</p>
          <p><i class="fas fa-phone me-2"></i>${shop.phone}</p>
          <div class="services-list mb-2">
            ${shop.services.map(service => `<span class="service-tag">${service.service_name}</span>`).join("")}
          </div>
          <p><small><i class="fas fa-star text-warning"></i> ${shop.rating}/10 rating • ${shop.hours} hours</small></p>
          <button class="btn btn-book w-100" onclick="bookService('${shop.name}')">
            <i class="fas fa-calendar-plus me-2"></i>Book Service
          </button>
        </div>
      `;

      L.marker(shop.coordinates, { icon: customIcon })
        .addTo(map)
        .bindPopup(popupContent, {
          maxWidth: 300,
          className: "custom-popup",
        });
    });
  }

  getShopAndService();

  // ✅ Add current location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        // Store user location for routing
        userLocation = { lat: lat, lng: lng };

        const currentLocationIcon = L.divIcon({
          html: `<div style="
              width: 30px;
              height: 30px;
              background: #cc4b2eff;
              border-radius: 50%;
              border: 3px solid white;
              box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            "></div>`,
          className: "current-location-marker",
          iconSize: [30, 30],
          iconAnchor: [15, 15],
        });

        const marker = L.marker([lat, lng], { icon: currentLocationIcon })
          .addTo(map)
          .bindPopup("<b style='color:white'>You are here</b>");

        // Optionally center map on user
        map.setView([lat, lng], 15);
      },
      (err) => {
        console.error("Geolocation error:", err);
        alert("Unable to retrieve your location.");
      }
    );
  } else {
    alert("Geolocation is not supported by this browser.");
  }
}

async function bookService(shopName) {
  document.getElementById("selectedShop").textContent = shopName;
  const modal = new bootstrap.Modal(document.getElementById("bookingModal"));
  const serv = shp.filter(e=>e.name == shopName)

  // const userId = document.getElementById("userId").textContent
  const res = await fetch(`../../helper/checkBookService.php?ddd=${serv[0].shop_id}`)
  const j = await res.json()
  console.log(serv)
  console.log(j)

  if(j.success){
    window.location.href = `./booking-status.php?buid=${j.success.id}`
  }
  const sel = document.getElementById("services")
  sel.innerHTML = "<option value='' disabled selected>Select Service</option>";
  console.log(typeof serv)
  document.getElementById("shop_id").value = serv[0].shop_id
  serv[0].services.forEach(e=>{
    const node = document.createElement("option")
    node.value = e.id
    node.textContent = e.service_name.toUpperCase()
    sel.appendChild(node)
  })
  modal.show();
}


async function addBooking(){
    const res = await fetch("../../helper/addBooking.php",{
      method:"POST",
      headers:{
        'Content-Type': 'application/json'
      },
      body:JSON.stringify({
        user_id: document.getElementById("id").value,
        shop: document.getElementById("selectedShop").textContent,
        preferred_date: document.getElementById("preferred_date").value,
        time: document.getElementById("time").value,
        shop_id : document.getElementById("shop_id").value,
        service_id : document.getElementById("services").value,
        vehicle_name : document.getElementById("vehicle_name").value,
        notes : document.getElementById("notes").value,
        vehicle_model : document.getElementById("vehicle_model").value,
        vehicle_plate_number : document.getElementById("vehicle_plate_number").value,
      })
    })
    const d = await res.json()
  
    if(d.error){
      console.log(d.message)
      return
    }
      window.location.href = "./booking-status.php";
}
function submitBooking() {
  addBooking()

}

document.addEventListener("DOMContentLoaded", function () {
  initMap();
});

document.addEventListener("DOMContentLoaded", function () {
  console.log("Map page loaded");
  if (!navigator.geolocation) {
    showLocationError("Geolocation is not supported by this browser.");
    return;
  }

  navigator.geolocation.getCurrentPosition(
    function (position) {
      const latitude = position.coords.latitude;
      const longitude = position.coords.longitude;
      const accuracy = position.coords.accuracy;

      console.log(
        `Location found: ${latitude}, ${longitude} (accuracy: ${accuracy}m)`
      );

      showLocationFound(latitude, longitude, accuracy);
    },
    function (error) {
      let errorMessage;
      switch (error.code) {
        case error.PERMISSION_DENIED:
          errorMessage =
            "Location access denied by user. Please enable location services and try again.";
          break;
        case error.POSITION_UNAVAILABLE:
          errorMessage =
            "Location information is unavailable. Please check your connection and try again.";
          break;
        case error.TIMEOUT:
          errorMessage = "Location request timed out. Please try again.";
          break;
        default:
          errorMessage =
            "An unknown error occurred while retrieving your location.";
          break;
      }

      showLocationError(errorMessage);
    },
    {
      timeout: 15000,
      maximumAge: 300000,
    }
  );
});

function showLocationFound(latitude, longitude, accuracy) {
  const notification = document.createElement("div");
  notification.className = "alert alert-success position-fixed";
  notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 10000;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        animation: slideInRight 0.5s ease;
        max-width: 400px;
    `;
  notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2 text-success"></i>
            <div>
                <strong>Location Found!</strong><br>
                <small>Lat: ${latitude.toFixed(6)}, Lng: ${longitude.toFixed(
    6
  )}</small><br>
                <small>Accuracy: ${Math.round(accuracy)}m</small>
            </div>
        </div>
    `;

  document.body.appendChild(notification);

  setTimeout(() => {
    notification.style.animation = "slideOutRight 0.5s ease";
    setTimeout(() => {
      if (document.body.contains(notification)) {
        document.body.removeChild(notification);
      }

      // console.log(latitude, longitude);
    }, 500);
  }, 4000);
}

// Function to draw route from current location to shop using real road routing
async function drawRouteToShop(shopCoordinates, shopName) {
  if (!userLocation) {
    alert("Unable to determine your current location. Please enable location services.");
    return;
  }

  // Remove existing route if any
  if (currentRoute) {
    map.removeLayer(currentRoute);
    if (currentRoute.distanceMarker) {
      map.removeLayer(currentRoute.distanceMarker);
    }
  }

  try {
    // Show loading indicator
    const loadingNotification = showLoadingNotification("Calculating route...");

    // Use OSRM API for real road-based routing
    const url = `https://router.project-osrm.org/route/v1/driving/${userLocation.lng},${userLocation.lat};${shopCoordinates[1]},${shopCoordinates[0]}?overview=full&geometries=geojson`;
    
    const response = await fetch(url);
    const data = await response.json();

    // Remove loading notification
    if (loadingNotification && document.body.contains(loadingNotification)) {
      document.body.removeChild(loadingNotification);
    }

    if (data.code !== 'Ok' || !data.routes || data.routes.length === 0) {
      throw new Error('Unable to find route');
    }

    const route = data.routes[0];
    const coordinates = route.geometry.coordinates;
    
    // Convert coordinates from [lng, lat] to [lat, lng] for Leaflet
    const latlngs = coordinates.map(coord => [coord[1], coord[0]]);

    // Get accurate distance in kilometers from OSRM
    const distance = route.distance / 1000; // Convert meters to kilometers
    const duration = route.duration / 60; // Convert seconds to minutes

    // Draw polyline following actual roads
    currentRoute = L.polyline(latlngs, {
      color: '#ff6b6b',
      weight: 5,
      opacity: 0.8,
      lineJoin: 'round',
      lineCap: 'round'
    }).addTo(map);

    // Add distance and duration marker at the midpoint of the route
    const midIndex = Math.floor(latlngs.length / 2);
    const midPoint = latlngs[midIndex];
    
    const distanceMarker = L.marker(midPoint, {
      icon: L.divIcon({
        className: 'distance-label',
        html: `<div style="
          background: #ff6b6b;
          color: white;
          padding: 8px 15px;
          border-radius: 20px;
          font-weight: bold;
          box-shadow: 0 3px 10px rgba(0,0,0,0.4);
          white-space: nowrap;
          font-size: 14px;
          border: 2px solid white;
        ">
          <i class="fas fa-route"></i> ${distance.toFixed(2)} km
          <span style="opacity: 0.8; margin-left: 8px;">
            <i class="fas fa-clock"></i> ${Math.round(duration)} min
          </span>
        </div>`,
        iconSize: [200, 40],
        iconAnchor: [100, 20]
      })
    }).addTo(map);

    // Store distance marker with route so we can remove it too
    currentRoute.distanceMarker = distanceMarker;

    // Fit map to show the entire route
    map.fitBounds(currentRoute.getBounds(), { padding: [50, 50] });

    // Show notification with accurate distance and duration
    showRouteNotification(shopName, distance, duration);

  } catch (error) {
    console.error('Routing error:', error);
    
    // Fallback to straight line if routing fails
    console.log('Falling back to straight-line distance');
    drawStraightLineRoute(shopCoordinates, shopName);
  }
}

// Fallback function for straight-line route (when API fails)
function drawStraightLineRoute(shopCoordinates, shopName) {
  // Remove existing route if any
  if (currentRoute) {
    map.removeLayer(currentRoute);
    if (currentRoute.distanceMarker) {
      map.removeLayer(currentRoute.distanceMarker);
    }
  }

  // Calculate straight-line distance using Haversine formula
  const distance = calculateHaversineDistance(
    userLocation.lat, 
    userLocation.lng, 
    shopCoordinates[0], 
    shopCoordinates[1]
  );

  // Draw polyline from user location to shop
  currentRoute = L.polyline(
    [[userLocation.lat, userLocation.lng], shopCoordinates],
    {
      color: '#ff6b6b',
      weight: 4,
      opacity: 0.7,
      dashArray: '10, 10',
      lineJoin: 'round'
    }
  ).addTo(map);

  // Add distance popup at the midpoint
  const midLat = (userLocation.lat + shopCoordinates[0]) / 2;
  const midLng = (userLocation.lng + shopCoordinates[1]) / 2;
  
  const distanceMarker = L.marker([midLat, midLng], {
    icon: L.divIcon({
      className: 'distance-label',
      html: `<div style="
        background: #ff6b6b;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        white-space: nowrap;
        font-size: 14px;
      ">📍 ~${distance.toFixed(2)} km</div>`,
      iconSize: [100, 30],
      iconAnchor: [50, 15]
    })
  }).addTo(map);

  // Store distance marker with route so we can remove it too
  currentRoute.distanceMarker = distanceMarker;

  // Fit map to show both points
  const bounds = L.latLngBounds([
    [userLocation.lat, userLocation.lng],
    shopCoordinates
  ]);
  map.fitBounds(bounds, { padding: [50, 50] });

  // Show notification
  showRouteNotification(shopName, distance, null, true);
}

// Haversine formula for straight-line distance calculation
function calculateHaversineDistance(lat1, lon1, lat2, lon2) {
  const R = 6371; // Radius of the Earth in kilometers
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = 
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  const distance = R * c;
  return distance;
}

// Function to show loading notification
function showLoadingNotification(message) {
  const notification = document.createElement("div");
  notification.className = "alert alert-info position-fixed";
  notification.id = "loadingNotification";
  notification.style.cssText = `
    top: 80px;
    right: 20px;
    z-index: 10000;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    max-width: 350px;
    background: #23272b;
    border: 2px solid #ff6b6b;
    color: white;
  `;
  notification.innerHTML = `
    <div class="d-flex align-items-center">
      <div class="spinner-border spinner-border-sm me-2" role="status" style="color: #ff6b6b;">
        <span class="visually-hidden">Loading...</span>
      </div>
      <div>
        <strong>${message}</strong>
      </div>
    </div>
  `;

  document.body.appendChild(notification);
  return notification;
}

// Function to show route notification
function showRouteNotification(shopName, distance, duration = null, isEstimate = false) {
  const notification = document.createElement("div");
  notification.className = "alert alert-success position-fixed";
  notification.style.cssText = `
    top: 80px;
    right: 20px;
    z-index: 10000;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    animation: slideInRight 0.5s ease;
    max-width: 350px;
    background: #23272b;
    border: 2px solid #ff6b6b;
    color: white;
  `;
  
  let durationText = '';
  if (duration !== null) {
    durationText = `<small><i class="fas fa-clock"></i> Estimated time: ${Math.round(duration)} min</small><br>`;
  }
  
  let distancePrefix = isEstimate ? '~' : '';
  let accuracyNote = isEstimate ? '<small style="opacity: 0.7;">(Straight-line estimate)</small>' : '<small style="opacity: 0.7;">(Road distance)</small>';
  
  notification.innerHTML = `
    <div class="d-flex align-items-start">
      <i class="fas fa-route me-2" style="color: #ff6b6b; font-size: 1.5rem; margin-top: 3px;"></i>
      <div style="flex: 1;">
        <strong>Route to ${shopName}</strong><br>
        <small><i class="fas fa-map-marked-alt"></i> Distance: ${distancePrefix}${distance.toFixed(2)} km</small><br>
        ${durationText}
        ${accuracyNote}
      </div>
    </div>
  `;

  document.body.appendChild(notification);

  setTimeout(() => {
    notification.style.animation = "slideOutRight 0.5s ease";
    setTimeout(() => {
      if (document.body.contains(notification)) {
        document.body.removeChild(notification);
      }
    }, 500);
  }, 5000);
}

// --- Shop Search Engine ---
document.addEventListener("DOMContentLoaded", function () {
  const shopSearch = document.getElementById("shopSearch");
  const shopSearchResults = document.getElementById("shopSearchResults");

  if (shopSearch) {
    shopSearch.addEventListener("input", function () {
      const query = shopSearch.value.trim().toLowerCase();
      shopSearchResults.innerHTML = "";
      if (!query) {
        shopSearchResults.style.display = "none";
        return;
      }
      const matches = shp.filter(shop => shop.name.toLowerCase().includes(query));
      if (matches.length === 0) {
        shopSearchResults.innerHTML = '<div class="list-group-item">No shops found.</div>';
        shopSearchResults.style.display = "block";
        return;
      }
      matches.forEach(shop => {
        const item = document.createElement("div");
        item.className = "list-group-item list-group-item-action";
        item.innerHTML = `<strong>${shop.name}</strong><br><small>${shop.address}</small>`;
        item.onclick = function () {
          // Draw route to the selected shop
          drawRouteToShop(shop.coordinates, shop.name);
          
          // Find marker and open popup
          map.eachLayer(layer => {
            if (layer.getLatLng && layer.getLatLng().lat === shop.coordinates[0] && layer.getLatLng().lng === shop.coordinates[1]) {
              if (layer.openPopup) layer.openPopup();
            }
          });
          shopSearch.value = shop.name;
          shopSearchResults.style.display = "none";
        };
        shopSearchResults.appendChild(item);
      });
      shopSearchResults.style.display = "block";
    });
    // Hide results when clicking outside
    document.addEventListener("click", function (e) {
      if (!shopSearch.contains(e.target) && !shopSearchResults.contains(e.target)) {
        shopSearchResults.style.display = "none";
      }
    });
  }
});
