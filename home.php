<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    header('Location: login.php');
    exit;
}
include 'header.php';
?>

<h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?> (User)</h2>
<a href="logout.php">Logout</a>

<?php include 'footer.php'; ?>
