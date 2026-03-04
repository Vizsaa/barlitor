<?php
include("../includes/config.php");
include("../includes/header.php");

// Get the user ID from URL, fallback to logged-in user if not set
$view_uid = isset($_GET['id']) ? (int)$_GET['id'] : (isLoggedIn() ? $_SESSION['user_id'] : null);

if (!$view_uid) {
    echo "<div class='container mt-4 alert alert-danger'>No user specified.</div>";
    include("../includes/footer.php");
    exit();
}

// Load user info from DB
$sql = "SELECT id, name, email, avatar, title, fname, lname, addressline, town, zipcode, phone 
        FROM users WHERE id=? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $view_uid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    echo "<div class='container mt-4 alert alert-danger'>User not found.</div>";
    include("../includes/footer.php");
    exit();
}

// Avatar URL
$avatarUrl = $user['avatar'] 
             ? "../" . $user['avatar'] . "?v=" . time() 
             : "https://bootdey.com/img/Content/avatar/avatar1.png";

// Determine permissions
$isOwnProfile = isLoggedIn() && $_SESSION['user_id'] == $view_uid;
$isAdmin = isAdmin();
$isEditing = isset($_GET['edit']) && $_GET['edit'] == '1' && $isOwnProfile;
?>

<div class="container-xl px-4 mt-4">
    <?php include("../includes/alert.php"); ?>

    <nav class="nav nav-borders">
        <a class="nav-link active ms-0">Profile</a>
    </nav>

    <hr class="mt-0 mb-4">

    <div class="row">

        <!-- LEFT COLUMN -->
        <div class="col-xl-4">
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Profile Picture</div>
                <div class="card-body text-center">
                    <img id="avatarPreview" class="img-account-profile rounded-circle mb-2"
                         src="<?= htmlspecialchars($avatarUrl) ?>"
                         style="width:140px;height:140px;object-fit:cover;">
                    
                    <?php if ($isOwnProfile && $isEditing): ?>
                        <div class="mt-3">
                            <form id="avatarForm" enctype="multipart/form-data">
                                <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;">
                                <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('avatarInput').click();">
                                    Change Profile Image
                                </button>
                            </form>
                        </div>
                    <?php elseif ($isOwnProfile): ?>
                        <p class="text-muted small mt-3">Click "Edit Profile" to change your image</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Account Details</div>
                <div class="card-body">

                    <?php if ($isEditing): ?>
                        <!-- EDIT MODE -->
                        <form method="POST" action="update.php">

                            <div class="mb-3">
                                <label class="small mb-1">Email</label>
                                <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">First name</label>
                                    <input class="form-control" type="text" name="fname" value="<?= htmlspecialchars($user['fname'] ?? '') ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="small mb-1">Last name</label>
                                    <input class="form-control" type="text" name="lname" value="<?= htmlspecialchars($user['lname'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Address</label>
                                    <input class="form-control" type="text" name="addressline" value="<?= htmlspecialchars($user['addressline'] ?? '') ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="small mb-1">Town</label>
                                    <input class="form-control" type="text" name="town" value="<?= htmlspecialchars($user['town'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Zip Code</label>
                                    <input class="form-control" type="text" name="zipcode" value="<?= htmlspecialchars($user['zipcode'] ?? '') ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="small mb-1">Title</label>
                                    <input class="form-control" type="text" name="title" value="<?= htmlspecialchars($user['title'] ?? '') ?>" placeholder="e.g., Mr, Mrs, Miss">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1">Phone</label>
                                <input class="form-control" type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">Save Changes</button>
                                <a href="profile.php?id=<?= $view_uid ?>" class="btn btn-secondary">Cancel</a>
                                <a href="change_password.php" class="btn btn-warning">Change Password</a>
                            </div>
                        </form>

                    <?php else: ?>
                        <!-- VIEW MODE -->
                        <div class="mb-3">
                            <label class="small mb-1">Email</label>
                            <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">First name</label>
                                <p class="text-muted"><?= htmlspecialchars($user['fname'] ?? '-') ?></p>
                            </div>

                            <div class="col-md-6">
                                <label class="small mb-1">Last name</label>
                                <p class="text-muted"><?= htmlspecialchars($user['lname'] ?? '-') ?></p>
                            </div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">Address</label>
                                <p class="text-muted"><?= htmlspecialchars($user['addressline'] ?? '-') ?></p>
                            </div>

                            <div class="col-md-6">
                                <label class="small mb-1">Town</label>
                                <p class="text-muted"><?= htmlspecialchars($user['town'] ?? '-') ?></p>
                            </div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">Zip Code</label>
                                <p class="text-muted"><?= htmlspecialchars($user['zipcode'] ?? '-') ?></p>
                            </div>

                            <div class="col-md-6">
                                <label class="small mb-1">Title</label>
                                <p class="text-muted"><?= htmlspecialchars($user['title'] ?? '-') ?></p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1">Phone</label>
                            <p class="text-muted"><?= htmlspecialchars($user['phone'] ?? '-') ?></p>
                        </div>

                        <!-- Buttons for logged-in user viewing own profile -->
                        <?php if ($isOwnProfile): ?>
                            <div class="d-flex gap-2">
                                <a href="profile.php?id=<?= $view_uid ?>&edit=1" class="btn btn-primary">Edit Profile</a>
                                <a href="change_password.php" class="btn btn-secondary">Change Password</a>
                            </div>
                        <?php endif; ?>

                        <!-- Admin controls -->
                        <?php if ($isAdmin): ?>
                            <div class="d-flex gap-2 mt-2">
                                <a href="/shop/admin/users.php" class="btn btn-info">View All Users</a>
                                <a href="/shop/admin/delete_user.php?id=<?= $user['id'] ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this user?');">
                                   Delete User
                                </a>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>
</div>

<?php if ($isOwnProfile && $isEditing): ?>
<script>
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    // Preview image
    const reader = new FileReader();
    reader.onload = function(event) {
        document.getElementById('avatarPreview').src = event.target.result;
    };
    reader.readAsDataURL(file);

    // Upload immediately
    const formData = new FormData();
    formData.append('avatar', file);
    formData.append('user_id', <?= $view_uid ?>);

    fetch('upload_avatar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Avatar uploaded successfully');
        } else {
            alert('Error uploading avatar: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error uploading avatar');
    });
});
</script>
<?php endif; ?>

<?php include("../includes/footer.php"); ?>