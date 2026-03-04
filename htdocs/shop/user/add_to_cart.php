<?php
session_start();
include("../includes/config.php");

// Require login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please login to add products to your cart.';
    header('Location: login.php');
    exit();
}

// Sanitize POST inputs
$product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
$type = isset($_POST['type']) ? $_POST['type'] : ''; // 'product' or 'tool'

// Fetch product/tool from database
$stmt = $conn->prepare("SELECT * FROM item WHERE item_id = ? AND deleted_at IS NULL LIMIT 1");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    $_SESSION['message'] = "Product not found.";
    header("Location: store.php");
    exit();
}

// Initialize cart session arrays if not exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        'products' => [],
        'tools' => []
    ];
}

// -----------------------------
// CASE 1: Regular product (quantity-based)
// -----------------------------
if ($type === 'product') {
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
    if ($quantity <= 0) $quantity = 1;

    // Merge logic: if product exists, increment quantity
    if (isset($_SESSION['cart']['products'][$product_id])) {
        $new_qty = $_SESSION['cart']['products'][$product_id]['quantity'] + $quantity;
        $_SESSION['cart']['products'][$product_id]['quantity'] = min($new_qty, $product['stock_quantity']); // enforce stock limit
    } else {
        $_SESSION['cart']['products'][$product_id] = [
            'title' => $product['title'],
            'price' => $product['sell_price'],
            'quantity' => min($quantity, $product['stock_quantity'])
        ];
    }

    $_SESSION['message'] = "{$product['title']} added to cart.";

// -----------------------------
// CASE 2: Tool (rental-based with quantity)
// -----------------------------
} elseif ($type === 'tool') {
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $due_date = isset($_POST['due_date']) ? $_POST['due_date'] : '';
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
    if ($quantity <= 0) $quantity = 1;

    // Validate dates
    if (!$start_date || !$due_date || strtotime($start_date) > strtotime($due_date)) {
        $_SESSION['message'] = "Invalid rental dates.";
        header("Location: store.php");
        exit();
    }

    // Merge logic: check if same tool & same rental dates already exist
    $merged = false;
    foreach ($_SESSION['cart']['tools'] as &$tool) {
        if ($tool['id'] == $product_id && $tool['start_date'] === $start_date && $tool['due_date'] === $due_date) {
            $new_qty = $tool['quantity'] + $quantity;
            $tool['quantity'] = min($new_qty, $product['stock_quantity']); // enforce stock limit
            $merged = true;
            break;
        }
    }
    unset($tool);

    if (!$merged) {
        $_SESSION['cart']['tools'][] = [
            'id' => $product_id,
            'title' => $product['title'],
            'rate' => $product['sell_price'],
            'start_date' => $start_date,
            'due_date' => $due_date,
            'quantity' => min($quantity, $product['stock_quantity']) // enforce stock limit
        ];
    }

    $_SESSION['message'] = "{$product['title']} rental added to cart.";

// -----------------------------
// INVALID TYPE
// -----------------------------
} else {
    $_SESSION['message'] = "Invalid product type.";
}

// Redirect back to cart page
header("Location: cart.php");
exit();
?>