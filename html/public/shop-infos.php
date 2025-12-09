<?php
// Get shop ID from URL
$shopId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Include database connection
include '../../helper/db.php';

// Fetch shop details
$shop = null;
$services = [];
$totalBookings = 0;
$ownerName = '';

if ($shopId > 0) {
    // Get shop info
    $shopQuery = "SELECT s.*, u.fullname as owner_name FROM shop s 
                  LEFT JOIN user u ON u.shop_id = s.id AND u.role = 'owner' 
                  WHERE s.id = ?";
    $stmt = $conn->prepare($shopQuery);
    $stmt->bind_param("i", $shopId);
    $stmt->execute();
    $result = $stmt->get_result();
    $shop = $result->fetch_assoc();
    $stmt->close();
    
    if ($shop) {
        $ownerName = $shop['owner_name'] ?? 'Shop Owner';
        
        // Get services
        $servicesQuery = "SELECT * FROM services WHERE shop_id = ?";
        $stmt = $conn->prepare($servicesQuery);
        $stmt->bind_param("i", $shopId);
        $stmt->execute();
        $servicesResult = $stmt->get_result();
        while ($service = $servicesResult->fetch_assoc()) {
            $services[] = $service;
        }
        $stmt->close();
        
        // Get total bookings count
        $bookingsQuery = "SELECT COUNT(*) as total FROM booking WHERE shop = ?";
        $stmt = $conn->prepare($bookingsQuery);
        $stmt->bind_param("s", $shopId);
        $stmt->execute();
        $bookingsResult = $stmt->get_result();
        $bookingsData = $bookingsResult->fetch_assoc();
        $totalBookings = $bookingsData['total'] ?? 0;
        $stmt->close();
    }
}

