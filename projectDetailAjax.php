<?php
include 'db_connect.php';
$pid = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE status='active' AND id=?");
$stmt->bind_param('i', $pid);
$stmt->execute();
$res = $stmt->get_result();
if($res && $project = $res->fetch_assoc()) {
    // Fetch images
    $images = [];
    $imgStmt = $conn->prepare("SELECT image_path FROM project_images WHERE project_id=? ORDER BY id ASC");
    $imgStmt->bind_param('i', $pid);
    $imgStmt->execute();
    $r2 = $imgStmt->get_result();
    while($row = $r2->fetch_assoc()) $images[] = $row['image_path'];
    $imgStmt->close();

    $mainImg = count($images) ? $images[0] : 'img/placeholder.jpg';
?>

<div style="max-width:1200px; margin:0 auto; display:flex; flex-wrap:wrap; gap:30px;">
    <!-- Left: Image -->
    <div style="flex:2; min-width:300px;">
        <div style="border-radius:10px; overflow:hidden; box-shadow:0 5px 20px rgba(0,0,0,0.1);">
            <img id="mainProjectImg" src="<?php echo htmlspecialchars($mainImg); ?>" 
                 style="width:100%; height:70vh; object-fit:cover; display:block; border-radius:10px;">
        </div>
        <?php if(!empty($images)): ?>
        <div style="display:flex; justify-content:center; gap:10px; margin-top:10px;">
            <?php foreach($images as $img): ?>
            <img src="<?php echo htmlspecialchars($img); ?>" onclick="changeMainImage(this)" 
                 style="width:60px; height:60px; object-fit:cover; border-radius:5px; cursor:pointer;">
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right: Project Details -->
    <div style="flex:1; min-width:250px;">
        <div style="background:#fff; padding:25px; border-radius:10px; box-shadow:0 5px 20px rgba(0,0,0,0.05);">
            <h2 style="margin-top:0; color:#333;"><?php echo htmlspecialchars($project['name']); ?></h2>
            <p style="color:#666; line-height:1.6;"><?php echo nl2br(htmlspecialchars(substr($project['description'],0,1000))); ?></p>
            <div style="margin-top:20px;">
                <h3 style="margin:0 0 10px 0; color:#333;">Project Details</h3>
                <p><strong>Client:</strong> <?php echo htmlspecialchars($project['client_name']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($project['category']); ?></p>
                <p><strong>Created:</strong> <?php echo htmlspecialchars($project['created_at']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($project['address']); ?></p>
            </div>
        </div>
    </div>
</div>

<?php } ?>
