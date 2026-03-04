
<?php
session_start();
include('../includes/config.php');

// Redirect if not admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Store user input for repopulation if validation fails
$_SESSION['title'] = trim($_POST['title'] ?? '');
$_SESSION['desc']  = trim($_POST['description'] ?? '');
$_SESSION['cost']  = trim($_POST['cost_price'] ?? '');
$_SESSION['sell']  = trim($_POST['sell_price'] ?? '');
$_SESSION['supplier_id'] = $_POST['supplier_id'] ?? null;
$_SESSION['type'] = $_POST['type'] ?? '';
$_SESSION['category'] = $_POST['category'] ?? '';
$_SESSION['stock_quantity'] = $_POST['stock_quantity'] ?? '';

// Only proceed if form was submitted
if (isset($_POST['submit'])) {
    $title       = $_SESSION['title'];
    $desc        = $_SESSION['desc'];
    $cost        = $_SESSION['cost'];
    $sell        = $_SESSION['sell'];
    $category    = $_SESSION['category'];
    $stockQty    = $_SESSION['stock_quantity'];
    $supplier_id = !empty($_POST['supplier_id']) ? intval($_POST['supplier_id']) : null;
    $type        = $_SESSION['type'];
    $targetPath  = null;

    $hasError = false;

    // --- VALIDATION ---
    if (empty($title)) {
        $_SESSION['titleError'] = "Please enter an item title.";
        $hasError = true;
    }

    if (empty($desc)) {
        $_SESSION['descError'] = "Please enter an item description.";
        $hasError = true;
    }

    if (empty($cost) || !is_numeric($cost) || $cost < 0) {
        $_SESSION['costError'] = "Invalid cost price.";
        $hasError = true;
    }

    if (empty($sell) || !is_numeric($sell) || $sell < 0) {
        $_SESSION['sellError'] = "Invalid sell price.";
        $hasError = true;
    }

    if (empty($category)) {
        $_SESSION['categoryError'] = "Please select a category.";
        $hasError = true;
    }

    if (empty($type) || !in_array($type, ['product', 'tool'])) {
        $_SESSION['typeError'] = "Please select a valid type.";
        $hasError = true;
    }

    if (!is_numeric($stockQty) || $stockQty < 0) {
        $_SESSION['stockError'] = "Invalid stock quantity.";
        $hasError = true;
    }

    // --- IMAGE UPLOAD HANDLING ---
    if (!empty($_FILES['image_path']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType = $_FILES['image_path']['type'];
        $fileSize = $_FILES['image_path']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['imageError'] = "Only JPG and PNG files are allowed.";
            $hasError = true;
        } elseif ($fileSize > 5 * 1024 * 1024) {
            $_SESSION['imageError'] = "Image file size exceeds 5MB.";
            $hasError = true;
        } else {
            $uploadDir = __DIR__ . '/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $uniqueName = uniqid('item_', true) . '.' . pathinfo($_FILES['image_path']['name'], PATHINFO_EXTENSION);
            $targetPath = 'images/' . $uniqueName;

            if (!move_uploaded_file($_FILES['image_path']['tmp_name'], $uploadDir . $uniqueName)) {
                $_SESSION['imageError'] = "Failed to upload image.";
                $hasError = true;
            }
        }
    }

    // Redirect back if errors exist
    if ($hasError) {
        header("Location: create.php");
        exit;
    }

    // --- DATABASE INSERT ---
    $stmt = $conn->prepare("
        INSERT INTO item 
        (title, description, cost_price, sell_price, image_path, category, stock_quantity, supplier_id, type) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssddssiis", $title, $desc, $cost, $sell, $targetPath, $category, $stockQty, $supplier_id, $type);

    if ($stmt->execute()) {
        // Clear session form data
        unset($_SESSION['title'], $_SESSION['desc'], $_SESSION['cost'], $_SESSION['sell'], $_SESSION['supplier_id'], $_SESSION['type'], $_SESSION['category'], $_SESSION['stock_quantity']);
        $_SESSION['success'] = "Item added successfully!";
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error'] = "Failed to add item. Please try again.";
        header("Location: create.php");
        exit;
    }
} else {
    header("Location: create.php");
    exit;
}
?>