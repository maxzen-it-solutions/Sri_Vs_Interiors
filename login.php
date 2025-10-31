<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'header.php';
?>

<!-- subheader -->
<section id="subheader">
    <div class="container-fluid m-5-hor">
        <div class="row">
            <div class="col-md-12">
                <h1>Login</h1>
            </div>
        </div>
    </div>
</section>
<!-- subheader end -->

<!-- login form -->
<section class="whitepage">
    <div class="container-fluid m-5-hor">
        <div class="row justify-content-center">
            <div class="col-md-6 contact-box">
                <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

                <form method="post" action="login_process.php">
                    <div class="form-group user-email">
                        <input type="email" class="form-control" required name="email" placeholder="Email Address">
                    </div>
                    <div class="form-group user-password">
                        <input type="password" class="form-control" required name="password" placeholder="Password">
                    </div>
                    <button type="submit" class="btn-contact">Login</button>
                </form>
                <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
</section>
<!-- login form end -->

<?php include 'footer.php'; ?>
