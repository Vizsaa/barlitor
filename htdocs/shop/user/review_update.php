<?php
session_start();
include('../includes/config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    $_SESSION['message'] = "Unauthorized.";
    header("Location: ../item/index.php");
    exit;
}

$reviewId = (int)($_POST['review_id'] ?? 0);
$userId = $_SESSION['user_id'];
$rating = intval($_POST['rating']);
$comment = trim($_POST['comment']);

// ✅ Fetch item_id first
$itemIdResult = mysqli_query($conn, "SELECT item_id FROM item_reviews WHERE review_id=$reviewId AND user_id=$userId");
$itemIdRow = mysqli_fetch_assoc($itemIdResult);
if (!$itemIdRow) {
    $_SESSION['message'] = "Review not found.";
    header("Location: ../item/index.php");
    exit;
}
$itemId = $itemIdRow['item_id'];

// ✅ Simple regex to mask foul words
$foulWords = [
    // English
    'shit', 'damn', 'fuck', 'bitch', 'bastard', 'asshole', 'dick', 'cunt', 'piss', 'bollocks', 'crap', 'slut', 'whore', 'faggot', 'motherfucker', 'twat', 'prick',

    // Tagalog / Filipino
    'tangina', 'puta', 'gago', 'tarantado', 'tanga', 'bobo', 'loko', 'ulol', 'engkanto', 'hayop', 'kapatidngputa', 'putangina', 'pokpok', 'leche', 'putangina mo', 'gaga', 'putang ina', 'ulol ka', 'loko ka'
];
foreach ($foulWords as $word) {
    $comment = preg_replace("/\b".preg_quote($word,'/')."\b/i", str_repeat('*', strlen($word)), $comment);
}

// Update review
$stmt = mysqli_prepare($conn, "UPDATE item_reviews SET rating=?, comment=?, created_at=NOW() WHERE review_id=? AND user_id=?");
mysqli_stmt_bind_param($stmt, "isii", $rating, $comment, $reviewId, $userId);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$_SESSION['success'] = "Review updated successfully!";
header("Location: ../item/show.php?id=$itemId");
exit;
?>
