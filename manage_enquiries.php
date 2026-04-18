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
    $stmt = $conn->prepare("DELETE FROM project_enquiries WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
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
    :root {
        --admin-bg: #f8fafc;
        --card-bg: #ffffff;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --accent-gold: #c8b16f;
        --accent-blue: #3b82f6;
        --accent-green: #10b981;
        --accent-amber: #f59e0b;
        --accent-red: #ef4444;
        --border-color: #e2e8f0;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        background-color: var(--admin-bg);
        color: var(--text-main);
        margin: 0;
    }

    .main-content {
        padding: 2.5rem;
        margin-left: 240px;
        transition: var(--transition);
    }

    .page-header {
        margin-bottom: 2.5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .page-header h1 {
        font-size: 1.875rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        margin: 0 0 0.5rem 0;
    }

    .page-header p {
        color: var(--text-muted);
        margin: 0;
        font-size: 0.95rem;
    }

    .search-container {
        position: relative;
        min-width: 300px;
    }

    .search-container i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .search-input {
        width: 100%;
        padding: 0.625rem 1rem 0.625rem 2.5rem;
        border-radius: 0.75rem;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        font-size: 0.875rem;
        transition: var(--transition);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 4px rgba(200, 177, 111, 0.1);
    }

    .table-wrapper {
        background: var(--card-bg);
        border-radius: 1rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table { margin-bottom: 0; width: 100%; border-collapse: collapse; }
    
    .table thead th {
        background: #fcfcfc;
        padding: 1rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        border-bottom: 2px solid var(--admin-bg);
        text-align: left;
    }

    .table tbody td {
        padding: 1.25rem;
        vertical-align: top;
        color: var(--text-main);
        border-bottom: 1px solid var(--border-color);
    }

    .contact-cell { font-size: 0.875rem; line-height: 1.6; }
    .contact-cell .phone { font-weight: 600; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem; }
    .contact-cell .email { color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem; }

    .location-cell { font-size: 0.875rem; color: var(--text-muted); max-width: 180px; }

    .project-info-cell { font-size: 0.875rem; }
    .project-info-cell .badge-custom { 
        display: inline-block; 
        padding: 0.2rem 0.6rem; 
        border-radius: 0.4rem; 
        background: #f1f5f9; 
        color: #475569; 
        font-size: 0.75rem; 
        font-weight: 600; 
        margin-bottom: 0.4rem;
        text-transform: capitalize;
    }
    .project-info-cell .budget { font-weight: 700; color: var(--accent-gold); }

    .message-cell {
        max-width: 300px;
        font-size: 0.875rem;
        color: var(--text-muted);
        display: -webkit-box;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    .status-contacted { background: #dcfce7; color: #166534; }
    .status-pending { background: #fffbeb; color: #92400e; }

    .action-group {
        display: flex;
        gap: 0.625rem;
        justify-content: flex-end;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.625rem;
        transition: var(--transition);
        text-decoration: none;
        border: 1px solid transparent;
    }

    .btn-mark-contacted { 
        background: #ecfdf5; 
        color: var(--accent-green); 
        border-color: #d1fae5; 
    }
    .btn-mark-contacted:hover { background: var(--accent-green); color: #fff; }

    .btn-delete { 
        background: #fef2f2; 
        color: var(--accent-red); 
        border-color: #fee2e2; 
    }
    .btn-delete:hover { background: var(--accent-red); color: #fff; }

    @media (max-width: 1200px) {
        .message-cell { max-width: 200px; }
    }

    @media (max-width: 992px) {
        .main-content { margin-left: 0; padding: 1.5rem; }
    }
</style>
</head>
<body>

<div class="main-content">
    <div class="page-header">
        <div>
            <h1>Project Enquiries</h1>
            <p>Review and manage specific project requests from the services page.</p>
        </div>
        <div class="search-container">
            <i class="bi bi-search"></i>
            <input type="text" id="enquirySearch" class="search-input" placeholder="Search by name, type or location...">
        </div>
    </div>

    <div class="table-wrapper">
    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Client Contact</th>
                    <th>Location</th>
                    <th>Project Info</th>
                    <th>Message</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="enquiry-row">
                    <td style="color: var(--text-muted); font-size: 0.8rem;">#<?= $row['id']; ?></td>

                    <td style="font-weight: 600;"><?= htmlspecialchars($row['name']); ?></td>

                    <td class="contact-cell">
                        <div class="phone"><i class="bi bi-telephone"></i> <?= htmlspecialchars($row['phone']); ?></div>
                        <div class="email"><i class="bi bi-envelope"></i> <?= htmlspecialchars($row['email']); ?></div>
                    </td>

                    <td class="location-cell">
                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($row['address']); ?>
                    </td>

                    <td class="project-info-cell">
                        <span class="badge-custom"><?= htmlspecialchars($row['building_type']); ?></span><br>
                        <span class="badge-custom" style="background: #eff6ff; color: #1e40af;"><?= htmlspecialchars($row['project_type']); ?></span><br>
                        <span class="budget"><?= $row['budget'] ? '₹' . number_format((float)$row['budget']) : 'No Budget Set'; ?></span>
                    </td>

                    <td>
                        <div class="message-cell" title="<?= htmlspecialchars($row['message']); ?>">
                            <?= htmlspecialchars($row['message']); ?>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">
                            Submitted: <?= date('d M Y, h:i A', strtotime($row['created_at'])); ?>
                        </div>
                    </td>

                    <!-- STATUS COLUMN -->
                    <td style="text-align:center; vertical-align: middle;">
                    <?php if ((int)$row['contacted'] === 1): ?>
                        <span class="status-pill status-contacted"><i class="bi bi-check-circle"></i> Contacted</span>
                    <?php else: ?>
                        <span class="status-pill status-pending"><i class="bi bi-clock-history"></i> Pending</span>
                    <?php endif; ?>
                    </td>

                    <!-- ACTIONS -->
                    <td style="vertical-align: middle;">
                        <div class="action-group">
                            <?php if ((int)$row['contacted'] === 0): ?>
                                <a href="manage_enquiries.php?contacted=<?= $row['id']; ?>"
                                   class="btn-action btn-mark-contacted"
                                   title="Mark as Contacted"
                                   onclick="return confirm('Mark this enquiry as contacted?');">
                                    <i class="bi bi-person-check"></i>
                                </a>
                            <?php endif; ?>

                            <a href="manage_enquiries.php?delete=<?= $row['id']; ?>"
                               class="btn-action btn-delete"
                               title="Delete Permanent"
                               onclick="return confirm('Are you sure you want to delete this enquiry?');">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </div>
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

<script>
    // Dynamic Search Filter
    document.getElementById('enquirySearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.enquiry-row');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>