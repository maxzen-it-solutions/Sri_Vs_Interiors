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
body {
    font-family: "Segoe UI", sans-serif;
    background-color: #f4f6f8;
    margin: 0;
}

/* Main content */
.main-content {
    padding: 40px 20px;
    margin-left: 250px;
    transition: margin 0.3s ease;
}

h1 {
    font-size: 2rem;
    color: #111827;
    font-weight: 600;
    margin-bottom: 5px;
}

.main-content p {
    color: #6b7280;
    font-size: 1rem;
    margin-bottom: 30px;
}

/* Dashboard cards */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
}

.card-link {
    text-decoration: none;
}

.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    padding: 20px;
    display: flex;
    flex-direction: column; /* Stack icon and text vertically */
    align-items: center;    /* Center horizontally */
    justify-content: center; /* Center vertically */
    gap: 15px;
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
    text-align: center;     /* Center the text */
}

.card .icon-wrapper {
    font-size: 2.5rem; /* slightly bigger icon */
    padding: 15px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}

.card h3 {
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
    color: #374151;
}

.card p {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
    color: #111827;
}


/* Card colors */
.card-inactive .icon-wrapper { background-color: #ef4444; }
.card-active .icon-wrapper { background-color: #2563eb; }
.card-leads .icon-wrapper { background-color: #16a34a; }
.card-enquiries .icon-wrapper {
    background-color: #f59e0b; /* Amber / Orange */
}
/* Responsive adjustments */
@media (max-width: 992px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
}
</style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></h1>
    <p>Here’s a quick summary of the system.</p>

    <div class="dashboard-cards">
        <a href="manage_products.php" class="card-link">
            <div class="card card-inactive">
                <div class="icon-wrapper"><i class="bi bi-eye-slash-fill"></i></div>
                <div>
                    <h3>Inactive Products</h3>
                    <p><?= $inactive; ?></p>
                </div>
            </div>
        </a>
        <a href="manage_products.php" class="card-link">
            <div class="card card-active">
                <div class="icon-wrapper"><i class="bi bi-box-seam-fill"></i></div>
                <div>
                    <h3>Active Products</h3>
                    <p><?= $active; ?></p>
                </div>
            </div>
        </a>
        <a href="leads.php" class="card-link">
            <div class="card card-leads">
                <div class="icon-wrapper"><i class="bi bi-person-lines-fill"></i></div>
                <div>
                    <h3>Leads</h3>
                    <p><?= $leads; ?></p>
                </div>
            </div>
        </a>
        <a href="manage_enquiries.php" class="card-link">
            <div class="card card-enquiries">
                <div class="icon-wrapper"><i class="bi bi-chat-dots-fill"></i></div>
                <div>
                    <h3>Project Enquiries</h3>
                    <p><?= $enquiries; ?></p>
                </div>
            </div>
        </a>
    </div>
</div>

</body>
</html>
