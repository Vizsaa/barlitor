# BarliTor Shop — Laravel

A full-featured automotive shop web application built with **Laravel 12**. Supports product sales, tool rentals, shopping cart, checkout with email receipts, user management, and admin reporting.

---

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Database:** MySQL (`db_sample`)
- **Frontend:** Bootstrap 5.3, Font Awesome 6
- **Email:** Mailtrap SMTP (for receipt delivery)
- **Session:** File-based

---

## Requirements

- PHP 8.2+
- Composer
- MySQL (XAMPP recommended)
- XAMPP or any local server with MySQL running

---

## Setup Instructions

### 1. Clone / Copy the project

Place the project folder somewhere accessible, e.g.:
```
C:\Users\YourName\barlitor-laravel\
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Copy environment file

```bash
cp .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Configure the database

In `.env`, make sure the following is set (already configured in `.env.example`):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sample
DB_USERNAME=root
DB_PASSWORD=
```

> Make sure MySQL is running and the `db_sample` database exists (XAMPP → Start MySQL).

### 6. Run migrations and seed the database

```bash
php artisan migrate --seed
```

This will create all tables and insert sample data including admin and customer accounts.

### 7. Start the development server

```bash
php artisan serve --port=8000
```

Then open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

---

## Default Accounts

| Role     | Email                | Password   |
|----------|----------------------|------------|
| Admin    | admin@barlitor.com     | admin123   |
| Customer | john@example.com     | password   |

---

## Features

### Customer
- Register, login, logout
- Browse and search/filter/sort items
- View item details and reviews
- Add products to cart (with quantity)
- Rent tools (with start/due date and quantity)
- Checkout with payment — receipt generated and emailed
- View order history with date-range filter
- Edit profile (name, address, avatar upload)
- Change password
- Submit/edit/delete reviews (only for purchased items)

### Admin
- All customer features
- Add, edit, delete items (with image upload)
- Manage users (view, delete)
- Manage suppliers (CRUD + soft delete/restore)
- Manage expenses (CRUD)
- View sales & rental reports with date-range filter
- Switch report view: All / Materials / Rentals
- Export report as CSV

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── ItemController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── ProfileController.php
│   │   ├── OrderController.php
│   │   ├── ReviewController.php
│   │   ├── AdminController.php
│   │   ├── SupplierController.php
│   │   ├── ExpenseController.php
│   │   └── ReportController.php
│   └── Middleware/
│       └── AdminMiddleware.php
└── Models/
    ├── User.php
    ├── Item.php
    ├── Supplier.php
    ├── OrderInfo.php
    ├── Payment.php
    ├── ProductSold.php
    ├── Rental.php
    ├── Expense.php
    ├── ItemReview.php
    └── Stock.php

resources/views/
├── layouts/app.blade.php
├── home.blade.php
├── auth/         (login, register)
├── items/        (index, show, create, edit)
├── cart/         (index, checkout)
├── profile/      (show, change_password)
├── orders/       (my_orders)
├── reviews/      (edit)
└── admin/
    ├── users/    (index, view)
    ├── suppliers/ (index, create, edit)
    ├── expenses/  (index, create, edit)
    └── reports/  (index)
```

---

## Email (Mailtrap)

Receipts are sent via Mailtrap after a successful checkout. Configuration is already set in `.env.example`. After checkout, check your Mailtrap inbox at [https://mailtrap.io](https://mailtrap.io).

---

## Uploaded Files

- Item images → `public/uploads/items/`
- User avatars → `public/uploads/avatars/`

These directories are excluded from git (`.gitkeep` files keep them tracked).

---

## Resetting the Database

To wipe and re-seed from scratch:

```bash
php artisan db:wipe --force
php artisan migrate --seed
```
