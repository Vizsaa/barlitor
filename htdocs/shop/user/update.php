<?php
// File: C:\information management\htdocs\shop\user\update.php
include_once("../includes/config.php");

// Require login
if (!isLoggedIn()) {
    $_SESSION['message'] = "Please login to update a profile.";
    redirect("login.php");
}

// Determine target user ID (from ?id= or fallback to current user)
$target_uid = isset($_GET['id']) ? (int)$_GET['id'] : getCurrentUserId();

// Permission check
$isOwnProfile = (getCurrentUserId() === $target_uid);
if (!$isOwnProfile && !isAdmin()) {
    $_SESSION['error'] = "You do not have permission to edit this profile.";
    redirect("profile.php?id=" . $target_uid);
}

// Only process POST submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect form data (match the names used in your edit form)
    $fname       = trim($_POST['fname'] ?? '');
    $lname       = trim($_POST['lname'] ?? '');
    $title       = trim($_POST['title'] ?? '');
    $addressline = trim($_POST['addressline'] ?? '');
    $town        = trim($_POST['town'] ?? '');
    $zipcode     = trim($_POST['zipcode'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');

    $errors = [];

    // Basic validation
    if ($fname === '') $errors[] = "First name is required.";
    if ($lname === '') $errors[] = "Last name is required.";

    // Handle avatar upload if present
    $avatar_db_path = null;
    if (!empty($_FILES['avatar']['name'])) {
        $file = $_FILES['avatar'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Error uploading avatar.";
        } else {
            $allowedTypes = [
                "image/jpeg" => "jpg",
                "image/png"  => "png",
                "image/gif"  => "gif"
            ];
            $maxSize = 5 * 1024 * 1024; // 5MB
            $mime = mime_content_type($file['tmp_name'] ?? '');

            if (!isset($allowedTypes[$mime])) {
                $errors[] = "Only JPG, PNG, GIF formats allowed for avatar.";
            }
            if ($file['size'] > $maxSize) {
                $errors[] = "Avatar exceeds 5MB limit.";
            }

            if (empty($errors)) {
                $uploadDir = realpath(__DIR__ . "/../uploads/avatars/") . "/";
                if ($uploadDir === false) {
                    $errors[] = "Avatar upload directory missing or not writable.";
                } else {
                    $ext = $allowedTypes[$mime];
                    $filename = "user_" . $target_uid . "_" . time() . "." . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                        $avatar_db_path = "uploads/avatars/" . $filename;
                    } else {
                        $errors[] = "Failed to save uploaded avatar.";
                    }
                }
            }
        }
    }

    // If validation failed, send errors back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        redirect("profile.php?id=" . $target_uid . "&edit=1");
    }

    // Build and run SQL update (two branches: with/without avatar)
    $fullName = $fname . " " . $lname;

    if ($avatar_db_path !== null) {
        $sql = "UPDATE users 
                SET name=?, avatar=?, title=?, fname=?, lname=?, addressline=?, town=?, zipcode=?, phone=? 
                WHERE id=? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssi",
            $fullName,
            $avatar_db_path,
            $title,
            $fname,
            $lname,
            $addressline,
            $town,
            $zipcode,
            $phone,
            $target_uid
        );
    } else {
        $sql = "UPDATE users 
                SET name=?, title=?, fname=?, lname=?, addressline=?, town=?, zipcode=?, phone=? 
                WHERE id=? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssi",
            $fullName,
            $title,
            $fname,
            $lname,
            $addressline,
            $town,
            $zipcode,
            $phone,
            $target_uid
        );
    }

    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . mysqli_error($conn);
        redirect("profile.php?id=" . $target_uid . "&edit=1");
    }

    $exec = mysqli_stmt_execute($stmt);
    $dbErr = mysqli_error($conn);
    mysqli_stmt_close($stmt);

    if ($exec === false) {
        $_SESSION['error'] = "Failed to update profile: " . $dbErr;
        redirect("profile.php?id=" . $target_uid . "&edit=1");
    }

    // Update session if the logged-in user updated their own profile
    if ($isOwnProfile) {
        $_SESSION['name'] = $fullName;
        if ($avatar_db_path !== null) $_SESSION['avatar'] = $avatar_db_path;
    }

    $_SESSION['success'] = "Profile updated successfully!";
    redirect("profile.php?id=" . $target_uid);
}

// If not POST, just redirect to profile
redirect("profile.php?id=" . $target_uid);
