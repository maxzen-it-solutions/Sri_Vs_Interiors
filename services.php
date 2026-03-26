<?php
// services.php - unified services page using project categories
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

/* ---------- helpers ---------- */
function getAdminEmail() {
    // adjust as needed or load from config
    return 'admin@example.com';
}

// fetch one random image for category
function randomCategoryImage($conn, $category) {
 
    }

// determine if a specific service detail is requested via slug
$serviceDetail = null;
$serviceProjects = [];
$galleryImages = [];
$mainImage = 'img/placeholder.jpg';
$formFeedback = '';

// -------- Handle Project Enquiry Form --------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['enquiry_submit'])) {

    $name          = trim($_POST['name'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $phone         = trim($_POST['phone'] ?? '');
    $address       = trim($_POST['address'] ?? '');
    $building_type = trim($_POST['building_type'] ?? '');
    $project_type  = trim($_POST['project_type'] ?? '');
    $budget        = trim($_POST['budget'] ?? '');
    $message       = trim($_POST['message'] ?? '');

    if (
        empty($name) || empty($email) || empty($phone) ||
        empty($address) || empty($building_type) ||
        empty($project_type) || empty($message)
    ) {
        $formFeedback = "Please fill all required fields.";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO project_enquiries
            (name, email, phone, address, building_type, project_type, budget, message)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssss",
            $name,
            $email,
            $phone,
            $address,
            $building_type,
            $project_type,
            $budget,
            $message
        );

        if ($stmt->execute()) {
            $formFeedback = "Thank you! Our team will contact you shortly.";
        } else {
            $formFeedback = "Something went wrong. Please try again.";
        }

        $stmt->close();
    }
}

if(isset($_GET['slug']) && trim($_GET['slug'])!==''){
    $slug = trim($_GET['slug']);
    // load service record
    $sstmt = $conn->prepare("SELECT * FROM services WHERE slug=? AND status='active' LIMIT 1");
    if($sstmt){
        $sstmt->bind_param('s',$slug);
        $sstmt->execute();
        $sres = $sstmt->get_result();
        if($sres && $row=$sres->fetch_assoc()){
            $serviceDetail = $row;
        }
        if($sres) $sres->free();
        $sstmt->close();
    }
    if($serviceDetail){
        // load projects/products belonging to this service category
        $pstmt = $conn->prepare("SELECT id,name,client_name,project_address,description,estimated_budget,status,project_phase FROM products WHERE LOWER(category)=LOWER(?) AND status='active' ORDER BY created_at DESC");
        if($pstmt){
            $pstmt->bind_param('s',$slug);
            $pstmt->execute();
            $pres = $pstmt->get_result();
            while($prow=$pres->fetch_assoc()){
                $serviceProjects[] = $prow;
            }
            if($pres) $pres->free();
            $pstmt->close();
        }
        // gather gallery images from those projects
        if(!empty($serviceProjects)){
            $ids = array_map(function($p){return (int)$p['id'];}, $serviceProjects);
            $in  = implode(',', $ids);
            $imgSql = "SELECT image_path FROM project_images WHERE project_id IN ($in) ORDER BY order_index ASC, id ASC";
            $imgRes = $conn->query($imgSql);
            while($imgRes && $imgRow=$imgRes->fetch_assoc()){
                $galleryImages[] = $imgRow['image_path'];
            }
            if(count($galleryImages)){
                $mainImage = $galleryImages[0];
            }
        }
    }
}

// fetch categories for grid listing (lowercase slug)
$cats=[];
$catStmt=$conn->prepare("SELECT DISTINCT LOWER(category) AS category FROM products WHERE category<>''");
if($catStmt){
    $catStmt->execute();
    $cres=$catStmt->get_result();
    while($r=$cres->fetch_assoc())$cats[]=$r['category'];
    if($cres)$cres->free();
    $catStmt->close();
}

$categoriesData = [];

$sql = "
SELECT 
  p.id,
  p.category,
  p.description,
  pi.image_path
FROM products p
LEFT JOIN project_images pi ON p.id = pi.project_id
WHERE p.status = 'active'
ORDER BY p.category, pi.order_index ASC
";

$res = $conn->query($sql);

