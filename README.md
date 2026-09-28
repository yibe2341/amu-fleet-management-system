# AMU Fleet Management System

A web-based fleet management system developed as a final-year Computer Science project for **Arba Minch University (AMU)**.

The system provides a centralized web application for managing university vehicles, drivers, schedules, permissions, maintenance requests, users, and other fleet-related activities.

## 📌 Project Overview

The **AMU Fleet Management System** was developed to support the management of university transportation and fleet-related operations through a database-driven web application.

The system includes different interfaces and functions for administrators, managers, drivers, mechanics, police personnel, and regular users.

## ✨ Key Features

* User registration and authentication
* Administrator management
* Vehicle registration and management
* Vehicle information viewing and searching
* Driver management
* Vehicle scheduling
* Vehicle permission and exit requests
* Maintenance request management
* Manager functions
* Mechanic functions
* Police-related scheduling functions
* User password management
* Internal messaging and communication
* File upload and download functionality
* Fleet reports
* University information pages
* Database-driven vehicle records

## 👥 System Roles

The application contains functionality for different types of users, including:

* **Administrator**
* **Manager**
* **Driver**
* **Mechanic**
* **Police**
* **Regular User**

## 🛠️ Technologies Used

| Technology      | Purpose                             |
| --------------- | ----------------------------------- |
| PHP             | Server-side application development |
| MySQL / MariaDB | Database management                 |
| HTML5           | Web page structure                  |
| CSS3            | User interface styling              |
| JavaScript      | Client-side functionality           |
| XAMPP           | Local development environment       |
| phpMyAdmin      | Database administration             |

## 📂 Project Structure

The main application is contained in:

```text
fleet_management/
```

Important components include:

```text
fleet_management/
├── config.example.php
├── fleet (3).sql
├── index.html
├── login.html
├── admin.php
├── manager.php
├── driver.php
├── mechanic.php
├── police.php
├── user.php
├── scheduler.php
├── vehicle-register.php
├── viewvehicles.php
├── requestmaintenance.php
├── report.php
└── ...
```

## 🚀 Running the Project Locally

### Requirements

* XAMPP
* Apache
* MySQL or MariaDB
* A modern web browser

### Installation

1. Clone the repository:

```bash
git clone https://github.com/yibe2341/amu-fleet-management-system.git
```

2. Copy the `fleet_management` folder into the XAMPP `htdocs` directory.

3. Start **Apache** and **MySQL** from the XAMPP Control Panel.

4. Open **phpMyAdmin**.

5. Create a database named:

```text
fleet
```

6. Import the database file:

```text
fleet (3).sql
```

7. Copy `config.example.php` and rename the copy to:

```text
config.php
```

8. Update the database credentials in `config.php` according to your local MySQL configuration.

9. Open the application in your browser:

```text
http://localhost/fleet_management/
```

## 🔐 Configuration and Security

The actual `config.php` file is intentionally excluded from this public repository because it may contain local database credentials.

Use `config.example.php` as the configuration template when setting up the project locally.

**Do not commit passwords or other sensitive credentials to the repository.**

## 🎓 Academic Project

**Project:** AMU Fleet Management System
**Institution:** Arba Minch University
**Field:** Computer Science
**Project Type:** Final-Year Project
**Development Environment:** XAMPP

## 👨‍💻 Developer

**Yibeltal Kibemo**

Computer Science Graduate
Arba Minch University

### Project Focus

This project demonstrates practical experience in:

* Web application development
* PHP programming
* Database management
* MySQL/MariaDB
* Authentication and user management
* Fleet and vehicle information systems
* System design
* Problem solving

---

**Note:** This project was developed for academic purposes as a university final-year project.
