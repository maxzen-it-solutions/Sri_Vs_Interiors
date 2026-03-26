<?php
include 'header.php';
include 'db_connect.php';

/* -------------------------
   FETCH CATEGORIES
------------------------- */
$categories = [];
$catRes = $conn->query("SELECT DISTINCT LOWER(category) AS category FROM products WHERE status='active'");
while ($row = $catRes->fetch_assoc()) {
    $categories[] = $row['category'];
}
// normalize, keep unique and ensure required categories are present
$categories = array_values(array_unique(array_map('strtolower', $categories)));
$required = [
  'bedroom',
  'hall',
  'kitchen',
  'corridor',
  'balcony',
  'tv-room',
  'false-ceiling',
  'wardrobe',
  'wall-design',
  'others'
];
foreach ($required as $req) {
  if (!in_array($req, $categories)) $categories[] = $req;
}
if (empty($categories)) {
  $categories = $required;
}

/* -------------------------
   FETCH PROJECTS
------------------------- */
$sql = "SELECT id, name, category, project_phase FROM products WHERE status='active' ORDER BY created_at DESC";
$res = $conn->query($sql);

$projects = [];
while ($row = $res->fetch_assoc()) {
    $pid = (int)$row['id'];

    // fetch first image
    $img = 'img/placeholder.jpg';
    $imgRes = $conn->query("SELECT image_path FROM project_images WHERE project_id=$pid ORDER BY COALESCE(order_index,id) ASC LIMIT 1");
    if ($imgRes && $imgRow = $imgRes->fetch_assoc()) {
        $img = $imgRow['image_path'];
    }

    $row['image'] = $img;
    $projects[] = $row;
}
?>

<!-- =========================
     SUB HEADER
========================= -->
<section id="subheader">
  <div class="container">
    <h1 class="left-shift">Our Projects</h1>
  </div>
</section>

<!-- =========================
     FILTER + SEARCH
========================= -->
<section class="projects-section">

  <div class="filter-search-bar">

    <!-- FILTER -->
    <ul id="phaseFilter">
      <li class="active" data-phase="all">ALL</li>
      <li data-phase="past">PAST</li>
      <li data-phase="present">PRESENT</li>
      <li data-phase="future">FUTURE</li>
    </ul>


    <!-- SEARCH -->
    <input type="text" id="searchInput" placeholder="Search project name...">
  </div>

  <!-- =========================
       PROJECT GRID
  ========================= -->
  <div class="projects-grid" id="projectsGrid">
    <?php foreach ($projects as $p): ?>
      <div class="project-card"
        data-phase="<?php echo strtolower($p['project_phase']); ?>"
        data-name="<?php echo strtolower($p['name']); ?>">

        <!-- Image -->

        <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">

        <!-- Bottom Text Overlay: grid keeps button fixed while title can wrap -->
        <div class="overlay" style="
          position:absolute;
          bottom:0;
          left:0;
          width:100%;
          display:grid;
          grid-template-columns:1fr auto;
          align-items:end;
          gap:10px;
          padding:20px;
          background:linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.25) 70%, transparent 100%);
        ">
          <div>
            <h4 style="margin:0; font-size:19px; font-weight:700; color:#f8d48b; word-break:break-word;">
              <?php echo strtoupper($p['name']); ?>
            </h4>
          </div>

          <!-- View More Button (kept no-wrap) -->
          <a href="projectsDetail.php?id=<?php echo $p['id']; ?>" style="
            background-color:#f8d48b;
            color:#000;
            padding:9px 16px;
            font-size:13px;
            font-weight:700;
            border-radius:3px;
            text-decoration:none;
            white-space:nowrap;
            margin-left:10px;
            transition:all 0.3s ease;
            display:inline-block;
          " onmouseover="this.style.backgroundColor='#e0be6f';" onmouseout="this.style.backgroundColor='#f8d48b';">View More</a>
        </div>

      </div>
    <?php endforeach; ?>

    <!-- Add Project Card: matches project-card style but shows a plus to add new projects -->
    <div class="project-card add-project-card"
         data-category="all"
         data-name="addproject"
         onclick="location.href='manage_products.php'"
         role="button"
         aria-label="Add Project">

      <div class="add-inner">
        <div class="plus-circle">+</div>
        <div class="add-label">Add Project</div>
      </div>

    </div>

    <div id="noProjectsMessage" style="display:none; text-align:center; padding:40px; color:#666; font-weight:600;">
      No projects available in this category.
    </div>
  </div>

</section>
<style>
  /* ======================================================
   PROJECTS SECTION BACKGROUND (MATCH INDEX)
====================================================== */
.projects-section {
  padding: 80px 0;
  background: linear-gradient(180deg, #f6f6f6 0%, #efefef 100%);
}

/* Subheader left-shift: nudges heading slightly left on desktop
   while keeping it aligned on small screens */
#subheader .left-shift {
  display: inline-block;
  transform: translateX(-60px);
  transition: transform 0.18s ease;
}
@media (max-width: 768px) {
  #subheader .left-shift { transform: translateX(0); }
}

#phaseFilter {
  list-style: none;
  display: flex;
  gap: 10px;
  padding: 0;
  margin: 0;
}

#phaseFilter li {
  cursor: pointer;
  font-weight: 700;
  color: #000;
  padding: 0 25px;
  position: relative;
  transition: transform 0.25s ease, color 0.25s ease;
}

#phaseFilter li:hover,
#phaseFilter li.active {
  color: #f8d48b;
  transform: translateX(4px);
}

#phaseFilter li::after {
  content: "";
  position: absolute;
  left: 50%;
  bottom: -6px;
  width: 0;
  height: 2px;
  background-color: #f8d48b;
  transform: translateX(-50%);
  transition: width 0.3s ease;
}

