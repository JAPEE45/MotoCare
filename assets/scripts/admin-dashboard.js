// Load dashboard stats on page load
document.addEventListener('DOMContentLoaded', function() {
  loadDashboardStats();
});

async function loadDashboardStats() {
  try {
    const response = await fetch('../../helper/adminGetDashboardStats.php');
    const data = await response.json();
    
    if (data.status === 'success') {
      const stats = data.data;
      
      // Update stat cards
      document.getElementById('totalShops').textContent = stats.total_shops;
      document.getElementById('newShopsThisMonth').textContent = `+${stats.new_shops_this_month} this month`;
      
      document.getElementById('totalServices').textContent = stats.total_services;
      
      document.getElementById('totalCustomers').textContent = stats.total_customers;
      document.getElementById('newCustomers').textContent = `+${stats.new_customers} new`;
      
      document.getElementById('totalRevenue').textContent = `₱${parseFloat(stats.total_revenue || 0).toLocaleString()}`;
      
      document.getElementById('totalBookings').textContent = stats.total_bookings;
      
      // Render recent activity
      renderRecentActivity(stats.recent_activity);
    } else {
      console.error('Failed to load dashboard stats');
    }
  } catch (error) {
    console.error('Error loading dashboard stats:', error);
  }
}

function renderRecentActivity(activities) {
  const container = document.getElementById('recentActivity');
  container.innerHTML = '';
  
  if (!activities || activities.length === 0) {
    container.innerHTML = '<p class="text-muted">No recent activities</p>';
    return;
  }
  
  activities.forEach(activity => {
    const activityItem = document.createElement('div');
    activityItem.className = 'activity-item d-flex justify-content-between align-items-center py-2 border-bottom';
    
    const statusBadge = getStatusBadge(activity.status);
    const date = new Date(activity.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    
    activityItem.innerHTML = `
      <div>
        <p class="mb-0"><strong>${activity.customer_name}</strong> - ${activity.shop_name}</p>
        <small class="text-muted">${activity.service_name} - ₱${parseFloat(activity.total_cost || 0).toLocaleString()}</small>
      </div>
      <div class="text-end">
        <span class="badge ${statusBadge}">${formatStatus(activity.status)}</span>
        <br>
        <small class="text-muted">${date}</small>
      </div>
    `;
    
    container.appendChild(activityItem);
  });
}

function getStatusBadge(status) {
  const badges = {
    'pending': 'bg-warning',
    'approved': 'bg-info',
    'ongoing': 'bg-primary',
    'completed': 'bg-success',
    'cancelled': 'bg-danger'
  };
  return badges[status] || 'bg-secondary';
}

function formatStatus(status) {
  if (status === 'pending') return 'ON QUEUE';
  return status.charAt(0).toUpperCase() + status.slice(1);
}
