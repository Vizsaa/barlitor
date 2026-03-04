<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', function () {
    $items = \App\Models\Item::whereNull('deleted_at')
        ->orderByDesc('item_id')
        ->limit(4)
        ->get();
    return view('home', ['items' => $items]);
})->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Items (public)
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');

// Auth-protected routes
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile/{id?}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart/remove-product/{id}', [CartController::class, 'removeProduct'])->name('cart.removeProduct');
    Route::get('/cart/remove-tool/{index}', [CartController::class, 'removeTool'])->name('cart.removeTool');
    Route::post('/cart/update', [CartController::class, 'updateQuantities'])->name('cart.update');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // My Orders
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.mine');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Item management (admin CRUD)
    Route::get('/items/create', [ItemController::class, 'create'])->name('admin.items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('admin.items.store');
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('admin.items.edit');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('admin.items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('admin.items.destroy');

    // Users management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{id}', [AdminController::class, 'viewUser'])->name('admin.users.view');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // Suppliers
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('admin.suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('admin.suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('admin.suppliers.store');
    Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('admin.suppliers.edit');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('admin.suppliers.update');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('admin.suppliers.destroy');
    Route::get('/suppliers/{id}/restore', [SupplierController::class, 'restore'])->name('admin.suppliers.restore');

    // Expenses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('admin.expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('admin.expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('admin.expenses.store');
    Route::get('/expenses/{id}/edit', [ExpenseController::class, 'edit'])->name('admin.expenses.edit');
    Route::put('/expenses/{id}', [ExpenseController::class, 'update'])->name('admin.expenses.update');
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy'])->name('admin.expenses.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
});
