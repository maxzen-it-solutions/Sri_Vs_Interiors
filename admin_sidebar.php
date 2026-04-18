<?php
// No session check here; already done in main page
$current_page = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="img/logo-white.png" rel="icon" sizes="32x32" type="image/png">

<style>
    body {
        margin: 0;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        background-color: #f8fafc;
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 260px;
        height: 100vh;
        background-color: #0f172a;
        color: #fff;
        display: flex;
        flex-direction: column;
        z-index: 1000;
        transform: translateX(0);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
    }

    body.sidebar-closed .sidebar {
        transform: translateX(-260px);
    }

    .sidebar-header {
        padding: 1.5rem;
        background-color: #1e293b;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .sidebar h2 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        color: #f8fafc;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .sidebar h2 i {
        color: #c8b16f;
    }

    nav {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 1rem 0;
        overflow-y: auto;
    }

    nav a {
        text-decoration: none;
        color: #94a3b8;
        padding: 0.875rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
    }

    nav a:hover {
        background-color: rgba(255, 255, 255, 0.03);
        color: #fff;
    }

    nav a.active {
        background-color: rgba(200, 177, 111, 0.1);
        color: #c8b16f;
        font-weight: 600;
        border-left-color: #c8b16f;
    }

    .logout-btn {
        background-color: #ef4444;
        color: #fff;
        text-align: center;
        padding: 0.75rem;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.2s;
        margin: 1rem 1.5rem;
        border-radius: 0.5rem;
        font-size: 0.9rem;
    }

    .logout-btn:hover {
        background-color: #dc2626;
    }

    .main-content {
        margin-left: 260px;
        padding: 2rem;
        transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body.sidebar-closed .main-content {
        margin-left: 0;
    }

    .sidebar-toggle {
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 1001;
        background: #0f172a;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0.5rem;
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: none;
        z-index: 900;
        background: rgba(15, 23, 42, 0.5);
    }

    @media (max-width: 992px) {
        .main-content {
            margin-left: 0;
            padding: 5rem 1rem 2rem 1rem;
        }

        .sidebar {
            transform: translateX(-260px);
        }

        body.sidebar-open .sidebar {
            transform: translateX(0);
        }

        body.sidebar-open #overlay {
            display: block;
        }
    }
</style>

<div class="sidebar">
    <div class="sidebar-header">
        <h2><i class="bi bi-pentagon-fill"></i> Admin Panel</h2>
    </div>
    <nav>
        <a href="admin_dashboard.php" class="<?= ($current_page === 'admin_dashboard.php') ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="manage_users.php" class="<?= ($current_page === 'manage_users.php') ? 'active' : '' ?>"><i class="bi bi-people"></i> Manage Users</a>
        <a href="manage_products.php" class="<?= ($current_page === 'manage_products.php') ? 'active' : '' ?>"><i class="bi bi-cart3"></i> Manage Products</a>
        <a href="manage_stats.php" class="<?= ($current_page === 'manage_stats.php') ? 'active' : '' ?>"><i class="bi bi-bar-chart-line"></i> Project Statistics</a>
        <a href="manage_partners.php" class="<?= ($current_page === 'manage_partners.php') ? 'active' : '' ?>"><i class="bi bi-person-workspace"></i> Manage Partners</a>
        <a href="leads.php" class="<?= ($current_page === 'leads.php') ? 'active' : '' ?>"><i class="bi bi-person-plus"></i> Leads</a>
        <a href="manage_enquiries.php" class="<?= ($current_page === 'manage_enquiries.php') ? 'active' : '' ?>"><i class="bi bi-chat-dots-fill"></i> Project Enquiries</a>
        <a href="admin_reviews.php" class="<?= ($current_page === 'admin_reviews.php') ? 'active' : '' ?>"><i class="bi bi-star-fill"></i> Client Reviews</a>
    </nav>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div id="overlay"></div>

<button class="sidebar-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>

<script>
    function toggleSidebar() {
        document.body.classList.toggle('sidebar-closed');
        document.body.classList.toggle('sidebar-open');
        setTimeout(function() {
            window.dispatchEvent(new Event('resize'));
        }, 250);
    }
    document.getElementById('overlay').addEventListener('click', function() {
        document.body.classList.add('sidebar-closed');
        document.body.classList.remove('sidebar-open');
        setTimeout(function() {
            window.dispatchEvent(new Event('resize'));
        }, 250);
    });
    (function() {
        if (window.innerWidth <= 992) {
            document.body.classList.add('sidebar-closed');
        }
    })();
</script>