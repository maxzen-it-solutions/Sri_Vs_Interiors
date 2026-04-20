<?php
include 'db_connect.php';

$pid = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($pid <= 0) exit;

/* -------------------------
   FETCH PROJECT
------------------------- */
$stmt = $conn->prepare("SELECT id, name, description, category, client_name, project_address, estimated_budget, start_date, end_date, project_phase, status FROM products WHERE id=? AND status='active'");
$stmt->bind_param('i', $pid);
$stmt->execute();
$res = $stmt->get_result();
$project = $res->fetch_assoc();
$stmt->close();

if (!$project) exit;

/* -------------------------
   FETCH IMAGES
------------------------- */
$images = [];
$imgStmt = $conn->prepare("SELECT image_path, media_type FROM project_images WHERE project_id=? ORDER BY order_index ASC, id ASC");
$imgStmt->bind_param('i', $pid);
$imgStmt->execute();
$r = $imgStmt->get_result();
while ($row = $r->fetch_assoc()) {
  $images[] = $row;
}
$imgStmt->close();

$mainImg = count($images) ? $images[0]['image_path'] : 'img/placeholder.jpg';
?>

<!-- 👇 data-category used by JS to update heading -->
<div data-category="<?php echo htmlspecialchars($project['category']); ?>">

  <style>
    .main-project-grid {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1.5fr 1fr;
      gap: 40px;
      align-items: start;
    }

    /* Tablet */
    @media (max-width: 992px) {
      .main-project-grid {
        grid-template-columns: 1fr;
      }
    }

    .main-project-grid > div {
      min-width: 0;
    }

    .main-img-holder {
      width: 100%;
      overflow: hidden;
    }

    .gallery-main-img {
      width: 100%;
      height: auto;
      max-width: 100%;
      display: block;
    }

    .main-project-grid h2,
    .main-project-grid p {
      word-break: break-word;
    }

    /* Ensure injected fragment styles for arrows are available */
    .proj-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0.5);
      color: #fff;
      border: none;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      cursor: pointer;
      font-size: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10;
      transition: transform .18s ease, background .12s ease;
    }

    /* 🔥 Push arrows OUTSIDE */
    .proj-arrow.prev {
      left: -24px;
    }

    .proj-arrow.next {
      right: -24px;
    }

    .proj-arrow:hover {
      transform: translateY(-50%) scale(1.06);
      background: rgba(0, 0, 0, 0.62);
    }

    /* Mobile tweak */
    @media (max-width:768px) {
      .proj-arrow.prev {
        left: -12px;
      }

      .proj-arrow.next {
        right: -12px;
      }
    }
  </style>

  <div class="main-project-grid">

    <!-- LEFT: IMAGES -->
    <div style="min-width: 0;">

      <div style="position:relative; border-radius:14px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,.15); background:#000;">
        <div class="main-img-holder">
          <!-- LEFT ARROW -->
          <button onclick="prevImage()" class="proj-arrow prev" aria-label="Previous image">❮</button>

          <div id="mainMediaWrapper">
            <?php $first = $images[0] ?? null; ?>
            <?php if ($first && $first['media_type'] === 'video'): ?>
              <video autoplay muted loop controls class="gallery-main-img">
                  <source src="<?php echo htmlspecialchars($first['image_path']); ?>" type="video/mp4">
              </video>
            <?php else: ?>
              <img id="mainProjectImg" class="gallery-main-img"
                   src="<?php echo htmlspecialchars($first['image_path'] ?? 'img/placeholder.jpg'); ?>"
                   loading="eager">
            <?php endif; ?>
          </div>

          <!-- RIGHT ARROW -->
          <button onclick="nextImage()" class="proj-arrow next" aria-label="Next image">❯</button>
        </div>
      </div>

      <?php if (!empty($images)): ?>
        <div style="display:flex;align-items:center;gap:10px;margin-top:15px;">

          <div id="thumbTrack" style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;overflow:hidden;scroll-behavior:smooth;">
            <?php foreach ($images as $index => $img): ?>
              <?php if ($img['media_type'] === 'video'): ?>
                <video class="thumb-img thumb-media"
                  data-index="<?php echo $index; ?>"
                  onclick="setMedia(<?php echo $index; ?>)"
                  style="width:70px;height:70px;object-fit:cover;border-radius:6px;cursor:pointer;">
                  <source src="<?php echo htmlspecialchars($img['image_path']); ?>">
                </video>
              <?php else: ?>
                <img src="<?php echo htmlspecialchars($img['image_path']); ?>"
                  class="thumb-img thumb-media"
                  data-index="<?php echo $index; ?>"
                  onclick="setMedia(<?php echo $index; ?>)"
                  style="width:70px;height:70px;object-fit:cover;border-radius:6px;cursor:pointer;">
              <?php endif; ?>
            <?php endforeach; ?>
          </div>

        </div>
      <?php endif; ?>

    </div>

    <!-- RIGHT: DETAILS -->
    <div style="min-width: 0;">
      <h2 style="margin-top:0;">
        <?php echo htmlspecialchars($project['name']); ?>
      </h2>

      <p style="line-height:1.7;color:#555;margin-bottom:25px;">
        <?php echo nl2br(htmlspecialchars($project['description'])); ?>
      </p>

      <div style="
          padding:20px;
          background:#fafafa;
          border-radius:12px;
          box-shadow:0 4px 12px rgba(0,0,0,.08);
      ">

        <?php if (!empty($project['client_name'])): ?>
          <p><strong>Client:</strong> <?php echo htmlspecialchars($project['client_name']); ?></p>
        <?php endif; ?>

        <?php if (!empty($project['project_address'])): ?>
          <p><strong>Address:</strong> <?php echo htmlspecialchars($project['project_address']); ?></p>
        <?php endif; ?>

        <?php if (!empty($project['estimated_budget'])): ?>
          <p><strong>Estimated Budget:</strong> ₹<?php echo number_format((float)$project['estimated_budget']); ?></p>
        <?php endif; ?>

        <?php
        $start = $project['start_date'] ? strtotime($project['start_date']) : false;
        $end = $project['end_date'] ? strtotime($project['end_date']) : false;
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
          <?php echo ($project['project_phase'] === 'handovered') ? 'Handed Over' : 'Ongoing'; ?>
        </p>

        <p><strong>Client Satisfaction:</strong>
          <?php echo ($project['status'] === 'completed') ? 'Satisfied' : 'In Progress'; ?>
        </p>

      </div>
    </div>

  </div>

</div>
