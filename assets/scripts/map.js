let map;
let shp = []
let current_services = []

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
  console.log(j)
  if(j.success){
    window.location.href = './booking-status.php'
  }
  const sel = document.getElementById("services")
  sel.innerHTML = "<option value='' disabled selected>Select Time</option>";
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
