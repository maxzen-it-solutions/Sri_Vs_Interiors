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
    $res = $conn->query("SELECT logo FROM partners WHERE id = $id");
    if ($res && $row = $res->fetch_assoc()) {
        $logo = $row['logo'];
        // Delete file
        if ($logo && file_exists('uploads/partners/' . $logo)) {
            unlink('uploads/partners/' . $logo);
        }
    }

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

<div class="main-content">
    <div style="padding: 30px 20px; background: linear-gradient(135deg, #f5f1e8 0%, #e8dcc8 100%); min-height: 100vh;">
        
        <!-- Page Header -->
        <div style="margin-bottom: 40px; max-width: 1200px; margin-left: auto; margin-right: auto;">
            <h1 style="font-size: 36px; font-weight: 800; color: #1a1a1a; margin: 0 0 8px 0; font-family: 'Poppins', sans-serif;">
                Partner Logos Management
            </h1>
            <p style="font-size: 14px; color: #666; margin: 0; font-family: 'Poppins', sans-serif;">Upload, manage, and activate partner logos displayed on your website</p>
        </div>

        <?php if (isset($success_message)): ?>
            <div style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border: 1px solid #b1dfbb; border-radius: 12px; padding: 16px 20px; margin-bottom: 30px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(21, 87, 36, 0.1); max-width: 1200px; margin-left: auto; margin-right: auto;">
                <span style="font-size: 22px;">✓</span>
                <p style="margin: 0; color: #155724; font-weight: 600; font-family: 'Poppins', sans-serif;"><?php echo $success_message; ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div style="background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border: 1px solid #f1b0b7; border-radius: 12px; padding: 16px 20px; margin-bottom: 30px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.1); max-width: 1200px; margin-left: auto; margin-right: auto;">
                <span style="font-size: 22px;">✕</span>
                <p style="margin: 0; color: #721c24; font-weight: 600; font-family: 'Poppins', sans-serif;"><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>

        <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
            
            <!-- Upload Card -->
            <div style="background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); border: 1px solid rgba(248, 212, 139, 0.2); height: fit-content;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 28px;">
                    <div style="background: linear-gradient(135deg, #f8d48b 0%, #f1d18a 100%); width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 12px rgba(248, 212, 139, 0.3);">📤</div>
                    <div>
                        <h2 style="font-size: 20px; font-weight: 700; margin: 0; color: #1a1a1a; font-family: 'Poppins', sans-serif;">Upload Logo</h2>
                        <p style="font-size: 12px; color: #999; margin: 4px 0 0 0; font-family: 'Poppins', sans-serif;">Add new partner</p>
                    </div>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div style="margin-bottom: 24px;">
                        <label for="logo" style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Poppins', sans-serif;">Select Image</label>
                        <div style="position: relative; border: 2px dashed #f8d48b; border-radius: 10px; padding: 20px; text-align: center; background: rgba(248, 212, 139, 0.05); transition: all 0.3s ease; cursor: pointer; min-height: 160px; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.borderColor='#e0be6f'; this.style.background='rgba(248, 212, 139, 0.1)';" onmouseout="this.style.borderColor='#f8d48b'; this.style.background='rgba(248, 212, 139, 0.05)';">
                            <input type="file" id="logo" name="logo" accept="image/*" required style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" onchange="previewLogo(event)">
                            <div id="logo-upload-prompt">
                                <div style="font-size: 32px; margin-bottom: 8px;">📁</div>
                                <p style="margin: 0 0 4px 0; font-weight: 600; color: #333; font-family: 'Poppins', sans-serif; font-size: 14px;">Click to upload</p>
                                <p style="margin: 0; font-size: 12px; color: #999; font-family: 'Poppins', sans-serif;">JPG, PNG, GIF (Max 5MB)</p>
                            </div>
                            <img id="logo-preview" src="#" alt="Logo Preview" style="display: none; max-width: 100%; max-height: 150px; border-radius: 8px;">
                        </div>
                    </div>

                    <button type="submit" name="upload_logo" 
                            style="width: 100%; background: linear-gradient(135deg, #f8d48b 0%, #f1d18a 100%); color: #000; padding: 16px 24px; font-size: 14px; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; font-family: 'Poppins', sans-serif; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(248, 212, 139, 0.3);"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(248, 212, 139, 0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(248, 212, 139, 0.3)';">
                        🚀 Upload Logo
                    </button>
                </form>
            </div>

            <!-- Partners List -->
            <div style="background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); border: 1px solid rgba(248, 212, 139, 0.2);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 28px;">
                    <div style="background: linear-gradient(135deg, #b2853f 0%, #c29a54 100%); width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 12px rgba(178, 133, 63, 0.3);">🤝</div>
                    <div>
                        <h2 style="font-size: 20px; font-weight: 700; margin: 0; color: #1a1a1a; font-family: 'Poppins', sans-serif;">Active Partners</h2>
                        <p style="font-size: 12px; color: #999; margin: 4px 0 0 0; font-family: 'Poppins', sans-serif;">Manage your partners</p>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-family: 'Poppins', sans-serif;">
                        <thead>
                            <tr style="border-bottom: 2px solid #f0f0f0; background: #fafafa;">
                                <th style="padding: 16px; text-align: left; font-size: 12px; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Logo</th>
                                <th style="padding: 16px; text-align: center; font-size: 12px; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                                <th style="padding: 16px; text-align: right; font-size: 12px; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 0;
                            while ($partner = $partners->fetch_assoc()): 
                                $count++;
                            ?>
                                <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(248, 212, 139, 0.08)';" onmouseout="this.style.background='transparent';">
                                    <td style="padding: 16px; text-align: left;">
                                        <div style="background: #f5f5f5; padding: 8px; border-radius: 8px; display: inline-block;">
                                            <img src="uploads/partners/<?php echo htmlspecialchars($partner['logo']); ?>"
                                                 alt="Partner Logo" style="height: 50px; width: auto; max-width: 120px; display: block;">
                                        </div>
                                    </td>
                                    <td style="padding: 16px; text-align: center;">
                                        <?php if ($partner['status'] == 'active'): ?>
                                            <span style="display: inline-block; background: linear-gradient(135deg, #d4edda 0%, #c8e6c9 100%); color: #2e7d32; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">✓ Active</span>
                                        <?php else: ?>
                                            <span style="display: inline-block; background: linear-gradient(135deg, #e0e0e0 0%, #d0d0d0 100%); color: #616161; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">○ Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 16px; text-align: right;">
                                        <a href="?toggle=<?php echo $partner['id']; ?>" 
                                           style="display: inline-block; padding: 8px 14px; margin-right: 8px; background: linear-gradient(135deg, #f8d48b 0%, #f1d18a 100%); color: #000; border: none; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px;"
                                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(248, 212, 139, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <?php echo $partner['status'] == 'active' ? '⊘ Deactivate' : '✓ Activate'; ?>
                                        </a>
                                        <a href="?delete=<?php echo $partner['id']; ?>" 
                                           onclick="return confirm('Are you sure? This action cannot be undone.');"
                                           style="display: inline-block; padding: 8px 14px; background: #f8e4e4; color: #d32f2f; border: none; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px;"
                                           onmouseover="this.style.background='#ffcdd2'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(211, 47, 47, 0.2)';"
                                           onmouseout="this.style.background='#f8e4e4'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            🗑 Delete
                                        </a>
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

<style>
    @media (max-width: 992px) {
        [style*="grid-template-columns: 1fr 2fr"] {
            grid-template-columns: 1fr !important;
        }
    }
    @media (max-width: 768px) {
        h1 { font-size: 28px !important; }
    }
</style>

<script>
function previewLogo(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('logo-preview');
        output.src = reader.result;
        output.style.display = 'block';
        document.getElementById('logo-upload-prompt').style.display = 'none';
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>