# 🩸 LifeBlood – Blood Bank Management System

**LifeBlood** is a Laravel-based Blood Bank Management System designed to manage blood donations, donor profiles, blood inventory, hospital registrations, blood requests, and donation appointments through a centralized platform.

The project aims to simplify blood bank operations, improve inventory tracking, and organize communication between donors, hospitals, and administrators.

---

## 📌 Table of Contents

* [Project Overview](#-project-overview)
* [Key Features](#-key-features)
* [Technology Stack](#-technology-stack)
* [User Roles](#-user-roles)
* [Project Structure](#-project-structure)
* [Database Schema](#-database-schema)
* [Installation Guide](#-installation-guide)
* [Environment Configuration](#-environment-configuration)
* [Application Routes](#-application-routes)
* [Development Status](#-development-status)
* [Future Improvements](#-future-improvements)
* [Troubleshooting](#-troubleshooting)
* [Security Considerations](#-security-considerations)
* [Author](#-author)
* [License](#-license)

---

## 🎯 Project Overview

LifeBlood is a blood bank management application developed to organize essential blood donation and inventory management activities.

The system is designed to support administrators, blood donors, and hospitals by providing dedicated modules for managing donor information, blood stock, donation records, hospital details, blood requests, and appointments.

### Project Objectives

* Maintain organized donor profiles and donation records.
* Monitor blood availability and inventory expiry dates.
* Manage hospital registrations and approval statuses.
* Track blood requests based on urgency and fulfillment status.
* Schedule and manage donor appointments.
* Provide a structured foundation for future reporting and analytics.

---

## ✨ Key Features

### 🩸 Blood Donation Management

* Create, view, update, and delete donation records.
* Record blood groups, donation dates, and quantities.
* Track health screening and donation approval status.
* Generate blood group-based donation reports.

### 📦 Blood Inventory Management

* Manage blood stock by blood group.
* Track available, reserved, and expired inventory.
* Record storage locations and expiry dates.
* Generate inventory stock reports.

### 👤 Donor Profile Management

* Maintain donor information and contact details.
* Store blood group, address, and emergency contact.
* Track eligibility and previous donation dates.
* Organize donor-related information.

### 🏥 Hospital Management

* Maintain hospital information and registration details.
* Track pending, approved, and suspended hospital statuses.
* Support hospital approval and suspension workflows.
* Generate hospital statistics.

### 🚑 Blood Request Management

* Create and manage blood requests.
* Record patient information and required blood groups.
* Categorize requests by normal, urgent, and critical priority.
* Track pending, approved, fulfilled, rejected, and cancelled requests.
* Filter urgent requests and generate request reports.

### 📅 Appointment Management

* Schedule blood donation appointments.
* Track upcoming appointments.
* Update appointment status.
* Cancel or complete scheduled appointments.

### 🔐 Authentication & Access Control

* User registration and login.
* Session-based authentication.
* Role-based access control for administrators, donors, and hospitals.
* Middleware-based route protection.

### 📊 Reporting & Administration

* Blood group donation reports.
* Inventory stock reports.
* Blood request statistics.
* Hospital statistics.
* Upcoming appointment tracking.

---

## 🛠️ Technology Stack

| Technology         | Purpose                          |
| ------------------ | -------------------------------- |
| PHP 8.2+           | Backend programming              |
| Laravel 12         | Web application framework        |
| MySQL / SQLite     | Database management              |
| Eloquent ORM       | Database interaction             |
| Blade Templates    | Server-side rendering            |
| Bootstrap 5        | Responsive user interface        |
| HTML5 & CSS3       | Frontend structure and styling   |
| JavaScript         | Client-side interactions         |
| Laravel Middleware | Authentication and authorization |
| Composer           | PHP dependency management        |
| Git & GitHub       | Version control                  |

---

## 👥 User Roles

### 1. Administrator

* Manage system records.
* Review hospital registration requests.
* Approve or suspend hospitals.
* Update blood request statuses.
* Access reports and administrative operations.

### 2. Donor

* Maintain donor profile information.
* View donation history.
* Schedule donation appointments.
* Track upcoming appointments.

### 3. Hospital

* Submit blood requests.
* Track request statuses.
* View relevant inventory information.
* Manage hospital-related requests.

> **Note:** Actual permissions depend on the middleware, route definitions, and controller authorization implemented in the project.

---

## 📁 Project Structure

```text
LifeBlood_BloodBank_Professional_v2/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── BloodDonationController.php
│   │   │   ├── BloodInventoryController.php
│   │   │   ├── DonorProfileController.php
│   │   │   ├── BloodRequestCRUDController.php
│   │   │   ├── HospitalCRUDController.php
│   │   │   └── AppointmentCRUDController.php
│   │   └── Middleware/
│   │       ├── RoleMiddleware.php
│   │       ├── AdminMiddleware.php
│   │       ├── DonorMiddleware.php
│   │       └── HospitalMiddleware.php
│   │
│   └── Models/
│       ├── User.php
│       ├── DonorProfile.php
│       ├── Hospital.php
│       ├── BloodDonation.php
│       ├── BloodInventory.php
│       ├── BloodRequest.php
│       └── Appointment.php
│
├── bootstrap/
│   └── app.php
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   └── database.php
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── database.sqlite
│
├── public/
│   └── index.php
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── auth/
│   │   ├── donations/
│   │   ├── inventory/
│   │   ├── donors/
│   │   ├── requests/
│   │   ├── hospitals/
│   │   ├── appointments/
│   │   ├── dashboard.blade.php
│   │   └── home.blade.php
│   ├── css/
│   └── js/
│
├── routes/
│   ├── web.php
│   ├── api.php
│   └── console.php
│
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── composer.lock
└── README.md
```

*The structure above represents the intended project organization. Some directories or files may differ in the current repository.*

---

## 🗄️ Database Schema

The core application is designed around seven primary database tables.

| Table             | Description                                        |
| ----------------- | -------------------------------------------------- |
| `users`           | User accounts, contact details, and roles          |
| `donor_profiles`  | Donor information, blood groups, and eligibility   |
| `hospitals`       | Hospital registration and approval details         |
| `blood_donations` | Donation records and screening statuses            |
| `blood_inventory` | Blood stock, quantities, and expiry dates          |
| `blood_requests`  | Hospital requests, urgency, and fulfillment status |
| `appointments`    | Donor appointment scheduling and status            |

### Main Relationships

* A user can have an associated donor profile.
* A user can be associated with hospital information.
* Donation records reference donor users.
* Blood requests reference hospitals.
* Appointment records reference donor users.
* Inventory records maintain blood group and stock information.

These relationships help maintain organized and consistent application data.

---

## ⚙️ Installation Guide

Follow these steps to run LifeBlood locally on your development machine.

### Prerequisites

Install the following software before proceeding:

* PHP 8.2 or later
* Composer
* MySQL or SQLite
* Git
* A compatible web browser

### Step 1: Clone the Repository

```bash
git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
```

Navigate to the project directory:

```bash
cd YOUR_REPOSITORY
```

Replace `YOUR_USERNAME/YOUR_REPOSITORY` with your actual GitHub repository path.

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Configure Environment Variables

Copy the example environment file.

**Windows PowerShell:**

```powershell
Copy-Item .env.example .env
```

**Linux / macOS:**

```bash
cp .env.example .env
```

### Step 4: Generate the Application Key

```bash
php artisan key:generate
```

### Step 5: Configure the Database

For MySQL, create a database named `lifeblood_laravel` using phpMyAdmin or your MySQL client.

Update your `.env` file with the appropriate database credentials.

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lifeblood_laravel
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

For SQLite, configure the SQLite database connection and ensure the database file exists.

### Step 6: Run Database Migrations

```bash
php artisan migrate
```

> **Important:** If your database already contains tables or production data, back it up and inspect the existing migration status before running migrations. Do not delete or recreate an existing database without a backup.

### Step 7: Clear Application Caches

```bash
php artisan optimize:clear
```

### Step 8: Start the Development Server

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### Step 9: Open the Application

Visit the following address in your browser:

```text
http://127.0.0.1:8000
```

---

## 🔧 Environment Configuration

Example configuration for local development:

```dotenv
APP_NAME=LifeBlood
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

SESSION_DRIVER=file
CACHE_STORE=file
```

Configure database credentials separately according to your chosen database engine.

**Production recommendations:**

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
```

Never commit your real `.env` file, database passwords, application secrets, or production credentials to a public repository.

---

## 🛣️ Application Routes

The application uses Laravel web routing for its main modules.

### Authentication Routes

| Method | Endpoint    | Purpose              |
| ------ | ----------- | -------------------- |
| GET    | `/`         | Home page            |
| GET    | `/login`    | Login page           |
| POST   | `/login`    | Process login        |
| GET    | `/register` | Registration page    |
| POST   | `/register` | Process registration |
| POST   | `/logout`   | Logout               |

### Resource Modules

| Endpoint        | Module                     |
| --------------- | -------------------------- |
| `/donations`    | Blood donation management  |
| `/inventory`    | Blood inventory management |
| `/donors`       | Donor profile management   |
| `/requests`     | Blood request management   |
| `/hospitals`    | Hospital management        |
| `/appointments` | Appointment management     |

### Reporting & Special Actions

| Endpoint                        | Purpose                        |
| ------------------------------- | ------------------------------ |
| `/donations/blood-group-report` | Donation report by blood group |
| `/inventory/stock-report`       | Inventory stock report         |
| `/requests/urgent`              | Urgent blood requests          |
| `/requests/report`              | Blood request statistics       |
| `/hospitals/statistics`         | Hospital statistics            |
| `/appointments/upcoming`        | Upcoming appointments          |

Additional routes support appointment completion, appointment cancellation, hospital approval, hospital suspension, and request status updates.

> Refer to `routes/web.php` for the actual route definitions and HTTP methods configured in your current version.

---

## 📈 Development Status

### Backend

* [x] Laravel application structure
* [x] Eloquent models for core resources
* [x] Controllers for core management modules
* [x] Database schema and relationships
* [x] Web route configuration
* [x] Authentication controller
* [x] Role-based middleware
* [x] CRUD-oriented resource controllers
* [x] Reporting and special-action methods

### Frontend

* [ ] Complete Blade templates for all modules
* [ ] Consistent Bootstrap 5 interface
* [ ] Form validation feedback
* [ ] Delete confirmation dialogs
* [ ] Flash messages and notifications
* [ ] Responsive navigation and dashboard

### Advanced Features

* [ ] Dashboard statistics and analytics
* [ ] PDF report generation
* [ ] Email notifications
* [ ] SMS notifications for urgent requests
* [ ] Blood compatibility information
* [ ] Advanced search and filtering

### Testing & Deployment

* [ ] Automated unit and feature tests
* [ ] Authorization and security testing
* [ ] Performance optimization
* [ ] Production deployment verification

*Status reflects the development plan described for the project; completion should be verified against the current codebase.*

---

## 🚀 Future Improvements

Potential improvements for future versions include:

* Advanced blood inventory monitoring.
* Automated expiry notifications.
* Improved donor search and filtering.
* Hospital request fulfillment workflows.
* Downloadable PDF and Excel reports.
* Email notifications for appointment updates.
* Dashboard charts and analytics.
* Automated testing and continuous integration.
* Production deployment with HTTPS and secure environment configuration.

---

## 🐛 Troubleshooting

### 1. Database Connection Error

Check the database name, username, password, host, and port in `.env`.

Then run:

```bash
php artisan config:clear
```

### 2. Missing Application Key

Generate an application key:

```bash
php artisan key:generate
```

### 3. Missing Database Tables

Check migration status:

```bash
php artisan migrate:status
```

If migrations are pending, inspect the database and migration files before running:

```bash
php artisan migrate
```

### 4. Session or Cache Table Errors

For local development, check the configured session and cache drivers in `.env`. File-based drivers can be used when appropriate.

Clear cached configuration:

```bash
php artisan optimize:clear
```

### 5. Route Configuration Problems

List the registered routes:

```bash
php artisan route:list
```

Review `routes/web.php` and confirm that the relevant controllers and middleware are correctly configured.

---

## 👨‍💻 Author

**Simi Chakma**
Software Developer | Laravel & Django Developer

* GitHub: [@simichakma](https://github.com/simichakma)
* Project: LifeBlood – Blood Bank Management System

---

## 📚 Resources

* [Laravel Documentation](https://laravel.com/docs/12.x)
* [Laravel Eloquent ORM](https://laravel.com/docs/12.x/eloquent)
* [Laravel Routing](https://laravel.com/docs/12.x/routing)
* [Laravel Blade Templates](https://laravel.com/docs/12.x/blade)
* [Bootstrap Documentation](https://getbootstrap.com/docs/5.3/getting-started/introduction/)

---

