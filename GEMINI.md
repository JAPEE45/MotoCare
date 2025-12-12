# MotoCare Project Context

## Project Overview
**MotoCare** is a web-based motorcycle/auto repair shop management system designed to connect customers with repair shops. It facilitates online booking, service tracking, and business management for shop owners and administrators.

**Key Technologies:**
*   **Frontend:** HTML5, CSS3, JavaScript (ES6+), Bootstrap 5.3, Font Awesome 6.4, Chart.js, Leaflet (Maps).
*   **Backend:** PHP 8.2 (Vanilla, no framework).
*   **Database:** MySQL/MariaDB.
*   **Email:** PHPMailer.
*   **Environment:** XAMPP (Windows).

## Architecture & File Structure

The project follows a generic 3-tier web architecture without a specific MVC framework.

*   **`html/` (Views):** Contains the user-facing pages, separated by role (`admin`, `customer`, `owner`, `staff`, `public`). Files are primarily `.php` to allow session checks and dynamic rendering.
*   **`helper/` (Controllers/API):** Contains the backend logic. These PHP scripts handle:
    *   **API Endpoints:** Receive POST/GET requests from frontend JS (e.g., `adminGetShops.php`, `addBooking.php`).
    *   **Database Interactions:** Execute SQL queries.
    *   **Utilities:** Database connection (`db.php`), Session management, Mailing.
    *   **Authentication:** `checkingUser.php`, `logout.php`.
*   **`assets/` (Static Resources):**
    *   `scripts/`: Frontend JavaScript logic (Fetch API calls, DOM manipulation).
    *   `styles/`: CSS stylesheets.
    *   `images/`: Static images.
*   **`db/`:** Database schema SQL dumps (e.g., `motocare (6).sql`).

## Setup & Running

This project is designed to run in a generic PHP/MySQL environment like XAMPP.

1.  **Database Setup:**
    *   Import `db/motocare (6).sql` into a MySQL database named `motocare`.
    *   Ensure `helper/db.php` has the correct credentials (default: `localhost`, user `root`, no password).

2.  **Server:**
    *   Place the project folder in `htdocs`.
    *   Start Apache and MySQL via XAMPP Control Panel.

3.  **Access:**
    *   Entry point: `http://localhost/MotoCare/html/index.php` (or `signin.php`).

## Development Conventions

*   **Authentication:** PHP Sessions. Protected pages start with session checks and role validation.
*   **API Communication:** Frontend uses `fetch()` to call `helper/*.php` scripts. Responses are typically JSON.
*   **Database Access:** Uses `mysqli` with prepared statements for security (SQL injection prevention). Connection is centrally managed in `helper/db.php`.
*   **Styling:** Bootstrap 5.3 is the primary framework. Custom styles are in `assets/styles/`.
*   **Maps:** Uses Leaflet.js (OpenStreetMap) instead of Google Maps API for cost/simplicity.

## Key Data Models

*   **User:** Stores all users (Admin, Owner, Staff, Customer). Distinguished by `role` column.
*   **Shop:** Details for each repair shop. Linked to an Owner (`owner_id`).
*   **Services:** Services offered by shops. Linked to `shop_id`.
*   **Booking:** Central table for appointments. Links User, Shop, and Services. Statuses: `Pending`, `Accepted`, `In Progress`, `Completed`, `Cancelled`, `Rejected`.

## Important Workflows

1.  **Shop Management (Admin):** Admin creates shops and assigns an "Owner" account.
2.  **Booking Flow (Customer):** Customer selects Shop -> Selects Services -> Picks Date/Time -> Enters Vehicle Details.
3.  **Booking Management (Staff/Owner):** Staff updates status (e.g., Pending -> Accepted -> In Progress -> Completed). Revenue is calculated upon completion.
