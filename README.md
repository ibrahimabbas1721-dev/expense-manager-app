# 💰 Expense & Profit Management System

A role-based web application for tracking income and expenses, built with PHP, MySQL, and vanilla JavaScript. The system supports two user roles — **Admin** (full control) and **User** (read-only dashboard) — and is designed as a portfolio project demonstrating authentication, RBAC, CRUD operations, and dashboard analytics.

---

## 📌 Overview

The Expense & Profit Management System lets an admin record income and expense transactions, manage user accounts, and view profit/loss analytics, while regular users can log in to view their own transaction history and reports in a read-only dashboard.

---

## 🚀 Features

### Authentication
- Secure login with hashed passwords (`password_verify`)
- Session-based authentication
- Role-based redirects after login (`admin` → admin dashboard, `user` → user dashboard)
- Protected routes via `requireLogin()` and `requireAdmin()` guards
- Logout with full session destruction

### Admin Panel
- Manage users (add / view / edit / delete)
- Manage transactions (add / edit / delete / view all)
- Analytics dashboard (total income, total expenses, profit/loss, recent transactions)
- Filter transactions by date, category, and amount

### User Dashboard
- View personal transactions (read-only)
- Filter by date / category
- Visual reports (income vs. expense charts)

### Security
- Prepared statements (MySQLi) to prevent SQL injection
- Password hashing
- Session-based access control
- Input validation/sanitization

---

## 🛠️ Tech Stack

| Layer        | Technology                  |
|--------------|------------------------------|
| Frontend     | HTML, CSS, JavaScript        |
| Backend      | PHP                          |
| Database     | MySQL (via XAMPP / MariaDB)  |
| Charts       | Chart.js                     |
| Server       | Apache (XAMPP)                |

---

## 🗄️ Database Schema

**Database name:** `expense_app`

### `users`
| Column     | Type                          | Notes                  |
|------------|--------------------------------|-------------------------|
| id         | INT, PK, AUTO_INCREMENT        |                         |
| name       | VARCHAR(100)                   |                         |
| email      | VARCHAR(100), UNIQUE           |                         |
| password   | VARCHAR(255)                   | Hashed (bcrypt)         |
| role       | ENUM('admin', 'user')          | Default: `user`         |
| created_at | TIMESTAMP                      | Default: current time   |

### `transactions`
| Column      | Type                            | Notes                          |
|-------------|----------------------------------|----------------------------------|
| id          | INT, PK, AUTO_INCREMENT          |                                  |
| user_id     | INT, FK → `users(id)`            |                                  |
| type        | ENUM('income', 'expense')        |                                  |
| amount      | DECIMAL(10,2)                    |                                  |
| category    | VARCHAR(100)                     |                                  |
| description | TEXT                              |                                  |
| date        | DATE                              |                                  |
| created_at  | TIMESTAMP                        | Default: current time           |

The full schema and seed data are available in [`expense_app.sql`](./expense_app.sql).

---

## 📂 Project Structure

```
expense_app/
 ├── public/
 │    ├── index.php
 │    ├── login.php
 │    ├── logout.php
 │    ├── dashboard.php
 │    ├── admin/
 │    │    ├── dashboard.php
 │    │    ├── users.php
 │    │    └── add_user.php
 │    ├── user/
 │    │    ├── user_dashboard.php
 │    │    └── user_transaction.php
 │    ├── transactions/
 │    │    ├── index.php
 │    │    └── add.php
 │    └── reports/
 │         ├── report.php
 │         └── user_report.php
 ├── includes/
 │    ├── db.php
 │    ├── auth.php
 │    ├── header.php
 │    ├── footer.php
 │    ├── admin_sideBar.php
 │    └── user_sideBar.php
 ├── assets/
 │    ├── css/
 │    ├── js/
 │    └── images/
 ├── expense_app.sql
 └── README.md
```

---

## ⚙️ Installation & Setup

1. **Install XAMPP** and start the Apache & MySQL services.

2. **Clone the project** into your `htdocs` folder:
```
   C:/xampp/htdocs/expense_app/
```

3. **Import the database**
   - Open phpMyAdmin
   - Create a database named `expense_app`
   - Import `expense_app.sql`

4. **Configure the database connection** in `includes/db.php`:
```php
   $host = "localhost";
   $user = "root";
   $password = "";
   $dbname = "expense_app";
```

5. **Run the project**
   Visit:
```
   http://localhost/expense_app/public/login.php
```

---

## 👤 Demo Credentials

> ⚠️ For local/demo use only — change or remove before deploying publicly.

| Role  | Email             | Password        |
|-------|-------------------|------------------|
| Admin | ibrahim@xyz.com   | *(set at signup)* |
| User  | bhoot@ex.com      | *(set at signup)* |

---

## 🗺️ Roadmap

- [x] Database design (`users`, `transactions`)
- [x] Login & session-based authentication
- [x] Role-based access control (admin/user)
- [ ] Admin user management (CRUD)
- [ ] Admin transaction management (CRUD)
- [ ] Admin analytics dashboard with filters
- [ ] User dashboard (view-only) with charts
- [ ] UI/UX polish (sidebar, responsive layout, dark mode)
- [ ] Export to PDF/Excel
- [ ] Deployment

---

## 📸 Screenshots
<img src="image-url.jpg" alt="Description of the image">

---

## 👨‍💻 Author

**Ibrahim Abbas**
GitHub: [@ibrahimabbas1721-dev](https://github.com/ibrahimabbas1721-dev)

---

## 📄 License

This project is for educational and portfolio purposes.