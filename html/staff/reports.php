<?php
include '../../helper/db.php';  
session_start();

$ID = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user WHERE ID = ?");
$stmt->bind_param("i", $ID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Check if user is staff or admin
if ($row['role'] !== 'staff' && $row['role'] !== 'admin' && $row['role'] !== 'owner') {
    header("Location: ../signin.php");
    exit();
}

$shop_id = $row['shop_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Repair Hub - Reports</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="../../assets/styles/variables.css">
    <link rel="stylesheet" href="../../assets/styles/sidebar.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            color: #fff;
        }
        .main-content {
            padding: 20px;
        }
        .content-wrapper {
            max-width: 100%;
        }
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .page-title span {
            color: #ef4444;
        }
        .page-subtitle {
            color: #94a3b8;
            margin-bottom: 30px;
        }
        .filter-card {
            background: linear-gradient(145deg, #1e293b, #0f172a);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        .filter-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: #93c5fd;
        }
        .form-select, .form-control {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #fff;
            border-radius: 10px;
        }
        .form-select:focus, .form-control:focus {
            background: rgba(30, 41, 59, 0.9);
            border-color: #3b82f6;
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }
        .form-select option {
            background: #1e293b;
            color: #fff;
        }
        .btn-filter {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-filter:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
        }
        .btn-export {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-export:hover {
            background: linear-gradient(135deg, #059669, #047857);
        }
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stats-card {
            background: linear-gradient(145deg, #1e293b, #0f172a);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            transition: transform 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .stats-icon.blue { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
        .stats-icon.green { background: rgba(16, 185, 129, 0.2); color: #10b981; }
        .stats-icon.red { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        .stats-icon.yellow { background: rgba(251, 191, 36, 0.2); color: #fbbf24; }
        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .stats-label {
            color: #94a3b8;
            font-size: 0.9rem;
        }
        .chart-card {
            background: linear-gradient(145deg, #1e293b, #0f172a);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            margin-bottom: 30px;
        }
        .chart-title {
            font-weight: 600;
            margin-bottom: 20px;
            color: #f8fafc;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .chart-title i {
            color: #3b82f6;
        }
        .table-card {
            background: linear-gradient(145deg, #1e293b, #0f172a);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        .table-card .table {
            color: #fff;
            margin-bottom: 0;
        }
        .table-card .table th {
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
            font-weight: 600;
            border: none;
            padding: 15px;
        }
        .table-card .table td {
            border-color: rgba(59, 130, 246, 0.1);
            padding: 12px 15px;
            vertical-align: middle;
        }
        .table-card .table tbody tr:hover {
            background: rgba(59, 130, 246, 0.1);
        }
        .service-rank {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .rank-badge {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }
        .rank-1 { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #000; }
        .rank-2 { background: linear-gradient(135deg, #94a3b8, #64748b); color: #000; }
        .rank-3 { background: linear-gradient(135deg, #cd7f32, #a0522d); color: #fff; }
        .rank-default { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
        .export-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        @media print {
            body { background: #fff; color: #000; }
            .navbar, .filter-card, .export-buttons, .btn { display: none !important; }
            .stats-card, .chart-card, .table-card { 
                background: #fff; 
                border: 1px solid #ddd;
                color: #000;
                break-inside: avoid;
            }
            .stats-value, .chart-title { color: #000; }
            .stats-label { color: #666; }
            .table { color: #000 !important; }
        }
    </style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <div class="sidebar-logo">
          <img src="../../assets/images/logo.svg" alt="MotoCare Logo" onerror="this.style.display='none'">
          <i class="fa-solid fa-motorcycle" style="display:none"></i>
        </div>
        <span class="sidebar-title">MotoCare</span>
      </div>
      
      <nav class="sidebar-nav">
        <a href="./dashboard.php" class="nav-item">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
        <a href="./booking-service.php" class="nav-item">
          <i class="fas fa-calendar-check"></i>
          <span>Bookings</span>
        </a>
        <a href="./reports.php" class="nav-item active">
          <i class="fas fa-chart-bar"></i>
          <span>Reports</span>
        </a>
      </nav>
      
      <div class="sidebar-footer">
        <div class="user-info">
          <div class="user-avatar">
            <?php echo strtoupper(substr($row['fullname'], 0, 1)); ?>
          </div>
          <div class="user-details">
            <span class="user-name"><?php echo $row['fullname'] ?></span>
            <span class="user-role"><?php echo ucfirst($row['role']) ?></span>
          </div>
        </div>
        <a href="../../helper/logout.php" class="nav-item logout-btn">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </a>
      </div>
      
      <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-chevron-left"></i>
      </button>
    </aside>
    
    <!-- Mobile Header -->
    <header class="mobile-header" id="mobileHeader">
      <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
      </button>
      <span class="mobile-title">MotoCare</span>
      <div class="mobile-user">
        <?php echo strtoupper(substr($row['fullname'], 0, 1)); ?>
      </div>
    </header>

    <input type="hidden" id="shopId" value="<?php echo $shop_id ?>">
    
    <main class="main-content" id="mainContent">
      <div class="content-wrapper">
        <div class="container-fluid">
            <h1 class="page-title"><i class="fas fa-chart-bar me-2" style="color: #3b82f6;"></i>Reports & <span>Analytics</span></h1>
            <p class="page-subtitle">View detailed reports on bookings, revenue, and shop performance</p>
            
            <!-- Filter Section -->
            <div class="filter-card">
                <h5 class="filter-title"><i class="fas fa-filter me-2"></i>Filter Reports</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Report Period</label>
                        <select class="form-select" id="reportPeriod">
                            <option value="daily">Daily (Today)</option>
                            <option value="weekly" selected>Weekly (Last 7 Days)</option>
                            <option value="monthly">Monthly (Last 30 Days)</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="startDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" id="endDate">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-filter w-100" onclick="generateReport()">
                            <i class="fas fa-sync-alt me-2"></i>Generate Report
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-row">
                <div class="stats-card">
                    <div class="stats-icon blue">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stats-value" id="totalBookings">0</div>
                    <div class="stats-label">Total Bookings</div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon green">
                        <i class="fas fa-peso-sign"></i>
                    </div>
                    <div class="stats-value" id="totalRevenue">₱0</div>
                    <div class="stats-label">Total Revenue (Labor)</div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon yellow">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-value" id="completedBookings">0</div>
                    <div class="stats-label">Completed Bookings</div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon red">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-value" id="pendingBookings">0</div>
                    <div class="stats-label">On Queue</div>
                </div>
            </div>
            
            <!-- Charts Row -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="chart-card">
                        <h5 class="chart-title"><i class="fas fa-chart-line"></i>Bookings & Revenue Trend</h5>
                        <canvas id="trendChart" height="100"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-card">
                        <h5 class="chart-title"><i class="fas fa-chart-pie"></i>Booking Status Distribution</h5>
                        <canvas id="statusChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Most Booked Services Table -->
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="table-card">
                        <h5 class="chart-title"><i class="fas fa-trophy"></i>Most Booked Services</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Service Name</th>
                                    <th>Bookings</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody id="topServicesTable">
                                <!-- Data will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="table-card">
                        <h5 class="chart-title"><i class="fas fa-history"></i>Recent Completed Bookings</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="recentBookingsTable">
                                <!-- Data will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Export Buttons -->
            <div class="export-buttons">
                <button class="btn btn-export" onclick="exportToPDF()">
                    <i class="fas fa-file-pdf me-2"></i>Export to PDF
                </button>
                <button class="btn btn-export" onclick="exportToExcel()">
                    <i class="fas fa-file-excel me-2"></i>Export to Excel
                </button>
                <button class="btn btn-filter" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print Report
                </button>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="../../assets/scripts/navbar.js"></script>
    <script>
        let trendChart, statusChart;
        const shopId = document.getElementById('shopId').value;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Set default dates
            const today = new Date();
            const lastWeek = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
            document.getElementById('endDate').value = today.toISOString().split('T')[0];
            document.getElementById('startDate').value = lastWeek.toISOString().split('T')[0];
            
            generateReport();
        });
        
        async function generateReport() {
            const period = document.getElementById('reportPeriod').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            try {
                const res = await fetch(`../../helper/getReports.php?shop_id=${shopId}&period=${period}&start=${startDate}&end=${endDate}`);
                const data = await res.json();
                
                // Update stats cards
                document.getElementById('totalBookings').textContent = data.stats.total_bookings || 0;
                document.getElementById('totalRevenue').textContent = '₱' + (data.stats.total_revenue || 0).toLocaleString();
                document.getElementById('completedBookings').textContent = data.stats.completed || 0;
                document.getElementById('pendingBookings').textContent = data.stats.pending || 0;
                
                // Update charts
                updateTrendChart(data.trend);
                updateStatusChart(data.status_distribution);
                
                // Update tables
                updateTopServicesTable(data.top_services);
                updateRecentBookingsTable(data.recent_bookings);
                
            } catch (error) {
                console.error('Error generating report:', error);
            }
        }
        
        function updateTrendChart(trendData) {
            const ctx = document.getElementById('trendChart').getContext('2d');
            
            if (trendChart) {
                trendChart.destroy();
            }
            
            trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trendData.labels || [],
                    datasets: [
                        {
                            label: 'Bookings',
                            data: trendData.bookings || [],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Revenue (₱)',
                            data: trendData.revenue || [],
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: { color: '#94a3b8' }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(59, 130, 246, 0.1)' },
                            ticks: { color: '#94a3b8' }
                        },
                        y: {
                            grid: { color: 'rgba(59, 130, 246, 0.1)' },
                            ticks: { color: '#94a3b8' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: { color: '#10b981' }
                        }
                    }
                }
            });
        }
        
        function updateStatusChart(statusData) {
            const ctx = document.getElementById('statusChart').getContext('2d');
            
            if (statusChart) {
                statusChart.destroy();
            }
            
            statusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'On Queue', 'In Progress', 'Cancelled'],
                    datasets: [{
                        data: [
                            statusData.completed || 0,
                            statusData.pending || 0,
                            statusData.progress || 0,
                            statusData.cancelled || 0
                        ],
                        backgroundColor: ['#10b981', '#fbbf24', '#3b82f6', '#ef4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#94a3b8', padding: 15 }
                        }
                    }
                }
            });
        }
        
        function updateTopServicesTable(services) {
            const tbody = document.getElementById('topServicesTable');
            if (!services || services.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No data available</td></tr>';
                return;
            }
            
            tbody.innerHTML = services.map((service, index) => `
                <tr>
                    <td>
                        <span class="rank-badge ${index === 0 ? 'rank-1' : index === 1 ? 'rank-2' : index === 2 ? 'rank-3' : 'rank-default'}">
                            ${index + 1}
                        </span>
                    </td>
                    <td>${service.service_name}</td>
                    <td>${service.booking_count}</td>
                    <td>₱${(service.total_revenue || 0).toLocaleString()}</td>
                </tr>
            `).join('');
        }
        
        function updateRecentBookingsTable(bookings) {
            const tbody = document.getElementById('recentBookingsTable');
            if (!bookings || bookings.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No completed bookings</td></tr>';
                return;
            }
            
            tbody.innerHTML = bookings.map(booking => `
                <tr>
                    <td><small>${booking.transaction_number}</small></td>
                    <td>${booking.customer_name}</td>
                    <td>${booking.service_name}</td>
                    <td>₱${(booking.total_cost || 0).toLocaleString()}</td>
                </tr>
            `).join('');
        }
        
        function exportToExcel() {
            // Collect data for export
            const reportData = {
                'Summary': [
                    ['Report Generated', new Date().toLocaleString()],
                    ['Total Bookings', document.getElementById('totalBookings').textContent],
                    ['Total Revenue', document.getElementById('totalRevenue').textContent],
                    ['Completed', document.getElementById('completedBookings').textContent],
                    ['On Queue', document.getElementById('pendingBookings').textContent]
                ]
            };
            
            // Create workbook
            const wb = XLSX.utils.book_new();
            
            // Add summary sheet
            const ws = XLSX.utils.aoa_to_sheet(reportData['Summary']);
            XLSX.utils.book_append_sheet(wb, ws, 'Summary');
            
            // Download
            XLSX.writeFile(wb, `Report_${new Date().toISOString().split('T')[0]}.xlsx`);
        }
        
        function exportToPDF() {
            window.print();
        }
    </script>
    <script src="../../assets/scripts/sidebar.js"></script>
</body>
</html>
