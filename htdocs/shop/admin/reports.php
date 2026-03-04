<?php
session_start();
include("../includes/adminHeader.php");
include("../includes/config.php");

// Require admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../user/login.php");
    exit();
}

// Filters
$dateFrom = isset($_GET['from']) ? $_GET['from'] : '';
$dateTo   = isset($_GET['to']) ? $_GET['to'] : '';
$viewType = isset($_GET['type']) ? $_GET['type'] : 'all'; // all | materials | rentals

if ($dateFrom === '') {
    $dateFrom = date('Y-m-01');
}
if ($dateTo === '') {
    $dateTo = date('Y-m-d');
}

// Validate date format (basic)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
    $dateFrom = date('Y-m-01');
    $dateTo = date('Y-m-d');
}

// Handle CSV export
if (isset($_GET['export']) && $_GET['export'] === '1') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=brutor_report_' . $dateFrom . '_to_' . $dateTo . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Type', 'Transaction ID', 'Order Date', 'Item', 'Quantity', 'Rate Charged', 'Line Total']);

    // Materials
    if ($viewType === 'all' || $viewType === 'materials') {
        $sql = "
            SELECT 'Materials' AS row_type,
                   oi.orderinfo_id,
                   oi.date_placed,
                   i.title,
                   ps.quantity,
                   ps.rate_charged,
                   (ps.quantity * ps.rate_charged) AS line_total
            FROM products_sold ps
            JOIN orderinfo oi ON oi.orderinfo_id = ps.transaction_id
            JOIN item i ON i.item_id = ps.product_id
            WHERE oi.date_placed BETWEEN ? AND ?
            ORDER BY oi.date_placed DESC, oi.orderinfo_id DESC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $dateFrom, $dateTo);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['row_type'],
                $row['orderinfo_id'],
                $row['date_placed'],
                $row['title'],
                (int)$row['quantity'],
                number_format($row['rate_charged'], 2),
                number_format($row['line_total'], 2),
            ]);
        }
        $stmt->close();
    }

    // Rentals
    if ($viewType === 'all' || $viewType === 'rentals') {
        $sql = "
            SELECT 'Rentals' AS row_type,
                   oi.orderinfo_id,
                   oi.date_placed,
                   i.title,
                   r.quantity,
                   r.rate_charged AS line_total
            FROM rental r
            JOIN orderinfo oi ON oi.orderinfo_id = r.transaction_id
            JOIN item i ON i.item_id = r.item_id
            WHERE oi.date_placed BETWEEN ? AND ?
            ORDER BY oi.date_placed DESC, oi.orderinfo_id DESC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $dateFrom, $dateTo);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['row_type'],
                $row['orderinfo_id'],
                $row['date_placed'],
                $row['title'],
                (int)$row['quantity'],
                '', // rate per day not stored separately here
                number_format($row['line_total'], 2),
            ]);
        }
        $stmt->close();
    }

    fclose($output);
    exit();
}

// Compute totals
function getScalar(mysqli $conn, string $sql, array $params = [], string $types = ""): float {
    $stmt = $conn->prepare($sql);
    if ($types !== "" && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $stmt->bind_result($value);
    $stmt->fetch();
    $stmt->close();
    return (float)$value;
}

$materialsTotal = 0.0;
$rentalsTotal   = 0.0;

if ($viewType === 'all' || $viewType === 'materials') {
    $materialsTotal = getScalar(
        $conn,
        "
        SELECT COALESCE(SUM(ps.quantity * ps.rate_charged), 0)
        FROM products_sold ps
        JOIN orderinfo oi ON oi.orderinfo_id = ps.transaction_id
        WHERE oi.date_placed BETWEEN ? AND ?
        ",
        [$dateFrom, $dateTo],
        "ss"
    );
}

if ($viewType === 'all' || $viewType === 'rentals') {
    $rentalsTotal = getScalar(
        $conn,
        "
        SELECT COALESCE(SUM(r.rate_charged), 0)
        FROM rental r
        JOIN orderinfo oi ON oi.orderinfo_id = r.transaction_id
        WHERE oi.date_placed BETWEEN ? AND ?
        ",
        [$dateFrom, $dateTo],
        "ss"
    );
}

$totalIncome = $materialsTotal + $rentalsTotal;

// Expenses in range
$expenseTotal = getScalar(
    $conn,
    "
    SELECT COALESCE(SUM(amount), 0)
    FROM expense
    WHERE expense_date BETWEEN ? AND ?
    ",
    [$dateFrom, $dateTo],
    "ss"
);

$netIncome = $totalIncome - $expenseTotal;

// Fetch detailed rows for grid
$materialsRows = [];
$rentalsRows = [];

if ($viewType === 'all' || $viewType === 'materials') {
    $sql = "
        SELECT oi.orderinfo_id,
               oi.date_placed,
               i.title,
               ps.quantity,
               ps.rate_charged,
               (ps.quantity * ps.rate_charged) AS line_total
        FROM products_sold ps
        JOIN orderinfo oi ON oi.orderinfo_id = ps.transaction_id
        JOIN item i ON i.item_id = ps.product_id
        WHERE oi.date_placed BETWEEN ? AND ?
        ORDER BY oi.date_placed DESC, oi.orderinfo_id DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $dateFrom, $dateTo);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $materialsRows[] = $row;
    }
    $stmt->close();
}

