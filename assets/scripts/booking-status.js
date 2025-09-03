let currentStep = 2;
let timeRemaining = 45;
let bookingStatus = "progress";

function simulateProgress() {
  const interval = setInterval(() => {
    timeRemaining -= 1;

    if (timeRemaining <= 0) {
      if (currentStep < 4) {
        currentStep++;
        updateProgress();

        if (currentStep === 3) {
          timeRemaining = 15;
        } else if (currentStep === 4) {
          timeRemaining = 0;
          bookingStatus = "completed";
          clearInterval(interval);
        }
      }
    } else {
      updateTimeDisplay();
    }
  }, 60000);

  const demoInterval = setInterval(() => {
    timeRemaining -= 1;
    updateTimeDisplay();

    if (timeRemaining <= 0) {
      if (currentStep < 4) {
        currentStep++;
        updateProgress();

        if (currentStep === 3) {
          timeRemaining = 15;
        } else if (currentStep === 4) {
          timeRemaining = 0;
          bookingStatus = "completed";
          clearInterval(demoInterval);
          showCompletionMessage();
        }
      }
    }
  }, 3000);
}

function updateProgress() {
  for (let i = 1; i <= 4; i++) {
    const step = document.getElementById(`step${i}`);
    step.classList.remove("active", "completed");

    if (i < currentStep) {
      step.classList.add("completed");
      step.querySelector(".timeline-icon i").className = "fas fa-check";
    } else if (i === currentStep) {
      step.classList.add("active");
    }
  }

  const statusBadge = document.getElementById("currentStatus");
  if (currentStep === 4) {
    statusBadge.textContent = "Completed";
    statusBadge.className = "status-badge status-completed";

    document.getElementById("cancelBtn").style.display = "none";
    document.getElementById("rescheduleBtn").style.display = "none";
  } else if (currentStep === 3) {
    statusBadge.textContent = "Quality Check";
    statusBadge.className = "status-badge status-progress";
  }
}

function updateTimeDisplay() {
  const timeDisplay = document.getElementById("timeRemaining");
  if (timeRemaining > 0) {
    timeDisplay.textContent = `${timeRemaining} minutes`;
  } else if (bookingStatus === "completed") {
    timeDisplay.textContent = "Completed!";
    timeDisplay.style.color = "#22c55e";
  }
}

function showCompletionMessage() {
  const notification = document.createElement("div");
  notification.className = "alert alert-success";
  notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                background: rgba(34, 197, 94, 0.2);
                border: 1px solid #22c55e;
                color: #22c55e;
                padding: 1rem;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            `;
  notification.innerHTML = `
                <strong><i class="fas fa-check-circle me-2"></i>Service Completed!</strong><br>
                Your motorcycle is ready for pickup.
            `;
  document.body.appendChild(notification);

  setTimeout(() => {
    notification.remove();
  }, 5000);
}

function contactShop() {
  alert("Calling Precision Tech Moto at (052) 811-4321...");
}

function cancelBooking() {
  if (currentStep >= 4) {
    alert("Cannot cancel a completed booking.");
    return;
  }
  const modal = new bootstrap.Modal(document.getElementById("cancelModal"));
  modal.show();
}

function confirmCancel() {
  alert("Booking cancelled successfully. A confirmation email has been sent.");
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("cancelModal")
  );
  modal.hide();

  document.getElementById("currentStatus").textContent = "Cancelled";
  document.getElementById("currentStatus").className =
    "status-badge status-pending";
  document.getElementById("cancelBtn").style.display = "none";
  document.getElementById("rescheduleBtn").style.display = "none";
}

function rescheduleBooking() {
  if (currentStep >= 3) {
    alert("Cannot reschedule - service is in final stages.");
    return;
  }
  const modal = new bootstrap.Modal(document.getElementById("rescheduleModal"));
  modal.show();
}

function confirmReschedule() {
  alert(
    "Booking rescheduled successfully. A confirmation email has been sent."
  );
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("rescheduleModal")
  );
  modal.hide();
}

document.addEventListener("DOMContentLoaded", function () {
  updateProgress();
  updateTimeDisplay();

  setTimeout(() => {
    simulateProgress();
  }, 2000);
});
