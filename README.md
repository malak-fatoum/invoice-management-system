# Invoice Management System

A web-based invoice management system developed as a **client project** to streamline invoice creation, customer management, shipment information, and invoice storage.

## Overview

This system was developed to replace manual invoice workflows and provide a centralized platform for managing customers, invoices, shipment details, users, and invoice-related information.

The project was built with a focus on usability, data organization, authentication, and role-based access control.

## Features

* 🔐 User authentication and session protection
* 👥 User management
* 🔑 Role-based permissions
* 🧑‍💼 Customer management
* 🧾 Invoice creation and management
* 📦 Shipment information management
* 🛒 Invoice items and calculations
* 🏦 Bank information
* 📋 Invoice preview
* 🖨️ Invoice printing
* 📄 PDF invoice generation
* 🔎 Saved invoice management
* 📊 Dashboard
* 🌐 Arabic interface
* 🗄️ MySQL database integration

## User Roles

The system includes role-based access control with different permissions for system users, including:

* **Admin**
* **Accountant**

Permissions are applied across the system to control access to sensitive operations such as editing, deleting, and managing users.

## Technologies

* **PHP**
* **MySQL**
* **HTML5**
* **CSS3**
* **JavaScript**
* **FPDF**
* **Composer**
* **XAMPP**

## Project Structure

```text
invoice-management-system/
│
├── api/
├── assets/
├── config/
├── css/
├── fpdf/
├── js/
├── pages/
├── composer.json
├── composer.lock
├── .gitignore
└── README.md
```

## Database

The application uses **MySQL** for storing system data, including:

* Users
* Customers
* Invoices
* Invoice items
* Shipment information
* Additional invoice information

For security and privacy reasons, real client data and local database configuration are not included in this repository.

## Local Setup

### 1. Requirements

Install:

* XAMPP
* PHP
* MySQL
* Composer

### 2. Clone the repository

```bash
git clone https://github.com/malak-fatoum/invoice-management-system.git
```

### 3. Move the project

Place the project inside:

```text
xampp/htdocs/
```

### 4. Configure the database

Create a MySQL database and configure the local database connection using the provided example configuration.

Rename:

```text
config/config.example.php
```

to:

```text
config/config.php
```

Then update the database settings according to your local environment.

### 5. Start XAMPP

Start:

* Apache
* MySQL

### 6. Open the application

```text
http://localhost/invoice-management-system/
```

## Security & Privacy

This repository is a **portfolio-safe version** of a client project.

Real client information, production credentials, passwords, and private database data are intentionally excluded from the repository.

## Project Type

**Client Project — Invoice Management System**

Developed as a customized business solution for managing invoices and related operations.

## Author

**Malak Fatoum**

GitHub:
https://github.com/malak-fatoum