// Shop images mapping
$shopImages = [
    1 => '../../assets/images/moljelube.jpg',
    2 => '../../assets/images/ptm.jpg',
    3 => ''
];
$shopImage = isset($shopImages[$shopId]) ? $shopImages[$shopId] : '';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $shop ? htmlspecialchars($shop['name']) : 'Shop Not Found'; ?> - Auto Repair Shop</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../../assets/styles/shop-infos.css">
    <style>
      /* Login Modal Styles */
      .login-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        justify-content: center;
        align-items: center;
      }
      .login-modal-overlay.show {
        display: flex;
      }
      .login-modal-content {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-radius: 20px;
        padding: 40px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
      }
      .login-modal-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
      }
      .login-modal-icon i {
        font-size: 36px;
        color: white;
      }
      .login-modal-title {
        color: #fff;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
      }
      .login-modal-text {
        color: #9ca3af;
        font-size: 16px;
        margin-bottom: 30px;
        line-height: 1.5;
      }
      .login-modal-btn {
        display: inline-block;
        padding: 14px 40px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        margin: 5px;
        transition: all 0.3s ease;
      }
      .login-modal-btn-primary {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: none;
      }
      .login-modal-btn-primary:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        transform: translateY(-2px);
      }
      .login-modal-btn-secondary {
        background: transparent;
        color: #9ca3af;
        border: 1px solid #374151;
      }
      .login-modal-btn-secondary:hover {
        background: #374151;
        color: white;
      }
      .back-btn {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 100;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s ease;
      }
      .back-btn:hover {
        background: rgba(0, 0, 0, 0.8);
        color: white;
      }
    </style>
  </head>
  <body>
    <!-- Back Button -->
    <a href="../index.php" class="back-btn">
      <i class="fas fa-arrow-left me-2"></i>Back
    </a>

    <!-- Login Required Modal -->
    <div class="login-modal-overlay" id="loginModal">
      <div class="login-modal-content">
        <div class="login-modal-icon">
          <i class="fas fa-lock"></i>
        </div>
        <h3 class="login-modal-title">Login Required</h3>
        <p class="login-modal-text">
          Please sign in to your account to book a service. If you don't have an account, you can create one for free!
        </p>
        <div>
          <a href="../signin.php?redirect=map&shop_id=<?php echo $shopId; ?>" class="login-modal-btn login-modal-btn-primary">
            <i class="fas fa-sign-in-alt me-2"></i>Sign In
          </a>
          <a href="../signup.php" class="login-modal-btn login-modal-btn-secondary">
            <i class="fas fa-user-plus me-2"></i>Sign Up
          </a>
        </div>
        <button class="login-modal-btn login-modal-btn-secondary mt-3" onclick="closeLoginModal()" style="width: 100%;">
          Cancel
        </button>
      </div>
    </div>

    <?php if ($shop): ?>
    <div class="">
      <div class="main-content">
        <div class="shop-card">
          <!-- Left Section -->
          <div class="left-section">
            <div class="shop-logo-container">
              <?php if ($shopImage): ?>
              <img
                src="<?php echo htmlspecialchars($shopImage); ?>"
                alt="<?php echo htmlspecialchars($shop['name']); ?>"
                class="shop-logo"
                onerror="this.src='https://cdn-icons-png.flaticon.com/512/3097/3097099.png'"
              />
              <?php else: ?>
              <img
                src="https://cdn-icons-png.flaticon.com/512/3097/3097099.png"
                alt="<?php echo htmlspecialchars($shop['name']); ?>"
                class="shop-logo"
              />
              <?php endif; ?>
            </div>
            <div class="shop-info">
              <h1 class="shop-name"><?php echo htmlspecialchars($shop['name']); ?></h1>
              <div class="owner-name">
                <i class="fas fa-user-tie"></i>
                <span>Owned by <?php echo htmlspecialchars($ownerName); ?></span>
              </div>
              <div class="location">
                <i class="fas fa-map-marker-alt"></i>
                <span><?php echo htmlspecialchars($shop['address']); ?></span>
              </div>
              <div class="rating-section">
                <div class="stars">
                  <?php 
                  $rating = floatval($shop['rating']) / 2; // Convert 10-scale to 5-star
                  for ($i = 1; $i <= 5; $i++):
                    if ($i <= floor($rating)):
                  ?>
                  <i class="fas fa-star"></i>
                  <?php elseif ($i - 0.5 <= $rating): ?>
                  <i class="fas fa-star-half-alt"></i>
                  <?php else: ?>
                  <i class="far fa-star"></i>
                  <?php endif; endfor; ?>
                </div>
                <span class="rating-number"><?php echo $shop['rating']; ?>/10</span>
              </div>
            </div>
          </div>

          <!-- Right Section -->
          <div class="right-section">
            <div class="right-container-1">
              <h2 class="services-header">Our Services</h2>
              <div class="services-list">
                <?php if (count($services) > 0): ?>
                  <?php foreach ($services as $service): ?>
                  <div class="service-item">
                    <div class="service-icon">
                      <i class="fas fa-wrench"></i>
                    </div>
                    <span class="service-name"><?php echo htmlspecialchars($service['service_name']); ?></span>
                  </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="text-muted">No services listed yet.</p>
                <?php endif; ?>
              </div>
            </div>

            <div class="right-container-2">
              <div class="stats-section">
                <div class="bookings-counter">
                  <div class="counter-number" id="bookingCounter">0</div>
                  <div class="counter-label">Total Bookings</div>
                </div>
              </div>

              <button class="book-now-btn" onclick="handleBooking()">
                <i class="fas fa-calendar-check"></i> Book Now!
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php else: ?>
    <div class="d-flex justify-content-center align-items-center vh-100">
      <div class="text-center">
        <i class="fas fa-store-slash" style="font-size: 80px; color: #ef4444; margin-bottom: 20px;"></i>
        <h2 style="color: #fff;">Shop Not Found</h2>
        <p style="color: #9ca3af;">The shop you're looking for doesn't exist or has been removed.</p>
        <a href="../index.php" class="btn btn-danger mt-3">
          <i class="fas fa-home me-2"></i>Back to Home
        </a>
      </div>
    </div>
    <?php endif; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
      // Shop data from PHP
      const shopId = <?php echo $shopId; ?>;
      const totalBookings = <?php echo $totalBookings; ?>;
      const shopLat = <?php echo $shop ? floatval($shop['lat']) : 0; ?>;
      const shopLng = <?php echo $shop ? floatval($shop['lg']) : 0; ?>;
      const shopName = "<?php echo $shop ? addslashes($shop['name']) : ''; ?>";

      // Counter Animation
      const counterElement = document.getElementById("bookingCounter");
      if (counterElement) {
        let currentCount = 0;
        const duration = 2000;
        const increment = Math.ceil(totalBookings / (duration / 16));

        function animateCounter() {
          if (currentCount < totalBookings) {
            currentCount += increment;
            if (currentCount > totalBookings) {
              currentCount = totalBookings;
            }
            counterElement.textContent = currentCount.toLocaleString();
            requestAnimationFrame(animateCounter);
          }
        }

        window.addEventListener("load", () => {
          setTimeout(animateCounter, 300);
        });
      }

      // Login Modal Functions
      function showLoginModal() {
        document.getElementById('loginModal').classList.add('show');
      }

      function closeLoginModal() {
        document.getElementById('loginModal').classList.remove('show');
      }

      // Close modal on outside click
      document.getElementById('loginModal').addEventListener('click', function(e) {
        if (e.target === this) {
          closeLoginModal();
        }
      });

      // Book Now Handler - Check if user is logged in
      function handleBooking() {
        // Make an AJAX call to check if user is logged in
        fetch('../../helper/checkUserSession.php')
          .then(response => response.json())
          .then(data => {
            if (data.loggedIn) {
              // User is logged in, redirect to map with shop ID
              window.location.href = `../customer/map.php?shop_id=${shopId}`;
            } else {
              // User not logged in, show login modal
              showLoginModal();
            }
          })
          .catch(error => {
            console.error('Error checking session:', error);
            // If error, assume not logged in and show modal
            showLoginModal();
          });
      }

      // Add subtle parallax effect on mouse move
      document.addEventListener("mousemove", (e) => {
        const leftSection = document.querySelector(".left-section");
        if (leftSection) {
          const mouseX = e.clientX / window.innerWidth;
          const mouseY = e.clientY / window.innerHeight;
          leftSection.style.setProperty("--mouse-x", mouseX);
          leftSection.style.setProperty("--mouse-y", mouseY);
        }
      });
    </script>
  </body>
</html>
