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

<div class="main-content">
    <div class="container-fluid" style="padding: 30px 20px; background: linear-gradient(135deg, #f5f1e8 0%, #e8dcc8 100%); min-height: 100vh;">
        
        <!-- Page Header -->
        <div style="margin-bottom: 40px;">
            <h1 style="font-size: 36px; font-weight: 800; color: #1a1a1a; margin: 0 0 8px 0; font-family: 'Poppins', sans-serif;">
                Project Statistics Management
            </h1>
            <p style="font-size: 14px; color: #666; margin: 0; font-family: 'Poppins', sans-serif;">Manage and update your project statistics across the website</p>
        </div>

        <?php if (isset($success_message)): ?>
            <div style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border: 1px solid #b1dfbb; border-radius: 12px; padding: 16px 20px; margin-bottom: 30px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(21, 87, 36, 0.1);">
                <span style="font-size: 22px;">✓</span>
                <p style="margin: 0; color: #155724; font-weight: 600; font-family: 'Poppins', sans-serif;"><?php echo $success_message; ?></p>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;">
            
            <!-- Update Form Card -->
            <div style="background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); border: 1px solid rgba(248, 212, 139, 0.2); transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 28px;">
                    <div style="background: linear-gradient(135deg, #f8d48b 0%, #f1d18a 100%); width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 12px rgba(248, 212, 139, 0.3);">📊</div>
                    <div>
                        <h2 style="font-size: 24px; font-weight: 700; margin: 0; color: #1a1a1a; font-family: 'Poppins', sans-serif;">Update Statistics</h2>
                        <p style="font-size: 12px; color: #999; margin: 4px 0 0 0; font-family: 'Poppins', sans-serif;">Modify project numbers</p>
                    </div>
                </div>

                <form method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                    
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Poppins', sans-serif;">Total Projects</label>
                        <input type="number" name="total_projects"
                               value="<?php echo $stats['total_projects']; ?>" required min="0"
                               style="width: 100%; padding: 14px 16px; border: 2px solid #e5e5e5; border-radius: 10px; font-size: 16px; font-family: 'Poppins', sans-serif; transition: all 0.3s ease; box-sizing: border-box;"
                               onmouseover="this.style.borderColor='#f8d48b';" onmouseout="this.style.borderColor='#e5e5e5';" onfocus="this.style.borderColor='#f8d48b'; this.style.boxShadow='0 0 0 3px rgba(248, 212, 139, 0.1)';" onblur="this.style.boxShadow='none';">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Poppins', sans-serif;">Completed Projects</label>
                        <input type="number" name="completed_projects"
                               value="<?php echo $stats['completed_projects']; ?>" required min="0"
                               style="width: 100%; padding: 14px 16px; border: 2px solid #e5e5e5; border-radius: 10px; font-size: 16px; font-family: 'Poppins', sans-serif; transition: all 0.3s ease; box-sizing: border-box;"
                               onmouseover="this.style.borderColor='#f8d48b';" onmouseout="this.style.borderColor='#e5e5e5';" onfocus="this.style.borderColor='#f8d48b'; this.style.boxShadow='0 0 0 3px rgba(248, 212, 139, 0.1)';" onblur="this.style.boxShadow='none';">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Poppins', sans-serif;">Ongoing Projects</label>
                        <input type="number" name="ongoing_projects"
                               value="<?php echo $stats['ongoing_projects']; ?>" required min="0"
                               style="width: 100%; padding: 14px 16px; border: 2px solid #e5e5e5; border-radius: 10px; font-size: 16px; font-family: 'Poppins', sans-serif; transition: all 0.3s ease; box-sizing: border-box;"
                               onmouseover="this.style.borderColor='#f8d48b';" onmouseout="this.style.borderColor='#e5e5e5';" onfocus="this.style.borderColor='#f8d48b'; this.style.boxShadow='0 0 0 3px rgba(248, 212, 139, 0.1)';" onblur="this.style.boxShadow='none';">
                    </div>

                    <button type="submit" name="update_stats" 
                            style="background: linear-gradient(135deg, #f8d48b 0%, #f1d18a 100%); color: #000; padding: 16px 24px; font-size: 15px; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; margin-top: 10px; font-family: 'Poppins', sans-serif; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(248, 212, 139, 0.3);"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(248, 212, 139, 0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(248, 212, 139, 0.3)';">
                        🔄 Update Statistics
                    </button>
                </form>
            </div>

            <!-- Statistics Display Card -->
            <div style="background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); border: 1px solid rgba(248, 212, 139, 0.2);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 28px;">
                    <div style="background: linear-gradient(135deg, #b2853f 0%, #c29a54 100%); width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 12px rgba(178, 133, 63, 0.3);">📈</div>
                    <div>
                        <h2 style="font-size: 24px; font-weight: 700; margin: 0; color: #1a1a1a; font-family: 'Poppins', sans-serif;">Current Statistics</h2>
                        <p style="font-size: 12px; color: #999; margin: 4px 0 0 0; font-family: 'Poppins', sans-serif;">Live project numbers</p>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    
                    <!-- Total Projects -->
                    <div style="background: linear-gradient(135deg, #e8f4f8 0%, #d0e8f0 100%); padding: 20px; border-radius: 12px; border-left: 4px solid #0288d1; position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 0; right: 0; font-size: 48px; opacity: 0.1;">📊</div>
                        <p style="font-size: 12px; color: #0277bd; font-weight: 600; text-transform: uppercase; margin: 0 0 8px 0; font-family: 'Poppins', sans-serif; letter-spacing: 0.5px;">Total Projects</p>
                        <h3 style="font-size: 42px; font-weight: 800; color: #0277bd; margin: 0; font-family: 'Poppins', sans-serif;"><?php echo $stats['total_projects']; ?></h3>
                    </div>

                    <!-- Completed Projects -->
                    <div style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); padding: 20px; border-radius: 12px; border-left: 4px solid #43a047; position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 0; right: 0; font-size: 48px; opacity: 0.1;">✓</div>
                        <p style="font-size: 12px; color: #2e7d32; font-weight: 600; text-transform: uppercase; margin: 0 0 8px 0; font-family: 'Poppins', sans-serif; letter-spacing: 0.5px;">Completed Projects</p>
                        <h3 style="font-size: 42px; font-weight: 800; color: #2e7d32; margin: 0; font-family: 'Poppins', sans-serif;"><?php echo $stats['completed_projects']; ?></h3>
                    </div>

                    <!-- Ongoing Projects -->
                    <div style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); padding: 20px; border-radius: 12px; border-left: 4px solid #f57c00; position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 0; right: 0; font-size: 48px; opacity: 0.1;">⚙️</div>
                        <p style="font-size: 12px; color: #e65100; font-weight: 600; text-transform: uppercase; margin: 0 0 8px 0; font-family: 'Poppins', sans-serif; letter-spacing: 0.5px;">Ongoing Projects</p>
                        <h3 style="font-size: 42px; font-weight: 800; color: #e65100; margin: 0; font-family: 'Poppins', sans-serif;"><?php echo $stats['ongoing_projects']; ?></h3>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .main-content > div[style*="display: grid"] {
            grid-template-columns: 1fr !important;
        }
        h1 { font-size: 28px !important; }
    }
</style>