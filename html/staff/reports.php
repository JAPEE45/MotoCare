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

// Debug: Output shop_id to console via JavaScript
echo "<script>console.log('Staff shop_id from database:', " . json_encode($shop_id) . ");</script>";
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
    
    <link rel="stylesheet" href="../../assets/styles/sidebar.css">
    <link rel="stylesheet" href="../../assets/styles/reports.css">
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <!-- <div class="sidebar-logo"> -->
          <!-- <img src="../../assets/images/logo.svg" alt="MotoCare Logo" onerror="this.style.display='none'"> -->
        <!-- </div> -->
        <span class="sidebar-title">AutoRepair Shop</span>
      </div>
      
      <nav class="sidebar-nav">
        <a href="./dashboard.php" class="nav-item active">
          <span>Dashboard</span>
        </a>
        <a href="./booking-service.php" class="nav-item">
          <span>Bookings</span>
        </a>
        <a href="./reports.php" class="nav-item">
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
            <span class="user-role">Staff</span>
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
                <div class="alert alert-info mb-3" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> Revenue is calculated from total cost entered by staff when completing bookings. If no cost was entered, the service labor price is used as an estimate.
                </div>
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
    <script>
        let trendChart, statusChart;
        let lastReportData = {}; // Store latest report data for export
        const shopId = document.getElementById('shopId').value;
        
        console.log('ShopId being used for queries:', shopId);
        console.log('Type of shopId:', typeof shopId);
        
        document.addEventListener('DOMContentLoaded', function() {
            // Set default dates to cover October 2025 where the data is
            const today = new Date();
            const threeMonthsAgo = new Date(today.getTime() - 90 * 24 * 60 * 60 * 1000);
            document.getElementById('endDate').value = today.toISOString().split('T')[0];
            document.getElementById('startDate').value = threeMonthsAgo.toISOString().split('T')[0];
            
            generateReport();
        });
        
        async function generateReport() {
            const period = document.getElementById('reportPeriod').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            console.log('Generating report with:', { shopId, period, startDate, endDate });
            
            try {
                const res = await fetch(`../../helper/getReports.php?shop_id=${shopId}&period=${period}&start=${startDate}&end=${endDate}`);
                const text = await res.text();
                console.log('Raw response:', text);
                
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    console.error('Response text:', text);
                    alert('Error: Server returned invalid data. Check console for details.');
                    return;
                }
                
                console.log('Report data received:', data);
                
                // Store the data for export
                lastReportData = data;
                
                // Show debug info
                if (data.debug) {
                    console.log('=== DEBUG INFO ===');
                    console.log('Shop ID used:', data.debug.shop_id);
                    console.log('Date range:', data.debug.start_date, 'to', data.debug.end_date);
                    console.log('Total bookings in DB for this shop:', data.debug.total_bookings_for_shop);
                    console.log('Stats query result:', data.debug.stats_data);
                    console.log('==================');
                }
                
                if (data.error) {
                    alert('Error: ' + data.error);
                    return;
                }
                
                // Update stats cards
                document.getElementById('totalBookings').textContent = data.stats.total_bookings || 0;
                document.getElementById('totalRevenue').textContent = '₱' + (data.stats.total_revenue || 0).toLocaleString();
                document.getElementById('completedBookings').textContent = data.stats.completed || 0;
                document.getElementById('pendingBookings').textContent = data.stats.pending || 0;
                
                console.log('Stats updated:', data.stats);
                
                // Update charts
                updateTrendChart(data.trend);
                updateStatusChart(data.status_distribution);
                
                // Update tables
                updateTopServicesTable(data.top_services);
                updateRecentBookingsTable(data.recent_bookings);
                
            } catch (error) {
                console.error('Error generating report:', error);
                alert('Error loading report data: ' + error.message);
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
            // Make sure we have report data
            if (!lastReportData || !lastReportData.stats) {
                alert('Please generate a report first before exporting.');
                return;
            }
            
            // Create workbook
            const wb = XLSX.utils.book_new();
            
            // Get date range
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            // === SHEET 1: EXECUTIVE SUMMARY ===
            const summaryData = [
                ['MOTOCARE BOOKING REPORT'],
                ['Generated:', new Date().toLocaleString()],
                ['Period:', startDate + ' to ' + endDate],
                ['Shop ID:', shopId],
                [],
                ['KEY METRICS'],
                ['Metric', 'Value'],
                ['Total Bookings', lastReportData.stats.total_bookings || 0],
                ['Total Revenue', '₱' + parseFloat(lastReportData.stats.total_revenue || 0).toLocaleString()],
                ['Completed Bookings', lastReportData.stats.completed || 0],
                ['On Queue', lastReportData.stats.pending || 0],
                ['In Progress', lastReportData.stats.in_progress || 0],
                ['Cancelled', lastReportData.stats.cancelled || 0],
                [],
                ['STATUS DISTRIBUTION'],
                ['Status', 'Count', 'Percentage'],
                ['Completed', lastReportData.status_distribution.completed || 0, 
                    ((lastReportData.status_distribution.completed || 0) / (lastReportData.stats.total_bookings || 1) * 100).toFixed(1) + '%'],
                ['On Queue', lastReportData.status_distribution.pending || 0,
                    ((lastReportData.status_distribution.pending || 0) / (lastReportData.stats.total_bookings || 1) * 100).toFixed(1) + '%'],
                ['In Progress', lastReportData.status_distribution.progress || 0,
                    ((lastReportData.status_distribution.progress || 0) / (lastReportData.stats.total_bookings || 1) * 100).toFixed(1) + '%'],
                ['Cancelled', lastReportData.status_distribution.cancelled || 0,
                    ((lastReportData.status_distribution.cancelled || 0) / (lastReportData.stats.total_bookings || 1) * 100).toFixed(1) + '%']
            ];
            
            const summarySheet = XLSX.utils.aoa_to_sheet(summaryData);
            
            // Style the summary sheet
            summarySheet['!cols'] = [{wch: 20}, {wch: 25}, {wch: 15}];
            summarySheet['!rows'] = [{hpt: 20}];
            
            XLSX.utils.book_append_sheet(wb, summarySheet, 'Executive Summary');
            
            // === SHEET 2: TREND ANALYSIS ===
            if (lastReportData.trend && lastReportData.trend.labels.length > 0) {
                const trendData = [
                    ['TREND ANALYSIS'],
                    ['Date', 'Bookings', 'Revenue (₱)'],
                ];
                
                for (let i = 0; i < lastReportData.trend.labels.length; i++) {
                    trendData.push([
                        lastReportData.trend.labels[i],
                        lastReportData.trend.bookings[i] || 0,
                        lastReportData.trend.revenue[i] || 0
                    ]);
                }
                
                // Calculate totals and averages
                const totalBookings = lastReportData.trend.bookings.reduce((a, b) => a + b, 0);
                const totalRevenue = lastReportData.trend.revenue.reduce((a, b) => a + b, 0);
                const avgBookings = (totalBookings / lastReportData.trend.labels.length).toFixed(2);
                const avgRevenue = (totalRevenue / lastReportData.trend.labels.length).toFixed(2);
                
                trendData.push(
                    [],
                    ['SUMMARY'],
                    ['Total Bookings:', totalBookings],
                    ['Total Revenue:', '₱' + totalRevenue.toLocaleString()],
                    ['Average Bookings/Day:', avgBookings],
                    ['Average Revenue/Day:', '₱' + avgRevenue]
                );
                
                const trendSheet = XLSX.utils.aoa_to_sheet(trendData);
                trendSheet['!cols'] = [{wch: 15}, {wch: 15}, {wch: 20}];
                XLSX.utils.book_append_sheet(wb, trendSheet, 'Trend Analysis');
            }
            
            // === SHEET 3: TOP SERVICES ===
            if (lastReportData.top_services && lastReportData.top_services.length > 0) {
                const servicesData = [
                    ['TOP PERFORMING SERVICES'],
                    ['Rank', 'Service Name', 'Bookings', 'Total Revenue (₱)', 'Avg Revenue (₱)'],
                ];
                
                lastReportData.top_services.forEach((service, index) => {
                    const avgRevenue = service.bookings > 0 ? (service.revenue / service.bookings).toFixed(2) : '0.00';
                    servicesData.push([
                        index + 1,
                        service.service_name,
                        service.bookings,
                        parseFloat(service.revenue).toFixed(2),
                        avgRevenue
                    ]);
                });
                
                const servicesSheet = XLSX.utils.aoa_to_sheet(servicesData);
                servicesSheet['!cols'] = [{wch: 8}, {wch: 30}, {wch: 12}, {wch: 18}, {wch: 18}];
                XLSX.utils.book_append_sheet(wb, servicesSheet, 'Top Services');
            }
            
            // === SHEET 4: RECENT BOOKINGS ===
            if (lastReportData.recent_bookings && lastReportData.recent_bookings.length > 0) {
                const bookingsData = [
                    ['RECENT BOOKINGS'],
                    ['Transaction ID', 'Customer', 'Service', 'Date', 'Time', 'Status', 'Revenue (₱)'],
                ];
                
                lastReportData.recent_bookings.forEach(booking => {
                    bookingsData.push([
                        booking.transaction_number || booking.id || 'N/A',
                        booking.customer_name || 'N/A',
                        booking.service_name || 'N/A',
                        booking.date || 'N/A',
                        booking.time_slot || 'N/A',
                        (booking.status || 'unknown').toUpperCase(),
                        parseFloat(booking.revenue || 0).toFixed(2)
                    ]);
                });
                
                const bookingsSheet = XLSX.utils.aoa_to_sheet(bookingsData);
                bookingsSheet['!cols'] = [{wch: 18}, {wch: 20}, {wch: 25}, {wch: 12}, {wch: 12}, {wch: 12}, {wch: 15}];
                XLSX.utils.book_append_sheet(wb, bookingsSheet, 'Recent Bookings');
            }
            
            // === SHEET 5: RAW DATA (ALL BOOKINGS) ===
            if (lastReportData.recent_bookings && lastReportData.recent_bookings.length > 0) {
                const rawData = [
                    ['COMPLETE BOOKING DATA'],
                    ['Transaction ID', 'Booking ID', 'Customer Name', 'Contact', 'Service', 'Date', 'Time', 'Status', 'Created', 'Notes', 'Revenue (₱)'],
                ];
                
                lastReportData.recent_bookings.forEach(booking => {
                    rawData.push([
                        booking.transaction_number || 'N/A',
                        booking.id || 'N/A',
                        booking.customer_name || 'N/A',
                        booking.contact || 'N/A',
                        booking.service_name || 'N/A',
                        booking.date || 'N/A',
                        booking.time_slot || 'N/A',
                        booking.status || 'N/A',
                        booking.created_at || 'N/A',
                        booking.notes || '',
                        parseFloat(booking.revenue || 0).toFixed(2)
                    ]);
                });
                
                const rawSheet = XLSX.utils.aoa_to_sheet(rawData);
                rawSheet['!cols'] = [{wch: 18}, {wch: 10}, {wch: 20}, {wch: 15}, {wch: 25}, {wch: 12}, {wch: 12}, {wch: 12}, {wch: 18}, {wch: 30}, {wch: 15}];
                XLSX.utils.book_append_sheet(wb, rawSheet, 'All Bookings Data');
            }
            
            // Generate filename with timestamp
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-').split('T')[0];
            const filename = `MotoCare_Report_${timestamp}.xlsx`;
            
            // Download
            XLSX.writeFile(wb, filename);
            
            console.log('Excel report generated successfully:', filename);
        }
        
        function exportToPDF() {
            window.print();
        }
    </script>
    <script src="../../assets/scripts/sidebar.js"></script>
</body>
</html>
