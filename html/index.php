<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MotoCare</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />

    <link rel="stylesheet" href="../assets/styles/index.css" />
    <link rel="stylesheet" href="../assets/styles/navbar.css" />
  </head>
  <body>
    <header
      class="d-flex align-items-center justify-content-between p-4 position-relative"
      style="z-index: 20"
    >
      <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
          <a class="navbar-brand" href="#">
            <i class="fa-solid fa-motorcycle"></i
            ><span style="color: var(--primary-red)"> Moto</span>Care
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link" href="./customer/homepage.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#partnership">Partnership</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#aboutus">About Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./signin.php">Sign In</a>
              </li>
              <li class="nav-item">
                <a class="nav-link signup" href="./signup.php">Sign Up</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <section
      class="position-relative px-4 py-5 min-vh-100 d-flex align-items-center overflow-hidden"
      id="home"
    >
      <img
        src="../assets/images/ChatGPT Image Aug 17, 2025, 08_44_46 PM-Picsart-AiImageEnhancer.png"
        alt=""
        class="position-absolute hero-bg-image"
      />

      <div
        class="container-fluid position-relative"
        style="z-index: 10; max-width: 1440px"
      >
        <div class="row align-items-center g-5 hero-content">
          <div class="col-lg-6">
            <div class="welcome-badge mb-4">WELCOME TO MotoCare</div>

            <h1 class="hero-title mb-4">
              Your <span class="text-red-primary">Trusted</span> Motor<br />
              Repair Service<br />
              Provider
            </h1>

            <p class="hero-description">
              We offer reliable and efficient services to ensure your vehicle is
              always in top condition.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="cta-section">
      <div class="overlay"></div>
      <div class="container">
        <div class="cta-content">
          <h2 class="cta-title">
            Ready to schedule<br />
            an <span class="text-red-primary">appointment</span>?
          </h2>
          <p class="cta-subtitle">Quality repairs are closer than you think.</p>
          <a href="./signin.php" class="cta-btn">Sign In Now!</a>
        </div>
      </div>
    </section>

    <section
      class="services-bg position-relative"
      style="padding: 100px 1.5rem"
      id="partnership"
    >
      <div class="container-fluid" style="max-width: 1440px">
        <div class="d-flex align-items-center justify-content-between mb-5">
          <div>
            <p
              class="text-red-primary small fw-medium mb-2"
              style="letter-spacing: 0.1em"
            >
              WHO WE WORK WITH
            </p>
            <h2 class="fs-1 fw-bold">Our Partner Shops</h2>
          </div>
        </div>

        <div class="row g-4 justify-content-center">
          <div class="col-12 col-lg-3">
            <div class="service-card">
              <div class="service-image position-relative">
                <img
                  src="../assets/images/moljelube.jpg"
                  alt="Engine Repair"
                  class="service-img"
                />
                <div class="service-overlay"></div>
              </div>
              <h3 class="service-title">MOLJE LUBE</h3>
            </div>
          </div>

          <div class="col-12 col-lg-3">
            <div class="service-card">
              <div class="service-image position-relative">
                <img
                  src="../assets/images/ptm.jpg"
                  alt="Brake Repair"
                  class="service-img"
                />
                <div class="service-overlay"></div>
              </div>
              <h3 class="service-title">PRECISION TECH<br />MOTOSHOP</h3>
            </div>
          </div>

          <div class="col-12 col-lg-3">
            <div class="service-card">
              <div class="service-image position-relative">
                <img src="" alt="CT GEAR" class="service-img" />
                <div class="service-overlay"></div>
              </div>
              <h3 class="service-title">CT GEAR</h3>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="aboutus">
      <div class="container">
        <div class="row align-items-center about-section">
          <div class="col-lg-6 px-4">
            <div class="section-title">ABOUT US</div>
            <h2 class="about-heading">
              Connecting Drivers to<br />Trusted Shops
            </h2>
            <p
              style="
                color: var(--text-gray);
                line-height: 1.7;
                margin-bottom: 30px;
              "
            >
              <span class="text-logo">MotoCare</span> is a centralized,
              web-based platform created to transform how auto repair services
              are managed and accessed in Virac, Catanduanes. Our goal is to
              make vehicle maintenance and repair more
              <strong>efficient, accessible, and transparent</strong> for both
              customers and shop owners.
            </p>
            <p
              style="
                color: var(--text-gray);
                line-height: 1.7;
                margin-bottom: 50px;
              "
            >
              We partner with local auto repair shops such as
              <strong>Molje Lube, Precision Tech Motoshop, and CT Gear</strong>
              to bridge the gap between traditional operations and modern
              customer service. Through our platform, customers can
              <strong>book appointments online</strong>,
              <strong>locate the nearest motor shop</strong> via GIS map,
              <strong>track their repair progress</strong> in real time, and
              receive <strong>SMS notifications</strong> for updates. For shop
              owners, our system provides a centralized dashboard to simplify
              job management, reduce errors, and improve communication.
            </p>
          </div>

          <div class="col-lg-6">
            <div class="mechanic-image text-center">
              <img
                src="../assets/images/undraw_details_sgb2.svg"
                alt=""
                class="img-fluid"
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <div id="mobileMenu" class="mobile-menu-overlay d-none d-lg-none">
      <div class="mobile-menu-content">
        <a href="#" class="mobile-menu-link">About</a>
        <a href="#" class="mobile-menu-link">Gallery</a>
        <a href="#" class="mobile-menu-link">Pricing</a>
        <a href="#" class="mobile-menu-link">Blog</a>
        <a href="#" class="mobile-menu-link">Contact</a>
        <button onclick="toggleMobileMenu()" class="mobile-close-btn">
          <svg
            class="text-white"
            width="24"
            height="24"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"
            />
          </svg>
        </button>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">
        <p class="copyright">
          Copyright © 2025 MotoCare. All rights reserved.
        </p>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/scripts/index.js"></script>
  </body>
</html>