if ($res) {
  while ($row = $res->fetch_assoc()) {
  $cat = strtolower($row['category']);
  if (!isset($categoriesData[$cat])) {
    $categoriesData[$cat] = [
      'category' => $row['category'],
      'projects' => []
    ];
  }

  $pid = $row['id'];
  if (!isset($categoriesData[$cat]['projects'][$pid])) {
    $categoriesData[$cat]['projects'][$pid] = [
      'details' => $row,
      'images' => []
    ];
  }

  if (!empty($row['image_path'])) {
    $categoriesData[$cat]['projects'][$pid]['images'][] = $row['image_path'];
  }
  }
}

include 'header.php';
?>

<!-- =========================
     SUB HEADER
========================= -->
<section id="subheader">
  <div class="container">
    <h1 class="left-shift"><?php echo $serviceDetail ? htmlspecialchars($serviceDetail['title']) : 'Our Services'; ?></h1>
  </div>
</section>

<?php foreach ($categoriesData as $categoryBlock): ?>

  <?php
    // get ONLY the first project of this category
    if(empty($categoryBlock['projects'])) continue;
    $project = reset($categoryBlock['projects']);

    $imgs = $project['images'];
    $mainImg = $imgs[0] ?? 'img/placeholder.jpg';
    $projectId = $project['details']['id'];
  ?>

<section style="padding:70px 0;border-bottom:1px solid #eee;">
  <div style="max-width:1200px;margin:auto;
              display:grid;
              grid-template-columns:2fr 1.3fr;
              gap:40px;align-items:start;">

    <!-- LEFT: IMAGE GALLERY -->
    <div>

    <!-- CATEGORY NAME ABOVE IMAGE -->
      <h2 style="
          margin-bottom:28px;
          font-weight:700;
          text-transform:uppercase;
          letter-spacing:1.5px;
          font-size:32px;">
        <?= htmlspecialchars($categoryBlock['category'] ?? '') ?>
      </h2>

    <!-- MAIN IMAGE -->

      <div style="border-radius:14px;overflow:hidden;
                  box-shadow:0 10px 30px rgba(0,0,0,.15);">
        <img src="<?php echo htmlspecialchars($mainImg); ?>"
             id="mainImg-<?php echo $projectId; ?>"
             style="width:100%;height:60vh;object-fit:cover;">
      </div>

      <!-- THUMBNAILS -->
      <?php if (!empty($imgs)): ?>
      <div style="display:flex;gap:10px;margin-top:15px;">
        <?php foreach ($imgs as $img): ?>
          <img src="<?php echo htmlspecialchars($img); ?>"
               onclick="document.getElementById('mainImg-<?php echo $projectId; ?>').src=this.src"
               style="width:70px;height:70px;object-fit:cover;
                      border-radius:6px;cursor:pointer;">
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

    </div>

    <!-- RIGHT: CONTENT -->
    <div class="project-content">
      

      <!-- DESCRIPTION HEADING -->
      <?php if (!empty($project['details']['description'])): ?>
        <h4 style="margin-top:35px;color:#333;font-size:20px;font-weight:700;">Description</h4>

        <p style="line-height:1.7;color:#555;font-size:16px;">
          <?= nl2br(htmlspecialchars($project['details']['description'])) ?>
        </p>
      <?php endif; ?>

      <!-- REVIEWS SECTION -->
      <?php
      $categoryName = $categoryBlock['category'];

      $reviewStmt = $conn->prepare("
      SELECT * FROM service_reviews
      WHERE category = ?
      ORDER BY created_at DESC
      ");

      $reviewStmt->bind_param("s",$categoryName);
      $reviewStmt->execute();
      $reviewRes = $reviewStmt->get_result();

      $reviews = [];
      while($row=$reviewRes->fetch_assoc()){
        $reviews[]=$row;
      }
      $reviewStmt->close();

      $reviewCount = count($reviews);
      ?>

      <div class="review-section" style="margin-top:50px;">

        <div class="review-header" onclick="toggleReviews('<?php echo $projectId; ?>')" 
          style="cursor:pointer; padding:16px; background:#f9f9f9; border-radius:8px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
          <h4 style="margin:0; color:#333; font-size:18px; font-weight:600;">
            Reviews (<?php echo $reviewCount; ?>)
          </h4>
          <span style="font-size:20px; color:#666;">▼</span>
        </div>

        <div class="review-list" id="reviews-<?php echo $projectId; ?>" style="display:none; max-height:500px; overflow-y:auto; margin-bottom:20px;">

          <?php if($reviewCount > 0): ?>
            <?php foreach($reviews as $rev): ?>
              <div class="review-item" style="padding:16px; border:1px solid #eee; border-radius:8px; margin-bottom:12px; background:#fff;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                  <strong style="color:#333; font-size:15px;"><?php echo htmlspecialchars($rev['reviewer_name']); ?></strong>
                  <span style="color:#f8d48b; font-size:14px; letter-spacing:2px;">
                    <?php
                    for($i=1;$i<=5;$i++){
                      echo $i <= (int)$rev['rating'] ? "★" : "☆";
                    }
                    ?>
                  </span>
                </div>
                <p style="margin:8px 0 0 0; color:#666; font-size:14px; line-height:1.5;">
                  <?php echo htmlspecialchars($rev['review']); ?>
                </p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="text-align:center; color:#999; padding:20px; font-size:14px;">No reviews yet. Be the first to share your experience!</p>
          <?php endif; ?>

        </div>

        <button class="write-review-btn" onclick="openReviewForm('<?php echo htmlspecialchars($categoryName); ?>')"
          style="background:#f8d48b; color:#000; padding:12px 24px; border:none; border-radius:6px; font-weight:600; cursor:pointer; transition:all 0.3s ease;">
          Write Review
        </button>

      </div>

    </div>
    </div>

  </div>
</section>

<?php endforeach; ?>

<style>


.build-enquiry {
  max-width: 900px;
  margin: 80px auto;
  background: #fff;
  padding: 50px;
  border-radius: 16px;
  box-shadow: 0 10px 40px rgba(0,0,0,.12);
}

.build-enquiry h2 {
  font-size: 32px;
  font-weight: 600;
  margin-bottom: 10px;
}

.build-enquiry .subtitle {
  color: #666;
  margin-bottom: 40px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-weight: 500;
  margin-bottom: 6px;
  color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 12px 14px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 14px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #b2853f;
}

.full-width {
  grid-column: span 2;
}

.submit-btn {
  margin-top: 30px;
  padding: 14px 36px;
  background: #b2853f;
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: 15px;
  cursor: pointer;
}

.submit-btn:hover {
  background: #f8d48b;
  color: #333;
}

@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }

  .full-width {
    grid-column: span 1;
  }

  .build-enquiry {
    padding: 30px;
  }
}

