<?php
include("../includes/config.php");
include("../includes/header.php");
?>

<div class="container-fluid container-lg my-5">
    <?php include("../includes/alert.php"); ?>

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-3">Create an account</h4>

                    <form action="store.php" method="POST" novalidate>

                        <div class="mb-3">
                            <label class="form-label">Full name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   required
                                   value="<?= isset($_SESSION['old']['name']) ? htmlspecialchars($_SESSION['old']['name']) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   required
                                   value="<?= isset($_SESSION['old']['email']) ? htmlspecialchars($_SESSION['old']['email']) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   required>
                            <div class="form-text">Minimum 4 characters</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm password</label>
                            <input type="password"
                                   name="confirmPass"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary">Register</button>
                            <a href="login.php" class="small">Already have an account? Login</a>
                        </div>

                    </form>
                </div>
            </div>

            <?php if (isset($_SESSION['old'])) unset($_SESSION['old']); ?>

        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
