# MotoCare Website Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [User Roles and Access Control](#user-roles-and-access-control)
4. [Core Features and Functionality](#core-features-and-functionality)
5. [Database Structure](#database-structure)
6. [User Flows](#user-flows)
7. [Technical Implementation](#technical-implementation)
8. [API Endpoints](#api-endpoints)
9. [Security Measures](#security-measures)
10. [File Structure](#file-structure)
11. [Future Enhancements](#future-enhancements)

## Project Overview

MotoCare is a comprehensive web-based motorcycle/auto repair shop management system designed to connect customers with auto repair shops in Virac, Catanduanes. The platform aims to make vehicle maintenance and repair services more efficient, accessible, and transparent for both customers and shop owners.

### Key Objectives
- Provide a centralized platform for auto repair services
- Enable online booking and appointment scheduling
- Offer real-time tracking of repair progress
- Facilitate communication between customers and service providers
- Provide analytics and reporting for business owners

## System Architecture

The application follows a traditional three-tier architecture with a clear separation of concerns:

### Frontend Layer
- **Technologies**: HTML5, CSS3, JavaScript (ES6+)
- **UI Framework**: Bootstrap 5.3 for responsive design
- **Additional Libraries**: 
  - Font Awesome 6.4 for icons
  - Chart.js for data visualization
  - Google Maps integration for location services

### Backend Layer
- **Server-side Language**: PHP 8.2
- **Session Management**: PHP sessions for user authentication
- **API Design**: JSON-based APIs for dynamic content loading
- **Email Services**: PHPMailer integration for notifications

### Data Layer
- **Database**: MySQL/MariaDB
- **Database Design**: Relational model with proper indexing
- **Connection**: MySQLi for database operations

## User Roles and Access Control

The system implements a role-based access control (RBAC) model with four distinct user types:

### 1. Administrator
- **Access Level**: Full system access
- **Responsibilities**:
  - Overall system management
  - Shop oversight and approval
  - User management across all roles
  - System-wide analytics and reporting
- **Key Pages**: 
  - [`html/admin/dashboard.php`](html/admin/dashboard.php:1)
  - [`html/admin/shop-management.php`](html/admin/shop-management.php:1)

### 2. Shop Owner
- **Access Level**: Shop-specific management
- **Responsibilities**:
  - Manage shop information and services
  - Add/remove staff members
  - View shop performance metrics
  - Approve or reject booking requests
- **Key Pages**:
  - [`html/owner/dashboard.php`](html/owner/dashboard.php:1)
  - [`html/owner/services.php`](html/owner/services.php:1)
  - [`html/owner/user-management.php`](html/owner/user-management.php:1)

### 3. Staff Member
- **Access Level**: Operational access within assigned shop
- **Responsibilities**:
  - Manage day-to-day operations
  - Update booking statuses
  - View assigned tasks
  - Generate reports for their shop
- **Key Pages**:
  - [`html/staff/dashboard.php`](html/staff/dashboard.php:1)
  - [`html/staff/booking-service.php`](html/staff/booking-service.php:1)
  - [`html/staff/reports.php`](html/staff/reports.php:1)

### 4. Customer
- **Access Level**: Public access with authenticated features
- **Responsibilities**:
  - Browse shops and services
  - Book appointments
  - Track repair status
  - Manage personal information
- **Key Pages**:
  - [`html/customer/homepage.php`](html/customer/homepage.php:1)
  - [`html/customer/booking-list.php`](html/customer/booking-list.php:1)
  - [`html/customer/booking-status.php`](html/customer/booking-status.php:1)

### Access Control Implementation
The system uses PHP session-based authentication with role verification:

```php
// Example from helper/checkingUser.php
session_start();
if(empty($_SESSION['user'])){
    header("Location: /MotoCare/html/signin.php");
    exit();
}
```

Role-specific checks are implemented at the top of protected pages:

```php
// Example from html/admin/dashboard.php
if ($row['role'] !== 'admin') {
    header("Location: ../signin.php");
    exit();
}
```

## Core Features and Functionality

### 1. Shop Management System

#### Shop Registration and Information
- **Feature**: Shop owners can register their businesses
- **Implementation**: 
  - Form submission to [`helper/adminAddShop.php`](helper/adminAddShop.php:1)
  - Database storage in `shop` table
  - Image upload for shop branding
- **Data Collected**:
  - Shop name, address, contact information
  - Geographic coordinates (latitude/longitude)
  - Operating hours
  - Services offered

#### Service Management
- **Feature**: Create and manage service offerings
- **Implementation**:
  - CRUD operations via [`helper/adminAddService.php`](helper/adminAddService.php:1) and [`helper/adminEditServices.php`](helper/adminEditServices.php:1)
  - Service pricing with minimum and maximum cost ranges
- **Data Collected**:
  - Service name and description
  - Price range (min_cost, max_cost)
  - Associated shop
  - Icon representation

#### Staff Assignment
- **Feature**: Assign staff members to specific shops
- **Implementation**:
  - User creation with staff role
  - Association with shop via shop_id in user table
  - Management through [`helper/adminAddStaff.php`](helper/adminAddStaff.php:1)

### 2. Booking System

#### Appointment Scheduling
- **Feature**: Customers can book service appointments
- **Implementation**:
  - Multi-step booking process
  - Service selection with date/time picker
  - Vehicle information collection
  - Backend processing through [`helper/addBooking.php`](helper/addBooking.php:1)
- **Booking Process**:
  1. Shop selection
  2. Service(s) selection
  3. Date/time preference
  4. Vehicle details
  5. Confirmation with transaction number

#### Multi-Service Booking
- **Feature**: Single booking can include multiple services
- **Implementation**:
  - Service IDs stored as comma-separated string in `service_ids` field
  - Primary service ID stored in `service_id` for backward compatibility
  - Pricing calculated based on selected services

#### Status Tracking
- **Feature**: Real-time booking status updates
- **Status Options**:
  - Pending (initial state)
  - Not Accepted (awaiting shop approval)
  - Accepted/Confirmed
  - In Progress (repair ongoing)
  - Completed
  - Cancelled
  - Rejected
- **Implementation**:
  - Status updates via [`helper/staffUpdateStatus.php`](helper/staffUpdateStatus.php:1)
  - Progress visualization on customer dashboard
  - Email/SMS notifications for status changes

### 3. Customer Interface

#### Shop Discovery
- **Feature**: Browse and search for available shops
- **Implementation**:
  - Public shop listing at [`html/public/shops.php`](html/public/shops.php:1)
  - Detailed shop information at [`html/public/shop-infos.php`](html/public/shop-infos.php:1)
  - Shop cards with images, ratings, and services

#### Location-Based Services
- **Feature**: Find shops based on geographic location
- **Implementation**:
  - Geographic coordinates stored for each shop
  - Map integration potential via [`html/customer/map.php`](html/customer/map.php:1)
  - Distance calculation capabilities

#### User Account Management
- **Feature**: Customer profile and booking history
- **Implementation**:
  - Account creation and authentication
  - Profile information management
  - Historical booking data with status tracking

### 4. Analytics and Reporting

#### Admin Dashboard
- **Features**:
  - System-wide statistics
  - Shop performance metrics
  - User activity tracking
  - Revenue reporting
- **Implementation**:
  - Data aggregation via [`helper/adminGetDashboardStats.php`](helper/adminGetDashboardStats.php:1)
  - Visualization with Chart.js
  - Real-time statistics updates

#### Owner Dashboard
- **Features**:
  - Shop-specific performance metrics
  - Staff management overview
  - Service popularity statistics
  - Booking volume tracking
- **Implementation**:
  - Custom queries for shop-specific data
  - Monthly performance charts
  - Appointment status distribution

#### Staff Dashboard
- **Features**:
  - Daily appointment calendar
  - Task management
  - Booking status updates
  - Performance reporting
- **Implementation**:
  - Interactive calendar view
  - Booking status management
  - Daily/weekly reporting capabilities

### 5. Communication System

#### Email Notifications
- **Feature**: Automated email notifications
- **Implementation**:
  - PHPMailer integration at [`helper/mailer.php`](helper/mailer.php:1)
  - Transactional emails for booking confirmations
  - Status update notifications

#### SMS Notifications
- **Feature**: Text message alerts for important updates
- **Implementation**:
  - SMS gateway integration at [`helper/sendSms.php`](helper/sendSms.php:1)
  - Booking confirmations
  - Status change notifications

## Database Structure

The system uses a relational database model with four primary tables:

### 1. user Table
Stores user account information with role-based access.

**Columns**:
- `ID`: Primary key
- `email`: User email address (login identifier)
- `contact`: Phone number
- `address`: Physical address
- `password`: Hashed password
- `email_id`: Google OAuth identifier
- `fullname`: User's full name
- `picture`: Profile image URL
- `role`: User role (admin, owner, staff, customer)
- `username`: Username (optional)
- `status`: Account status (active, inactive)
- `shop_id`: Associated shop (for owners and staff)
- `createdAt`: Account creation date

### 2. shop Table
Contains information about registered repair shops.

**Columns**:
- `id`: Primary key
- `icon`: Font Awesome icon class
- `address`: Shop location
- `lg`: Longitude coordinate
- `lat`: Latitude coordinate
- `service_id`: Default service ID
- `rating`: Shop rating (1-10 scale)
- `hours`: Operating hours
- `name`: Shop name
- `owner_id`: Associated owner user ID

### 3. services Table
Lists all available services across shops.

**Columns**:
- `id`: Primary key
- `service_name`: Name of the service
- `min_cost`: Minimum service cost
- `max_cost`: Maximum service cost
- `createdAt`: Service creation date
- `shop_id`: Associated shop
- `description`: Service description
- `icon`: Font Awesome icon class

### 4. booking Table
Manages all booking transactions and appointments.

**Columns**:
- `id`: Primary key
- `user_id`: Customer user ID
- `shop`: Shop ID
- `preferred_time`: Appointment date
- `time`: Appointment time slot
- `status`: Booking status
- `repair_status`: Detailed repair progress
- `total_cost`: Final service cost
- `vehicle_name`: Customer's vehicle
- `createdAt`: Booking creation timestamp
- `service_id`: Primary service ID
- `service_ids`: All selected service IDs (comma-separated)
- `transaction_number`: Unique transaction identifier
- `notes`: Additional customer notes
- `vehicle_model`: Vehicle model
- `vehicle_plate_number`: License plate number

### Database Relationships
- One-to-Many: A shop can have multiple services
- One-to-Many: A shop can have multiple staff members
- One-to-Many: A customer can have multiple bookings
- Many-to-Many: Bookings can include multiple services

## User Flows

### 1. Customer Journey

#### Registration/Login
```
Landing Page → Sign In/Sign Up → Account Creation → Dashboard
```

#### Shop Discovery
```
Homepage → Shop Listing → Shop Details → Service Selection
```

#### Booking Process
```
Shop Selection → Service Selection → Date/Time Selection → 
Vehicle Information → Confirmation → Transaction Number
```

#### Status Tracking
```
Dashboard → Booking List → Booking Status → Real-time Updates
```

### 2. Shop Owner Journey

#### Shop Registration
```
Admin Approval → Shop Creation → Service Setup → Staff Assignment
```

#### Daily Operations
```
Dashboard → Booking Review → Status Updates → Performance Analytics
```

### 3. Staff Journey

#### Daily Tasks
```
Dashboard → Calendar View → Appointment Details → Status Updates
```

#### Reporting
```
Dashboard → Reports → Performance Metrics → Data Export
```

### 4. Admin Journey

#### System Management
```
Dashboard → User Management → Shop Approval → System Analytics
```

## Technical Implementation

### Frontend Implementation

#### Responsive Design
- **Approach**: Mobile-first with Bootstrap 5.3
- **Breakpoints**: Standard Bootstrap breakpoints (xs, sm, md, lg, xl, xxl)
- **Components**: Reusable Bootstrap components with custom styling

#### CSS Architecture
- **Variables**: Centralized in [`assets/styles/variables.css`](assets/styles/variables.css:1)
- **Organization**: Component-based CSS files
- **Theming**: CSS custom properties for consistent theming

#### JavaScript Functionality
- **DOM Manipulation**: Dynamic content loading
- **AJAX Calls**: API interactions without page refresh
- **Form Validation**: Client-side validation before submission
- **Chart Rendering**: Data visualization with Chart.js

### Backend Implementation

#### Session Management
- **Authentication**: PHP session-based authentication
- **Role Verification**: Server-side role checks on protected pages
- **Session Timeout**: Automatic logout after inactivity

#### API Design
- **Format**: JSON-based responses
- **Structure**: Consistent response format with status and data
- **Error Handling**: Standardized error messages and HTTP status codes

#### Database Operations
- **Connection**: Centralized in [`helper/db.php`](helper/db.php:1)
- **Queries**: Prepared statements to prevent SQL injection
- **Transactions**: Multi-query operations with transaction support

### File Upload Handling
- **Security**: File type validation and size restrictions
- **Storage**: Organized directory structure
- **Processing**: Image optimization and thumbnail generation

## API Endpoints

### User Management
- `helper/addUser.php` - Create new user accounts
- `helper/editAccount.php` - Update user information
- `helper/deleteAccount.php` - Remove user accounts
- `helper/checkingUser.php` - Verify user authentication
- `helper/logout.php` - Terminate user session

### Shop Management
- `helper/adminAddShop.php` - Create new shop
- `helper/adminUpdateShop.php` - Update shop information
- `helper/adminGetShops.php` - Retrieve shop listings
- `helper/getShopAndServices.php` - Get shop and service details

### Service Management
- `helper/adminAddService.php` - Add new service
- `helper/adminEditServices.php` - Update service information
- `helper/adminDeleteService.php` - Remove service
- `helper/adminGetServices.php` - Retrieve service listings

### Booking Management
- `helper/addBooking.php` - Create new booking
- `helper/updateBooking.php` - Modify existing booking
- `helper/acceptBooking.php` - Approve booking request
- `helper/rejectBooking.php` - Decline booking request
- `helper/cancelBooking.php` - Cancel booking
- `helper/bookingStatus.php` - Check booking status

### Analytics and Reporting
- `helper/adminGetDashboardStats.php` - Get system statistics
- `helper/adminGraph.php` - Retrieve chart data
- `helper/getReports.php` - Generate performance reports
- `helper/getMonthly.php` - Monthly data aggregation

### Communication
- `helper/mailer.php` - Send email notifications
- `helper/sendSms.php` - Send SMS notifications

### Session Management
- `helper/autoLogin.php` - Automatic authentication
- `helper/checkUserSession.php` - Verify active session
- `helper/checkOwner.php` - Verify owner role
- `helper/checkStaff.php` - Verify staff role

## Security Measures

### Authentication Security
- **Password Hashing**: Secure password storage
- **Session Management**: Secure session handling
- **Role Verification**: Server-side role checks
- **Session Timeout**: Automatic logout after inactivity

### Input Validation
- **Form Validation**: Client and server-side validation
- **SQL Injection Prevention**: Prepared statements
- **XSS Prevention**: Output sanitization
- **File Upload Security**: Type and size validation

### Access Control
- **Role-Based Access**: Page-level access control
- **Session Verification**: Protected page checks
- **Direct Access Prevention**: Authentication checks

### Data Protection
- **Sensitive Data**: Secure storage of personal information
- **Error Handling**: Generic error messages to prevent information leakage
- **HTTPS**: Secure data transmission (recommended)

## File Structure

```
MotoCare/
├── html/                    # Frontend pages
│   ├── admin/             # Admin-specific pages
│   ├── customer/          # Customer-specific pages
│   ├── owner/             # Owner-specific pages
│   ├── public/            # Publicly accessible pages
│   └── staff/             # Staff-specific pages
├── helper/                # Backend utilities and APIs
│   ├── PHPMailer/         # Email functionality
│   ├── db.php            # Database connection
│   └── [API endpoints]   # Various API endpoints
├── assets/                # Frontend assets
│   ├── images/            # Images and media
│   ├── scripts/           # JavaScript files
│   └── styles/            # CSS files
├── db/                    # Database files
│   └── motocare (6).sql   # Database schema
└── [Root files]           # Project documentation
```

### Key Files by Function

#### Authentication
- [`html/signin.php`](html/signin.php:1) - Login page
- [`html/signup.php`](html/signup.php:1) - Registration page
- [`helper/checkingUser.php`](helper/checkingUser.php:1) - User verification
- [`helper/logout.php`](helper/logout.php:1) - Logout functionality

#### Dashboard
- [`html/admin/dashboard.php`](html/admin/dashboard.php:1) - Admin dashboard
- [`html/owner/dashboard.php`](html/owner/dashboard.php:1) - Owner dashboard
- [`html/staff/dashboard.php`](html/staff/dashboard.php:1) - Staff dashboard
- [`html/customer/homepage.php`](html/customer/homepage.php:1) - Customer homepage

#### Booking Management
- [`html/customer/booking-status.php`](html/customer/booking-status.php:1) - Booking status tracking
- [`html/staff/booking-service.php`](html/staff/booking-service.php:1) - Booking management
- [`helper/addBooking.php`](helper/addBooking.php:1) - Booking creation
- [`helper/updateBooking.php`](helper/updateBooking.php:1) - Booking updates

#### Shop Management
- [`html/admin/shop-management.php`](html/admin/shop-management.php:1) - Shop administration
- [`html/owner/services.php`](html/owner/services.php:1) - Service management
- [`html/public/shop-infos.php`](html/public/shop-infos.php:1) - Public shop information

#### Analytics
- [`helper/adminGetDashboardStats.php`](helper/adminGetDashboardStats.php:1) - Statistics retrieval
- [`helper/adminGraph.php`](helper/adminGraph.php:1) - Chart data
- [`helper/getReports.php`](helper/getReports.php:1) - Report generation

## Future Enhancements

### Technical Improvements
1. **Framework Migration**: Consider migrating to a modern PHP framework (Laravel, Symfony)
2. **API Standardization**: Implement REST API standards with consistent response formats
3. **Testing Framework**: Add unit and integration tests
4. **Caching Implementation**: Improve performance with data caching
5. **Frontend Framework**: Consider React/Vue for dynamic frontend components

### Feature Enhancements
1. **Mobile Application**: Native mobile apps for iOS and Android
2. **Payment Integration**: Online payment processing
3. **Advanced Analytics**: Machine learning for business insights
4. **Review System**: Customer feedback and rating system
5. **Inventory Management**: Parts and supplies tracking
6. **Appointment Reminders**: Automated notification system

### Security Enhancements
1. **Two-Factor Authentication**: Additional security layer
2. **API Rate Limiting**: Prevent abuse of API endpoints
3. **Audit Logging**: Track system changes and user actions
4. **Data Encryption**: Enhanced data protection measures
5. **Security Headers**: Implement security HTTP headers

### User Experience Improvements
1. **Real-time Updates**: WebSocket implementation for live updates
2. **Advanced Search**: Filter and search capabilities
3. **Multi-language Support**: Internationalization features
4. **Accessibility Improvements**: WCAG compliance
5. **Progressive Web App**: Offline functionality

This documentation provides a comprehensive overview of the MotoCare website's logic, features, and technical implementation. It serves as a guide for developers, administrators, and stakeholders to understand the system's architecture and functionality.