/* ===== CSS-ONLY STAR RATING SYSTEM ===== */
.star-rating {
  display: flex;
  flex-direction: row-reverse;
  justify-content: center;
  align-items: center;
  gap: 12px;
  font-size: 36px;
}

.star-rating input {
  display: none;
}

.star-rating label {
  color: #ddd;
  cursor: pointer;
  transition: color 0.2s ease;
}

/* When hovering the container, temporarily reset all stars to gray */
.star-rating:hover label {
  color: #ddd !important;
}

/* Highlight hovered star and all stars to its left (after it in DOM) */
/* Also highlight selected stars when NOT hovering */
.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
  color: #f8d48b !important;
}

/* Subheader left-shift: nudges heading slightly left on desktop */
#subheader .left-shift {
  display: inline-block;
  transform: translateX(-60px);
  transition: transform 0.18s ease;
}
@media (max-width: 768px) {
  #subheader .left-shift { transform: translateX(0); }
}

/* Align right content with image body */
.project-content {
  margin-top: 58px; /* aligns with category heading height */
}

/* Mobile adjustment */
@media (max-width: 768px) {
  .project-content {
    margin-top: 20px;
  }
}

</style>


  <script>
    document.querySelectorAll('.service-card').forEach(card=>{
      card.addEventListener('click',function(){
        var title = card.querySelector('h4').textContent.toLowerCase();
        var detail = document.getElementById('detail-'+title);
        if(detail){
          document.querySelectorAll('.category-details').forEach(d=>d.style.display='none');
          detail.style.display='block';
          detail.scrollIntoView({behavior:'smooth'});
        }
      });
    });

  </script>

  <?php if (!empty($formFeedback)): ?>
    <div class="enq-feedback">
      <?= htmlspecialchars($formFeedback) ?>
    </div>
  <?php endif; ?>

  <!-- enquiry form -->
  <section class="build-enquiry">
  <h2>Start Your Project</h2>
  <p class="subtitle">
    Tell us about your dream space and our experts will get in touch with you.
  </p>

  <form method="post" action="services.php#enquiry">

    <div class="form-grid">

      <div class="form-group">
        <label>Your Name *</label>
        <input type="text" name="name" placeholder="Enter your full name" required>
      </div>

      <div class="form-group">
        <label>Email Address *</label>
        <input type="email" name="email" placeholder="example@email.com" required>
      </div>

      <div class="form-group">
        <label>Phone Number *</label>
        <input type="tel" name="phone" placeholder="+91 XXXXX XXXXX" required>
      </div>

      <div class="form-group">
        <label>Project Location *</label>
        <input type="text" name="address" placeholder="City / Area / Full address" required>
      </div>

      <div class="form-group">
        <label>Type of Building *</label>
        <select name="building_type" required>
          <option value="">Select building type</option>
          <option>1 BHK</option>
          <option>2 BHK</option>
          <option>3 BHK</option>
          <option>Apartment</option>
          <option>Independent House</option>
          <option>Villa</option>
          <option>Commercial Space</option>
        </select>
      </div>

      <div class="form-group">
        <label>Project Category *</label>
        <select name="project_type" required>
          <option value="">Select project category</option>
          <option>Interior Design</option>
          <option>Construction</option>
          <option>Renovation</option>
          <option>Interior + Construction</option>
        </select>
      </div>

      <div class="form-group full-width">
        <label>Estimated Budget (Optional)</label>
        <input type="text" name="budget" placeholder="Approx budget (₹)">
      </div>

      <div class="form-group full-width">
        <label>Project Description *</label>
        <textarea name="message" rows="5"
          placeholder="Tell us about your requirements, rooms, style preference, timeline, etc."
          required></textarea>
      </div>

    </div>

    <button type="submit" name="enquiry_submit" class="submit-btn">
      Request Free Consultation
    </button>

  </form>
