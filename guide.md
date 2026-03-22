---

## MP1 — Admin item routes (CRUD/import/restore)

**File:**  `routes/web.php`
```php
// Item management (admin CRUD)
Route::get('/items/trashed', [ItemController::class, 'trashed'])->name('admin.items.trashed');
Route::get('/items/import/template', [ItemController::class, 'downloadTemplate'])->name('admin.items.downloadTemplate');
Route::post('/items/import', [ItemController::class, 'import'])->name('admin.items.import');
Route::get('/items/create', [ItemController::class, 'create'])->name('admin.items.create');
Route::post('/items', [ItemController::class, 'store'])->name('admin.items.store');
Route::delete('/items/images/{imageId}', [ItemController::class, 'deleteImage'])->name('admin.items.deleteImage');
Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('admin.items.edit');
Route::put('/items/{id}', [ItemController::class, 'update'])->name('admin.items.update');
Route::post('/items/{id}/restore', [ItemController::class, 'restore'])->name('admin.items.restore');
Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('admin.items.destroy');
```
> Registers the admin-only inventory endpoints for managing items, imports, and restores.

---

## MP2 — User registration + admin account control

**File:**  `app/Http/Controllers/AuthController.php`
```php
$avatarPath = null;
if ($request->hasFile('avatar')) {
    $file = $request->file('avatar');
    $filename = 'user_' . time() . '_reg.' . $file->getClientOriginalExtension();
    $file->move(public_path('images/avatars'), $filename);
    $avatarPath = 'images/avatars/' . $filename;
}

$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => $request->password,
    'role' => 'customer',
    'status' => 'active',
    'avatar' => $avatarPath,
]);
```

**File:**  `app/Http/Controllers/AdminController.php`
```php
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:active,inactive',
    ]);

    $user = User::findOrFail($id);

    if (auth()->id() == $user->id) {
        return back()->with('error', 'You cannot change your own status.');
    }

    $user->update(['status' => $request->status]);

    $label = $request->status === 'active' ? 'activated' : 'deactivated';
    return back()->with('success', 'User account ' . $label . ' successfully.');
}
```
> Creates user accounts with optional avatar upload and lets admins activate/deactivate accounts.

---

## MP3 — Email verification + inactive account login block

**File:**  `app/Http/Controllers/AuthController.php`
```php
$user = Auth::user();

if ($user->status === 'inactive') {
    Auth::logout();
    return back()
        ->with('error', 'Your account is deactivated. Please wait for an admin to reactivate your account.')
        ->withInput(['email' => $request->email]);
}

// Seeded demo accounts bypass email verification
$bypassVerification = in_array($user->email, ['admin@barlitor.com', 'john@example.com'], true);
if (!$bypassVerification && !$user->hasVerifiedEmail()) {
    Auth::logout();
    return back()
        ->with('error', 'Your email address is not verified. Please check your inbox.')
        ->with('unverified_email', $user->email)
        ->withInput(['email' => $request->email]);
}
```
> Logs users out and rejects the login if the account is inactive or the email is unverified.

---

## MP4 — One review per user per item

**File:**  `app/Http/Controllers/ReviewController.php`
```php
$existing = ItemReview::where('item_id', $request->item_id)
    ->where('user_id', Auth::id())
    ->first();

if ($existing) {
    return back()->with('error', 'You have already reviewed this item. You can edit your review instead.');
}

ItemReview::create([
    'item_id' => $request->item_id,
    'user_id' => Auth::id(),
    'rating' => $request->rating,
    'comment' => $request->comment,
]);

return back()->with('success', 'Review submitted successfully!');
```
> Prevents duplicate reviews by the same user for the same item.

---

## MP6 — Filter by category, type, and price

**File:**  `app/Http/Controllers/ItemController.php`
```php
if ($category && $category !== 'all') {
    $query->where('category', $category);
}

// Type filter
if ($type && in_array($type, ['product', 'tool'])) {
    $query->where('type', $type);
}

// Price range
if ($minPrice !== '' && is_numeric($minPrice)) {
    $query->where('sell_price', '>=', (float) $minPrice);
}
if ($maxPrice !== '' && is_numeric($maxPrice)) {
    $query->where('sell_price', '<=', (float) $maxPrice);
}
```
> Applies request-driven filters to the items query before sorting and rendering results.

---

## MP7 — Sales charts data aggregation (yearly totals)

**File:**  `app/Http/Controllers/ReportController.php`
```php
// Chart 1 — Yearly monthly totals (always both series)
$monthlyMaterials = DB::table('products_sold')
    ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'products_sold.transaction_id')
    ->whereYear('orderinfo.date_placed', $chartYear)
    ->selectRaw('MONTH(orderinfo.date_placed) as month, SUM(products_sold.quantity * products_sold.rate_charged) as total')
    ->groupByRaw('MONTH(orderinfo.date_placed)')
    ->pluck('total', 'month');

$monthlyRentals = DB::table('rental')
    ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'rental.transaction_id')
    ->whereYear('orderinfo.date_placed', $chartYear)
    ->selectRaw('MONTH(orderinfo.date_placed) as month, SUM(rental.rate_charged) as total')
    ->groupByRaw('MONTH(orderinfo.date_placed)')
    ->pluck('total', 'month');
```
> Builds monthly totals for materials and rentals used by the admin report charts.

---

## MP8 — Home page search (Scout + pagination)

**File:**  `app/Http/Controllers/HomeController.php`
```php
$query = trim((string) $request->input('search', ''));

if ($query !== '') {
    $results = Item::search($query)
        ->query(fn ($q) => $q->with(['images', 'primaryImage']))
        ->paginate(12)
        ->appends(['search' => $query]);

    return view('home', [
        'searchQuery' => $query,
        'searchResults' => $results,
        'items' => collect(),
    ]);
}
```

**File:**  `app/Models/Item.php`
```php
public function toSearchableArray(): array
{
    return [
        'title' => $this->title,
        'description' => $this->description,
        'category' => $this->category,
        'type' => $this->type,
    ];
}
```
> Runs Scout search with pagination on the home page and defines the fields that are indexed.

---
