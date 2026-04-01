<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include 'db_connect.php';

// Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle Deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM project_reviews WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin_reviews.php?status=deleted");
    exit;
}

include 'admin_sidebar.php';
$result = mysqli_query($conn, "SELECT * FROM project_reviews ORDER BY created_at DESC");
?>

<div class="main-content">

<h2>Project Reviews</h2>

<table border="1" width="100%" cellpadding="10">
<tr>
<th>ID</th>
<th>Client Name</th>
<th>Rating</th>
<th>Review</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['client_name']) ?></td>
<td><?= $row['rating'] ?> ⭐</td>
<td><?= htmlspecialchars($row['review']) ?></td>
<td><?= $row['created_at'] ?></td>
<td>
    <a href="?delete=<?= $row['id'] ?>" style="color:red;" onclick="return confirm('Delete this review?')">Delete</a>
</td>
</tr>

<?php } ?>

</table>

</div>