<?php
session_start();
include('../includes/config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    $_SESSION['message'] = "Please login to delete your review.";
    header("Location: ../item/index.php");
    exit;
}

$userId = (int)$_SESSION['user_id'];
$reviewId = (int)($_GET['id'] ?? 0);

if ($reviewId <= 0) {
    $_SESSION['message'] = "Invalid review ID.";
    header("Location: ../item/index.php");
    exit;
}

// Fetch review to get item_id and confirm ownership
$stmt = mysqli_prepare($conn, "SELECT item_id FROM item_reviews WHERE review_id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $reviewId, $userId);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    mysqli_stmt_close($stmt);
    $_SESSION['message'] = "Review not found or you do not have permission to delete it.";
    header("Location: ../item/index.php");
    exit;
}

mysqli_stmt_bind_result($stmt, $itemId);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Delete the review
$stmt = mysqli_prepare($conn, "DELETE FROM item_reviews WHERE review_id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $reviewId, $userId);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($ok) {
    $_SESSION['success'] = "Review deleted successfully.";
} else {
    $_SESSION['message'] = "Failed to delete review. Please try again.";
}

// Redirect back to item page
header("Location: ../item/show.php?id={$itemId}");
exit;
?>
