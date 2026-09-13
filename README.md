# MediBook

MediBook is a full-stack hospital appointment booking system built using
PHP and MySQL. It allows patients to find doctors and book appointments,
doctors to manage appointments and availability, and administrators to
manage doctors and users.

## Features

### Patient

-   Patient registration and login
-   Search doctors by name or specialization
-   View doctor information
-   Book appointments
-   View appointment history
-   Cancel appointments
-   Track appointment status

### Doctor

-   Doctor login
-   View appointment requests
-   Confirm or reject appointments
-   Mark appointments as completed
-   Manage availability

### Admin

-   Admin dashboard
-   Add, edit, and delete doctors
-   View registered users
-   View system statistics

## Tech Stack

-   Frontend: HTML5, CSS3, JavaScript
-   Backend: PHP
-   Database: MySQL / MariaDB
-   Web Server: Apache
-   Development Environment: XAMPP
-   Deployment: Linux VPS
-   Reverse Proxy: Nginx

## Database

The application uses a relational database consisting of:

``` text
users
patients
doctors
doctor_availability
appointments
```

A composite unique constraint is used in the `appointments` table to
prevent multiple patients from booking the same doctor, date, and time.

## Project Structure

``` text
medibook/
├── admin/
├── config/
├── css/
├── doctor/
├── includes/
├── js/
├── patient/
├── index.php
├── login.php
├── register.php
├── logout.php
├── database.sql
└── seed_admin.php
```

## Installation

### 1. Clone the repository

``` bash
git clone https://github.com/YOUR_USERNAME/medibook.git
cd medibook
```

### 2. Copy to XAMPP

For Linux:

``` bash
sudo cp -r . /opt/lampp/htdocs/medibook
```

For Windows:

``` text
C:\xampp\htdocs\medibook
```

### 3. Create the database

Open phpMyAdmin and import `database.sql`.

Alternatively:

``` bash
mysql -u root -p < database.sql
```

### 4. Configure the database

Edit `config/database.php`:

``` php
$host = "localhost";
$username = "root";
$password = "";
$database = "medibook";
```

Use the credentials configured on your system.

### 5. Start the application

Open:

``` text
http://localhost/medibook/
```

## Authentication

The application uses PHP sessions for authentication and role-based
access control.

Supported roles:

``` text
PATIENT
DOCTOR
ADMIN
```

Passwords are stored using PHP's `password_hash()` function and verified
using `password_verify()`.

## Security

The project implements:

-   Password hashing
-   PHP session-based authentication
-   Role-based authorization
-   Prepared SQL statements
-   Server-side input validation
-   Database constraints for preventing duplicate appointments

## Deployment Architecture

The production deployment uses Nginx as a reverse proxy:

``` text
Internet
   |
   v
Nginx
   |
   v
Apache / XAMPP
   |
   v
PHP Application
   |
   v
MySQL / MariaDB
```

## Future Improvements

-   Doctor-specific appointment time slots
-   Email and SMS notifications
-   Online payment integration
-   Patient medical records
-   Appointment reminders
-   REST API
-   Advanced admin analytics
-   Production security hardening

## Author

**Your Name**

MediBook was developed as a full-stack web application demonstrating
PHP, MySQL, authentication, CRUD operations, database relationships,
appointment management, and Linux server deployment.