if ($viewType === 'all' || $viewType === 'rentals') {
    $sql = "
        SELECT oi.orderinfo_id,
               oi.date_placed,
               i.title,
               r.start_date,
               r.due_date,
               r.quantity,
               r.rate_charged AS line_total
        FROM rental r
        JOIN orderinfo oi ON oi.orderinfo_id = r.transaction_id
        JOIN item i ON i.item_id = r.item_id
        WHERE oi.date_placed BETWEEN ? AND ?
        ORDER BY oi.date_placed DESC, oi.orderinfo_id DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $dateFrom, $dateTo);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $rentalsRows[] = $row;
    }
    $stmt->close();
}
?>

<div class="container py-5">
    <h2 class="mb-4">Reports &amp; Analytics</h2>

    <!-- Filters -->
    <form method="get" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">From (Order Date)</label>
            <input type="date" name="from" value="<?= htmlspecialchars($dateFrom) ?>" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">To (Order Date)</label>
            <input type="date" name="to" value="<?= htmlspecialchars($dateTo) ?>" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">View</label>
            <div class="btn-group w-100" role="group">
                <button type="submit" name="type" value="all"
                        class="btn btn-outline-primary <?= $viewType === 'all' ? 'active' : '' ?>">All</button>
                <button type="submit" name="type" value="materials"
                        class="btn btn-outline-primary <?= $viewType === 'materials' ? 'active' : '' ?>">Materials</button>
                <button type="submit" name="type" value="rentals"
                        class="btn btn-outline-primary <?= $viewType === 'rentals' ? 'active' : '' ?>">Rentals</button>
            </div>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary mt-4 w-100">Apply</button>
            <a href="reports.php?from=<?= htmlspecialchars($dateFrom) ?>&to=<?= htmlspecialchars($dateTo) ?>&type=<?= htmlspecialchars($viewType) ?>&export=1"
               class="btn btn-success mt-4 w-100">
                Export CSV
            </a>
        </div>
    </form>

    <!-- Summary cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-light mb-3">
                <div class="card-header">Materials Income</div>
                <div class="card-body">
                    <h5 class="card-title">₱<?= number_format($materialsTotal, 2) ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light mb-3">
                <div class="card-header">Rentals Income</div>
                <div class="card-body">
                    <h5 class="card-title">₱<?= number_format($rentalsTotal, 2) ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light mb-3">
                <div class="card-header">Expenses</div>
                <div class="card-body">
                    <h5 class="card-title text-danger">₱<?= number_format($expenseTotal, 2) ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light mb-3">
                <div class="card-header">Net Income</div>
                <div class="card-body">
                    <h5 class="card-title <?= $netIncome >= 0 ? 'text-success' : 'text-danger' ?>">
                        ₱<?= number_format($netIncome, 2) ?>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail grids -->
    <?php if ($viewType === 'all' || $viewType === 'materials'): ?>
        <h4 class="mt-4">Materials Sold</h4>
        <?php if (empty($materialsRows)): ?>
            <p class="text-muted">No materials sold in the selected range.</p>
        <?php else: ?>
            <div class="table-responsive mb-4">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Transaction #</th>
                            <th>Order Date</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Rate</th>
                            <th>Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materialsRows as $row): ?>
                            <tr>
                                <td><?= (int)$row['orderinfo_id'] ?></td>
                                <td><?= htmlspecialchars($row['date_placed']) ?></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= (int)$row['quantity'] ?></td>
                                <td>₱<?= number_format($row['rate_charged'], 2) ?></td>
                                <td>₱<?= number_format($row['line_total'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($viewType === 'all' || $viewType === 'rentals'): ?>
        <h4 class="mt-4">Tool Rentals</h4>
        <?php if (empty($rentalsRows)): ?>
            <p class="text-muted">No rentals in the selected range.</p>
        <?php else: ?>
            <div class="table-responsive mb-4">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Transaction #</th>
                            <th>Order Date</th>
                            <th>Item</th>
                            <th>Start Date</th>
                            <th>Due Date</th>
                            <th>Quantity</th>
                            <th>Total Charged</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rentalsRows as $row): ?>
                            <tr>
                                <td><?= (int)$row['orderinfo_id'] ?></td>
                                <td><?= htmlspecialchars($row['date_placed']) ?></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= htmlspecialchars($row['start_date']) ?></td>
                                <td><?= htmlspecialchars($row['due_date']) ?></td>
                                <td><?= (int)$row['quantity'] ?></td>
                                <td>₱<?= number_format($row['line_total'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>

