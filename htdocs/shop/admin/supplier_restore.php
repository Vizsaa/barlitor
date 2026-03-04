<?php
session_start();
include("../includes/config.php");

// Require admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Unauthorized access.";
    header("Location: ../index.php");
    exit();
}

// Validate supplier ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid supplier ID.";
    header("Location: suppliers.php");
    exit();
}

$supplierId = (int)$_GET['id'];

// Restore supplier
$sql = "UPDATE suppliers SET deleted_at = NULL WHERE supplier_id = $supplierId";
if (mysqli_query($conn, $sql)) {
    $_SESSION['success'] = "Supplier restored successfully.";
} else {
    $_SESSION['message'] = "Error restoring supplier.";
}

header("Location: suppliers.php");
exit();
