<?php
// No session check here; already done in main page
$current_page = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link href="img/logo-white.png" rel="icon" sizes="32x32" type="image/png">

<style>
/* Sidebar styles same as your previous code */
body { margin: 0; font-family: 'Segoe UI', sans-serif; transition: margin-left 0.3s ease; }
.sidebar { position: fixed; top: 0; left: 0; width: 240px; height: 100vh; background-color: #1e293b; color: #fff; display: flex; flex-direction: column; border-right: 1px solid #334155; box-shadow: 2px 0 5px rgba(0,0,0,0.15); z-index: 1000; transform: translateX(0); transition: transform 0.3s ease; }
body.sidebar-closed .sidebar { transform: translateX(-240px); }
.sidebar h2 { text-align: center; padding: 18px 0; font-size: 1.4rem; margin: 0; background-color: #111827; border-bottom: 1px solid #374151; }
nav { flex: 1; display: flex; flex-direction: column; padding-top: 10px; }
nav a { text-decoration: none; color: #cbd5e1; padding: 12px 20px; display: flex; align-items: center; gap: 10px; border-left: 4px solid transparent; transition: all 0.2s ease; }
nav a:hover { background-color: #2563eb; color: #fff; border-left: 4px solid #93c5fd; }
nav a.active { background-color: #1d4ed8; color: #fff; font-weight: bold; border-left: 4px solid #60a5fa; }
.logout-btn { background-color: #dc2626; color: #fff; text-align: center; padding: 12px 0; text-decoration: none; font-weight: 500; transition: background 0.2s; margin: 10px 15px; }
.logout-btn:hover { background-color: #b91c1c; }
.main-content { margin-left: 240px; padding: 30px 20px; transition: margin-left 0.3s ease; }
body.sidebar-closed .main-content { margin-left: 0; }
.sidebar-toggle { position: fixed; top: 15px; left: 15px; z-index: 1001; background: #1e293b; color: #fff; border: 1px solid #374151; border-radius: 6px; width: 40px; height: 40px; font-size: 1.5rem; cursor: pointer; }
#overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: none; z-index: 900; background: transparent; }
body.sidebar-open #overlay { display: block; }
@media (max-width: 992px) { .main-content { margin-left: 0; } }
</style>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <nav>
        <a href="admin_dashboard.php" class="<?= ($current_page === 'admin_dashboard.php') ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="manage_users.php" class="<?= ($current_page === 'manage_users.php') ? 'active' : '' ?>"><i class="bi bi-people"></i> Manage Users</a>
        <a href="manage_products.php" class="<?= ($current_page === 'manage_products.php') ? 'active' : '' ?>"><i class="bi bi-cart3"></i> Manage Products</a>
        <a href="leads.php" class="<?= ($current_page === 'leads.php') ? 'active' : '' ?>"><i class="bi bi-person-plus"></i> Leads</a>
    </nav>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div id="overlay"></div>

<button class="sidebar-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>

<script>
function toggleSidebar() {
    document.body.classList.toggle('sidebar-closed');
    document.body.classList.toggle('sidebar-open');
    setTimeout(function(){ window.dispatchEvent(new Event('resize')); }, 250);
}
document.getElementById('overlay').addEventListener('click', function() {
    document.body.classList.add('sidebar-closed');
    document.body.classList.remove('sidebar-open');
    setTimeout(function(){ window.dispatchEvent(new Event('resize')); }, 250);
});
(function(){ if (window.innerWidth <= 768) { document.body.classList.add('sidebar-closed'); } })();
</script>
