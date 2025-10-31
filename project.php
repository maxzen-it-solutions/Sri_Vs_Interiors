<?php include 'header.php'; ?>
<?php
include 'db_connect.php';

// Fetch active projects
$products = [];
$res = $conn->query("SELECT id, name, description, category FROM products WHERE status='active' ORDER BY created_at DESC");
if ($res) {
  while ($r = $res->fetch_assoc()) $products[] = $r;
}

// Fetch first image for each project
$imgStmt = $conn->prepare("SELECT image_path FROM project_images WHERE project_id = ? ORDER BY id ASC LIMIT 1");

// Map category to lowercase class
function map_category_class($cat) {
  $c = strtolower(trim((string)$cat));
  if (strpos($c, 'past') !== false) return 'past';
  if (strpos($c, 'present') !== false) return 'present';
  if (strpos($c, 'future') !== false) return 'future';
  return 'uncategorized';
}

// Group projects by category
$groups = ['past'=>[], 'present'=>[], 'future'=>[]];
foreach ($products as $p) {
  $pid = (int)$p['id'];
  $thumb = null;
  if ($imgStmt) {
    $imgStmt->bind_param('i', $pid);
    $imgStmt->execute();
    $r = $imgStmt->get_result();
    if ($r && $row = $r->fetch_assoc()) $thumb = $row['image_path'];
    if ($r) $r->free();
  }
  $p['image_path'] = $thumb;
  $cls = map_category_class($p['category']);
  if (isset($groups[$cls])) $groups[$cls][] = $p;
}
if ($imgStmt) $imgStmt->close();
?>

<!-- Subheader -->
<section id="subheader" style="padding:20px 0;">
  <div class="container-fluid m-5-hor">
    <div class="row">
      <div class="col-md-12">
        <h1>Projects</h1>
      </div>
    </div>
  </div>
</section>

<!-- Projects Gallery -->
<section aria-label="projects" id="projects">
  <div class="container-fluid">
    <div class="row">
      <div class="v-align">
        <div class="col-md-11 col-xs-12">

          <!-- Filter Buttons -->
          <ul id="filter-porto">
            <li class="filt-projects-w selected" data-project="*">All</li>
            <li class="filt-projects-w" data-project=".past">Past Projects</li>
            <li class="filt-projects-w" data-project=".present">Present Projects</li>
            <li class="filt-projects-w" data-project=".future">Future Projects</li>
          </ul>

          <!-- Gallery Container -->
          <div id="w-gallery-container" class="w-gallery-container">
            <?php
            // Define redirect page for each category
            $redirectPages = [
              'past' => 'projects-past.php',
              'present' => 'projectsDetail2.php',
              'future' => 'projectsFuture.php'
            ];

            // Loop through categories and display their projects
            foreach ($groups as $cls => $items) {
              foreach ($items as $it) {
                $img = $it['image_path'] ? $it['image_path'] : 'img/placeholder.jpg';
                $page = $redirectPages[$cls] ?? '#';
                ?>
                <div class="w-gallery <?php echo $cls; ?>">
                  <div class="projects-grid onStep" data-animation="fadeInUp" data-time="0">
                    <div class="hovereffect-color">
                      <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($it['name']); ?>" class="w-gallery-image">
                      <div class="overlay">
                        <div class="v-align wrap">
                          <a href="<?php echo $page; ?>?id=<?php echo urlencode($it['id']); ?>">
                            <i class="fa fa-link"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }
            }
            ?>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
