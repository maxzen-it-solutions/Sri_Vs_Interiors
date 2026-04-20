<?php
include 'header.php';
include 'db_connect.php';

/* -------------------------
   FETCH PROJECTS
------------------------- */
// Optimized query using correlated subquery to avoid N+1 problem
$sql = "SELECT p.id, p.name, p.category, p.project_phase, 
        (SELECT CONCAT(image_path, '||', media_type) 
         FROM project_images 
         WHERE project_id = p.id 
         ORDER BY COALESCE(order_index, id) ASC 
         LIMIT 1) AS media
        FROM products p 
        WHERE p.status='active' 
        ORDER BY p.created_at DESC";
$res = $conn->query($sql); // Execute the query
$allProjects = []; // Use a single array for all projects
while ($row = $res->fetch_assoc()) $allProjects[] = $row; // Populate the array
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

  <!-- ALL PROJECTS -->
  <div id="allProjectsSection" class="project-group-wrapper">
    <div class="projects-grid">
      <?php if (empty($allProjects)): ?>
        <div class="empty-msg-container">
          <p class="empty-msg">No projects found.</p>
        </div>
      <?php else: ?>
        <?php foreach ($allProjects as $p): ?>
          <div class="project-card"
            data-phase="<?= h($p['project_phase']); ?>"
            data-category="<?= h($p['category']); ?>"
            data-name="<?= h(strtolower($p['name'])); ?>">
            <?php
            $mediaData = !empty($p['media']) ? explode('||', $p['media']) : [];
            $mediaPath = $mediaData[0] ?? 'img/placeholder.jpg';
            $mediaType = $mediaData[1] ?? 'image';
            ?>

            <?php if ($mediaType === 'video'): ?>
              <video autoplay muted loop playsinline
                style="width:100%; height:100%; object-fit:cover;">
                <source src="<?= h($mediaPath); ?>" type="video/mp4">
              </video>
            <?php else: ?>
              <img src="<?= h($mediaPath); ?>"
                alt="<?= h($p['name']); ?>">
            <?php endif; ?>
            <div class="overlay">
              <div class="card-text-wrap">
                <h4 class="project-title"><?= h(strtoupper($p['name'])); ?></h4>
              </div>
              <a href="projectsDetail.php?id=<?= $p['id']; ?>" class="view-more-btn">View More</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>



  <div id="noProjectsMessage" style="display:none; text-align:center; padding:40px; color:#666; font-weight:600;">
    No projects match your search criteria.
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
    #subheader .left-shift {
      transform: translateX(0);
    }
  }

  #phaseFilter {
    list-style: none;
    display: flex;
    gap: 10px;
    padding: 0;
    margin: 0;
  }

  .project-card video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
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

  /* Grouping Styles */
  .project-group-wrapper {
    margin-bottom: 60px;
  }

  .section-title-container {
    padding: 0 40px;
    margin-bottom: 25px;
  }

  .section-title-container h2 {
    font-size: 28px;
    font-weight: 700;
    color: #111;
    position: relative;
    display: inline-block;
    border-left: 6px solid #f8d48b;
    padding-left: 15px;
  }

  /* ======================================================
   SEARCH INPUT
====================================================== */
  #searchInput {
    padding: 12px 16px;
    width: 280px;
    margin-left: auto;
    /* 🔥 pushes search to the right */
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

    .section-title-container {
      padding: 0 15px;
    }

    .section-title-container h2 {
      font-size: 20px;
    }

    .project-group-wrapper {
      margin-bottom: 30px;
    }
  }


  /* ======================================================
   PROJECT GRID – DESKTOP / TABLET / MOBILE
====================================================== */
  .projects-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    /* Desktop: 3 */
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
    max-width: 480px;
    margin: 0 auto;
    height: 480px;
    overflow: hidden;
    border-radius: 12px;
    background: #000;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.45);
    transform: translateZ(0);
    will-change: transform;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  /* Hover effect (desktop only) */
  @media (hover: hover) {
    .project-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
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
      aspect-ratio: 3 / 4.2;
      /* 🔥 Taller mobile look */
      border-radius: 20px;
    }
  }

  /* Ensure mobile project cards match index.php mobile sizing (centered card, fixed height) */
  @media (max-width: 767px) {
    .project-card {
      width: 100% !important;
      max-width: 350px !important;
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
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: end;
    gap: 10px;
    padding: 20px;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.25) 70%, transparent 100%);
  }

  .overlay h4 {
    margin: 0;
    color: #f8d48b;
    font-size: 19px;
    font-weight: 700;
  }

  .overlay a {
    background: #f8d48b;
    color: #000;
    padding: 9px 16px;
    font-size: 13px;
    font-weight: 700;
    border-radius: 3px;
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

  .empty-msg-container {
    grid-column: 1 / -1;
    padding: 20px 0;
    color: #888;
    font-style: italic;
  }

  .add-project-card {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    color: #222;
    cursor: pointer;
    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.06);
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
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
  }

  .add-label {
    margin-top: 12px;
    color: #222;
    font-weight: 700;
  }

  @media (max-width: 576px) {
    .plus-circle {
      width: 68px;
      height: 68px;
      font-size: 40px;
    }
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