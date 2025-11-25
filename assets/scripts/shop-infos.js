// Counter Animation
const targetBookings = 1247; // Total number of bookings
const counterElement = document.getElementById("bookingCounter");
let currentCount = 0;
const duration = 2000; // 2 seconds
const increment = Math.ceil(targetBookings / (duration / 16)); // 60fps

function animateCounter() {
  if (currentCount < targetBookings) {
    currentCount += increment;
    if (currentCount > targetBookings) {
      currentCount = targetBookings;
    }
    counterElement.textContent = currentCount.toLocaleString();
    requestAnimationFrame(animateCounter);
  }
}

// Start counter animation when page loads
window.addEventListener("load", () => {
  setTimeout(animateCounter, 300);
});

// Book Now Handler
function handleBooking() {
  
}

// Add subtle parallax effect on mouse move
document.addEventListener("mousemove", (e) => {
  const leftSection = document.querySelector(".left-section::before");
  const mouseX = e.clientX / window.innerWidth;
  const mouseY = e.clientY / window.innerHeight;

  document
    .querySelector(".left-section")
    .style.setProperty("--mouse-x", mouseX);
  document
    .querySelector(".left-section")
    .style.setProperty("--mouse-y", mouseY);
});
