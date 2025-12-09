// Services data storage (in-memory for demo)
let services = [
  {
    id: 1,
    name: "Oil Change",
    description:
      "Complete engine oil change with premium oil and filter replacement",
    minPrice: 1200,
    maxPrice: 2000,
    icon: "fas fa-oil-can",
  },
  {
    id: 2,
    name: "Brake Service",
    description:
      "Comprehensive brake inspection, pad replacement, and brake fluid service",
    minPrice: 2500,
    maxPrice: 5000,
    icon: "fas fa-wrench",
  },
  {
    id: 3,
    name: "Tire Rotation",
    description:
      "Professional tire rotation and pressure check for optimal performance",
    minPrice: 800,
    maxPrice: 2000,
    icon: "fas fa-dharmachakra",
  },
];

let editingId = null;

// DOM Elements
const servicesContainer = document.getElementById("servicesContainer");
const emptyState = document.getElementById("emptyState");
const serviceForm = document.getElementById("serviceForm");
const modalTitle = document.getElementById("modalTitle");
const saveButton = document.getElementById("saveService");

// Initialize
const shopId = document.getElementById("shopId").textContent
document.addEventListener("DOMContentLoaded", async function () {
  const res = await fetch(`../../helper/adminGetServices.php?shop_id=${shopId}`);
  const k = await res.json();
  console.log(k)
  services = k
  renderServices();

  // Form submission
  saveButton.addEventListener("click", handleSaveService);

  // Reset form when modal is hidden
  const serviceModal = document.getElementById("serviceModal");
  serviceModal.addEventListener("hidden.bs.modal", resetForm);
});

function renderServices() {
  if (services.length === 0) {
    servicesContainer.style.display = "none";
    emptyState.style.display = "block";
    return;
  }

  servicesContainer.style.display = "flex";
  emptyState.style.display = "none";

  servicesContainer.innerHTML = services
    .map(
      (service) => `
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="${service.icon}"></i>
                        </div>
                        <h4 class="service-title">${service.name}</h4>
                        <p class="service-description">${
                          service.description
                        }</p>
                        <div class="service-price">₱${service.minPrice.toLocaleString()} - ₱${service.maxPrice.toLocaleString()}</div>
                        <div class="action-buttons">
                            <button class="btn btn-outline-light btn-sm" onclick="editService(${
                              service.id
                            })">
                                <i class="fas fa-edit me-1"></i>Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteService(${
                              service.id
                            })">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            `
    )
    .join("");
}

function showNotification(title, message, type = "success") {
  const toast = document.getElementById("notificationToast");
  const toastTitle = document.getElementById("toastTitle");
  const toastBody = document.getElementById("toastBody");
  const toastIcon = document.getElementById("toastIcon");
  const toastTime = document.getElementById("toastTime");

  // Set content
  toastTitle.textContent = title;
  toastBody.textContent = message;
  toastTime.textContent = "Just now";

  // Set icon based on type
  const icons = {
    success: "fas fa-check-circle text-success",
    error: "fas fa-exclamation-circle text-danger",
    info: "fas fa-info-circle text-primary",
    warning: "fas fa-exclamation-triangle text-warning",
  };

  toastIcon.className = icons[type] || icons.info;

  // Show toast
  const bsToast = new bootstrap.Toast(toast);
  bsToast.show();
}

function resetForm() {
  serviceForm.reset();
  editingId = null;
  modalTitle.textContent = "Add New Service";
  saveButton.textContent = "Save Service";
}

async function handleSaveService() {
  const formData = new FormData(serviceForm);
  const serviceData = {
    shop_id: shopId,
    id : editSelectedId,
    name: document.getElementById("serviceName").value,
    description: document.getElementById("serviceDescription").value,
    minPrice: parseFloat(document.getElementById("serviceMinPrice").value),
    maxPrice: parseFloat(document.getElementById("serviceMaxPrice").value),
    icon: document.getElementById("serviceIcon").value,
  };

  // Validate required fields
  if (!serviceData.name) {
    showNotification(
      "Validation Error",
      "Please fill in all required fields.",
      "error"
    );
    return;
  }

  if (editingId) {
    // Update existing service
    const res = await fetch("../../helper/adminEditServices.php",{
    method:"POST",
    headers:{"Content-Type":"application/json"},
    body:JSON.stringify(serviceData)
  })
  const k = await res.json()
  console.log(k)
    const index = services.findIndex((s) => s.id == editingId);
    
    if (index !== -1) {
      services[index] = { ...services[index], ...serviceData };
      showNotification(
        "Service Updated",
        `${serviceData.name} has been updated successfully.`,
        "success"
      );
    }
  } else {
    // Add new service
    alert("Add")
    const ress = await fetch("../../helper/adminAddService.php",{
    method:"POST",
    headers:{"Content-Type":"application/json"},
    body:JSON.stringify(serviceData)
  })
  const kl = await ress.json()
  console.log(kl)
    const newService = {
      id: Date.now(), // Simple ID generation
      ...serviceData,
    };
    services.push(newService);
    showNotification(
      "Service Added",
      `${serviceData.name} has been added successfully.`,
      "success"
    );
  }

  // Close modal and refresh display
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("serviceModal")
  );
  modal.hide();
  renderServices();
}
let editSelectedId = null
function editService(id) {
  const service = services.find((s) => s.id == id);
  if (!service) return;
  editSelectedId = id
  editingId = id;
  modalTitle.textContent = "Edit Service";
  saveButton.textContent = "Update Service";

  // Populate form
  document.getElementById("serviceName").value = service.name;
  document.getElementById("serviceDescription").value = service.description;
  document.getElementById("serviceMinPrice").value = service.minPrice;
  document.getElementById("serviceMaxPrice").value = service.maxPrice;
  document.getElementById("serviceIcon").value = service.icon;

  // Show modal
  const modal = new bootstrap.Modal(document.getElementById("serviceModal"));
  modal.show();
}

async function deleteService(id) {
  const service = services.find((s) => s.id == id);
  if (!service) return;

  if (confirm(`Are you sure you want to delete "${service.name}"?`)) {
    services = services.filter((s) => s.id !== id);
    const r = await fetch(`../../helper/adminDeleteService.php?service_id=${id}`);
    const c = await r.json();
    console.log(c)
    showNotification(
      "Service Deleted",
      `${service.name} has been deleted successfully.`,
      "info"
    );
    renderServices();
  }
}
