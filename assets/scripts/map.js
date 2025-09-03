let map;

function initMap() {
  map = L.map("map").setView([13.586, 124.2374], 14);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors",
  }).addTo(map);

  const shops = [
    {
      name: "Molje Lube",
      icon: "fas fa-wrench",
      address: "San Roque St, Virac, Catanduanes",
      phone: "(052) 811-1234",
      coordinates: [13.5875, 124.237],
      services: ["Oil Change", "Brake Service", "Tire Rotation", "Quick Lube"],
      rating: "4.8/5 (124 reviews)",
      hours: "Open until 7 PM",
    },
    {
      name: "CT Gear",
      icon: "fas fa-cogs",
      address: "Concepcion, Virac, Catanduanes",
      phone: "(052) 811-5678",
      coordinates: [13.5852, 124.2395],
      services: [
        "Transmission",
        "Engine Repair",
        "Diagnostics",
        "Performance Tuning",
      ],
      rating: "4.9/5 (98 reviews)",
      hours: "Open 24/7",
    },
    {
      name: "Precision Tech Moto",
      icon: "fas fa-motorcycle",
      address: "Rawis, Virac, Catanduanes",
      phone: "(052) 811-4321",
      coordinates: [13.589, 124.235],
      services: [
        "Motorcycle Service",
        "Electronic Diagnostics",
        "Custom Parts",
        "Performance Mods",
      ],
      rating: "4.7/5 (156 reviews)",
      hours: "Open until 9 PM",
    },
  ];

  shops.forEach((shop) => {
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
                        cursor: pointer;
                    ">
                        <i class="${shop.icon}" style="
                            color: white; 
                            font-size: 16px; 
                            transform: rotate(45deg);
                        "></i>
                    </div>`,
      className: "custom-marker",
      iconSize: [40, 40],
      iconAnchor: [20, 35],
    });

    const popupContent = `
                    <div class="popup-content">
                        <h5><i class="${shop.icon} me-2"></i>${shop.name}</h5>
                        <p><i class="fas fa-map-marker-alt me-2"></i>${
                          shop.address
                        }</p>
                        <p><i class="fas fa-phone me-2"></i>${shop.phone}</p>
                        <div class="services-list mb-2">
                            ${shop.services
                              .map(
                                (service) =>
                                  `<span class="service-tag">${service}</span>`
                              )
                              .join("")}
                        </div>
                        <p><small><i class="fas fa-star text-warning"></i> ${
                          shop.rating
                        } • ${shop.hours}</small></p>
                        <button class="btn btn-book w-100" onclick="bookService('${
                          shop.name
                        }')">
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

function bookService(shopName) {
  document.getElementById("selectedShop").textContent = shopName;
  const modal = new bootstrap.Modal(document.getElementById("bookingModal"));
  modal.show();
}

function submitBooking() {
  window.location.href = "./booking-status.html";
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
      enableHighAccuracy: true,
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
