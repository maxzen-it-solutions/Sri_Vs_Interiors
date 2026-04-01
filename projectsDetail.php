<?php
session_start();
include 'db_connect.php';

$currentProject = null;
$images = [];
$mainImg = 'img/placeholder.jpg';

// If an id is provided in the URL, load that project specifically
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $gid = (int)$_GET['id'];
  $pstmt = $conn->prepare("
    SELECT id, name, description, category, created_at,
      client_name, project_address, estimated_budget,
      start_date, end_date,
      project_phase, status
      FROM products
      WHERE id = ? AND status='active'
    ");
  if ($pstmt) {
    $pstmt->bind_param('i', $gid);
    $pstmt->execute();
    $gres = $pstmt->get_result();
    if ($gres && $prow = $gres->fetch_assoc()) {
      $currentProject = $prow;
    }
    if ($gres) $gres->free();
    $pstmt->close();
  }
}

// If no specific project requested, pick the latest active project as default
if (!$currentProject) {
  $pstmt = $conn->prepare("
    SELECT id, name, description, category, created_at,
    client_name, project_address, estimated_budget,
    start_date, end_date,
    project_phase, status
    FROM products 
    WHERE status='active'
    ORDER BY created_at DESC LIMIT 1
    ");
  if ($pstmt) {
    $pstmt->execute();
    $gres = $pstmt->get_result();
    if ($gres && $prow = $gres->fetch_assoc()) {
      $currentProject = $prow;
    }
    if ($gres) $gres->free();
    $pstmt->close();
  }
}

// Determine category to use for related projects (use current project's category if available)
$categoryFilter = $currentProject ? $currentProject['category'] : 'present';

// Fetch related projects in the same category
$projects = [];
$projStmt = $conn->prepare("SELECT id, name, description, category, created_at FROM products WHERE status='active' AND LOWER(category)=LOWER(?) ORDER BY created_at DESC");
if ($projStmt) {
  $projStmt->bind_param('s', $categoryFilter);
  $projStmt->execute();
  $pres = $projStmt->get_result();
  while ($r = $pres->fetch_assoc()) $projects[] = $r;
  if ($pres) $pres->free();
  $projStmt->close();
}

// Load images for the current project (if any)
if ($currentProject) {
  $pid = (int)$currentProject['id'];
            $imgStmt = $conn->prepare("SELECT image_path FROM project_images WHERE project_id = ? ORDER BY order_index ASC, id ASC");
  if ($imgStmt) {
    $imgStmt->bind_param('i', $pid);
    $imgStmt->execute();
    $r2 = $imgStmt->get_result();
    while ($row = $r2->fetch_assoc()) $images[] = $row['image_path'];
    while ($row = $r2->fetch_assoc()) $images[] = '/' . ltrim($row['image_path'], '/');
    if ($r2) $r2->free();
    $imgStmt->close();
  }
  $mainImg = count($images) ? $images[0] : 'img/placeholder.jpg';
  $mainImg = count($images) ? '/' . ltrim($images[0], '/') : '/img/placeholder.jpg';
}

include 'header.php';
?>

  <!-- Subheader -->
  <section id="subheader">
    <div class="container-fluid m-5-hor">
      <div class="row">
        <div class="col-md-12">
          <?php
            $catRaw = isset($currentProject['category']) ? $currentProject['category'] : 'present';
            $catDisplay = (strtolower($catRaw) !== 'present') ? $catRaw . ' Designs' : 'Present Projects';
          ?>
          <h1 style="text-transform:uppercase;" data-category="<?php echo htmlspecialchars($catRaw); ?>">
            <?php echo htmlspecialchars($catDisplay); ?>
          </h1>

        </div>
      </div>
    </div>
  </section>

<?php
// $projects already fetched above. Do not overwrite `$currentProject` here.
if (empty($projects)) {
  echo '<section style="padding:40px 0;"><div style="max-width:1200px;margin:0 auto;">No present projects found.</div></section>';
}
?>

  <!-- Main Project Section -->
  <section id="main-project" style="padding:60px 0;">
  <div class="main-project-grid" style="max-width:1200px;margin:0 auto;display:grid;grid-template-columns:2fr 1.3fr;gap:40px;align-items:start;">

    <!-- LEFT: IMAGES -->
    <div>

      <!-- Main Image -->
      <div style="position:relative;border-radius:14px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.15);">

        <!-- LEFT ARROW -->
        <button onclick="prevImage()" class="proj-arrow prev" aria-label="Previous image">❮</button>

        <!-- MAIN IMAGE -->
        <img id="mainProjectImg"
             src="<?php echo htmlspecialchars($mainImg); ?>"
             style="width:100%;height:65vh;object-fit:cover;display:block;">

        <!-- RIGHT ARROW -->
        <button onclick="nextImage()" class="proj-arrow next" aria-label="Next image">❯</button>

      </div>

      <!-- Thumbnails + Arrows -->
      <?php if (!empty($images)): ?>
      <div style="display:flex;align-items:center;gap:10px;margin-top:15px;justify-content:center;">

        <div id="thumbTrack" style="display:flex;gap:10px;justify-content:center;flex-wrap:nowrap;overflow:hidden;white-space:nowrap;">
          <?php foreach ($images as $index => $img): ?>
            <img src="<?php echo htmlspecialchars($img); ?>"
              class="thumb-img"
              data-index="<?php echo $index; ?>"
              onclick="setMainImageByIndex(<?php echo $index; ?>)"
              style="width:70px;height:70px;object-fit:cover;border-radius:6px;cursor:pointer;">

          <?php endforeach; ?>
        </div>

        

      </div>
      <?php endif; ?>
    </div>

    <!-- RIGHT: DETAILS -->
    <div>

      <h2><?php echo htmlspecialchars($currentProject['name']); ?></h2>

      <p style="line-height:1.7;color:#555;margin-bottom:25px;">
        <?php echo nl2br(htmlspecialchars($currentProject['description'])); ?>
      </p>

      <div style="
          padding:20px;
          background:#fafafa;
          border-radius:12px;
          box-shadow:0 4px 12px rgba(0,0,0,.08);
      ">

        <?php if(!empty($currentProject['client_name'])): ?>
          <p><strong>Client:</strong> <?php echo htmlspecialchars($currentProject['client_name']); ?></p>
        <?php endif; ?>

        <?php if(!empty($currentProject['project_address'])): ?>
          <p><strong>Address:</strong> <?php echo htmlspecialchars($currentProject['project_address']); ?></p>
        <?php endif; ?>

        <?php if(!empty($currentProject['estimated_budget'])): ?>
          <p><strong>Estimated Budget:</strong> ₹<?php echo number_format((float)$currentProject['estimated_budget']); ?></p>
        <?php endif; ?>

        <?php 
          $start = $currentProject['start_date'] ? strtotime($currentProject['start_date']) : false;
          $end = $currentProject['end_date'] ? strtotime($currentProject['end_date']) : false;
          if ($start && $end): 
        ?>
          <p><strong>Project Duration:</strong>
            <?php 
              echo date("d M Y", $start) .
                  " - " .
                  date("d M Y", $end);
            ?>
          </p>
        <?php endif; ?>

        <p><strong>Status:</strong>
          <?php echo ($currentProject['project_phase'] === 'handovered') ? 'Handed Over' : 'Ongoing'; ?>
        </p>

        <p><strong>Client Satisfaction:</strong>
          <?php echo ($currentProject['status'] === 'completed') ? 'Satisfied' : 'In Progress'; ?>
        </p>

      </div>

    </div>
</section>

      <!-- Related Projects Heading -->

        <section style="padding: 20px 0; text-align:center; background:#f9f9f9;">
          <h2 style="margin:0; font-size:32px; font-weight:700; color:#222; letter-spacing:1px;">
            Related Projects
          </h2>
        </section>
        
      <!-- Related Projects Grid -->
      <section style="padding:50px 0; background:#fff;">
        <div class="related-grid-wrap" style="max-width:1300px; margin:0 auto;">
          <div class="related-grid" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(400px,1fr)); gap:30px; background:#fff;">

            <?php
            $imgStmt = $conn->prepare("SELECT image_path FROM project_images WHERE project_id = ? ORDER BY id ASC LIMIT 1");

            foreach ($projects as $p):
                $pid = (int)$p['id'];
                $thumb = 'img/placeholder.jpg';

                if($imgStmt){
                    $imgStmt->bind_param('i',$pid);
                    $imgStmt->execute();
                    $r = $imgStmt->get_result();
                    if($r && $row=$r->fetch_assoc()) $thumb = $row['image_path'];
                    if($r) $r->free();
                }
            ?>

              <div onclick="loadProject(<?php echo $pid; ?>)" 
                  style="cursor:pointer; border-radius:16px; overflow:hidden; 
                          box-shadow:0 8px 24px rgba(0,0,0,0.2); position:relative; height:420px; transition:transform 0.3s ease, box-shadow 0.3s ease;">

                <img src="<?php echo htmlspecialchars($thumb); ?>" 
                    style="width:100%; height:100%; object-fit:cover; display:block;">

                <!-- Content overlay at bottom: only show project name (no button) -->
                <div style="position:absolute; bottom:0; left:0; right:0; padding:25px; color:#fff; text-align:center; background:linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.6) 100%);">
                  <h3 style="margin:0; font-size:20px; font-weight:700; color:#f9c54d; text-transform:uppercase; letter-spacing:1px; word-break:break-word;">
                    <?php echo htmlspecialchars($p['name']); ?>
                  </h3>
                </div>

              </div>

            <?php endforeach; ?>

            <?php if($imgStmt) $imgStmt->close(); ?>

          </div>
        </div>
      </section>

      <style>
        /* Project detail arrows — consistent placement and responsive */
        .proj-arrow{position:absolute;top:50%;transform:translateY(-50%);background:rgba(0,0,0,0.5);color:#fff;border:none;width:48px;height:48px;border-radius:50%;cursor:pointer;font-size:20px;display:flex;align-items:center;justify-content:center;z-index:5;transition:transform .18s ease,background .12s ease;}
        .proj-arrow.prev{left:14px}
        .proj-arrow.next{right:14px}
        .proj-arrow:hover{transform:translateY(-50%) scale(1.06);background:rgba(0,0,0,0.62)}
        @media(max-width:768px){.proj-arrow.prev{left:8px}.proj-arrow.next{right:8px}}

        /* Hover effects for project cards */
        [onclick*="loadProject"]:hover {
          transform: translateY(-8px) !important;
          box-shadow: 0 12px 32px rgba(0,0,0,0.3) !important;
        }

        /* Desktop: larger card height */
        @media (min-width: 992px) {
          [onclick*="loadProject"] {
            height: 500px !important;
          }
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
          div[style*="grid-template-columns"] {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)) !important;
            gap: 30px !important;
            background: #fff !important;
          }

          [onclick*="loadProject"] {
            height: 360px !important;
          }

          [onclick*="loadProject"] h3 {
            font-size: 18px !important;
          }
        }

          /* Thumbnail styles */
          .thumb-img {
            opacity: 0.75;
            transition: opacity 0.18s ease, transform 0.18s ease;
            border: 2px solid transparent;
          }

          .thumb-img:hover,
          .thumb-img.active {
            opacity: 1;
            transform: scale(1.05);
            border-color: rgba(0,0,0,0.08);
          }

          /* Prevent very long project names from overflowing the viewport */
          /* Main project title */
          .main-project-grid h2 {
            word-break: break-word;
            overflow-wrap: anywhere;
            hyphens: auto;
            max-width: 100%;
            margin-bottom: 12px;
          }

          /* Related project titles (cards) */
          .related-grid h3 {
            word-break: break-word;
            overflow-wrap: anywhere;
            hyphens: auto;
            max-width: 100%;
          }

          /* Description text should also wrap long words */
          .main-project-grid p {
            word-break: break-word;
            overflow-wrap: anywhere;
          }

          /* Responsive adjustments: add side gaps and center content on narrow screens */
          .main-project-grid { padding: 0 20px; box-sizing: border-box; }
          .related-grid-wrap { padding: 0 20px; box-sizing: border-box; }

          @media (max-width: 767px) {
            /* Stack columns and reduce gaps */
            .main-project-grid {
              display: grid !important;
              grid-template-columns: 1fr !important;
              gap: 18px !important;
              max-width: 420px !important;
              padding: 0 18px !important;
            }

            /* Constrain main project image to match project card look */
            #mainProjectImg {
              height: 360px !important;
              max-height: 360px !important;
              object-fit: cover !important;
              border-radius: 12px !important;
              display: block;
            }

            /* Thumbnails: center and allow horizontal scroll if overflowing */
            #thumbTrack {
              justify-content: center !important;
              overflow-x: auto !important;
              -webkit-overflow-scrolling: touch;
              gap: 8px !important;
              padding-bottom: 8px !important;
            }

            /* Related grid: reduce min width and center */
            .related-grid {
              grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)) !important;
              gap: 20px !important;
              max-width: 420px !important;
              margin: 0 auto !important;
            }

            /* Ensure related cards match mobile height used site-wide */
            [onclick*="loadProject"] {
              height: 360px !important;
            }

            /* Slightly reduce main title size on small phones to improve layout */
            .main-project-grid h2 {
              font-size: 20px !important;
              line-height: 1.2 !important;
              padding-right: 6px;
            }
          }

        </style>


  <!-- Footer -->
  <?php include 'footer.php'; ?>

