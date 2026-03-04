<?php
session_start();
include("../includes/config.php");
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Unauthorized.";
    header("Location: ../index.php");
    exit();
}

$id = (int)$_GET['id'];

// Fetch supplier
$result = mysqli_query($conn, "SELECT * FROM suppliers WHERE supplier_id=$id");
$supplier = mysqli_fetch_assoc($result);

if (!$supplier) {
    $_SESSION['message'] = "Supplier not found.";
    header("Location: suppliers.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $lead_time = trim($_POST['lead_time']);
    $website = trim($_POST['website']);

    $stmt = mysqli_prepare($conn, "UPDATE suppliers SET name=?, contact_email=?, contact_phone=?, lead_time=?, website=? WHERE supplier_id=?");
    mysqli_stmt_bind_param($stmt, "sssssi", $name, $email, $phone, $lead_time, $website, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $_SESSION['success'] = "Supplier updated successfully!";
    header("Location: suppliers.php");
    exit();
}
?>

<?php include("../includes/header.php"); ?>

<div class="container py-5">
    <h2>Edit Supplier</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Supplier Name</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($supplier['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($supplier['contact_email']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($supplier['contact_phone']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Lead Time</label>
            <input type="text" class="form-control" name="lead_time" value="<?= htmlspecialchars($supplier['lead_time']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Website</label>
            <input type="url" class="form-control" name="website" value="<?= htmlspecialchars($supplier['website']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Supplier</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>
