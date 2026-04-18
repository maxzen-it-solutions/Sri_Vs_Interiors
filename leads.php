<?php
ob_start(); // start output buffering
session_start();

// Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'db_connect.php';      // database connection

// Handle CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Clear any previous output from the buffer
    while (ob_get_level()) ob_end_clean();

    $filename = "leads_export_" . date('Y-m-d_His') . ".csv";

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    // Add UTF-8 BOM for proper Excel character encoding and to handle multi-line messages correctly
    fwrite($output, "\xEF\xBB\xBF");

    fputcsv($output, ['Lead ID', 'Customer Name', 'Email Address', 'Phone Number', 'Full Message', 'Submission Timestamp']);

    $export_query = $conn->query("SELECT id, name, email, phone, message, created_at FROM leads ORDER BY created_at DESC");
    while ($row = $export_query->fetch_assoc()) {
        // Format values for professional Excel display
        $phone = "\t" . $row['phone']; // Force Excel to treat phone as a string (prevents scientific notation)
        $date = date('d M Y, h:i:s A', strtotime($row['created_at'])); // Detailed and sorted timestamp
        
        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['email'],
            $phone,
            $row['message'], // Quotes handled automatically by fputcsv to preserve full multi-line text
            $date
        ]);
    }
    fclose($output);
    exit;
}

include 'admin_sidebar.php';   // sidebar included after session check

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM leads WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
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
    :root {
        --admin-bg: #f8fafc;
        --card-bg: #ffffff;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --accent-gold: #c8b16f;
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
        padding: 2.5rem 2.5rem 5rem 2.5rem;
        transition: all 0.3s ease;
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

    .table { margin-bottom: 0; }
    
    .table thead th {
        background: #fcfcfc;
        padding: 1rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        border-bottom: 2px solid var(--admin-bg);
    }

    .table tbody td {
        padding: 1.25rem;
        vertical-align: middle;
        color: var(--text-main);
        border-bottom: 1px solid var(--border-color);
    }

    .contact-info-cell {
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .contact-info-cell span { display: block; }
    .contact-info-cell .email { color: var(--text-muted); }

    .message-cell {
        max-width: 320px;
        font-size: 0.875rem;
        color: var(--text-muted);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .date-cell { font-size: 0.8rem; font-weight: 500; color: var(--text-muted); }

    .btn-delete-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fef2f2;
        color: var(--accent-red);
        border-radius: 0.5rem;
        border: 1px solid #fee2e2;
        transition: var(--transition);
        text-decoration: none;
    }

    .btn-delete-icon:hover {
        background: var(--accent-red);
        color: #fff;
    }

    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: var(--card-bg);
        color: var(--text-main);
        border: 1px solid var(--border-color);
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: var(--transition);
        margin-top: 0.5rem;
    }
    .btn-export:hover {
        background-color: #fffcf5;
        border-color: var(--accent-gold);
        color: var(--accent-gold);
    }

    @media (max-width: 992px) {
        .main-content { padding: 5rem 1rem 2rem 1rem; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .search-container { min-width: 100%; }
        
        .table thead { display: none; }
        .table tbody tr { 
            display: block; 
            border-bottom: 2px solid var(--admin-bg);
            padding: 1rem 0;
        }
        .table tbody td { 
            display: flex; 
            justify-content: space-between; 
            padding: 0.5rem 1rem; 
            border-bottom: none;
        }
        .table tbody td::before {
            content: attr(data-label);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: var(--text-muted);
        }
    }
</style>
</head>
<body>
<?php include 'admin_sidebar.php'; ?>
<div class="main-content">
    <div class="page-header">
        <div>
            <h1>Contact Leads</h1>
            <p>Manage inquiries and potential clients from your website contact forms.</p>
            <a href="leads.php?export=csv" class="btn-export">
                <i class="bi bi-download"></i> Export CSV (Excel)
            </a>
        </div>
        <div class="search-container">
            <i class="bi bi-search"></i>
            <input type="text" id="leadSearch" class="search-input" placeholder="Search by name or email...">
        </div>
    </div>

    <div class="table-responsive">
    <div class="table-wrapper">
    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table" id="leadsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="lead-row">
                    <td data-label="ID"><?= $row['id']; ?></td>
                    <td data-label="Name" style="font-weight: 600;"><?= htmlspecialchars($row['name']); ?></td>
                    <td data-label="Email" class="contact-info-cell">
                        <span class="email"><?= htmlspecialchars($row['email']); ?></span>
                    </td>
                    <td data-label="Phone"><?= htmlspecialchars($row['phone']); ?></td>
                    <td data-label="Message">
                        <div class="message-cell" title="<?= htmlspecialchars($row['message']); ?>">
                            <?= htmlspecialchars($row['message']); ?>
                        </div>
                    </td>
                    <td data-label="Date" class="date-cell"><?= date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                    <td data-label="Action" style="text-align: right;">
                        <a href="leads.php?delete=<?= $row['id']; ?>" class="btn-delete-icon" onclick="return confirm('Are you sure you want to delete this lead?');">
                            <i class="bi bi-trash3"></i>
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
</div>

<script>
    // Dynamic Search Filter
    document.getElementById('leadSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.lead-row');

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
