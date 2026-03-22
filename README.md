# BarliTor Shop

**BarliTor Shop** is a full-stack automotive shop web application for managing **product sales**, **tool rentals**, shopping cart, checkout with email receipts, user accounts with email verification, and admin reporting. It is built on **Laravel 12** with a dark-themed **Tailwind CSS** frontend.

---

## Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation & Setup](#installation--setup)
- [Default Accounts](#default-accounts)
- [Project Structure](#project-structure)
- [Database & Models](#database--models)
- [Routes Overview](#routes-overview)
- [Features](#features)
- [Form Validation](#form-validation)
- [Email & File Uploads](#email--file-uploads)
- [Resetting the Database](#resetting-the-database)
- [Email Setup (Mailtrap)](#email-setup-mailtrap)

---

## Overview

BarliTor Shop serves two main user roles:

- **Customers** — Browse items (products and rental tools), search and filter, add to cart, checkout, receive email receipts, manage profile, and leave reviews for items they have purchased or rented.
- **Admins** — In addition to customer features, admins manage the catalog (items, images, suppliers), users, expenses, and view/export sales and rental reports.

The application uses **session-based carts** (products and tools with quantities and rental dates), creates **orders** and **payments** on checkout, sends **email receipts** via SMTP (e.g. Mailtrap), and supports **email verification** for new registrations. The UI uses a consistent **dark theme** (`#1a1a1a`, `#111111`, gray-800 borders, orange-500 accents) with Tailwind CSS and Font Awesome icons.

---

## Tech Stack

| Layer        | Technology |
|-------------|------------|
| **Backend** | Laravel 12 (PHP 8.2+) |
| **Database**| MySQL (e.g. `db_sample`) |
| **Frontend**| Tailwind CSS 4, Vite 7, Font Awesome 6 |
| **Auth**    | Session-based (Laravel auth), email verification |
| **Email**   | SMTP (e.g. Mailtrap for receipts and verification) |
| **Build**   | Vite, Node.js, npm |

There is **no Bootstrap** in the project; all views use Tailwind utility classes and the dark theme described above.

---

## Requirements

- **PHP** 8.2 or higher  
- **Composer**  
- **Node.js** and **npm**  
- **MySQL** (e.g. XAMPP, Laragon, or any local MySQL server)

---

## Installation & Setup

### 1. Clone the repository

```bash
git clone <repository-url> barlitor
cd barlitor
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment and app key

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database configuration

Edit `.env` and set your MySQL credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sample
DB_USERNAME=root
DB_PASSWORD=
```

Create an empty database named `db_sample` (or your chosen name) in MySQL before running migrations.

### 5. Migrations and seed data

```bash
php artisan migrate --seed
```

This creates all tables and seeds default admin/customer users, suppliers, and sample items.

### 6. Run the application

**Option A — Two terminals**

- Terminal 1 (assets): `npm run dev`
- Terminal 2 (server): `php artisan serve`

**Option B — Single command (if available)**

- `composer run dev` (runs server, Vite, queue, and logs concurrently)

Then open **http://127.0.0.1:8000** in your browser.

---

## Default Accounts

| Role      | Email             | Password  |
|----------|-------------------|-----------|
| **Admin**   | admin@barlitor.com   | admin123  |
| **Customer**| john@example.com   | password  |

These accounts are created by the database seeder and (for demo) bypass email verification.

---

## Project Structure

```
barlitor/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Auth, Cart, Checkout, Item, Order, Profile, Report, Review, Supplier, Admin, Expense
│   │   └── Middleware/      # AdminMiddleware (admin role check)
│   ├── Mail/                # EmailVerificationMail
│   ├── Models/              # User, Item, OrderInfo, Payment, ProductSold, Rental, Supplier, Expense, ItemReview, ItemImage, Stock
│   ├── Imports/             # ItemsImport (Excel/CSV import for items)
│   └── Providers/
├── config/                  # Laravel config (app, auth, database, mail, etc.)
├── database/
│   ├── migrations/          # All table and column migrations
│   ├── seeders/             # DatabaseSeeder (users, suppliers, items)
│   └── db_sample_dump.sql    # Optional sample DB dump
├── public/                  # index.php, images/, uploads/
├── resources/
│   ├── views/               # Blade templates
│   │   ├── layouts/         # app.blade.php (main layout), admin.blade.php
│   │   ├── auth/            # login, register, verify_pending
│   │   ├── items/           # index, show, create, edit, trashed
│   │   ├── cart/            # index, checkout
│   │   ├── orders/          # my_orders
│   │   ├── profile/         # show, change_password
│   │   ├── reviews/         # edit
│   │   └── admin/           # users, suppliers, expenses, reports, reviews
│   ├── css/                 # app.css (Tailwind)
│   └── js/                  # app.js (Vite entry)
├── routes/
│   └── web.php              # All web routes (public, auth, cart, checkout, admin)
├── .env.example
├── composer.json
├── package.json
└── vite.config.js
```

---

## Database & Models

### Main tables (from migrations)

- **users** — Accounts (name, email, password, role, status, avatar, address, email_verification_token, etc.)
- **item** — Products and rental tools (title, description, cost_price, sell_price, category, type, stock_quantity, supplier_id, image_path, soft deletes)
- **item_images** — Gallery images per item (image_path, is_primary, sort_order)
- **supplier** — Suppliers (name, contact_email, contact_phone, lead_time, website, soft deletes)
- **orderinfo** — Orders (user_id, date_placed, status)
- **orderline** — Order line items (for order details)
- **payment** — Payments linked to orders
- **products_sold** — Sold products per transaction (product_id, quantity, rate)
- **rental** — Tool rentals (item_id, customer_id, start_date, due_date, quantity, rate)
- **expense** — Admin-tracked expenses
- **item_reviews** — Customer reviews for items (item_id, user_id, rating, comment)
- **stock**, **barcodes**, **customer**, **cache**, **jobs** — Supporting or legacy tables

### Eloquent models

- **User** — Authentication, `isAdmin()`, `hasVerifiedEmail()`, orders relation  
- **Item** — Products/tools, soft deletes, relations: supplier, reviews, images, primaryImage, stock  
- **ItemImage** — Item gallery images  
- **OrderInfo** — Order header, relations to user, payments, product sold, rentals  
- **Payment**, **ProductSold**, **Rental** — Checkout-related models  
- **Supplier** — Soft deletes  
- **Expense**, **ItemReview**, **Stock** — Supporting models  

---

## Routes Overview

### Public

- `GET /` — Home (featured items)
- `GET /items` — Item listing (search, category, sort)
- `GET /items/{id}` — Item detail and reviews

### Authentication

- `GET/POST /login`, `GET/POST /register`, `GET /logout`
- `GET /verify-email/{token}` — Email verification
- `POST /resend-verification`, `GET /verify-pending`

### Authenticated (customers and admins)

- **Profile:** `GET /profile/{id}`, `POST /profile/update`, avatar, `GET/POST /change-password`
- **Cart:** `GET /cart`, `POST /cart/add`, remove product/tool, `POST /cart/update`
- **Checkout:** `GET/POST /checkout`
- **Orders:** `GET /my-orders`
- **Reviews:** `POST /reviews`, `GET/PUT/DELETE /reviews/{id}`

### Admin (`/admin`, `auth` + `admin` middleware)

- **Items:** CRUD, trashed list, restore, delete image, CSV template download, import (Excel/CSV)
- **Users:** list, view, delete, update role/status
- **Suppliers:** CRUD, restore (soft delete)
- **Expenses:** CRUD
- **Reports:** Sales/rentals by date range, view toggle (All / Materials / Rentals), **export CSV**
- **Reviews:** Admin list/index

---

## Features

### Customer

- **Browse & search** — Item list with search, category filter, and sort (price, newest, oldest, type).
- **Item detail** — View item, gallery images, and reviews; add to cart (product quantity or tool with start/due date and quantity).
- **Cart** — Separate lists for products and rental tools; update quantities; remove items.
- **Checkout** — Confirm cart, enter amount paid; order and payment records created; receipt emailed (if SMTP configured).
- **My orders** — Order history with optional date-range filter.
- **Account** — Register (with email verification), login, logout; edit profile (name, address, avatar); change password.
- **Reviews** — Submit, edit, or delete reviews only for items the user has purchased or rented.

### Admin

- Everything a customer can do, plus:
- **Items** — Create, edit, delete (soft delete); multi-image gallery and legacy single image; restore from trashed; download CSV import template; import items (Excel/CSV).
- **Users** — View list and user detail; delete user; change role and status (e.g. active/inactive).
- **Suppliers** — Full CRUD; soft delete and restore.
- **Expenses** — Full CRUD.
- **Reports** — Sales and rental report by date range; switch view (All / Materials / Rentals); **export report as CSV**.
- **Reviews** — Admin index of all reviews.

---

## Form Validation

The app uses **server-side** (Laravel) and **client-side** (HTML5 + JS) validation on key forms so that invalid data is caught early and errors are shown clearly.

### Where it’s applied

- **Item create/edit** — Title, description (with live character counter), cost/sell price, category, type, stock, optional images. Sell price can show a non-blocking warning when lower than cost. Inline `@error` messages and red borders on failure; `old()` repopulation.
- **Registration** — Name, email, password (with strength hint), password confirmation (with match/mismatch message), optional avatar. Inline `@error` and red borders; `old()` for name and email only (never for passwords).

### How it works

- **Server-side:** Laravel validation in `ItemController::store/update` and `AuthController::register` with custom messages; Blade `@error('field')` under each field and error-state border classes.
- **Client-side:** HTML5 attributes (`required`, `minlength`, `maxlength`, `min`, `type="number"`, `type="email"`, etc.) and a small JS script per form: blur/input validation and a final check on submit that prevents submit and scrolls to the first error. This is progressive enhancement; with JS disabled, server-side and layout error list still work.

The **global error list** (all validation errors in a red alert at the top) is rendered in `layouts/app.blade.php` and is unchanged by the per-form validation.

---

## Email & File Uploads

### Email

- **Verification** — After register, a verification link is sent; users can open it or request a resend from the “verify pending” page. Demo accounts (e.g. admin@barlitor.com, john@example.com) are treated as already verified.
- **Receipts** — After a successful checkout, a receipt email is sent to the customer (when SMTP is configured, e.g. Mailtrap).

### File uploads

- **Item images** — Stored under `public/images/items/` (or as configured). Items can have a legacy single `image_path` and/or multiple gallery images via the `item_images` table.
- **User avatars** — Stored under `public/images/avatars/` (or `public/uploads/avatars/` depending on config). Used for profile and registration.

Ensure these directories exist and are writable; they are often kept in the repo via `.gitkeep`.

---

## Resetting the Database

To wipe the database and re-run migrations and seeders:

```bash
php artisan db:wipe --force
php artisan migrate --seed
```

---

## Email Setup (Mailtrap)

Receipts and verification emails are sent via SMTP. For local testing, use [Mailtrap](https://mailtrap.io) and set in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@barlitor.com"
MAIL_FROM_NAME="BarliTor Shop"
```

Then check the Mailtrap inbox to see test emails (receipts and verification links).

---

## License & Credits

This project uses the Laravel framework and other open-source components. See `composer.json` and `package.json` for dependencies and licenses.
