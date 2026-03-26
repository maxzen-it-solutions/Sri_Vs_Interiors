<?php
ob_start();
session_start();

// Admin access only
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'db_connect.php';
include 'admin_sidebar.php';

// Handle delete enquiry
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM project_enquiries WHERE id = $id");
    header("Location: manage_enquiries.php");
    exit;
}

// Handle mark as contacted
if (isset($_GET['contacted'])) {
    $id = intval($_GET['contacted']);
    $conn->query("UPDATE project_enquiries SET contacted = 1 WHERE id = $id");
    header("Location: manage_enquiries.php");
    exit;
}

// Fetch enquiries
$result = $conn->query("SELECT * FROM project_enquiries ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin | Project Enquiries</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="img/logo-white.png" rel="icon" sizes="32x32" type="image/png">

<style>
body {
    font-family: "Segoe UI", sans-serif;
    background-color: #f4f6f8;
    margin: 0;
}

.main-content {
    margin-left: 250px;
    padding: 40px 20px;
    transition: margin 0.3s ease;
}

h1 {
    font-size: 2rem;
    color: #111827;
    font-weight: 600;
    margin-bottom: 20px;
}

.table-wrapper {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    overflow-x: auto;
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: top;
}

th {
    background-color: #f59e0b;
    color: white;
    font-weight: 500;
}

tr:hover {
    background-color: #f1f5f9;
}

.delete-btn {
    color: #fff;
    background-color: #ef4444;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    transition: 0.2s;
    font-size: 0.9rem;
}

.delete-btn:hover {
    background-color: #dc2626;
}

@media (max-width: 992px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
}
.status {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
}

.status.contacted {
    background: #dcfce7;
    color: #166534;
}

.status.not-contacted {
    background: #fee2e2;
    color: #991b1b;
}

.contact-btn {
    background: #16a34a;
    color: #fff;
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    margin-right: 6px;
}

.contact-btn:hover {
    background: #15803d;
}
</style>
</head>
<body>

<div class="main-content">
    <h1>Project Enquiries</h1>

    <div class="table-wrapper">
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Building</th>
                    <th>Project Type</th>
                    <th>Budget</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>

                    <td><?= htmlspecialchars($row['name']); ?></td>

                    <td>
                        📞 <?= htmlspecialchars($row['phone']); ?><br>
                        ✉ <?= htmlspecialchars($row['email']); ?>
                    </td>

                    <td><?= nl2br(htmlspecialchars($row['address'])); ?></td>

                    <td><?= htmlspecialchars($row['building_type']); ?></td>

                    <td><?= htmlspecialchars($row['project_type']); ?></td>

                    <td><?= htmlspecialchars($row['budget'] ?: '—'); ?></td>

                    <td style="max-width:260px;">
                        <?= nl2br(htmlspecialchars($row['message'])); ?>
                    </td>

                    <!-- STATUS COLUMN -->
                    <td style="text-align:center;">
                    <?php if ((int)$row['contacted'] === 1): ?>
                        <span class="status contacted">✔ Contacted</span>
                    <?php else: ?>
                        <span class="status not-contacted">✖ Not Contacted</span>
                    <?php endif; ?>
                    </td>

                    <!-- DATE -->
                    <td><?= date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>

                    <!-- ACTION -->
                    <td>
                    <?php if ((int)$row['contacted'] === 0): ?>
                        <a href="manage_enquiries.php?contacted=<?= $row['id']; ?>"
                        class="contact-btn"
                        onclick="return confirm('Mark this enquiry as contacted?');">
                            Mark Contacted
                        </a>
                    <?php endif; ?>

                        <a href="manage_enquiries.php?delete=<?= $row['id']; ?>"
                        class="delete-btn"
                        onclick="return confirm('Delete this enquiry?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>

            </tbody>
        </table>
    <?php else: ?>
        <p>No project enquiries found.</p>
    <?php endif; ?>
    </div>
</div>

</body>
</html>