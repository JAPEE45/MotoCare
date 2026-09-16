# 🏍️ MotoCare - Motorcycle Repair & Service Booking Platform

> **Connecting motorcycle riders with trusted repair shops through interactive mapping, seamless online booking, and end-to-end workshop management.**

---

## 📖 Table of Contents
- [Why Use This App? (User Benefits)](#-why-use-this-app-user-benefits)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [System Architecture & Roles](#-system-architecture--roles)
- [How to Use the System (Workflow)](#-how-to-use-the-system-workflow)
- [Installation / Setup Instructions](#-installation--setup-instructions)
- [Project Directory Structure](#-project-directory-structure)
- [License](#-license)

---

## 💡 Why Use This App? (User Benefits)

* **Target Users:** Motorcycle riders, vehicle owners, independent repair shop owners, and workshop mechanics.
* **Main Problem Solved:** Motorcycle owners often struggle with unexpected breakdowns, locating reputable repair shops nearby, long walk-in queues, and lack of transparency regarding service availability and pricing. Simultaneously, small and medium repair shops lack digital tools to manage customer appointments, staff assignments, and revenue streams.

### 🌟 Key Value Propositions:
* **For Riders & Customers:** Effortlessly locate verified nearby repair shops on an interactive map, review available services and upfront pricing, schedule appointments in seconds, and track their repair status in real-time.
* **For Shop Owners & Mechanics:** Digitize manual booking logs, eliminate scheduling conflicts, allocate tasks to staff, track earnings, and improve customer retention through automated email/SMS status updates.

---

## ⚡ Key Features

* 🗺️ **Interactive Geolocation & Shop Locator**  
  Explore nearby accredited motorcycle repair shops on a live map powered by Leaflet.js and OpenStreetMap. View shop details, coordinates, addresses, and available services at a glance.

* 📅 **Seamless Service Booking & Scheduling**  
  Select customized repair packages (e.g., tune-ups, tire replacement, oil changes, engine overhaul), specify appointment dates/times, and submit bookings with immediate confirmation tracking.

* 🔄 **Real-Time Booking Status Pipeline**  
  Stay updated at every stage of repair with dynamic status flags: Pending, Accepted, In Progress, Completed, or Cancelled.

* 👥 **Role-Based Access Control (RBAC)**  
  Tailored, secure dashboards for four distinct user roles:
  * **Customer:** Search, book, manage vehicles, and view service history.
  * **Staff / Mechanic:** Manage workshop queue, update repair stages, and generate daily reports.
  * **Shop Owner:** Oversee services catalog, staff personnel, business revenue, and shop profile.
  * **Super Admin:** Platform oversight, verify/onboard repair shops, and monitor system-wide analytics.

* 📊 **Insightful Business Dashboards & Reports**  
  Visual metrics showcasing shop revenue, completed bookings, customer growth, and service volume.

* 📩 **Automated Notifications**  
  Notification pipeline powered by PHPMailer and SMS integration to alert users on appointment changes.

---

## 🛠️ Tech Stack

| Category | Technology / Library |
|---|---|
| **Frontend** | HTML5, CSS3, JavaScript (ES6+), Bootstrap 5.3, FontAwesome 6 |
| **Mapping & GIS** | [Leaflet.js](https://leafletjs.com/) (OpenStreetMap API) |
| **Backend** | PHP 7.4+ / 8.x (RESTful Helper APIs & Session Auth) |
| **Database** | MySQL / MariaDB (Relational schema with foreign keys and transactions) |
| **Utilities & Mail** | PHPMailer (SMTP Notifications), SMS Gateway API |
| **Server / Environment** | Apache HTTP Server (via XAMPP / WAMP / LAMP) |
| **Version Control** | Git & GitHub |

---

## 🔐 System Architecture & Roles

`mermaid
flowchart TD
    A[MotoCare Platform] --> B[Customer Portal]
    A --> C[Staff Portal]
    A --> D[Shop Owner Portal]
    A --> E[Super Admin Portal]

    B -->|Find & Book| F[(MySQL Database)]
    C -->|Update Status| F
    D -->|Manage Services & Staff| F
    E -->|Manage Shops & System| F
`

---

## 🚀 How to Use the System (Workflow)

### 1. Customer Journey
1. **Explore & Discover:** Visit the homepage and navigate to **Find Location** to view the interactive map of motorcycle shops.
2. **Select Shop & Services:** Click on a repair shop pin to view shop ratings, distance, and available services.
3. **Register / Sign In:** Log into your customer account or sign up with contact and vehicle details.
4. **Book Appointment:** Choose the required service, select preferred date and time, add repair notes, and submit.
5. **Track Progress:** Monitor real-time status changes in **Booking Status** as mechanics accept and complete the service.

### 2. Staff / Mechanic Journey
1. **Login:** Authenticate via the staff portal (staff credentials or assigned staff account).
2. **Queue Management:** View incoming repair bookings on the **Staff Dashboard**.
3. **Service Fulfillment:** Accept or reschedule bookings, update status (In Progress -> Completed), and log completion notes.

### 3. Shop Owner Journey
1. **Access Owner Dashboard:** Log into the owner account linked to the shop.
2. **Catalog & Staff Management:** Add, update, or remove repair services with transparent pricing; create and assign staff accounts.
3. **Financial Overview:** View total appointments served, daily/monthly revenue metrics, and customer reports.

### 4. Admin Journey
1. **System Administration:** Log into the Admin Panel (/html/admin/dashboard.php).
2. **Shop Verification:** Add and approve new motorcycle repair shops, assign coordinates on the map, and designate shop owners.
3. **Platform Oversight:** Monitor system-wide transactions, active shops, and user activity.

---

## 💻 Installation / Setup Instructions

Follow these step-by-step instructions to get a local development instance running on your machine:

### 1. Prerequisites
* **XAMPP** (recommended) or any local server stack with **PHP 7.4+** and **MySQL / MariaDB**.
* **Git** installed on your system.
* Web browser (Chrome, Firefox, Edge).

### 2. Clone the Repository
Clone the repository directly into your web server's root directory (htdocs for XAMPP):

`ash
# Navigate to your server htdocs folder
cd C:/xampp/htdocs/

# Clone the repository
git clone https://github.com/JAPEE45/MotoCare.git MotoCare
`

### 3. Database Configuration
1. Start **Apache** and **MySQL** from your XAMPP Control Panel.
2. Open your browser and go to **phpMyAdmin**: http://localhost/phpmyadmin/.
3. Create a new database named:
   `sql
   motocare
   `
4. Click on the motocare database, go to the **Import** tab, and import the SQL schema file located at:
   `	ext
   db/motocare (6).sql
   `
5. *(Optional)* If you need the latest revenue fields, import db/revenue_update.sql.

### 4. Verify Database Connection
Check helper/db.php and verify that your database credentials match your local setup:
`php
System.Management.Automation.Internal.Host.InternalHost =  localhost;      
 = root;          
 = ; 
 = motocare; 
`

### 5. Initialize Administrator Account
Open your browser and navigate to the one-time admin setup script:
` ext
http://localhost/MotoCare/helper/createAdminAccount.php
`
* **Default Admin Email:** dmin@motocare.com
* **Default Admin Password:** dmin123
*(For security, remove or restrict access to createAdminAccount.php after initial use).*

### 6. Run the Application
Open your web browser and access the platform:
` ext
http://localhost/MotoCare/html/index.php
`

---

## 📂 Project Directory Structure

` ext
MotoCare/
├── assets/
│ ├── images/ # Icons, banners, and asset graphics
│ ├── scripts/ # Client-side JavaScript (map, booking, admin logic)
│ └── styles/ # Custom stylesheets and Bootstrap overrides
├── db/
│ ├── motocare (6).sql # Core database schema and sample data
│ └── revenue_update.sql # Supplemental database migrations
├── helper/
│ ├── PHPMailer/ # Mailing library for email notifications
│ ├── db.php # Database connection configuration
│ ├── addBooking.php # Booking processing API
│ ├── adminGetShops.php # Admin shop data API
│ ├── sendSms.php # SMS dispatch gateway
│ └── ... # Role-based backend helper scripts
├── html/
│ ├── admin/ # Administrator dashboard & shop management
│ ├── customer/ # Customer map, booking list, and status
│ ├── owner/ # Shop owner dashboard, staff & service management
│ ├── staff/ # Staff mechanic queue & repair status tracking
│ ├── index.php # Public landing page
│ ├── signin.php # User authentication
│ └── signup.php # Customer registration
└── README.md # Project documentation
`

---

