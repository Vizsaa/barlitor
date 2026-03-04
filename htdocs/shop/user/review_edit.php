<?php
session_start();
include('../includes/header.php');
include('../includes/config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    $_SESSION['message'] = "Please login to edit your review.";
    header("Location: ../item/index.php");
    exit;
}

$reviewId = (int)($_GET['id'] ?? 0);
$userId = $_SESSION['user_id'];

// Fetch review
$sql = "SELECT * FROM item_reviews WHERE review_id = $reviewId AND user_id = $userId";
$result = mysqli_query($conn, $sql);
$review = mysqli_fetch_assoc($result);

if (!$review) {
    $_SESSION['message'] = "Review not found.";
    header("Location: ../item/index.php");
    exit;
}
?>

<body class="bg-light">
<div class="container py-5">
    <h2>Edit Your Review</h2>

    <form method="POST" action="review_update.php">
        <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
        <div class="mb-3">
            <label for="rating" class="form-label">Rating</label>
            <select name="rating" id="rating" class="form-select" required>
                <?php for ($i=1; $i<=5; $i++): ?>
                    <option value="<?= $i ?>" <?= $review['rating']==$i?'selected':'' ?>><?= $i ?> star<?= $i>1?'s':'' ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="comment" class="form-label">Comment</label>
            <textarea name="comment" id="comment" class="form-control" rows="3" required><?= htmlspecialchars($review['comment']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Review</button>
        <a href="../item/show.php?id=<?= $review['item_id'] ?>" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
<?php include('../includes/footer.php'); ?>
</body>