#phaseFilter li.active::after {
  width: 60%;
}

/* ======================================================
   SEARCH INPUT
====================================================== */
#searchInput {
  padding: 12px 16px;
  width: 280px;
  margin-left: auto;   /* 🔥 pushes search to the right */
}
.filter-search-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  margin-bottom: 40px; 
  padding: 0 40px;
  box-sizing: border-box;
}

@media (max-width: 768px) {
  .filter-search-bar {
    flex-direction: column;
    align-items: stretch;
    gap: 15px;
    padding: 0 15px;
  }

  #searchInput {
    width: 100%;
    margin-left: 0;
  }
}


/* ======================================================
   PROJECT GRID – DESKTOP / TABLET / MOBILE
====================================================== */
.projects-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* Desktop: 3 */
  gap: 35px;
  width: 100%;
  padding: 0 40px;
  box-sizing: border-box;
  contain: layout paint style;
}

/* Tablet */
@media (max-width: 992px) {
  .projects-grid {
    grid-template-columns: repeat(2, 1fr);
    padding: 0 25px;
  }
}

/* Mobile */
@media (max-width: 576px) {
  .projects-grid {
    grid-template-columns: 1fr;
    padding: 0 12px;
    gap: 25px;
  }
}

/* ======================================================
   PROJECT CARD (MATCH INDEX + PROJECT PAGES)
====================================================== */
.project-card {
  position: relative;
  width: 100%;
  aspect-ratio: 4 / 4.5; /* Desktop / Tablet ratio */
  overflow: hidden;
  border-radius: 18px;
  background: #000;
  box-shadow: 0 12px 30px rgba(0,0,0,0.35);
  transform: translateZ(0);
  will-change: transform;
  transition: transform 0.35s ease, box-shadow 0.35s ease;
}

/* Hover effect (desktop only) */
@media (hover: hover) {
  .project-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.6);
  }
}

/* Card image */
.project-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  display: block;
}

/* ======================================================
   MOBILE CARD SIZE (MATCH YOUR REFERENCE)
====================================================== */
@media (max-width: 576px) {
  .project-card {
    aspect-ratio: 3 / 4.2;   /* 🔥 Taller mobile look */
    border-radius: 20px;
  }
}

/* Ensure mobile project cards match index.php mobile sizing (centered card, fixed height) */
@media (max-width: 767px) {
  .project-card {
    width: 100% !important;
    max-width: 400px !important;
    height: 360px !important;
    margin-left: auto !important;
    margin-right: auto !important;
    border-radius: 16px !important;
    display: block !important;
  }

  .project-card img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    object-position: center !important;
    border-radius: 16px !important;
    margin-left: auto !important;
    margin-right: auto !important;
    display: block !important;
  }
}

/* ======================================================
   CARD OVERLAY
====================================================== */
.overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  background: linear-gradient(
    to top,
    rgba(0,0,0,0.75),
    rgba(0,0,0,0.15),
    transparent
  );
}

.overlay h4 {
  margin: 0;
  color: #f8d48b;
  font-size: 18px;
  font-weight: 700;
}

.overlay a {
  background: #f8d48b;
  color: #000;
  padding: 8px 16px;
  font-size: 13px;
  font-weight: 700;
  border-radius: 4px;
  text-decoration: none;
}

/* ======================================================
   FILTERING (NO GAPS / NO LAG)
====================================================== */
.project-card.hide {
  visibility: hidden;
  pointer-events: none;
  position: absolute;
  display: none;
}

.add-project-card {
  display: flex;
  align-items: center;
  justify-content: center;
  background: #ffffff;
  color: #222;
  cursor: pointer;
  box-shadow: 0 10px 22px rgba(0,0,0,0.04);
  border: 1px solid rgba(0,0,0,0.06);
  padding: 0;
  box-sizing: border-box;
}

.add-project-card .add-inner {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  width: 100%;
  text-align: center;
}
.plus-circle {
  width: 84px;
  height: 84px;
  border-radius: 50%;
  background: #f8d48b;
  color: #000;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 48px;
  font-weight: 800;
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}
.add-label {
  margin-top: 12px;
  color: #222;
  font-weight: 700;
}

@media (max-width: 576px) {
  .plus-circle { width: 68px; height: 68px; font-size: 40px; }
}

@media (max-width: 768px) {
  #phaseFilter {
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 10px;
  }

  #phaseFilter li {
    flex-shrink: 0;
  }
}


</style>

<script>
const filterButtons = document.querySelectorAll('#phaseFilter li');
const searchInput = document.getElementById('searchInput');
const cards = document.querySelectorAll('.project-card');

let activePhase = 'all';

function applyFilters() {
  const term = searchInput.value.toLowerCase().trim();

  cards.forEach(card => {
    const name = card.dataset.name;
    const phase = card.dataset.phase;

    const matchPhase = activePhase === 'all' || phase === activePhase;
    const matchSearch = term === '' || name.includes(term);

    card.classList.toggle('hide', !(matchPhase && matchSearch));
  });

  const noMsg = document.getElementById('noProjectsMessage');
  const visible = [...cards].filter(c => !c.classList.contains('hide') && !c.classList.contains('add-project-card'));
  if (noMsg) noMsg.style.display = visible.length === 0 ? 'block' : 'none';
}

filterButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    filterButtons.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activePhase = btn.dataset.phase;
    applyFilters();
  });
});

let searchTimer;
searchInput.addEventListener('input', () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(applyFilters, 120);
});

applyFilters();
</script>


<?php include 'footer.php'; ?>
