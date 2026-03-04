<?php
session_start();
include("../includes/config.php");

// Require admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "Unauthorized access.";
    header("Location: ../index.php");
    exit();
}

// Fetch active suppliers
$activeSuppliers = mysqli_query($conn, "SELECT * FROM suppliers WHERE deleted_at IS NULL ORDER BY supplier_id ASC");

// Fetch deleted suppliers
$deletedSuppliers = mysqli_query($conn, "SELECT * FROM suppliers WHERE deleted_at IS NOT NULL ORDER BY supplier_id ASC");
?>

<?php include("../includes/header.php"); ?>

<div class="container py-5">
    <h2>Suppliers</h2>
    <a href="supplier_create.php" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i> Add Supplier</a>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <!-- Active Suppliers -->
    <h4>Active Suppliers</h4>
    <table class="table table-bordered table-striped mb-5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Lead Time</th>
                <th>Website</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($activeSuppliers)): ?>
                <tr>
                    <td><?= $row['supplier_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['contact_email']) ?></td>
                    <td><?= htmlspecialchars($row['contact_phone']) ?></td>
                    <td><?= htmlspecialchars($row['lead_time']) ?></td>
                    <td><a href="<?= htmlspecialchars($row['website']) ?>" target="_blank"><?= htmlspecialchars($row['website']) ?></a></td>
                    <td>
                        <a href="supplier_edit.php?id=<?= $row['supplier_id'] ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        <a href="supplier_delete.php?id=<?= $row['supplier_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this supplier?');"><i class="fa-solid fa-trash"></i> Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Deleted Suppliers -->
    <h4>Deleted Suppliers</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Lead Time</th>
                <th>Website</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($deletedSuppliers)): ?>
                <tr class="table-secondary">
                    <td><?= $row['supplier_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['contact_email']) ?></td>
                    <td><?= htmlspecialchars($row['contact_phone']) ?></td>
                    <td><?= htmlspecialchars($row['lead_time']) ?></td>
                    <td><a href="<?= htmlspecialchars($row['website']) ?>" target="_blank"><?= htmlspecialchars($row['website']) ?></a></td>
                    <td>
                        <a href="supplier_restore.php?id=<?= $row['supplier_id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Restore this supplier?');"><i class="fa-solid fa-rotate-left"></i> Restore</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

<?php include("../includes/footer.php"); ?>
