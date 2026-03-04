<?php
session_start();
include("../includes/config.php");
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Unauthorized.";
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $lead_time = trim($_POST['lead_time']);
    $website = trim($_POST['website']);

    $stmt = mysqli_prepare($conn, "INSERT INTO suppliers (name, contact_email, contact_phone, lead_time, website) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $lead_time, $website);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $_SESSION['success'] = "Supplier added successfully!";
    header("Location: suppliers.php");
    exit();
}
?>

<?php include("../includes/header.php"); ?>

<div class="container py-5">
    <h2>Add Supplier</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Supplier Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone">
        </div>
        <div class="mb-3">
            <label class="form-label">Lead Time</label>
            <input type="text" class="form-control" name="lead_time">
        </div>
        <div class="mb-3">
            <label class="form-label">Website</label>
            <input type="url" class="form-control" name="website">
        </div>
        <button type="submit" class="btn btn-success">Save Supplier</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>
