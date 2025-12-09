<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Auto Repair Hub - Browse Repair Shops</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
  <link rel="stylesheet" href="../../assets/styles/variables.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      min-height: 100vh;
      color: #fff;
    }
    .navbar {
      background: rgba(15, 23, 42, 0.95) !important;
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(59, 130, 246, 0.3);
      padding: 1rem 0;
    }
    .navbar-brand {
      font-weight: 700;
      font-size: 1.4rem;
    }
    .navbar-brand i {
      color: #3b82f6;
      margin-right: 8px;
    }
    .nav-link {
      color: #cbd5e1 !important;
      font-weight: 500;
      transition: color 0.3s;
    }
    .nav-link:hover {
      color: #3b82f6 !important;
    }
    .btn-signin {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      border: none;
      padding: 8px 24px;
      border-radius: 25px;
      color: white;
      font-weight: 600;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .btn-signin:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
      color: white;
    }
    .hero-section {
      padding: 120px 0 60px;
      text-align: center;
    }
    .hero-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }
    .hero-title span {
      color: #ef4444;
    }
    .hero-subtitle {
      color: #94a3b8;
      font-size: 1.1rem;
      max-width: 600px;
      margin: 0 auto 2rem;
    }
    .search-container {
      max-width: 600px;
      margin: 0 auto 3rem;
    }
    .search-input {
      background: rgba(30, 41, 59, 0.8);
      border: 2px solid rgba(59, 130, 246, 0.3);
      border-radius: 50px;
      padding: 15px 25px;
      color: #fff;
      font-size: 1rem;
      width: 100%;
      transition: border-color 0.3s, box-shadow 0.3s;
    }
    .search-input:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
    }
    .search-input::placeholder {
      color: #64748b;
    }
    .map-container {
      height: 400px;
      border-radius: 20px;
      overflow: hidden;
      margin-bottom: 3rem;
      border: 3px solid rgba(59, 130, 246, 0.3);
    }
    #map {
      height: 100%;
      width: 100%;
    }
    .shops-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
      gap: 25px;
      padding-bottom: 3rem;
    }
    .shop-card {
      background: linear-gradient(145deg, #1e293b, #0f172a);
      border-radius: 20px;
      overflow: hidden;
      border: 1px solid rgba(59, 130, 246, 0.2);
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .shop-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
      border-color: rgba(59, 130, 246, 0.5);
    }
    .shop-header {
      padding: 25px;
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(239, 68, 68, 0.1));
      border-bottom: 1px solid rgba(59, 130, 246, 0.2);
    }
    .shop-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
      margin-bottom: 15px;
    }
    .shop-name {
      font-size: 1.4rem;
      font-weight: 700;
      margin-bottom: 8px;
      color: #f8fafc;
    }
    .shop-address {
      color: #94a3b8;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .shop-address i {
      color: #ef4444;
    }
    .shop-body {
      padding: 25px;
    }
    .shop-info-row {
      display: flex;
      align-items: center;
      margin-bottom: 12px;
      color: #cbd5e1;
    }
    .shop-info-row i {
      width: 25px;
      color: #3b82f6;
    }
    .rating-stars {
      color: #fbbf24;
    }
    .services-section {
      margin-top: 20px;
    }
    .services-title {
      font-size: 1rem;
      font-weight: 600;
      color: #94a3b8;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .services-title i {
      color: #ef4444;
    }
    .services-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      background: rgba(15, 23, 42, 0.5);
      border-radius: 12px;
      overflow: hidden;
    }
    .services-table th {
      background: rgba(59, 130, 246, 0.2);
      padding: 12px 15px;
      text-align: left;
      font-weight: 600;
      font-size: 0.85rem;
      color: #93c5fd;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .services-table td {
      padding: 12px 15px;
      border-bottom: 1px solid rgba(59, 130, 246, 0.1);
      font-size: 0.9rem;
    }
    .services-table tr:last-child td {
      border-bottom: none;
    }
    .services-table .service-name {
      font-weight: 600;
      color: #f8fafc;
    }
    .services-table .service-desc {
      color: #94a3b8;
      font-size: 0.85rem;
    }
    .services-table .service-price {
      color: #4ade80;
      font-weight: 700;
      white-space: nowrap;
    }
    .btn-book {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      border: none;
      padding: 12px 30px;
      border-radius: 10px;
      color: white;
      font-weight: 600;
      width: 100%;
      margin-top: 20px;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .btn-book:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
      color: white;
    }
    .btn-view-map {
      background: transparent;
      border: 2px solid #3b82f6;
      padding: 10px 20px;
      border-radius: 10px;
      color: #3b82f6;
      font-weight: 600;
      width: 100%;
      transition: all 0.3s;
    }
    .btn-view-map:hover {
      background: #3b82f6;
      color: white;
    }
    .no-shops {
      text-align: center;
      padding: 60px 20px;
      color: #64748b;
    }
    .no-shops i {
      font-size: 4rem;
      margin-bottom: 20px;
      color: #475569;
    }
    .section-title {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .section-title i {
      color: #ef4444;
    }
    @media (max-width: 768px) {
      .hero-title {
        font-size: 1.8rem;
      }
      .shops-grid {
        grid-template-columns: 1fr;
      }
      .map-container {
        height: 300px;
      }
    }
    /* Sort controls */
    .sort-controls {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
      flex-wrap: wrap;
    }
    .sort-btn {
      background: rgba(30, 41, 59, 0.8);
      border: 1px solid rgba(59, 130, 246, 0.3);
      padding: 8px 20px;
      border-radius: 25px;
      color: #cbd5e1;
      font-size: 0.9rem;
      cursor: pointer;
      transition: all 0.3s;
    }
    .sort-btn:hover, .sort-btn.active {
      background: #3b82f6;
      color: white;
      border-color: #3b82f6;
    }
    .sort-btn i {
      margin-right: 6px;
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="../index.php">
        <span style="color: #ef4444;">Auto</span>Repair Hub
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto me-3">
          <li class="nav-item">
            <a class="nav-link" href="../index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="shops.php">Browse Shops</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="map.php">Map View</a>
          </li>
        </ul>
        <a href="../signin.php" class="btn btn-signin">
          <i class="fas fa-sign-in-alt me-2"></i>Sign In
        </a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">Find Your <span>Perfect</span> Repair Shop</h1>
      <p class="hero-subtitle">
        Browse all available auto repair shops, compare services, and check prices - no registration required!
      </p>
      <div class="search-container">
        <input type="text" id="shopSearch" class="search-input" placeholder="Search shops by name or location...">
      </div>
    </div>
  </section>

  <!-- Map Section -->
  <section class="container mb-5">
    <h2 class="section-title"><i class="fas fa-map-marked-alt"></i> Shop Locations</h2>
    <div class="map-container">
      <div id="map"></div>
    </div>
  </section>

  <!-- Shops Grid -->
  <section class="container">
    <h2 class="section-title"><i class="fas fa-store"></i> All Repair Shops</h2>
    
    <div class="sort-controls">
      <button class="sort-btn active" data-sort="name">
        <i class="fas fa-sort-alpha-down"></i>Name
      </button>
      <button class="sort-btn" data-sort="rating">
        <i class="fas fa-star"></i>Rating
      </button>
      <button class="sort-btn" data-sort="services">
        <i class="fas fa-tools"></i>Most Services
      </button>
    </div>
    
    <div class="shops-grid" id="shopsGrid">
      <!-- Shops will be loaded dynamically -->
    </div>
    
    <div class="no-shops" id="noShops" style="display: none;">
      <i class="fas fa-search"></i>
      <h3>No shops found</h3>
      <p>Try adjusting your search criteria</p>
    </div>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
  <script>
    let shops = [];
    let map;
    let markers = [];

    // Initialize the page
    document.addEventListener('DOMContentLoaded', async function() {
      await fetchShops();
      initMap();
      renderShops();
      setupEventListeners();
    });

    async function fetchShops() {
      try {
        const res = await fetch('../../helper/getShopAndServicesPublic.php');
        shops = await res.json();
        console.log('Loaded shops:', shops);
      } catch (error) {
        console.error('Error fetching shops:', error);
        shops = [];
      }
    }

    function initMap() {
      map = L.map('map').setView([13.586, 124.2374], 13);
      
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
      }).addTo(map);

      shops.forEach(shop => {
        if (shop.coordinates && shop.coordinates[0] && shop.coordinates[1]) {
          const customIcon = L.divIcon({
            html: `
              <div style="
                position: relative;
                width: 40px;
                height: 50px;
              ">
                <div style="
                  width: 40px;
                  height: 40px;
                  background: linear-gradient(135deg, #ef4444, #dc2626);
                  border-radius: 50% 50% 50% 0;
                  transform: rotate(-45deg);
                  border: 3px solid white;
                  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                  display: flex;
                  align-items: center;
                  justify-content: center;
                ">
                  <i class="${shop.icon || 'fas fa-wrench'}" style="color: white; font-size: 14px; transform: rotate(45deg);"></i>
                </div>
              </div>
            `,
            className: 'custom-marker',
            iconSize: [40, 50],
            iconAnchor: [20, 50],
            popupAnchor: [0, -45]
          });

          const marker = L.marker(shop.coordinates, { icon: customIcon })
            .addTo(map)
            .bindPopup(`
              <div style="min-width: 200px; font-family: 'Segoe UI', sans-serif;">
                <h5 style="margin: 0 0 8px; color: #1e293b; font-weight: 700;">${shop.name}</h5>
                <p style="margin: 0 0 5px; color: #64748b; font-size: 0.85rem;">
                  <i class="fas fa-map-marker-alt" style="color: #ef4444; margin-right: 5px;"></i>
                  ${shop.address}
                </p>
                <p style="margin: 0 0 5px; color: #64748b; font-size: 0.85rem;">
                  <i class="fas fa-star" style="color: #fbbf24; margin-right: 5px;"></i>
                  ${shop.rating}/10 rating
                </p>
                <p style="margin: 0 0 10px; color: #64748b; font-size: 0.85rem;">
                  <i class="fas fa-clock" style="color: #3b82f6; margin-right: 5px;"></i>
                  ${shop.hours} hours service
                </p>
                <a href="../signin.php" style="
                  display: block;
                  background: linear-gradient(135deg, #ef4444, #dc2626);
                  color: white;
                  text-align: center;
                  padding: 8px;
                  border-radius: 8px;
                  text-decoration: none;
                  font-weight: 600;
                  font-size: 0.9rem;
                ">Book Now</a>
              </div>
            `, {
              maxWidth: 300,
              className: 'custom-popup'
            });
          
          // Add tooltip with shop name (always visible on hover)
          marker.bindTooltip(`<strong style="font-size: 14px; font-weight: 700;">${shop.name}</strong>`, {
            permanent: false,
            direction: 'top',
            offset: [0, -45],
            className: 'shop-tooltip'
          });
          
          markers.push({ marker, shop });
        }
      });

      // Fit map to show all markers
      if (markers.length > 0) {
        const group = new L.featureGroup(markers.map(m => m.marker));
        map.fitBounds(group.getBounds().pad(0.1));
      }
    }

    function renderShops(filteredShops = null) {
      const grid = document.getElementById('shopsGrid');
      const noShops = document.getElementById('noShops');
      const displayShops = filteredShops || shops;

      if (displayShops.length === 0) {
        grid.style.display = 'none';
        noShops.style.display = 'block';
        return;
      }

      grid.style.display = 'grid';
      noShops.style.display = 'none';

      grid.innerHTML = displayShops.map(shop => `
        <div class="shop-card" data-shop-id="${shop.shop_id}">
          <div class="shop-header">
            <div class="shop-icon">
              <i class="${shop.icon || 'fas fa-wrench'}"></i>
            </div>
            <h3 class="shop-name">${shop.name}</h3>
            <p class="shop-address">
              <i class="fas fa-map-marker-alt"></i>
              ${shop.address}
            </p>
          </div>
          <div class="shop-body">
            <div class="shop-info-row">
              <i class="fas fa-star"></i>
              <span class="rating-stars">${'★'.repeat(Math.round(shop.rating/2))}${'☆'.repeat(5-Math.round(shop.rating/2))}</span>
              <span class="ms-2">(${shop.rating}/10)</span>
            </div>
            <div class="shop-info-row">
              <i class="fas fa-clock"></i>
              <span>${shop.hours} hours service available</span>
            </div>
            <div class="shop-info-row">
              <i class="fas fa-phone"></i>
              <span>${shop.contact || 'Contact available after booking'}</span>
            </div>
            
            <div class="services-section">
              <h4 class="services-title">
                <i class="fas fa-tools"></i>
                Services & Labor Prices
              </h4>
              ${shop.services && shop.services.length > 0 ? `
                <table class="services-table">
                  <thead>
                    <tr>
                      <th>Service</th>
                      <th>Description</th>
                      <th>Price (₱)</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${shop.services.map(service => `
                      <tr>
                        <td class="service-name">${service.service_name}</td>
                        <td class="service-desc">${service.description || '-'}</td>
                        <td class="service-price">₱${service.min_cost} - ₱${service.max_cost}</td>
                      </tr>
                    `).join('')}
                  </tbody>
                </table>
              ` : '<p style="color: #64748b;">No services listed yet</p>'}
            </div>
            
            <div class="row g-2 mt-3">
              <div class="col-6">
                <button class="btn-view-map" onclick="focusOnMap(${shop.coordinates ? shop.coordinates[0] : 0}, ${shop.coordinates ? shop.coordinates[1] : 0})">
                  <i class="fas fa-map-marker-alt me-1"></i>View on Map
                </button>
              </div>
              <div class="col-6">
                <a href="../signin.php" class="btn btn-book">
                  <i class="fas fa-calendar-plus me-1"></i>Book Now
                </a>
              </div>
            </div>
          </div>
        </div>
      `).join('');
    }

    function focusOnMap(lat, lng) {
      if (lat && lng) {
        map.setView([lat, lng], 16);
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Find and open the popup for this location
        markers.forEach(({ marker, shop }) => {
          if (shop.coordinates[0] === lat && shop.coordinates[1] === lng) {
            marker.openPopup();
          }
        });
      }
    }

    function setupEventListeners() {
      // Search functionality
      document.getElementById('shopSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const filtered = shops.filter(shop => 
          shop.name.toLowerCase().includes(searchTerm) ||
          shop.address.toLowerCase().includes(searchTerm)
        );
        renderShops(filtered);
      });

      // Sort functionality
      document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          
          const sortType = this.dataset.sort;
          let sorted = [...shops];
          
          switch(sortType) {
            case 'name':
              sorted.sort((a, b) => a.name.localeCompare(b.name));
              break;
            case 'rating':
              sorted.sort((a, b) => b.rating - a.rating);
              break;
            case 'services':
              sorted.sort((a, b) => (b.services?.length || 0) - (a.services?.length || 0));
              break;
          }
          
          renderShops(sorted);
        });
      });
    }
  </script>
  
  <style>
    .shop-tooltip {
      background: #1e293b !important;
      border: 2px solid #3b82f6 !important;
      border-radius: 8px !important;
      padding: 8px 12px !important;
      color: #fff !important;
      font-family: 'Segoe UI', sans-serif !important;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
    }
    .shop-tooltip::before {
      border-top-color: #3b82f6 !important;
    }
    .leaflet-popup-content-wrapper {
      border-radius: 12px !important;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
    }
    .leaflet-popup-tip {
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2) !important;
    }
  </style>
</body>
</html>
