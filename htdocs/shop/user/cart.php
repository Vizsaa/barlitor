<?php
session_start();
include("../includes/header.php");
include("../includes/config.php");

// Require login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please login to view your cart.';
    header('Location: login.php');
    exit();
}

// --- Handle removals ---
if (isset($_GET['remove_product'])) {
    $pid = (int)$_GET['remove_product'];
    unset($_SESSION['cart']['products'][$pid]);
    $_SESSION['message'] = "Product removed from cart.";
    header("Location: cart.php");
    exit();
}

if (isset($_GET['remove_tool'])) {
    $index = (int)$_GET['remove_tool'];
    if (isset($_SESSION['cart']['tools'][$index])) {
        unset($_SESSION['cart']['tools'][$index]);
        $_SESSION['cart']['tools'] = array_values($_SESSION['cart']['tools']); // reindex
        $_SESSION['message'] = "Tool rental removed from cart.";
    }
    header("Location: cart.php");
    exit();
}

// --- Handle product quantity updates ---
if (isset($_POST['update_quantities'])) {
    foreach ($_POST['quantities'] as $pid => $qty) {
        $pid = (int)$pid;
        $qty = (int)$qty;

        // Fetch stock from database
        $stmt = $conn->prepare("SELECT stock_quantity FROM item WHERE item_id = ? AND deleted_at IS NULL LIMIT 1");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->bind_result($stock_qty);
        $stmt->fetch();
        $stmt->close();

        if ($qty <= 0) {
            unset($_SESSION['cart']['products'][$pid]);
        } else {
            $_SESSION['cart']['products'][$pid]['quantity'] = min($qty, $stock_qty);
        }
    }
    $_SESSION['message'] = "Cart updated successfully.";
    header("Location: cart.php");
    exit();
}

// --- Initialize totals ---
$product_total = 0;
$tool_total = 0;
?>

<div class="container my-5">
    <h2 class="mb-4">Your Cart</h2>

```
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Products Table -->
<?php if (!empty($_SESSION['cart']['products'])): ?>
    <form method="post" action="cart.php">
        <h4>Products</h4>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($_SESSION['cart']['products'] as $pid => $product): 
                    $subtotal = $product['price'] * $product['quantity'];
                    $product_total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($product['title']); ?></td>
                        <td>₱<?= number_format($product['price'], 2); ?></td>
                        <td>
                            <input type="number" name="quantities[<?= $pid ?>]" value="<?= $product['quantity'] ?>" min="1" class="form-control" style="width: 80px;">
                        </td>
                        <td>₱<?= number_format($subtotal, 2); ?></td>
                        <td>
                            <a href="cart.php?remove_product=<?= $pid ?>" class="btn btn-sm btn-danger">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td colspan="2"><strong>₱<?= number_format($product_total, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <button type="submit" name="update_quantities" class="btn btn-success mb-4">Update Quantities</button>
    </form>
<?php else: ?>
    <p class="text-muted">No products in your cart.</p>
<?php endif; ?>

<!-- Tools Table -->
<?php if (!empty($_SESSION['cart']['tools'])): ?>
    <h4>Tool Rentals</h4>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Rate</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($_SESSION['cart']['tools'] as $index => $tool): 
                // Fetch stock
                $stmt = $conn->prepare("SELECT stock_quantity FROM item WHERE item_id = ? AND deleted_at IS NULL LIMIT 1");
                $stmt->bind_param("i", $tool['id']);
                $stmt->execute();
                $stmt->bind_result($stock_qty);
                $stmt->fetch();
                $stmt->close();

                // Enforce stock limit
                if ($tool['quantity'] > $stock_qty) {
                    $_SESSION['cart']['tools'][$index]['quantity'] = $stock_qty;
                    $tool['quantity'] = $stock_qty;
                }

                $subtotal = $tool['rate'] * $tool['quantity'];
                $tool_total += $subtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($tool['title']); ?></td>
                    <td>₱<?= number_format($tool['rate'], 2); ?></td>
                    <td><?= htmlspecialchars($tool['start_date']); ?></td>
                    <td><?= htmlspecialchars($tool['due_date']); ?></td>
                    <td><?= $tool['quantity']; ?></td>
                    <td>₱<?= number_format($subtotal, 2); ?></td>
                    <td>
                        <a href="cart.php?remove_tool=<?= $index ?>" class="btn btn-sm btn-danger">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end"><strong>Total:</strong></td>
                    <td colspan="2"><strong>₱<?= number_format($tool_total, 2); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
<?php else: ?>
    <p class="text-muted">No tool rentals in your cart.</p>
<?php endif; ?>

<!-- Checkout -->
<?php if (!empty($_SESSION['cart']['products']) || !empty($_SESSION['cart']['tools'])): ?>
    <?php $grand_total = $product_total + $tool_total; ?>
    <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light border rounded">
        <h4>Grand Total: <span class="text-success">₱<?= number_format($grand_total, 2); ?></span></h4>
        <a href="checkout.php" class="btn btn-primary btn-lg">Proceed to Checkout</a>
    </div>
<?php endif; ?>
```

</div>

<?php include("../includes/footer.php"); ?>
