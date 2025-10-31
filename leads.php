<?php
ob_start(); // start output buffering
session_start();

// Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'db_connect.php';      // database connection
include 'admin_sidebar.php';   // sidebar included after session check

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM leads WHERE id = $id");
    header("Location: leads.php"); // refresh after deletion
    exit;
}

// Fetch all leads
$result = $conn->query("SELECT * FROM leads ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin | Leads</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="img/logo-white.png" rel="icon" sizes="32x32" type="image/png">

<style>
body { font-family: "Segoe UI", sans-serif; background-color: #f4f6f8; margin: 0; }
.main-content { margin-left: 250px; padding: 40px 20px; transition: margin 0.3s ease; }
h1 { font-size: 2rem; color: #111827; font-weight: 600; margin-bottom: 10px; }
.table-wrapper { background-color: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow-x: auto; padding: 20px; }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e5e7eb; word-wrap: break-word; }
th { background-color: #2563eb; color: white; font-weight: 500; }
tr:hover { background-color: #f1f5f9; }
a.delete-btn { color: #fff; background-color: #ef4444; padding: 6px 12px; border-radius: 6px; text-decoration: none; transition: 0.2s; }
a.delete-btn:hover { background-color: #dc2626; }
@media (max-width: 992px) { .main-content { margin-left: 0; padding: 20px; } }
</style>
</head>
<body>

<div class="main-content">
    <h1>Contact Leads</h1>

    <div class="table-wrapper">
    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['phone']); ?></td>
                    <td><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                    <td><?= $row['created_at']; ?></td>
                    <td>
                        <a href="leads.php?delete=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this lead?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No leads found.</p>
    <?php endif; ?>
    </div>
</div>

</body>
</html>
