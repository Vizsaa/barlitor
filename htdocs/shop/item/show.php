<?php
session_start();
include('../includes/header.php');
include('../includes/config.php');

// Validate item ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid item ID.";
    header('Location: index.php');
    exit;
}

$itemId = (int)$_GET['id'];

// Fetch item details with supplier website
$sql = "SELECT i.*, s.name AS supplier_name, s.website AS supplier_website
        FROM item i
        LEFT JOIN suppliers s ON i.supplier_id = s.supplier_id
        WHERE i.item_id = $itemId AND i.deleted_at IS NULL";
$result = mysqli_query($conn, $sql);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    $_SESSION['message'] = "Item not found.";
    header('Location: index.php');
    exit;
}

// Check if user can review (must be logged in as customer & bought the product/rented tool)
$canReview = false;
if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'customer') {
    $userId = (int)$_SESSION['user_id'];

    // Check in products_sold
    $purchaseCheck = mysqli_query($conn, "
        SELECT 1 
        FROM orderinfo oi
        JOIN products_sold ps ON oi.orderinfo_id = ps.transaction_id
        WHERE oi.user_id = $userId AND ps.product_id = $itemId
        LIMIT 1
    ");

    // Check in rental
    $rentalCheck = mysqli_query($conn, "
        SELECT 1
        FROM rental r
        WHERE r.customer_id = $userId AND r.item_id = $itemId
        LIMIT 1
    ");

    if (mysqli_num_rows($purchaseCheck) > 0 || mysqli_num_rows($rentalCheck) > 0) {
        $canReview = true;
    }
}

// Fetch existing reviews
$reviewsResult = mysqli_query($conn, "
    SELECT r.*, u.name 
    FROM item_reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.item_id = $itemId
    ORDER BY r.created_at DESC
");
$reviews = [];
while ($row = mysqli_fetch_assoc($reviewsResult)) {
    $reviews[] = $row;
}
?>

<body class="bg-light">
<div class="container py-5">

    <div class="row">
        <!-- Item Image -->
        <div class="col-md-6">
            <img src="<?= !empty($item['image_path']) ? $item['image_path'] : './images/default.png' ?>" 
                 class="img-fluid border rounded" alt="<?= htmlspecialchars($item['title']) ?>">
        </div>

        <!-- Item Details -->
        <div class="col-md-6">
            <h2 class="fw-bold"><?= htmlspecialchars($item['title']) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($item['description']) ?></p>
            <p>Category: <strong><?= htmlspecialchars($item['category'] ?? 'N/A') ?></strong></p>
            <p>Supplier: 
    <?php if (!empty($item['supplier_name'])): ?>
        <?php if (!empty($item['supplier_website'])): ?>
            <a href="<?= htmlspecialchars($item['supplier_website']) ?>" target="_blank" class="text-decoration-none">
                <?= htmlspecialchars($item['supplier_name']) ?>
            </a>
        <?php else: ?>
            <?= htmlspecialchars($item['supplier_name']) ?>
        <?php endif; ?>
    <?php else: ?>
        N/A
    <?php endif; ?>
</p>

            <p class="<?= ($item['stock_quantity'] <= 0 ? 'text-danger' : 'text-success') ?>">
                Stock: <?= (int)$item['stock_quantity'] ?>
            </p>
            <p class="fw-semibold text-success fs-4">₱<?= number_format($item['sell_price'], 2) ?></p>

            <!-- Add to Cart -->
            <?php if ($item['stock_quantity'] > 0): ?>
                <form method="POST" action="../user/add_to_cart.php" class="mt-3">
                    <input type="hidden" name="product_id" value="<?= $item['item_id'] ?>">
                    <input type="hidden" name="type" value="product">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="<?= $item['stock_quantity'] ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-cart-plus"></i> Add to Cart
                    </button>
                </form>
            <?php else: ?>
                <div class="alert alert-danger mt-3">Out of stock</div>
            <?php endif; ?>

            <!-- Short Description -->
            <div class="mt-4 p-3 border rounded bg-white">
                <h5>Product Notes</h5>
                <p><?= htmlspecialchars($item['description']) ?></p>
            </div>

            <!-- Reviews Section -->
            <div class="mt-4">
                <h4>Reviews</h4>

                <!-- Review Form -->
                <?php if ($canReview): ?>
                    <form method="POST" action="../user/review_store.php" class="mb-4">
                        <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                        <div class="mb-2">
                            <label for="rating" class="form-label">Rating</label>
                            <select name="rating" id="rating" class="form-select" required>
                                <option value="">Select rating</option>
                                <?php for ($i=1; $i<=5; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?> star<?= $i>1?'s':'' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
                    <div class="alert alert-warning">You can only leave a review if you purchased this item.</div>
                <?php else: ?>
                    <div class="alert alert-warning">Please log in as a verified customer to leave a review.</div>
                <?php endif; ?>

                <!-- Display Reviews -->
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $rev): ?>
                        <div class="border p-3 rounded mb-2 bg-white d-flex justify-content-between align-items-start">
                            <div>
                                <strong><?= htmlspecialchars($rev['name']) ?></strong> 
                                <span class="text-warning"><?= str_repeat('★', $rev['rating']) . str_repeat('☆', 5 - $rev['rating']) ?></span>
                                <p class="mb-0"><?= htmlspecialchars($rev['comment']) ?></p>
                                <small class="text-muted"><?= $rev['created_at'] ?></small>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <?php 
                                // Show Edit button to owner
                                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $rev['user_id']): ?>
                                    <a href="../user/review_edit.php?id=<?= $rev['review_id'] ?>&item_id=<?= $item['item_id'] ?>" 
                                       class="btn btn-sm btn-outline-primary mb-1">
                                        Edit
                                    </a>
                                <?php endif; ?>

                                <?php 
                                // Show Delete button to admin or owner
                                if (isset($_SESSION['user_id']) && 
                                    ($_SESSION['role'] === 'admin' || $_SESSION['user_id'] == $rev['user_id'])): ?>
                                    <a href="../user/review_delete.php?id=<?= $rev['review_id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this review?')">
                                        Delete
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews yet.</p>
                <?php endif; ?>

            </div>

        </div>
    </div>

</div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
