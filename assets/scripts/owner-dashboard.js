// Sample data - replace with actual API calls
const dashboardData = {
  customers: 1248,
  staff: 24,
  appointments: 342,
  servicesNum: 9,
  monthlyData: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
    customers: [820, 932, 1015, 1127, 1190, 1248],
    appointments: [245, 298, 315, 328, 335, 342],
    revenue: [28500, 32100, 38900, 41200, 43800, 45600],
  },
  appointmentStatus: {
    pending: 45,
    confirmed: 89,
    completed: 178,
    cancelled: 30,
  },
  services: {
    labels: [
      "Oil Change",
      "Brake Service",
      "Tire Service",
      "Engine Repair",
      "AC Service",
    ],
    data: [85, 72, 68, 45, 38],
  },
};

const shopId = document.getElementById("shopId").textContent
// Initialize dashboard
document.addEventListener("DOMContentLoaded", async function () {
  // animateCounters();
  const res = await fetch(`../../helper/adminGraph.php?shop_id=${shopId}`)
  const s = await res.json();
  console.log(s)
   const b = await fetch(`../../helper/getMonthly.php?shop_id=${shopId}`)
  const bb = await b.json()
  dashboardData.monthlyData = bb  
  dashboardData.appointmentStatus = s
  initializeCharts();
});


function animateCounters() {
  animateCounter("totalCustomers", dashboardData.customers, 2000);
  animateCounter("totalStaff", dashboardData.staff, 1500);
  animateCounter("totalAppointments", dashboardData.appointments, 2500);
  animateRevenue("totalRevenue", dashboardData.servicesNum, 2000);
}
console.log(dashboardData.services);

function animateCounter(elementId, targetValue, duration) {
  const element = document.getElementById(elementId);
  const startValue = 0;
  const startTime = performance.now();

  function updateCounter(currentTime) {
    const elapsedTime = currentTime - startTime;
    const progress = Math.min(elapsedTime / duration, 1);
    const currentValue = Math.floor(
      startValue + (targetValue - startValue) * easeOutQuart(progress)
    );

    element.textContent = currentValue.toLocaleString();

    if (progress < 1) {
      requestAnimationFrame(updateCounter);
    }
  }

  requestAnimationFrame(updateCounter);
}

function animateRevenue(elementId, targetValue, duration) {
  const element = document.getElementById(elementId);
  const startValue = 0;
  const startTime = performance.now();

  function updateRevenue(currentTime) {
    const elapsedTime = currentTime - startTime;
    const progress = Math.min(elapsedTime / duration, 1);
    const currentValue = Math.floor(
      startValue + (targetValue - startValue) * easeOutQuart(progress)
    );

    element.textContent = currentValue.toLocaleString();

    if (progress < 1) {
      requestAnimationFrame(updateRevenue);
    }
  }

  requestAnimationFrame(updateRevenue);
}

function easeOutQuart(t) {
  return 1 - --t * t * t * t;
}

// Initialize charts
function initializeCharts() {
  initPerformanceChart();
  initStatusChart();
}

function initPerformanceChart() {
  const ctx = document.getElementById("performanceChart").getContext("2d");
  new Chart(ctx, {
    type: "line",
    data: {
      labels: dashboardData.monthlyData.labels,
      datasets: [
        {
          label: "Customers",
          data: dashboardData.monthlyData.customers,
          borderColor: "#ef4444",
          backgroundColor: "rgba(239, 68, 68, 0.1)",
          tension: 0.4,
          fill: true,
        },
        {
          label: "Appointments",
          data: dashboardData.monthlyData.appointments,
          borderColor: "#0064e0",
          backgroundColor: "rgba(0, 100, 224, 0.1)",
          tension: 0.4,
          fill: true,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          labels: {
            color: "#ffffff",
          },
        },
      },
      scales: {
        x: {
          ticks: { color: "#9ca3af" },
          grid: { color: "rgba(255, 255, 255, 0.1)" },
        },
        y: {
          ticks: { color: "#9ca3af" },
          grid: { color: "rgba(255, 255, 255, 0.1)" },
        },
      },
    },
  });
}

function initStatusChart() {
  const ctx = document.getElementById("statusChart").getContext("2d");
  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["Pending", "Confirmed", "Completed", "Cancelled"],
      datasets: [
        {
          data: [
            dashboardData.appointmentStatus.pending,
            dashboardData.appointmentStatus.confirmed,
            dashboardData.appointmentStatus.completed,
            dashboardData.appointmentStatus.cancelled,
          ],
          backgroundColor: ["#ffc107", "#28a745", "#007bff", "#dc3545"],
          borderWidth: 0,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "bottom",
          labels: {
            color: "#ffffff",
            padding: 20,
          },
        },
      },
    },
  });
}

// // Refresh data (simulate real-time updates)
// setInterval(function () {
//   // Simulate small changes in data
//   dashboardData.customers += Math.floor(Math.random() * 3);
//   dashboardData.appointments += Math.floor(Math.random() * 2);

//   // Update counters without animation for real-time feel
//   document.getElementById("totalCustomers").textContent =
//     dashboardData.customers.toLocaleString();
//   document.getElementById("totalAppointments").textContent =
//     dashboardData.appointments.toLocaleString();
// }, 30000); // Update every 30 seconds
