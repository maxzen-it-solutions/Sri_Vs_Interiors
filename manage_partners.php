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

// Handle logo upload
if (isset($_POST['upload_logo'])) {

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {

        $allowed = ['jpg','jpeg','png','gif'];
        $filename = $_FILES['logo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Upload directory
        $upload_dir = __DIR__ . '/uploads/partners/';

        // Create folder if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (in_array($ext, $allowed)) {

            // Unique filename
            $new_filename = time() . '_' . uniqid() . '.' . $ext;

            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {

                $stmt = $conn->prepare("INSERT INTO partners (logo, status) VALUES (?, 'active')");
                $stmt->bind_param("s", $new_filename);
                $stmt->execute();
                $stmt->close();

                $success_message = "Partner logo uploaded successfully!";

            } else {
                $error_message = "Upload failed. Check folder permissions.";
            }

        } else {
            $error_message = "Invalid file type. Only JPG, PNG, GIF allowed.";
        }

    } else {
        $error_message = "Please select an image.";
    }
}

// Handle status toggle
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $current = $conn->query("SELECT status FROM partners WHERE id = $id")->fetch_assoc();
    $new_status = ($current['status'] == 'active') ? 'inactive' : 'active';

    $stmt = $conn->prepare("UPDATE partners SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_partners.php");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    $stmt_get = $conn->prepare("SELECT logo FROM partners WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $res = $stmt_get->get_result();
    if ($res && $row = $res->fetch_assoc()) {
        $logo = $row['logo'];
        if ($logo && file_exists('uploads/partners/' . $logo)) {
            unlink('uploads/partners/' . $logo);
        }
    }
    $stmt_get->close();

    // Delete from database
    $stmt = $conn->prepare("DELETE FROM partners WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success_message'] = "Partner logo deleted successfully!";
    header("Location: manage_partners.php");
    exit;
}

// Check for flash messages
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Fetch all partners
$partners = $conn->query("SELECT * FROM partners ORDER BY id DESC");
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
        --accent-red: #ef4444;
        --border-color: #e2e8f0;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .main-content {
        padding: 2.5rem 2.5rem 5rem 2.5rem;
        transition: all 0.3s ease;
    }

    .partners-container {
        background-color: var(--admin-bg);
        min-height: calc(100vh - 60px);
    }

    .page-header {
        margin-bottom: 2.5rem;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
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

    .alert-custom {
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .alert-success-custom {
        background: #ecfdf5;
        border: 1px solid var(--accent-green);
        color: #065f46;
    }

    .alert-error-custom {
        background: #fef2f2;
        border: 1px solid var(--accent-red);
        color: #991b1b;
    }

    .partners-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 350px 1fr;
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

    .card-header-custom {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
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

    .card-header-custom h2 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-main);
    }

    .upload-zone {
        position: relative;
        border: 2px dashed var(--border-color);
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        background: var(--admin-bg);
        transition: var(--transition);
        cursor: pointer;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .upload-zone:hover {
        border-color: var(--accent-gold);
        background: #fffcf5;
    }

    .btn-premium {
        width: 100%;
        background: var(--text-main);
        color: white;
        padding: 0.875rem;
        border-radius: 0.625rem;
        border: none;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-premium:hover {
        background-color: #1e293b;
        transform: translateY(-1px);
    }

    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-custom th {
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--admin-bg);
    }

    .table-custom td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
    }

    .logo-wrapper {
        background: #f8fafc;
        padding: 0.5rem;
        border-radius: 0.75rem;
        display: inline-block;
        border: 1px solid var(--border-color);
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 1rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #f1f5f9; color: #475569; }

    .action-group {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-toggle { background: #eff6ff; color: var(--accent-blue); }
    .btn-toggle:hover { background: #dbeafe; }
    .btn-delete { background: #fef2f2; color: var(--accent-red); }
    .btn-delete:hover { background: #fee2e2; }

    @media (max-width: 992px) {
        .main-content { padding: 5rem 1rem 2rem 1rem; }
        .partners-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="main-content">
    <div class="partners-container">
        
        <!-- Page Header -->
        <header class="page-header">
            <h1>Partner Logos Management</h1>
            <p>Upload, manage, and activate partner logos displayed on your website</p>
        </header>

        <?php if (isset($success_message)): ?>
            <div class="alert-custom alert-success-custom">
                <i class="bi bi-check-circle-fill"></i>
                <span><?php echo $success_message; ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert-custom alert-error-custom">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><?php echo $error_message; ?></span>
            </div>
        <?php endif; ?>

        <div class="partners-grid">
            
            <!-- Upload Card -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <div class="icon-box"><i class="bi bi-cloud-arrow-up"></i></div>
                    <div>
                        <h2>Upload Logo</h2>
                    </div>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="upload-zone">
                        <input type="file" id="logo" name="logo" accept="image/*" required style="position: absolute; inset: 0; opacity: 0; cursor: pointer;" onchange="previewLogo(event)">
                        <div id="logo-preview-container" style="display: none; width: 100%; text-align: center;">
                            <img id="logo-preview" src="#" alt="Logo Preview" style="max-width: 100%; max-height: 140px; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Click or drag to change</p>
                        </div>
                        
                            <div id="logo-upload-prompt">
                            <i class="bi bi-image" style="font-size: 2rem; color: var(--accent-gold); margin-bottom: 0.5rem;"></i>
                            <p style="margin: 0; font-weight: 600; color: var(--text-main); font-size: 0.9rem;">Drop image or click</p>
                            <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">JPG, PNG, GIF up to 5MB</p>
                            </div>
                    </div>

                    <button type="submit" name="upload_logo" class="btn-premium">
                        <i class="bi bi-plus-circle"></i> Add Partner
                    </button>
                </form>
            </div>

            <!-- Partners List -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <div class="icon-box" style="background: #ecfdf5; color: var(--accent-green); border-color: #d1fae5;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <h2>Active Partners</h2>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Logo Preview</th>
                                <th style="text-align: center;">Visibility</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 0;
                            while ($partner = $partners->fetch_assoc()): 
                                $count++;
                            ?>
                                <tr>
                                    <td>
                                        <div class="logo-wrapper">
                                            <img src="uploads/partners/<?php echo htmlspecialchars($partner['logo']); ?>" loading="lazy"
                                                 alt="Partner Logo" style="height: 40px; width: auto; max-width: 120px; display: block; object-fit: contain;">
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($partner['status'] == 'active'): ?>
                                            <span class="status-pill status-active">Live</span>
                                        <?php else: ?>
                                            <span class="status-pill status-inactive">Hidden</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <div class="action-group">
                                            <a href="?toggle=<?php echo $partner['id']; ?>" class="btn-action btn-toggle" title="Change Visibility">
                                                <i class="bi <?php echo $partner['status'] == 'active' ? 'bi-eye-slash' : 'bi-eye'; ?>"></i>
                                            </a>
                                            <a href="?delete=<?php echo $partner['id']; ?>" class="btn-action btn-delete" 
                                               onclick="return confirm('Are you sure you want to delete this partner logo?');" title="Delete Permanent">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if ($count === 0): ?>
                                <tr>
                                    <td colspan="3" style="padding: 40px; text-align: center; color: #999; font-style: italic;">
                                        No partner logos uploaded yet. Start by uploading your first logo!
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function previewLogo(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('logo-preview');
        output.src = reader.result;
        document.getElementById('logo-preview-container').style.display = 'block';
        document.getElementById('logo-upload-prompt').style.display = 'none';
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>