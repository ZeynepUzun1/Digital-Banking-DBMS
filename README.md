# 🏦 Digital Banking & Support System

A role-based banking simulation platform that enables users to perform financial operations and submit support requests, while administrators manage and review tickets.

---

## Project Overview

This project implements a banking workflow with two interfaces:

### 👤 User Panel

Users can:

* Add and manage bank cards
* Pay bills securely
* Submit help/support requests
* Create support tickets

### 🛠 Admin Panel

Administrators can:

* View submitted support tickets
* Monitor help requests
* Manage customer issues

---

## System Architecture

```
                  Web Browser
                       ↓
                PHP Application Layer
                       ↓
         ┌───────────────┬───────────────┐
         │     MySQL     │    MongoDB    │
         │  (Bank Data)  │ (Tickets)     │
         └───────────────┴───────────────┘
```

---

## Project Structure

```
│
├── CS306_GROUP_53_PHASE3_SQLDUMP.sql   # MySQL schema & data
│
└── scripts/
    ├── admin/
    │   ├── index.php            # Admin dashboard entry
    │   ├── admin_tickets.php    # Ticket management
    │   ├── admin_layout.css     # Admin UI styling
    │   ├── db.php               # MySQL connection (ignore)
    │   └── db_mongo.php         # MongoDB connection (ignore)
    │
    └── user/
        ├── index.php            # User dashboard entry
        ├── add_card.php         # Add card functionality
        ├── pay_bill.php         # Bill payment processing
        ├── create_ticket.php    # Ticket creation
        ├── submit_help.php      # Help request submission
        ├── layout.css           # User UI styling
        ├── db.php               # MySQL connection (ignore)
        └── db_mongo.php         # MongoDB connection (ignore)
```

---

## Technologies Used

* **PHP** — backend logic
* **MySQL** — financial & transactional data
* **MongoDB** — support ticket storage
* **HTML/CSS** — user interfaces
* **Apache / XAMPP / MAMP** — local server environment

---

## 🚀 Installation & Setup

### 1️⃣ Clone the repository

```bash
git clone https://github.com/yourusername/project-name.git
cd Group53_phase03_CS306
```

---

### 2️⃣ Import MySQL Database

Open phpMyAdmin or MySQL CLI and import:

```
CS306_GROUP_53_PHASE3_SQLDUMP.sql
```

This will create required tables and sample data.

---

### 3️⃣ Configure Database Connections

Inside both directories:

```
scripts/admin/
scripts/user/
```

update:

#### `db.php`

```php
$host = "localhost";
$user = "your_user";
$password = "your_password";
$database = "your_database";
```

---

### 4️⃣ Ensure MongoDB is Running

Start MongoDB locally.

Example database:

```
support_system
```

Collections are created automatically.

---

### 5️⃣ Run the Project

Move the project into your server root:

```
htdocs/   (XAMPP)
```

