<?php
include("../includes/config.php");

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;

// Only allow users to upload their own avatar
if (!$user_id || $user_id != $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Check if file was uploaded
if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit();
}

$file = $_FILES['avatar'];
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$max_size = 5 * 1024 * 1024; // 5MB

// Validate file type
if (!in_array($file['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP allowed']);
    exit();
}

// Validate file size
if ($file['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum 5MB']);
    exit();
}

// Create uploads directory if it doesn't exist
$upload_dir = "../uploads/avatars/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Generate unique filename
$file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$unique_name = "user_" . $user_id . "_" . time() . "." . $file_ext;
$upload_path = $upload_dir . $unique_name;

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save file']);
    exit();
}

// Update database with new avatar path
$relative_path = "uploads/avatars/" . $unique_name;
$sql = "UPDATE users SET avatar=? WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit();
}

mysqli_stmt_bind_param($stmt, "si", $relative_path, $user_id);
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => false, 'message' => 'Failed to update database']);
    mysqli_stmt_close($stmt);
    exit();
}

mysqli_stmt_close($stmt);

// Update session avatar
$_SESSION['avatar'] = $relative_path;

echo json_encode(['success' => true, 'message' => 'Avatar uploaded successfully', 'avatar_url' => $relative_path]);
exit();
?>