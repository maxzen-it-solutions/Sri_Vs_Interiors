<?php
// Start session safely
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db_connect.php';

// Redirect if admin not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle form submission
if (isset($_POST['update_stats'])) {
    $total_projects = intval($_POST['total_projects']);
    $completed_projects = intval($_POST['completed_projects']);
    $ongoing_projects = intval($_POST['ongoing_projects']);

    // Check if stats row exists
    $check = $conn->query("SELECT id FROM site_stats LIMIT 1");
    if ($check->num_rows > 0) {
        // Update existing row
        $stmt = $conn->prepare("UPDATE site_stats SET total_projects=?, completed_projects=?, ongoing_projects=? WHERE id=1");
        $stmt->bind_param("iii", $total_projects, $completed_projects, $ongoing_projects);
    } else {
        // Insert new row
        $stmt = $conn->prepare("INSERT INTO site_stats (total_projects, completed_projects, ongoing_projects) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $total_projects, $completed_projects, $ongoing_projects);
    }
    $stmt->execute();
    $stmt->close();

    $success_message = "Statistics updated successfully!";
}

// Fetch current stats
$stats = $conn->query("SELECT * FROM site_stats LIMIT 1")->fetch_assoc();
if (!$stats) {
    $stats = ['total_projects' => 0, 'completed_projects' => 0, 'ongoing_projects' => 0];
}
?>

<?php include 'admin_sidebar.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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
        --border-color: #e2e8f0;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .main-content {
        padding: 2.5rem 2.5rem 5rem 2.5rem;
        transition: all 0.3s ease;
    }

    .stats-container {
        background-color: var(--admin-bg);
        min-height: calc(100vh - 60px);
    }

    .page-header {
        margin-bottom: 2.5rem;
    }

    .page-header h1 {
        font-size: 1.875rem;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.025em;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .alert-success-custom {
        background: #ecfdf5;
        border: 1px solid #10b981;
        color: #065f46;
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
    }

    .card-custom {
        background: var(--card-bg);
        border-radius: 1.25rem;
        padding: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        transition: var(--transition);
    }

    .card-custom:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 0;
        background: none;
        border: none;
    }

    .icon-box {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background: #fffcf5;
        color: var(--accent-gold);
        border: 1px solid #fdf3dc;
    }

    .card-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-main);
    }

    .form-group-custom {
        margin-bottom: 1.5rem;
    }

    .form-group-custom label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        margin-bottom: 0.625rem;
    }

    .input-custom {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 0.625rem;
        font-size: 1rem;
        transition: var(--transition);
        background-color: #fff;
    }

    .input-custom:focus {
        outline: none;
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 4px rgba(200, 177, 111, 0.1);
    }

    .btn-update {
        width: 100%;
        background-color: var(--text-main);
        color: white;
        padding: 0.875rem;
        border-radius: 0.625rem;
        border: none;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-update:hover {
        background-color: #1e293b;
        transform: translateY(-1px);
    }

    .display-stat-item {
        padding: 1.25rem;
        border-radius: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        background: #f8fafc;
        border: 1px solid var(--border-color);
    }

    .display-stat-item .label {
        font-weight: 600;
        color: var(--text-muted);
    }

    .display-stat-item .value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-main);
    }

    .line-clamp {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    @media (max-width: 768px) {
        .main-content { padding: 5rem 1rem 2rem 1rem; }
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="main-content">
    <div class="stats-container">
        
        <header class="page-header">
            <h1>Project Statistics</h1>
            <p>Manage the performance indicators displayed across your premium interior website.</p>
        </header>

        <?php if (isset($success_message)): ?>
            <div class="alert-success-custom">
                <i class="bi bi-check-circle-fill"></i>
                <span><?php echo $success_message; ?></span>
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <!-- Update Form -->
            <div class="card-custom">
                <div class="card-header">
                    <div class="icon-box"><i class="bi bi-pencil-square"></i></div>
                    <div>
                        <h2>Modify Numbers</h2>
                    </div>
                </div>

                <form method="POST">
                    <div class="form-group-custom">
                        <label>Total Projects</label>
                        <input type="number" name="total_projects" class="input-custom" value="<?php echo $stats['total_projects']; ?>" required min="0">
                    </div>

                    <div class="form-group-custom">
                        <label>Completed Projects</label>
                        <input type="number" name="completed_projects" class="input-custom" value="<?php echo $stats['completed_projects']; ?>" required min="0">
                    </div>

                    <div class="form-group-custom">
                        <label>Ongoing Projects</label>
                        <input type="number" name="ongoing_projects" class="input-custom" value="<?php echo $stats['ongoing_projects']; ?>" required min="0">
                    </div>

                    <button type="submit" name="update_stats" class="btn-update">
                        Update Live Data
                    </button>
                </form>
            </div>

            <!-- Live Preview -->
            <div class="card-custom">
                <div class="card-header">
                    <div class="icon-box" style="background: #eff6ff; color: var(--accent-blue); border-color: #dbeafe;">
                        <i class="bi bi-eye"></i>
                    </div>
                    <div>
                        <h2>Live Preview</h2>
                    </div>
                </div>

                <div class="display-stat-item">
                    <span class="label">Overall Portfolio</span>
                    <span class="value"><?php echo number_format($stats['total_projects']); ?></span>
                </div>

                <div class="display-stat-item" style="border-left: 4px solid var(--accent-green);">
                    <span class="label">Delivered Success</span>
                    <span class="value" style="color: var(--accent-green);"><?php echo number_format($stats['completed_projects']); ?></span>
                </div>

                <div class="display-stat-item" style="border-left: 4px solid var(--accent-amber);">
                    <span class="label">In Progress</span>
                    <span class="value" style="color: var(--accent-amber);"><?php echo number_format($stats['ongoing_projects']); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>