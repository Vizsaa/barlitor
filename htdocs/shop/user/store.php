<?php
session_start();
include("../includes/config.php");

// Helper function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Reject non-POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['message'] = 'Invalid request method.';
    redirect('register.php');
}

// Collect input
$name        = trim($_POST['name'] ?? '');
$email       = trim($_POST['email'] ?? '');
$password    = trim($_POST['password'] ?? '');
$confirmPass = trim($_POST['confirmPass'] ?? '');

// Store sticky values
$_SESSION['old'] = [
    'name'  => $name,
    'email' => $email
];

// -------------------------------
// Basic validation
// -------------------------------
if ($name === '' || $email === '' || $password === '' || $confirmPass === '') {
    $_SESSION['message'] = 'Please fill out all required fields.';
    redirect('register.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = 'Invalid email address.';
    redirect('register.php');
}

if ($password !== $confirmPass) {
    $_SESSION['message'] = 'Passwords do not match.';
    redirect('register.php');
}

if (strlen($password) < 4) {
    $_SESSION['message'] = 'Password must be at least 4 characters.';
    redirect('register.php');
}

// -------------------------------
// Check for existing email
// -------------------------------
$sqlCheck = "SELECT id FROM users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sqlCheck);
if (!$stmt) {
    $_SESSION['message'] = 'Database error (check prepare failed).';
    redirect('register.php');
}
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    $_SESSION['message'] = 'Email already registered. Please log in.';
    redirect('register.php');
}
mysqli_stmt_close($stmt);

// -------------------------------
// Insert new user (plaintext password per class requirement)
// -------------------------------
$sqlInsert = "INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, 'customer', NOW())";
$stmt = mysqli_prepare($conn, $sqlInsert);
if (!$stmt) {
    $_SESSION['message'] = 'Database error (insert prepare failed).';
    redirect('register.php');
}
mysqli_stmt_bind_param($stmt, "sss", $name, $email, $password);
$ok = mysqli_stmt_execute($stmt);

if ($ok) {
    $newId = mysqli_insert_id($conn);

    // Auto-login after registration
    session_regenerate_id(true);
    $_SESSION['user_id'] = $newId;
    $_SESSION['email']   = $email;
    $_SESSION['role']    = 'customer';
    $_SESSION['name']    = $name;

    unset($_SESSION['old']);

    $_SESSION['message'] = 'Registration successful. Welcome!';
    redirect('profile.php');

} else {
    mysqli_stmt_close($stmt);
    $_SESSION['message'] = 'Failed to create account.';
    redirect('register.php');
}