# BruTor Shop

A modern automotive shop web application built with **Laravel 11/12**, **Tailwind CSS v4**, and **Vite**.

## How to Run This Project (Important Note on `htdocs`)

**No, you do not need to put anything in `htdocs`.**

In modern PHP development, especially with the Laravel framework, you don't use XAMPP's `htdocs` directory to serve the application. Instead, Laravel comes with its own built-in development server that you run directly from the command line.

Follow the exact steps below to get the project running locally.

---

## 🚀 Setup Instructions

### 1. Requirements Before You Start
Make sure you have installed on your computer:
1. **PHP 8.2+** (You can install this via XAMPP, but don't put the code in `htdocs`)
2. **MySQL / MariaDB** (You can use the XAMPP Control Panel to start the MySQL module)
3. **Composer** (PHP package manager)
4. **Node.js & npm** (For compiling the frontend Tailwind CSS)

---

### 2. Prepare the Database
1. Open your XAMPP Control Panel and **Start** the `MySQL` module.
2. Go to `http://localhost/phpmyadmin` in your browser.
3. Create a new, blank database named exactly: **`db_sample`**
4. (*Optional*) You can import the included `db_sample.sql` file at this step, but running the migrations and seeders (Step 6) is the recommended Laravel way.

---

### 3. Open Terminal and Navigate to the Project
Open PowerShell, CMD, or VS Code terminal and navigate into the Laravel directory:
```bash
cd brutor-laravel
```
*(All following commands must be run inside this `brutor-laravel` folder!)*

---

### 4. Install Dependencies
Install both the backend (PHP) and frontend (JavaScript/Tailwind) dependencies:
```bash
composer install
npm install
```

---

### 5. Setup Environment File
Copy the example environment file to create your own configuration:
```bash
cp .env.example .env
```
Generate your application encryption key:
```bash
php artisan key:generate
```
*(Check your new `.env` file to ensure `DB_DATABASE=db_sample` matches the database you created in Step 2).*

---

### 6. Migrate and Seed the Database
Run this command to build all the tables and populate the default items, users, and admin accounts:
```bash
php artisan migrate --seed
```

---

### 7. Run the Application (Requires Two Terminals)

Because we are using Tailwind CSS and Vite, you need to run **two** terminal processes simultaneously.

**Terminal 1: Start the Frontend Asset Compiler**
(Inside the `brutor-laravel` folder)
```bash
npm run dev
```

**Terminal 2: Start the Laravel PHP Server**
(Inside the `brutor-laravel` folder)
```bash
php artisan serve
```

---

### 🎉 Done!
Your application is now live. Do not close those two terminal windows while you are working.
Open your browser and visit: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔄 How to Restart the Project Later

If you shut down your PC, you do not need to reinstall everything. Just follow these steps to start the application again:

1. Open your **XAMPP Control Panel** and **Start** the `MySQL` module.
2. Open PowerShell, CMD, or VS Code terminal and navigate into the Laravel directory:
   ```bash
   cd path/to/barlitor/brutor-laravel
   ```
3. Open **two** terminal windows inside that directory and run:
   - Terminal 1: `php artisan serve`
   - Terminal 2: `npm run dev`
4. Open your browser and visit: **http://127.0.0.1:8000**

---

## 👥 Default Login Accounts
*(Created automatically if you ran `php artisan migrate --seed`)*

| Role | Email | Password |
|---|---|---|
| **Admin** | admin@brutor.com | admin123 |
| **Customer** | john@example.com | password |
