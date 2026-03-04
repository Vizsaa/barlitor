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

// Soft delete: set deleted_at timestamp (create column if needed)
$checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM suppliers LIKE 'deleted_at'");
if(mysqli_num_rows($checkColumn) == 0) {
    mysqli_query($conn, "ALTER TABLE suppliers ADD deleted_at TIMESTAMP NULL DEFAULT NULL");
}

$delete = mysqli_query($conn, "UPDATE suppliers SET deleted_at = NOW() WHERE supplier_id = $supplierId");

if($delete){
    $_SESSION['success'] = "Supplier deleted successfully.";
}else{
    $_SESSION['message'] = "Failed to delete supplier.";
}

header("Location: suppliers.php");
exit();
