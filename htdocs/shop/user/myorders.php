<?php
session_start();
include("../includes/config.php");
include("../includes/header.php");

// Require login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Please login to view your orders.";
    header("Location: login.php");
    exit();
}

$userId = (int)$_SESSION['user_id'];

// Optional date-range filter
$dateFrom = isset($_GET['from']) ? $_GET['from'] : '';
$dateTo   = isset($_GET['to']) ? $_GET['to'] : '';

$params = [$userId];
$types  = "i";
$dateFilterSql = "";

if ($dateFrom !== '' && $dateTo !== '') {
    $dateFilterSql = " AND oi.date_placed BETWEEN ? AND ?";
    $params[] = $dateFrom;
    $params[] = $dateTo;
    $types   .= "ss";
} elseif ($dateFrom !== '') {
    $dateFilterSql = " AND oi.date_placed >= ?";
    $params[] = $dateFrom;
    $types   .= "s";
} elseif ($dateTo !== '') {
    $dateFilterSql = " AND oi.date_placed <= ?";
    $params[] = $dateTo;
    $types   .= "s";
}

// Fetch orders for this user
$sql = "
    SELECT oi.orderinfo_id,
           oi.date_placed,
           oi.status,
           p.amount_paid,
           p.paid_on
    FROM orderinfo oi
    LEFT JOIN payment p ON p.transaction_id = oi.orderinfo_id
    WHERE oi.user_id = ?
          $dateFilterSql
    ORDER BY COALESCE(p.paid_on, oi.date_placed) DESC, oi.orderinfo_id DESC
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Database error: " . $conn->error);
}

// Bind parameters dynamically
$stmt->bind_param($types, ...$params);
$stmt->execute();
$ordersResult = $stmt->get_result();
$orders = [];
while ($row = $ordersResult->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();

// Helper to fetch materials for a given transaction
function getMaterialsForTransaction(mysqli $conn, int $transactionId): array {
    $sql = "
        SELECT ps.product_id,
               i.title,
               ps.quantity,
               ps.rate_charged
        FROM products_sold ps
        JOIN item i ON i.item_id = ps.product_id
        WHERE ps.transaction_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
    return $rows;
}

// Helper to fetch rentals for a given transaction
function getRentalsForTransaction(mysqli $conn, int $transactionId): array {
    $sql = "
        SELECT r.item_id,
               i.title,
               r.start_date,
               r.due_date,
               r.quantity,
               r.rate_charged
        FROM rental r
        JOIN item i ON i.item_id = r.item_id
        WHERE r.transaction_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
    return $rows;
}
?>

<div class="container my-5">
    <h2 class="mb-4">My Orders</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Date-range filter -->
    <form method="get" class="row g-3 align-items-end mb-4">
        <div class="col-md-4">
            <label class="form-label">From (Order Date)</label>
            <input type="date" name="from" value="<?= htmlspecialchars($dateFrom) ?>" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">To (Order Date)</label>
            <input type="date" name="to" value="<?= htmlspecialchars($dateTo) ?>" class="form-control">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary mt-4">Filter</button>
            <a href="myorders.php" class="btn btn-secondary mt-4">Clear</a>
        </div>
    </form>

    <?php if (empty($orders)): ?>
        <div class="alert alert-warning">You have no orders for the selected period.</div>
    <?php else: ?>
        <div class="accordion" id="ordersAccordion">
            <?php foreach ($orders as $index => $order): ?>
                <?php
                    $materials = getMaterialsForTransaction($conn, (int)$order['orderinfo_id']);
                    $rentals   = getRentalsForTransaction($conn, (int)$order['orderinfo_id']);

                    $materialsTotal = 0;
                    foreach ($materials as $m) {
                        $materialsTotal += $m['quantity'] * $m['rate_charged'];
                    }

                    $rentalsTotal = 0;
                    foreach ($rentals as $r) {
                        $rentalsTotal += $r['rate_charged'];
                    }

                    $orderTotal = $materialsTotal + $rentalsTotal;
                    $paid = $order['amount_paid'] !== null ? (float)$order['amount_paid'] : null;
                    $change = ($paid !== null) ? ($paid - $orderTotal) : null;
                ?>
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="heading-<?= $order['orderinfo_id'] ?>">
                        <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse-<?= $order['orderinfo_id'] ?>"
                                aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>"
                                aria-controls="collapse-<?= $order['orderinfo_id'] ?>">
                            <div class="d-flex flex-column flex-md-row w-100 justify-content-between">
                                <span>
                                    <strong>Transaction #<?= $order['orderinfo_id'] ?></strong>
                                    &mdash; <?= htmlspecialchars($order['status']) ?>
                                </span>
                                <span class="text-muted">
                                    Order Date: <?= htmlspecialchars($order['date_placed']) ?>
                                    <?php if ($order['paid_on']): ?>
                                        | Paid On: <?= htmlspecialchars($order['paid_on']) ?>
                                    <?php endif; ?>
                                </span>
                                <span class="text-success fw-semibold">
                                    Total: ₱<?= number_format($orderTotal, 2) ?>
                                </span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse-<?= $order['orderinfo_id'] ?>"
                         class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>"
                         aria-labelledby="heading-<?= $order['orderinfo_id'] ?>"
                         data-bs-parent="#ordersAccordion">
                        <div class="accordion-body">

                            <?php if (!empty($materials)): ?>
                                <h5>Materials Sold</h5>
                                <div class="table-responsive mb-3">
                                    <table class="table table-sm table-striped align-middle">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Quantity</th>
                                                <th>Rate</th>
                                                <th>Line Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($materials as $m): ?>
                                                <?php $lineTotal = $m['quantity'] * $m['rate_charged']; ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($m['title']) ?></td>
                                                    <td><?= (int)$m['quantity'] ?></td>
                                                    <td>₱<?= number_format($m['rate_charged'], 2) ?></td>
                                                    <td>₱<?= number_format($lineTotal, 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($rentals)): ?>
                                <h5>Tool Rentals</h5>
                                <div class="table-responsive mb-3">
                                    <table class="table table-sm table-striped align-middle">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Start Date</th>
                                                <th>Due Date</th>
                                                <th>Quantity</th>
                                                <th>Total Rate Charged</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rentals as $r): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($r['title']) ?></td>
                                                    <td><?= htmlspecialchars($r['start_date']) ?></td>
                                                    <td><?= htmlspecialchars($r['due_date']) ?></td>
                                                    <td><?= (int)$r['quantity'] ?></td>
                                                    <td>₱<?= number_format($r['rate_charged'], 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>

                            <div class="border-top pt-3">
                                <p class="mb-1"><strong>Order Total:</strong> ₱<?= number_format($orderTotal, 2) ?></p>
                                <?php if ($paid !== null): ?>
                                    <p class="mb-1"><strong>Amount Paid:</strong> ₱<?= number_format($paid, 2) ?></p>
                                    <p class="mb-0"><strong>Change:</strong> ₱<?= number_format($change, 2) ?></p>
                                <?php else: ?>
                                    <p class="mb-0 text-muted">Payment record not found.</p>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>

