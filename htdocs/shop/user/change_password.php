// Handle errors or success
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    } else if ($success) {
        $_SESSION['success'] = "Password updated successfully!";
    }
    
    if ($success) {
        redirect("profile.php?id=" . $uid);
    }<?php
// File: C:\information management\htdocs\shop\user\change_password.php

include_once("../includes/config.php");
include_once("../includes/header.php");

// Require login
if (!isLoggedIn()) {
    $_SESSION['message'] = "Please login to change your password.";
    redirect("login.php");
}

$uid = getCurrentUserId();
if (!$uid) {
    $_SESSION['error'] = "Invalid session. Please login again.";
    redirect("login.php");
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $current_password = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';



    // Step 1: Validate that fields are not empty
    if (empty($current_password)) {
        $errors[] = "Current password is required.";
    }
    if (empty($new_password)) {
        $errors[] = "New password is required.";
    }
    if (empty($confirm_password)) {
        $errors[] = "Confirm password is required.";
    }

    // Step 2: Check if new passwords match
    if (!empty($new_password) && !empty($confirm_password) && $new_password !== $confirm_password) {
        $errors[] = "New password and confirmation do not match.";
    }

    // Step 3: Check password length
    if (!empty($new_password) && strlen($new_password) < 4) {
        $errors[] = "Password must be at least 4 characters long.";
    }

    // Step 4: Fetch the current password from database
    if (empty($errors)) {
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            $errors[] = "Database error: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "i", $uid);
            if (!mysqli_stmt_execute($stmt)) {
                $errors[] = "Database query failed: " . mysqli_stmt_error($stmt);
            } else {
                mysqli_stmt_bind_result($stmt, $db_password);
                if (mysqli_stmt_fetch($stmt)) {
                    // User found, check if current password matches
                    if ($current_password !== $db_password) {
                        $errors[] = "Current password is incorrect.";
                    }
                } else {
                    $errors[] = "User not found in database.";
                }
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Step 5: If no errors, update the password
    if (empty($errors)) {
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            $errors[] = "Database error: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "si", $new_password, $uid);
            if (!mysqli_stmt_execute($stmt)) {
                $errors[] = "Failed to update password: " . mysqli_stmt_error($stmt);
            } else {
                $success = true;
                $_SESSION['success'] = "Password updated successfully!";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Handle errors or success
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }
    
    if ($success) {
        redirect("profile.php?id=" . $uid);
    }
}
?>

<div class="container-xl px-4 mt-4">
    <?php include("../includes/alert.php"); ?>

    <h2 class="mb-4">Change Password</h2>

    <form method="POST" action="change_password.php">
        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" class="form-control" name="current_password" required>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" class="form-control" name="new_password" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" name="confirm_password" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Change Password</button>
        <a href="profile.php?id=<?= htmlspecialchars($uid) ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include("../includes/footer.php"); ?>