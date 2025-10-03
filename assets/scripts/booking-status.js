// Sample booking data - Set to null to show default state
let bookingData;
async function getBookingUpdate(){
  const res = await fetch("../../helper/bookingStatus.php")
  const json = await res.json()
  console.log(json)
  if(json.error){
  console.log("EWror")
    return
  }
 
  bookingData = {
  id: json.booking_id,
  serviceType: json.service_name,
  vehicle: json.vehicle_name || 'N/A',
  appointmentDate: json.preferred_time || 'N/A',
  timeSlot: "10:00 AM - 12:00 PM",
  status: json.status, // pending, progress, completed
  estimatedCompletion: "2 hours remaining",
};
 if(json.status == "cancelled" ){
        bookingData = null
  }
// document.getElementById("payment").textContent = bookingData.total_cost || 'N/A'
document.getElementById("vehicle_model").textContent = json.vehicle_model
document.getElementById("shop_name").textContent = json.shop_name
document.getElementById("shop_address").textContent = json.address
const cancelBtn = document.getElementById("cancelBtn")
if(json.status != "not accepted" && json.status != "pending"){
  cancelBtn.disabled = true
}
initializePage()
}



// Set to null to test default state
// bookingData = null;

function initializePage() {

  if (bookingData) {
    showBookingContent();
    updateBookingDisplay();
    startStatusSimulation();
  } else {
    showNoBookingContent();
  }
}

function showBookingContent() {
  document.getElementById("noBookingContent").style.display = "none";
  document.getElementById("bookingContent").style.display = "flex";
}

function showNoBookingContent() {
  document.getElementById("bookingContent").style.display = "none";
  document.getElementById("noBookingContent").style.display = "flex";
}

function updateBookingDisplay() {
  if (!bookingData) return;

  // Update booking details
  document.getElementById(
    "bookingId"
  ).innerHTML = `<i class="fas fa-ticket-alt me-2"></i>${bookingData.id}`;
  document.getElementById("serviceType").textContent = bookingData.serviceType;
  document.getElementById("vehicle").textContent = bookingData.vehicle;
  document.getElementById("appointmentDate").textContent =
    bookingData.appointmentDate;
  document.getElementById("timeSlot").textContent = bookingData.timeSlot;
  // document.getElementById("estimatedTimeText").textContent =
  //   bookingData.estimatedCompletion;

  // Update progress based on status
  updateProgressSteps(bookingData.status);
}

function updateProgressSteps(status) {
  // Reset all steps
  const steps = ["step1", "step2", "step3"];
  const labels = ["label1", "label2", "label3"];

  steps.forEach((stepId) => {
    const step = document.getElementById(stepId);
    step.classList.remove("active", "completed");
  });

  labels.forEach((labelId) => {
    const label = document.getElementById(labelId);
    label.classList.remove("active", "completed");
  });

  let progressWidth = 0;
  let statusText = "";
  let statusClass = "";

  const actionBtn = document.getElementById("actionBtn");
  status === "progress" ? actionBtn.style.display = "none" : actionBtn.style.display = "flex"
  

  switch (status) {
    case "pending":
      document.getElementById("step1").classList.add("active");
      document.getElementById("label1").classList.add("active");
      progressWidth = 0;
      statusText = "Pending";
      statusClass = "status-pending";
      break;

    case "progress":
      document.getElementById("step1").classList.add("completed");
      document.getElementById("label1").classList.add("completed");
      document.getElementById("step2").classList.add("active");
      document.getElementById("label2").classList.add("active");
      progressWidth = 50;
      statusText = "In Progress";
      statusClass = "status-progress";
      break;

    case "completed":
      document.getElementById("step1").classList.add("completed");
      document.getElementById("label1").classList.add("completed");
      document.getElementById("step2").classList.add("completed");
      document.getElementById("label2").classList.add("completed");
      document.getElementById("step3").classList.add("completed");
      document.getElementById("label3").classList.add("completed");
      progressWidth = 100;
      statusText = "Completed";
      statusClass = "status-completed";
      // document.getElementById("estimatedTimeText").textContent =
      //   "Service completed!";
      break;
  }

  // Update progress bar
  document.getElementById("progressFill").style.width = progressWidth + "%";

  // Update status badge
  const statusBadge = document.getElementById("currentStatus");
  statusBadge.textContent = statusText;
  statusBadge.className = `status-badge ${statusClass}`;
}

function startStatusSimulation() {
  // Simulate real-time status updates
  let currentStep = 0;
  const statuses = ["pending", "progress", "completed"];

  // Uncomment to enable automatic status progression

  setInterval(() => {
    if (currentStep < statuses.length - 1) {
      currentStep++;
    
      updateProgressSteps(bookingData.status);

      if (statuses[currentStep] === "progress") {
        bookingData.estimatedCompletion = "1 hour remaining";
        
      } else if (statuses[currentStep] === "completed") {
        bookingData.estimatedCompletion = "Service completed!";
      }

      // document.getElementById("estimatedTimeText").textContent =
      //   bookingData.estimatedCompletion;
    }
  }, 5000); // Change status every 5 seconds
}

async function cancelBooking() {
  if (confirm("Are you sure you want to cancel this booking?")) {
    // Simulate booking cancellation
    const res = await fetch(`../../helper/cancelBooking.php?bookingId=${bookingData.id}`)
    const j = await res.json()
    console.log(j)
    bookingData = null;

    showNoBookingContent();

    // Show success message
    setTimeout(() => {
      alert(
        "Booking cancelled successfully. You will receive a confirmation email shortly."
      );
    }, 500);
  }
}

function rescheduleBooking() {
  const modal = new bootstrap.Modal(document.getElementById("rescheduleModal"));

  // Show the modal
  modal.show();
}

// Manual status change functions for testing
function changeStatus(newStatus) {
  if (bookingData) {
    bookingData.status = newStatus;
    updateProgressSteps(newStatus);
    console.log(newStatus)

    // Update estimated time based on status
    switch (newStatus) {
      case "pending":
        bookingData.estimatedCompletion = "2 hours remaining";
        break;
      case "progress":
        bookingData.estimatedCompletion = "1 hour remaining";
        break;
      case "completed":
        bookingData.estimatedCompletion = "Service completed!";
        break;
    }

    // document.getElementById("estimatedTimeText").textContent =
    //   bookingData.estimatedCompletion;
  }
}

// Initialize page on load
document.addEventListener("DOMContentLoaded", 
getBookingUpdate);

// Add some interactivity - click on steps to change status (for demo purposes)
document.addEventListener("DOMContentLoaded", () => {
  // Only add event listeners if booking data exists and elements are present
  if (bookingData && document.getElementById("step1")) {
    const step1 = document.getElementById("step1");
    const step2 = document.getElementById("step2");   
    const step3 = document.getElementById("step3");

    // Add event listeners with null checks
    if (step1) {
      step1.addEventListener("click", () => changeStatus("pending"));
      step1.style.cursor = "pointer";
    }

    if (step2) {
      step2.addEventListener("click", () => changeStatus("progress"));
      step2.style.cursor = "pointer";
    }

    if (step3) {
      step3.addEventListener("click", () => changeStatus("completed"));
      step3.style.cursor = "pointer";
    }
  }
});