</section>

<!-- Review Modal Popup -->
<div id="reviewModal" class="review-modal" style="
  display:none;
  position:fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background:rgba(0,0,0,0.6);
  justify-content:center;
  align-items:center;
  z-index:1000;
">

  <div class="review-modal-content" style="
    background:#fff;
    padding:40px;
    border-radius:12px;
    width:90%;
    max-width:500px;
    box-shadow:0 10px 40px rgba(0,0,0,0.3);
    position:relative;
  ">

    <span onclick="closeReviewForm()" class="close-review" style="
      position:absolute;
      top:16px;
      right:20px;
      font-size:32px;
      color:#999;
      cursor:pointer;
      transition:color 0.2s ease;
    " onmouseover="this.style.color='#333'" onmouseout="this.style.color='#999'">×</span>

    <h3 style="margin:0 0 25px 0; font-size:24px; color:#333;">Write a Review</h3>

    <form action="save_review.php" method="POST">

      <input type="hidden" name="category" id="reviewCategory">

      <div style="margin-bottom:20px;">
        <input type="text" name="reviewer_name" placeholder="Your Name" required
          style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px; font-size:14px;">
      </div>

      <div style="margin-bottom:25px;">
        <label style="font-weight:600; display:block; margin-bottom:15px; color:#333;">Rating</label>
        <div class="star-rating">
          <input type="radio" name="rating" value="5" id="star5" required>
          <label for="star5">★</label>
          
          <input type="radio" name="rating" value="4" id="star4">
          <label for="star4">★</label>
          
          <input type="radio" name="rating" value="3" id="star3">
          <label for="star3">★</label>
          
          <input type="radio" name="rating" value="2" id="star2">
          <label for="star2">★</label>
          
          <input type="radio" name="rating" value="1" id="star1">
          <label for="star1">★</label>
        </div>
      </div>

      <div style="margin-bottom:20px;">
        <textarea name="review" placeholder="Write your review..." required
          style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px; font-size:14px; resize:vertical; min-height:120px;"></textarea>
      </div>

      <button type="submit" style="
        width:100%;
        padding:14px;
        background:#f8d48b;
        color:#000;
        border:none;
        border-radius:6px;
        font-weight:600;
        font-size:15px;
        cursor:pointer;
        transition:all 0.3s ease;
      " onmouseover="this.style.backgroundColor='#e0be6f'" onmouseout="this.style.backgroundColor='#f8d48b'">
        Submit Review
      </button>

    </form>

  </div>

</div>

<?php include 'footer.php';
?>

<script>
// Review Form Functions
function openReviewForm(category){
  document.getElementById("reviewModal").style.display="flex";
  document.getElementById("reviewCategory").value = category;
}

function closeReviewForm(){
  document.getElementById("reviewModal").style.display="none";
}

function toggleReviews(id){
  let box = document.getElementById("reviews-"+id);
  if(box.style.display==="block"){
    box.style.display="none";
  }else{
    box.style.display="block";
  }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
  const modal = document.getElementById('reviewModal');
  if(e.target === modal) {
    closeReviewForm();
  }
});
</script>
