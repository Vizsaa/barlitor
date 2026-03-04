<?php
// Backward-compat shim for old cart endpoint.
// Redirect all requests to the new cart/add/update handlers.
session_start();

// If this was a POST from the legacy home page form, map fields into the new structure.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Old form used `item_id`, `item_qty`, `type=add`
    $itemId  = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
    $qty     = isset($_POST['item_qty']) ? (int)$_POST['item_qty'] : 1;

    if ($itemId > 0 && $qty > 0) {
        // Treat everything from this legacy page as a regular product add.
        $_POST = [
            'product_id' => $itemId,
            'quantity'   => $qty,
            'type'       => 'product',
        ];
        // Forward into the new add_to_cart logic.
        require __DIR__ . '/user/add_to_cart.php';
        exit;
    }
}

// For any other access (e.g. GET), just send the user to the main cart page.
header('Location: /shop/user/cart.php');
exit;

