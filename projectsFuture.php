<?php
session_start();
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
  <meta charset="utf-8">
  <title>Sri VS Interiors| Future Projects</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/themify-icons.css">
  <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="css/on3step-style.css" rel="stylesheet">
</head>
<body>
<div class="content-wrapper">

  <!-- Header -->
  <?php include 'header.php'; ?>

  <section id="subheader">
    <div class="container-fluid m-5-hor">
      <div class="row">
        <div class="col-md-12">
          <h1>Future Projects</h1>
        </div>
      </div>
    </div>
  </section>

<?php
// Fetch all future projects
$projects = [];
$stmt = $conn->prepare("SELECT * FROM products WHERE status='active' AND LOWER(category)='future' ORDER BY created_at DESC");
$stmt->execute();
$res = $stmt->get_result();
while($r = $res->fetch_assoc()) $projects[] = $r;
$stmt->close();

// Default first project
$currentProject = $projects[0] ?? null;
if(!$currentProject) {
    echo '<section style="padding:40px 0;"><div style="max-width:1200px;margin:0 auto;">No future projects found.</div></section>';
    exit;
}

$pid = (int)$currentProject['id'];
$images = [];
$imgStmt = $conn->prepare("SELECT image_path FROM project_images WHERE project_id=? ORDER BY id ASC");
$imgStmt->bind_param('i', $pid);
$imgStmt->execute();
$r2 = $imgStmt->get_result();
while($row = $r2->fetch_assoc()) $images[] = $row['image_path'];
$imgStmt->close();
$mainImg = $images[0] ?? 'img/placeholder.jpg';
?>

<!-- Main Project Section -->
<section id="main-project" style="padding:50px 0; font-family:Arial,sans-serif;">
  <div style="max-width:1200px; margin:0 auto; display:flex; flex-wrap:wrap; gap:30px;">
    <div style="flex:2; min-width:300px;">
      <div style="border-radius:10px; overflow:hidden; box-shadow:0 5px 20px rgba(0,0,0,0.1);">
        <img id="mainProjectImg" src="<?php echo htmlspecialchars($mainImg); ?>" style="width:100%; height:70vh; object-fit:cover; border-radius:10px;">
      </div>
      <?php if(!empty($images)): ?>
      <div style="display:flex; justify-content:center; gap:10px; margin-top:10px;">
        <?php foreach($images as $img): ?>
          <img src="<?php echo htmlspecialchars($img); ?>" onclick="changeImage(this, 'mainProjectImg')" style="width:60px; height:60px; object-fit:cover; border-radius:5px; cursor:pointer;">
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <div style="flex:1; min-width:250px;">
      <div style="background:#fff; padding:25px; border-radius:10px; box-shadow:0 5px 20px rgba(0,0,0,0.05);">
        <h2><?php echo htmlspecialchars($currentProject['name']); ?></h2>
        <p style="color:#666; line-height:1.6;"><?php echo nl2br(htmlspecialchars($currentProject['description'])); ?></p>
        <div style="margin-top:20px;">
          <h3 style="margin:0 0 10px 0; color:#333;">Project Details</h3>
          <p><strong>Client:</strong> <?php echo htmlspecialchars($currentProject['client_name']); ?></p>
          <p><strong>Category:</strong> <?php echo htmlspecialchars($currentProject['category']); ?></p>
          <p><strong>Created:</strong> <?php echo htmlspecialchars($currentProject['created_at']); ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Related Projects Heading -->

        <section style="padding: 20px 0; text-align:center; background:#f9f9f9;">
          <h2 style="margin:0; font-size:32px; font-weight:700; color:#222; letter-spacing:1px;">
            Related Projects
          </h2>
        </section>

<!-- Related Projects -->
<section id="related-projects" style="padding:50px 0;">
  <div class="container-fluid m-5-hor">
    <h2 style="font-weight:700; margin-bottom:20px;">Related Projects</h2>
    <div class="scroll-gallery-wrapper">
      <div class="scroll-gallery-track">
        <?php
        $imgStmt = $conn->prepare("SELECT image_path FROM project_images WHERE project_id=? ORDER BY id ASC LIMIT 1");
        foreach($projects as $p):
          $pid = (int)$p['id'];
          $thumb = 'img/placeholder.jpg';
          if($imgStmt){
            $imgStmt->bind_param('i', $pid);
            $imgStmt->execute();
            $r = $imgStmt->get_result();
            if($r && $row = $r->fetch_assoc()) $thumb = $row['image_path'];
            if($r) $r->free();
          }
        ?>
        <div class="scroll-item" onclick="loadProject(<?php echo $pid; ?>)">
          <div class="hovereffect">
            <img src="<?php echo htmlspecialchars($thumb); ?>" alt="">
            <div class="overlay"><h3><?php echo htmlspecialchars($p['name']); ?></h3></div>
          </div>
        </div>
        <?php endforeach;
        if($imgStmt) $imgStmt->close();
        ?>
      </div>
    </div>
  </div>
</section>
    <style>
      .scroll-gallery-wrapper { overflow:hidden; width:100%; }
      .scroll-gallery-track { display:flex; gap:15px; }
      .scroll-gallery-track .scroll-item { flex:0 0 auto; width:450px; height:400px; position:relative; }
      .scroll-gallery-track .scroll-item img { width:100%; height:100%; object-fit:cover; border-radius:10px; }
      .scroll-item .hovereffect { position:relative; overflow:hidden; width:100%; height:100%; }
      .scroll-item .overlay { position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); color:#fff; opacity:0; transition:opacity 0.3s; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; padding:10px; }
      .scroll-item .hovereffect:hover .overlay { opacity:1; }
      .scroll-item .overlay h3 { margin:0 0 5px 0; font-size:18px; }
      .scroll-item .overlay p { margin:0; font-size:14px; }
   </style>

<!-- Footer -->
<?php include 'footer.php'; ?>

</div>

<!-- Scripts -->
<script>
function changeImage(thumbnail, mainId){
    const mainImg = document.getElementById(mainId);
    mainImg.style.opacity = 0;
    setTimeout(()=>{
        mainImg.src = thumbnail.src;
        mainImg.style.opacity = 1;
    }, 200);
}

function loadProject(pid){
    fetch('projectDetailAjax.php?id='+pid)
    .then(res => res.text())
    .then(html => {
        document.getElementById('main-project').innerHTML = html;
        window.scrollTo({top:0, behavior:'smooth'});
    });
}

// Smooth scroll gallery
document.addEventListener("DOMContentLoaded", function() {
    const track = document.querySelector('.scroll-gallery-track');
    if(!track) return;
    const items = Array.from(track.children);
    items.forEach(item => track.appendChild(item.cloneNode(true)));

    let trackWidth = 0;
    items.forEach(item=>{
        const style = getComputedStyle(item);
        const gap = parseInt(style.marginRight) || 0;
        trackWidth += item.offsetWidth + gap;
    });

    const duration = trackWidth / 100;
    track.style.animation = `scroll-left ${duration}s linear infinite`;

    const styleEl = document.createElement('style');
    styleEl.innerHTML = `
      @keyframes scroll-left {
        0% { transform: translateX(0); }
        100% { transform: translateX(-${trackWidth/2}px); }
      }
    `;
    document.head.appendChild(styleEl);
});
</script>

</body>
</html>
