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
                <h1>Register</h1>
            </div>
        </div>
    </div>
</section>
<!-- subheader end -->

<!-- register form -->
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

                <form method="post" action="register_process.php">
                    <div class="form-group user-name">
                        <input type="text" class="form-control" required name="name" placeholder="Full Name">
                    </div>
                    <div class="form-group user-email">
                        <input type="email" class="form-control" required name="email" placeholder="Email Address">
                    </div>
                    <div class="form-group user-password">
                        <input type="password" class="form-control" required name="password" placeholder="Password">
                    </div>
                    <div class="form-group user-password-confirm">
                        <input type="password" class="form-control" required name="confirm_password" placeholder="Confirm Password">
                    </div>
                    <button type="submit" class="btn-contact">Register</button>
                </form>
                <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</section>
<!-- register form end -->

<?php include 'footer.php'; ?>
