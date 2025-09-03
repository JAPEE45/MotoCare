document
  .getElementById("findLocationBtn")
  .addEventListener("click", function () {
    const button = this;
    const loadingOverlay = document.getElementById("loadingOverlay");

    if (!navigator.geolocation) {
      showLocationError("Geolocation is not supported by this browser.");
      return;
    }

    loadingOverlay.style.display = "flex";

    button.classList.add("btn-loading");
    button.innerHTML = `
        <div class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        Locating...
    `;

    navigator.geolocation.getCurrentPosition(
      function (position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        const accuracy = position.coords.accuracy;

        console.log(
          `Location found: ${latitude}, ${longitude} (accuracy: ${accuracy}m)`
        );

        loadingOverlay.style.display = "none";

        button.classList.remove("btn-loading");
        button.innerHTML = '<i class="fas fa-map-marker-alt"></i>Find Location';

        window.location.href = "./map.html";
      },
      function (error) {
        loadingOverlay.style.display = "none";

        button.classList.remove("btn-loading");
        button.innerHTML = '<i class="fas fa-map-marker-alt"></i>Find Location';

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

function showLocationError(errorMessage) {
  const notification = document.createElement("div");
  notification.className = "alert alert-danger position-fixed";
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
        <div class="d-flex align-items-start">
            <i class="fas fa-exclamation-triangle me-2 text-danger mt-1"></i>
            <div>
                <strong>Location Error</strong><br>
                <small>${errorMessage}</small>
            </div>
            <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;

  document.body.appendChild(notification);

  setTimeout(() => {
    if (document.body.contains(notification)) {
      notification.style.animation = "slideOutRight 0.5s ease";
      setTimeout(() => {
        if (document.body.contains(notification)) {
          document.body.removeChild(notification);
        }
      }, 500);
    }
  }, 8000);
}

window.addEventListener("scroll", function () {
  const navbar = document.querySelector(".navbar");
  if (window.scrollY > 50) {
    navbar.style.background = "rgba(15, 15, 15, 0.98)";
    navbar.style.boxShadow = "0 2px 20px rgba(0, 0, 0, 0.5)";
  } else {
    navbar.style.background = "rgba(15, 15, 15, 0.95)";
    navbar.style.boxShadow = "none";
  }
});

const style = document.createElement("style");
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

var tooltipTriggerList = [].slice.call(
  document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});
