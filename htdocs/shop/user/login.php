<?php
include("../includes/config.php");
include("../includes/header.php");

// If already logged in, redirect to home
if (isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'You are already logged in.';
    redirect('../index.php');
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Basic validation
    if ($email === '' || $password === '') {
        $_SESSION['message'] = 'Please enter both email and password.';
        redirect('login.php');
    }

    // Query: plaintext password comparison (class requirement)
    $query = "SELECT id, name, email, role, avatar 
              FROM users 
              WHERE email = ? AND password = ? 
              LIMIT 1";

    if ($stmt = mysqli_prepare($conn, $query)) {

        mysqli_stmt_bind_param($stmt, "ss", $email, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) === 1) {

            mysqli_stmt_bind_result($stmt, $id, $db_name, $db_email, $role, $avatar);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            // Success — create clean session
            session_regenerate_id(true);

            $_SESSION['user_id'] = (int)$id;
            $_SESSION['email']   = $db_email;
            $_SESSION['role']    = $role ?: 'customer';
            $_SESSION['name']    = $db_name ?: $db_email;
            $_SESSION['avatar']  = $avatar;

            $_SESSION['message'] = "Login successful. Welcome, " . htmlspecialchars($_SESSION['name']) . "!";

            redirect('../index.php');

        } else {
            // Invalid credentials
            mysqli_stmt_close($stmt);
            $_SESSION['message'] = 'Wrong email or password.';
            $_SESSION['old']['email'] = $email;
            redirect('login.php');
        }

    } else {
        // Prepare error
        $_SESSION['message'] = 'Database error. Please try again later.';
        redirect('login.php');
    }
}
?>

<div class="container my-5">
    <?php include("../includes/alert.php"); ?>

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-3">Sign in</h4>

                    <form method="POST" action="login.php" novalidate>

                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   required
                                   value="<?= isset($_SESSION['old']['email']) 
                                               ? htmlspecialchars($_SESSION['old']['email']) 
                                               : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary">Sign in</button>
                            <a href="register.php" class="small">Not a member? Register</a>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (isset($_SESSION['old'])) unset($_SESSION['old']); ?>

        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