</div>


<script>
  

function loadProject(pid){
  const container = document.getElementById('main-project');
  container.innerHTML = '<div class="ajax-loader"></div>';
  
  fetch('projectDetailAjax.php?id='+pid)
    .then(res=>res.text())
    .then(html=>{
        // parse returned html for category and update heading (append " Designs" for categories)
        const temp = document.createElement('div');
        temp.innerHTML = html;
        const catEl = temp.querySelector('[data-category]');
        const cat = catEl ? catEl.dataset.category : null;
        if (cat) {
          const h1 = document.querySelector('#subheader h1');
          if (h1) {
            const display = (cat.toLowerCase() !== 'present') ? (cat + ' Designs') : 'Present Projects';
            h1.textContent = display.toUpperCase();
            h1.dataset.category = cat;
          }
        }

        document.getElementById('main-project').innerHTML = html;
        // reinitialize gallery controls for injected content
        if (typeof reinitGallery === 'function') reinitGallery();
        window.scrollTo({top:0, behavior:'smooth'});
    });
}

function changeMainImage(img){
  if (typeof changeImage === 'function') {
    changeImage(img, 'mainProjectImg');
  } else {
    document.getElementById('mainProjectImg').src = img.src;
  }
}

// Image gallery helpers
let currentImageIndex = 0;
let imageSources = [];

function reinitGallery(){
  // collect thumbnails
  imageSources = Array.from(document.querySelectorAll('.thumb-img')).map(img => img.src);

  // if main image matches a thumbnail, set index accordingly
  const main = document.getElementById('mainProjectImg');
  if (main && imageSources.length) {
    const idx = imageSources.indexOf(main.src);
    currentImageIndex = idx >= 0 ? idx : 0;
    setMainImageByIndex(currentImageIndex);
  }
}

document.addEventListener("DOMContentLoaded", reinitGallery);

function setMainImageByIndex(index) {
  if (!imageSources.length) return;
  currentImageIndex = index;
  const main = document.getElementById('mainProjectImg');
  if (main) main.src = imageSources[index];

  // highlight active thumbnail
  document.querySelectorAll('.thumb-img').forEach((img, i) => {
    img.classList.toggle('active', i === index);
  });
}

function nextImage() {
  if (!imageSources.length) return;
  currentImageIndex = (currentImageIndex + 1) % imageSources.length;
  setMainImageByIndex(currentImageIndex);
}

function prevImage() {
  if (!imageSources.length) return;
  currentImageIndex = (currentImageIndex - 1 + imageSources.length) % imageSources.length;
  setMainImageByIndex(currentImageIndex);
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
