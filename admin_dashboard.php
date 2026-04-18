<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db_connect.php';

// Redirect if admin not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch dashboard data
$inactiveResult = $conn->query("SELECT COUNT(*) AS total FROM products WHERE status='inactive'");
$inactive = $inactiveResult ? (int)$inactiveResult->fetch_assoc()['total'] : 0;

$activeResult = $conn->query("SELECT COUNT(*) AS total FROM products WHERE status='active'");
$active = $activeResult ? (int)$activeResult->fetch_assoc()['total'] : 0;

$leadsResult = $conn->query("SELECT COUNT(*) AS total FROM leads");
$leads = $leadsResult ? (int)$leadsResult->fetch_assoc()['total'] : 0;

$enquiriesResult = $conn->query("SELECT COUNT(*) AS total FROM project_enquiries");
$enquiries = $enquiriesResult ? (int)$enquiriesResult->fetch_assoc()['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Interior Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="img/logo-white.png" rel="icon" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --admin-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --accent-gold: #c8b16f;
            --accent-blue: #3b82f6;
            --accent-red: #ef4444;
            --accent-green: #10b981;
            --accent-amber: #f59e0b;
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

        .dashboard-header {
            margin-bottom: 2.5rem;
        }

        .dashboard-header h1 {
            font-size: 1.875rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Utility */
        .line-clamp-2 {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: var(--accent-gold);
        }

        .stat-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-info h3 {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
        }

        .stat-info .value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1.2;
        }

        /* Stat Colors */
        .bg-inactive {
            background: #fef2f2;
            color: var(--accent-red);
        }

        .bg-active {
            background: #eff6ff;
            color: var(--accent-blue);
        }

        .bg-leads {
            background: #ecfdf5;
            color: var(--accent-green);
        }

        .bg-enquiries {
            background: #fffbeb;
            color: var(--accent-amber);
        }

        /* Quick Actions */
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
        }

        .action-btn {
            background: var(--card-bg);
            padding: 1.25rem;
            border-radius: 0.75rem;
            border: 1px dashed #cbd5e1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-main);
            text-align: center;
            transition: var(--transition);
        }

        .action-btn i {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            color: var(--accent-gold);
        }

        .action-btn span {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .action-btn:hover {
            border-style: solid;
            border-color: var(--accent-gold);
            background: #fffcf5;
            color: var(--accent-gold);
        }

        @media (max-width: 992px) {
            .main-content {
                padding: 5rem 1rem 2rem 1rem;
            }

            .dashboard-header h1 {
                font-size: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>

<body>

    <?php include 'admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="dashboard-header">
            <h1>Welcome Back, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></h1>
            <p>Your interior business overview at a glance.</p>
        </div>

        <div class="stats-grid">
            <a href="manage_products.php" class="stat-card">
                <div class="stat-icon bg-active"><i class="bi bi-check2-circle"></i></div>
                <div class="stat-info">
                    <h3>Active Projects</h3>
                    <div class="value"><?= $active; ?></div>
                </div>
            </a>

            <a href="manage_products.php" class="stat-card">
                <div class="stat-icon bg-inactive"><i class="bi bi-pause-circle"></i></div>
                <div class="stat-info">
                    <h3>Paused/Draft</h3>
                    <div class="value"><?= $inactive; ?></div>
                </div>
            </a>

            <a href="leads.php" class="stat-card">
                <div class="stat-icon bg-leads"><i class="bi bi-send-check"></i></div>
                <div class="stat-info">
                    <h3>Contact Leads</h3>
                    <div class="value"><?= $leads; ?></div>
                </div>
            </a>

            <a href="manage_enquiries.php" class="stat-card">
                <div class="stat-icon bg-enquiries"><i class="bi bi-chat-left-dots"></i></div>
                <div class="stat-info">
                    <h3>New Enquiries</h3>
                    <div class="value"><?= $enquiries; ?></div>
                </div>
            </a>
        </div>

        <h2 class="section-title">Quick Actions</h2>
        <div class="actions-grid">
            <a href="manage_products.php" class="action-btn">
                <i class="bi bi-plus-square"></i>
                <span>Add New Project</span>
            </a>
            <a href="manage_partners.php" class="action-btn">
                <i class="bi bi-person-workspace"></i>
                <span>Manage Partners</span>
            </a>
            <a href="manage_stats.php" class="action-btn">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Update Site Stats</span>
            </a>
            <a href="admin_reviews.php" class="action-btn">
                <i class="bi bi-star"></i>
                <span>View Reviews</span>
            </a>
        </div>
    </div>

</body>

</html>