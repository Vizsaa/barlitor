<?php
session_start();
include('../includes/config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    $_SESSION['message'] = "You must be logged in as a customer to review.";
    header("Location: ../item/index.php");
    exit;
}

$userId = (int)$_SESSION['user_id'];
$itemId = (int)($_POST['item_id'] ?? 0);
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$comment = trim($_POST['comment'] ?? '');

// Basic validation
if ($itemId <= 0) {
    $_SESSION['message'] = "Invalid product.";
    header("Location: ../item/index.php");
    exit;
}
if ($rating < 1 || $rating > 5) $rating = 5; // default to 5 if invalid
if ($comment === '') {
    $_SESSION['message'] = "Please write a comment for your review.";
    header("Location: ../item/show.php?id={$itemId}");
    exit;
}
if (strlen($comment) > 2000) {
    $comment = substr($comment, 0, 2000);
}

// -------------------------------
// Check purchase ownership
// -------------------------------
// 1) Check orderline (legacy orderline.product_id)
// 2) If not found, check products_sold (products_sold.product_id)
// -------------------------------
$owned = false;

// Check orderline
$query1 = "
    SELECT 1
    FROM orderinfo oi
    JOIN orderline ol ON oi.orderinfo_id = ol.orderinfo_id
    WHERE oi.user_id = ? AND ol.product_id = ?
    LIMIT 1
";
if ($stmt = mysqli_prepare($conn, $query1)) {
    mysqli_stmt_bind_param($stmt, "ii", $userId, $itemId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $owned = true;
    }
    mysqli_stmt_close($stmt);
}

// If not found, check products_sold -> transaction_id -> orderinfo.user_id
if (!$owned) {
    $query2 = "
        SELECT 1
        FROM orderinfo oi
        JOIN products_sold ps ON oi.orderinfo_id = ps.transaction_id
        WHERE oi.user_id = ? AND ps.product_id = ?
        LIMIT 1
    ";
    if ($stmt = mysqli_prepare($conn, $query2)) {
        mysqli_stmt_bind_param($stmt, "ii", $userId, $itemId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $owned = true;
        }
        mysqli_stmt_close($stmt);
    }
}

if (!$owned) {
    $_SESSION['message'] = "You can only review products you purchased.";
    header("Location: ../item/show.php?id={$itemId}");
    exit;
}

// -------------------------------
// Check if user already submitted a review
// -------------------------------
if ($stmt = mysqli_prepare($conn, "SELECT review_id FROM item_reviews WHERE user_id=? AND item_id=?")) {
    mysqli_stmt_bind_param($stmt, "ii", $userId, $itemId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        // fetch review_id for redirect
        mysqli_stmt_bind_result($stmt, $existingReviewId);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION['message'] = "You already submitted a review. You can edit it.";
        header("Location: ../user/review_edit.php?id=" . intval($existingReviewId));
        exit;
    }
    mysqli_stmt_close($stmt);
}

// -------------------------------
// Mask foul words
// -------------------------------
$foulWords = [
    'shit','damn','fuck','bitch','bastard','asshole','dick','cunt','piss','bollocks','crap','slut','whore','faggot','motherfucker','twat','prick',
    'tangina','puta','gago','tarantado','tanga','bobo','loko','ulol','engkanto','hayop','kapatidngputa','putangina','pokpok','leche','putangina mo','gaga','putang ina','ulol ka','loko ka'
];
foreach ($foulWords as $word) {
    $comment = preg_replace("/\b".preg_quote($word,'/')."\b/i", str_repeat('*', strlen($word)), $comment);
}

// -------------------------------
// Insert review
// -------------------------------
if ($stmt = mysqli_prepare($conn, "INSERT INTO item_reviews (item_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())")) {
    mysqli_stmt_bind_param($stmt, "iiis", $itemId, $userId, $rating, $comment);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($ok) {
        $_SESSION['success'] = "Review submitted successfully!";
    } else {
        $_SESSION['message'] = "Failed to submit review. Please try again.";
    }
} else {
    $_SESSION['message'] = "Database error preparing statement.";
}

header("Location: ../item/show.php?id={$itemId}");
exit;
?>
