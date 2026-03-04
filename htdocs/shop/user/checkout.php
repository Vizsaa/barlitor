<?php
session_start();
include("../includes/header.php");
include("../includes/config.php");
include("../includes/mail.php");

// Require login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Please login to checkout.';
    header('Location: login.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// Redirect if cart is empty
if (empty($_SESSION['cart']['products']) && empty($_SESSION['cart']['tools'])) {
    $_SESSION['message'] = 'Your cart is empty.';
    header('Location: cart.php');
    exit();
}

// Compute grand total
$grand_total = 0.0;
if (!empty($_SESSION['cart']['products'])) {
    foreach ($_SESSION['cart']['products'] as $product) {
        $grand_total += $product['price'] * $product['quantity'];
    }
}
if (!empty($_SESSION['cart']['tools'])) {
    foreach ($_SESSION['cart']['tools'] as $tool) {
        $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / (60*60*24) + 1;
        $grand_total += $tool['rate'] * $days * $tool['quantity'];
    }
}

// Handle checkout submission
if (isset($_POST['checkout'])) {
    $amount_paid = (float)$_POST['amount_paid'];

    if ($amount_paid < $grand_total) {
        $_SESSION['message'] = 'Amount paid is less than the grand total.';
        header('Location: checkout.php');
        exit();
    }

    $conn->begin_transaction();

    try {
        $date_placed = date('Y-m-d');

        // 1. Create orderinfo
        $stmt = $conn->prepare("INSERT INTO orderinfo (user_id, date_placed, status) VALUES (?, ?, 'Processing')");
        $stmt->bind_param("is", $user_id, $date_placed);
        $stmt->execute();
        $transaction_id = $stmt->insert_id;
        $stmt->close();

        // 2. Insert products sold
        if (!empty($_SESSION['cart']['products'])) {
            foreach ($_SESSION['cart']['products'] as $pid => $product) {
                $quantity = (int)$product['quantity'];
                $rate = (float)$product['price'];

                $stmt = $conn->prepare("INSERT INTO products_sold (transaction_id, product_id, quantity, rate_charged) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiid", $transaction_id, $pid, $quantity, $rate);
                $stmt->execute();
                $stmt->close();

                // Update stock
                $stmt = $conn->prepare("UPDATE item SET stock_quantity = GREATEST(0, stock_quantity - ?) WHERE item_id = ?");
                $stmt->bind_param("ii", $quantity, $pid);
                $stmt->execute();
                $stmt->close();
            }
        }

        // 3. Insert tool rentals
        if (!empty($_SESSION['cart']['tools'])) {
            foreach ($_SESSION['cart']['tools'] as $tool) {
                $pid = (int)$tool['id'];
                $start_date = $tool['start_date'];
                $due_date = $tool['due_date'];
                $quantity = (int)$tool['quantity'];
                $days = (strtotime($due_date) - strtotime($start_date)) / (60*60*24) + 1;
                $rate_total = $tool['rate'] * $days * $quantity;

                $stmt = $conn->prepare("INSERT INTO rental (transaction_id, customer_id, item_id, start_date, due_date, rate_charged, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiissdi", $transaction_id, $user_id, $pid, $start_date, $due_date, $rate_total, $quantity);
                $stmt->execute();
                $stmt->close();

                // Update stock
                $stmt = $conn->prepare("UPDATE item SET stock_quantity = GREATEST(0, stock_quantity - ?) WHERE item_id = ?");
                $stmt->bind_param("ii", $quantity, $pid);
                $stmt->execute();
                $stmt->close();
            }
        }

        // 4. Record payment
        $paid_on = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO payment (transaction_id, amount_paid, paid_on) VALUES (?, ?, ?)");
        $stmt->bind_param("dds", $transaction_id, $amount_paid, $paid_on);
        $stmt->execute();
        $stmt->close();

        $change = $amount_paid - $grand_total;

        // 5. Generate receipt
        $receipt_folder = __DIR__ . "/../Receipts/";
        if (!is_dir($receipt_folder)) mkdir($receipt_folder, 0777, true);
        $receipt_filename = "receipt_" . $transaction_id . ".txt";
        $receipt_file_path = $receipt_folder . $receipt_filename;

        $lines = [];
        $lines[] = "Receipt for Transaction #$transaction_id";
        $lines[] = "Date: " . date('Y-m-d H:i:s');
        $lines[] = "----------------------------------------";

        if (!empty($_SESSION['cart']['products'])) {
            $lines[] = "Products:";
            foreach ($_SESSION['cart']['products'] as $product) {
                $lines[] = $product['title'] . " x " . $product['quantity'] . " @ ₱" . number_format($product['price'], 2) . " = ₱" . number_format($product['price'] * $product['quantity'], 2);
            }
        }

        if (!empty($_SESSION['cart']['tools'])) {
            $lines[] = "Tool Rentals:";
            foreach ($_SESSION['cart']['tools'] as $tool) {
                $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / (60*60*24) + 1;
                $lines[] = $tool['title'] . " | " . $tool['start_date'] . " to " . $tool['due_date'] . " x " . $tool['quantity'] . " units for $days days @ ₱" . number_format($tool['rate'], 2) . " = ₱" . number_format($tool['rate'] * $days * $tool['quantity'], 2);
            }
        }

        $lines[] = "----------------------------------------";
        $lines[] = "Grand Total: ₱" . number_format($grand_total, 2);
        $lines[] = "Amount Paid: ₱" . number_format($amount_paid, 2);
        $lines[] = "Change: ₱" . number_format($change, 2);

        file_put_contents($receipt_file_path, implode(PHP_EOL, $lines));

        // 6. Send receipt via email
        $stmt = $conn->prepare("SELECT email, name FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($email, $name);
        $stmt->fetch();
        $stmt->close();

        $subject = "Your Transaction Receipt #$transaction_id";
        $plain = implode(PHP_EOL, $lines);
        $htmlLines = array_map('htmlspecialchars', $lines);
        $body_html = "<pre style=\"font-family: monospace;\">" . implode("\n", $htmlLines) . "</pre>";

        $attachments = [['path' => $receipt_file_path, 'name' => $receipt_filename]];
        send_email($email, $name ?: 'Customer', $subject, $body_html, $plain, $attachments);

        // Commit transaction and clear cart
        $conn->commit();
        unset($_SESSION['cart']);

        $_SESSION['success'] = "Checkout successful! Receipt has been sent to your email.";
        header("Location: cart.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Checkout failed: " . $e->getMessage();
        header("Location: cart.php");
        exit();
    }
}
?>

<div class="container my-5">
    <h2>Checkout</h2>

```
<?php if (isset($_SESSION['cart'])): ?>
    <p>Please confirm your payment and proceed.</p>
    <p>Grand Total: <strong>₱<?= number_format($grand_total, 2); ?></strong></p>

    <form method="post" action="checkout.php">
        <div class="mb-3">
            <label for="amount_paid" class="form-label">Amount Paid</label>
            <input type="number" name="amount_paid" id="amount_paid" class="form-control" step="0.01" min="<?= htmlspecialchars($grand_total) ?>" required>
        </div>
        <button type="submit" name="checkout" class="btn btn-success">Confirm Checkout</button>
    </form>
<?php endif; ?>
```

</div>

<?php include("../includes/footer.php"); ?>
