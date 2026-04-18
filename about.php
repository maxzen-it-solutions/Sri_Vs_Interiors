<?php
include 'db_connect.php';

$stats = $conn->query("SELECT * FROM site_stats LIMIT 1")->fetch_assoc();
if (!$stats) {
  $stats = ['total_projects' => 0, 'completed_projects' => 0, 'ongoing_projects' => 0];
}
$total_projects = $stats['total_projects'];
$completed_projects = $stats['completed_projects'];
$ongoing_projects = $stats['ongoing_projects'];
?>

<?php include 'header.php'; ?>
<!-- subheader -->
<section id="subheader">
  <div class="container-fluid m-5-hor">
    <div class="row">
      <div class="col-md-12">
        <h1>
          About Us
        </h1>
      </div>
    </div>
  </div>
</section>
<!-- subheader end -->

<section style="background:linear-gradient(180deg, #faf7f3 0%, #f1e7d9 100%); color:#3c1e00; text-align:center; padding:100px 20px; font-family:'Poppins', Arial, sans-serif;">


  <h2 style="font-size:34px; font-weight:800; text-transform:uppercase; letter-spacing:3px; color:#b2853f; margin-bottom:20px;">
    Our Philosophy
  </h2>

  <div style="width:100px; height:3px; background-color:#b2853f; margin:0 auto 40px; border-radius:3px;"></div>

  <p style="max-width:900px; margin:0 auto 25px; font-size:22px; font-weight:500; line-height:1.7; color:#b2853f; font-style:italic;">
    “Design is not just what it looks like — it’s how it makes you feel.”
  </p>

  <p style="max-width:850px; margin:0 auto; font-size:18px; line-height:1.8; color:#4b3a24;">
    Every design we create blends
    <strong style="color:#b2853f;">function</strong>,
    <strong style="color:#b2853f;">emotion</strong>, and
    <strong style="color:#b2853f;">innovation</strong> — bringing your dream space to life.
  </p>

</section>


<section style="background-color:#cdfcfa; color:#3c1e00; text-align:center; padding:100px 20px; font-family:'Poppins', Arial, sans-serif;">

  <!-- Heading -->
  <h2 style="font-size:28px; font-weight:400; margin-bottom:50px; color:#3c1e00;">
    Setting the Standard for
    <strong style="font-weight:700; color:#000;">Quality Interiors</strong>
  </h2>

  <!-- Icon container -->
  <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:60px; max-width:1200px; margin:0 auto;">

    <!-- Item 1 -->
    <div style="width:160px; text-align:center;">
      <i class="fa-solid fa-people-group" style="font-size:40px; color:#b2853f; margin-bottom:15px;"></i>
      <h3 style="font-size:16px; font-weight:700; margin:0;">Customer<br>Satisfaction</h3>
    </div>

    <!-- Item 2 -->
    <div style="width:160px; text-align:center;">
      <i class="fa-solid fa-gears" style="font-size:40px; color:#b2853f; margin-bottom:15px;"></i>
      <h3 style="font-size:16px; font-weight:700; margin:0;">Quality<br>Workmanship</h3>
    </div>

    <!-- Item 3 -->
    <div style="width:160px; text-align:center;">
      <i class="fa-solid fa-medal" style="font-size:40px; color:#b2853f; margin-bottom:15px;"></i>
      <h3 style="font-size:16px; font-weight:700; margin:0;">High-quality<br>Finishing</h3>
    </div>

    <!-- Item 4 -->
    <div style="width:160px; text-align:center;">
      <i class="fa-solid fa-user-tie" style="font-size:40px; color:#b2853f; margin-bottom:15px;"></i>
      <h3 style="font-size:16px; font-weight:700; margin:0;">Experienced<br>Professionals</h3>
    </div>

    <!-- Item 5 -->
    <div style="width:160px; text-align:center;">
      <i class="fa-regular fa-clock" style="font-size:40px; color:#b2853f; margin-bottom:15px;"></i>
      <h3 style="font-size:16px; font-weight:700; margin:0;">Timely<br>Delivery</h3>
    </div>

  </div>

</section>


<!-- section about -->
<section id="about-us-1" class="h-bg no-padding col-content color-page">
  <style>
    /* Override white text to black for about page only and increase paragraph size */
    #about-us-1,
    #about-us-2 {
      color: #000 !important;
    }

    #about-us-1 p,
    #about-us-2 p,
    #about-us-1 .detail,
    #about-us-2 .detail {
      color: #111 !important;
      font-size: 1.7rem !important;
      line-height: 1.5 !important;
    }

    #about-us-1 a,
    #about-us-2 a {
      color: #1d4ed8 !important;
    }

    /* Premium counters styles (scoped) */
    .premium-counters-section {
      margin-top: 20px;
    }

    .premium-counters {
      display: flex;
      gap: 20px;
    }

    .premium-card {
      transition: transform .32s ease, box-shadow .32s ease;
    }

    .premium-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 18px 36px rgba(16, 24, 40, 0.12);
    }

    .premium-card .card-accent {
      will-change: transform;
    }

    .premium-card .counter {
      font-family: 'Poppins', Arial, sans-serif;
    }

    .premium-card .card-body p {
      margin: 0
    }

    /* Responsive adjustments */
    @media(max-width:900px) {
      .premium-counters {
        flex-direction: column;
        align-items: center;
      }

      .premium-card {
        max-width: 520px;
        width: 100%;
      }
    }
  </style>
  <div class="container-fluid">
    <div class="row">

      <div class="image-container col-lg-6 hidden-md hidden-sm hidden-xs pull-left onStep" data-animation="fadeInLeft" data-time="0"></div>

      <div class="col-lg-6 p-90">
        <div class="sp-padding">
          <h3 class="bg-dots">
            Perfection concept
            <span class="devider-cont"></span>
          </h3>

          <p>
            At Interior, we transform spaces into meaningful and functional places. Our team combines thoughtful space planning,
            material selection, and lighting design to create interiors that reflect your personality and lifestyle.
            From initial concept to final installation, we manage every detail to deliver beautiful, practical rooms you'll
            love living in.
          </p>

          <div class="premium-counters-section" style="padding:48px 20px; background:linear-gradient(180deg,#f7f4ef 0%, #f1ebe0 100%); border-radius:16px;">
            <div class="premium-counters" style="max-width:1100px;margin:0 auto;display:flex;gap:20px;flex-wrap:wrap;justify-content:center;align-items:stretch;">

              <!-- PREMIUM CARD 1 -->
              <div class="premium-card" style="flex:1 1 300px; min-width:240px; max-width:360px; position:relative; overflow:hidden; border-radius:18px; background:#ffffff; box-shadow:0 8px 24px rgba(16,24,40,0.08); border:1px solid rgba(19,19,19,0.03);">
                <div class="card-accent" style="position:absolute;left:16px;top:14px;height:8px;width:72px;background:linear-gradient(90deg,#b2853f,#f1d18a);border-radius:8px 8px 8px 8px; box-shadow:0 4px 10px rgba(178,133,63,0.12);"></div>
                <div class="card-body" style="padding:36px 22px;text-align:center;color:#111;">
                  <h2 class="counter" data-target="<?php echo $total_projects; ?>" style="font-size:56px;line-height:1;font-weight:800;margin:0 0 8px;color:#111;">0</h2>
                  <p style="color:#6b6b6b;font-size:15px;margin:0;letter-spacing:0.3px;">Total Projects</p>
                </div>
              </div>

              <!-- PREMIUM CARD 2 -->
              <div class="premium-card" style="flex:1 1 300px; min-width:240px; max-width:360px; position:relative; overflow:hidden; border-radius:18px; background:#ffffff; box-shadow:0 8px 24px rgba(16,24,40,0.08); border:1px solid rgba(19,19,19,0.03);">
                <div class="card-accent" style="position:absolute;left:16px;top:14px;height:8px;width:72px;background:linear-gradient(90deg,#b2853f,#f1d18a);border-radius:8px; box-shadow:0 4px 10px rgba(178,133,63,0.12);"></div>
                <div class="card-body" style="padding:36px 22px;text-align:center;color:#111;">
                  <h2 class="counter" data-target="<?php echo $completed_projects; ?>" style="font-size:56px;line-height:1;font-weight:800;margin:0 0 8px;color:#111;">0</h2>
                  <p style="color:#6b6b6b;font-size:15px;margin:0;letter-spacing:0.3px;">Completed Projects</p>
                  <p style="color:#9a9a9a;font-size:13px;margin-top:6px;">(Happy Clients)</p>
                </div>
              </div>

              <!-- PREMIUM CARD 3 -->
              <div class="premium-card" style="flex:1 1 300px; min-width:240px; max-width:360px; position:relative; overflow:hidden; border-radius:18px; background:#ffffff; box-shadow:0 8px 24px rgba(16,24,40,0.08); border:1px solid rgba(19,19,19,0.03);">
                <div class="card-accent" style="position:absolute;left:16px;top:14px;height:8px;width:72px;background:linear-gradient(90deg,#b2853f,#f1d18a);border-radius:8px; box-shadow:0 4px 10px rgba(178,133,63,0.12);"></div>
                <div class="card-body" style="padding:36px 22px;text-align:center;color:#111;">
                  <h2 class="counter" data-target="<?php echo $ongoing_projects; ?>" style="font-size:56px;line-height:1;font-weight:800;margin:0 0 8px;color:#111;">0</h2>
                  <p style="color:#6b6b6b;font-size:15px;margin:0;letter-spacing:0.3px;">Ongoing Projects</p>
                </div>
              </div>

            </div>
          </div>



        </div>
        <div class="clearfix"></div>
      </div>

    </div>
  </div>
</section>
<style>
  .glass-card {
    backdrop-filter: blur(18px);
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 24px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    transition: 0.3s ease-in-out;
  }

  .glass-card:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: scale(1.03);
  }

  /* Premium counters styles (scoped) */
  .premium-counters-section {
    margin-top: 20px;
  }

  .premium-card a {
    color: inherit;
  }

  .premium-card .counter {
    font-family: 'Poppins', Arial, sans-serif;
  }

  .premium-card .card-body p {
    margin: 0
  }
</style>

<!-- COUNTER ANIMATION -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".counter");

    counters.forEach(counter => {
      let target = +counter.getAttribute("data-target");
      let count = 0;

      const updateCount = () => {
        let increment = target / 100; // Speed control

        if (count < target) {
          count += increment;
          counter.innerText = Math.ceil(count);
          requestAnimationFrame(updateCount);
        } else {
          counter.innerText = target;
        }
      };

      updateCount();
    });
  });
</script>
<!-- section about end -->

<!-- section about -->
<section id="about-us-2" class="h-bg no-padding col-content color-page">
  <div class="container-fluid">
    <div class="row">

      <div class="image-container col-lg-6 hidden-md hidden-sm hidden-xs pull-right onStep" data-animation="fadeInRight" data-time="0"></div>

      <div class="col-lg-6 p-60">
        <div class="sp-padding">
          <h3 class="bg-dots">
            Powerful Interior Design
            <span class="devider-cont"></span>
          </h3>

          <p>
            Interior delivers bespoke interior design solutions that balance style and usability.
            We specialize in residential and commercial projects, offering tailored designs, curated finishes,
            and hands-on project coordination to ensure high-quality results on time and on budget.
          </p>

        </div>
        <div class="clearfix"></div>
      </div>

    </div>
  </div>
</section>
<!-- section about end -->




<!-- gallery -->
<section>
  <div class="container-fluid m-5-hor">
    <div class="row">

      <div class="onStep" data-animation="fadeInUp" data-time="0">
        <div id="owl-gal" class="owl-carousel">

          <div class="item">
            <div class="gal-home big-img">
              <div class="hovereffect">
                <a href="img/gallery-home/big/img1.jpg" class="gal-link">
                  <img alt="imageportofolio" class="img-responsive" src="img/gallery-home/img1.jpg">
                </a>
                <div class="overlay">
                  <h3>Scandinavian Residence
                    <span class="devider"></span>
                  </h3>
                </div>
              </div>
            </div>
          </div>

          <div class="item">
            <div class="gal-home">
              <div class="hovereffect">
                <a href="img/gallery-home/big/img2.jpg" class="gal-link">
                  <img alt="imageportofolio" class="img-responsive" src="img/gallery-home/img2.jpg">
                </a>
                <div class="overlay">
                  <h3>Brown Perspective
                    <span class="devider"></span>
                  </h3>
                </div>
              </div>
            </div>
          </div>

          <div class="item">
            <div class="gal-home big-img">
              <div class="hovereffect">
                <a href="img/gallery-home/big/img3.jpg" class="gal-link">
                  <img alt="imageportofolio" class="img-responsive" src="img/gallery-home/img3.jpg">
                </a>
                <div class="overlay">
                  <h3>Artificial Design
                    <span class="devider"></span>
                  </h3>
                </div>
              </div>
            </div>
          </div>

          <div class="item">
            <div class="gal-home big-img">
              <div class="hovereffect">
                <a href="img/gallery-home/big/img4.jpg" class="gal-link">
                  <img alt="imageportofolio" class="img-responsive" src="img/gallery-home/img4.jpg">
                </a>
                <div class="overlay">
                  <h3>Scandinavian Residence
                    <span class="devider"></span>
                  </h3>
                </div>
              </div>
            </div>
          </div>

          <div class="item">
            <div class="gal-home">
              <div class="hovereffect">
                <a href="img/gallery-home/big/img5.jpg" class="gal-link">
                  <img alt="imageportofolio" class="img-responsive" src="img/gallery-home/img5.jpg">
                </a>
                <div class="overlay">
                  <h3>Brown Perspective
                    <span class="devider"></span>
                  </h3>
                </div>
              </div>
            </div>
          </div>

          <div class="item">
            <div class="gal-home big-img">
              <div class="hovereffect">
                <a href="img/gallery-home/big/img6.jpg" class="gal-link">
                  <img alt="imageportofolio" class="img-responsive" src="img/gallery-home/img6.jpg">
                </a>
                <div class="overlay">
                  <h3>Artificial Design
                    <span class="devider"></span>
                  </h3>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
<!-- gallery end -->
<script>
  (function($) {
    $(document).ready(function() {
      var $g = $('#owl-gal');
      if (!$g.length) return;
      // If carousel already initialized, do not start autoplay (gallery is manual)
      if ($g.hasClass('owl-loaded')) {
        return;
      }
      // Otherwise initialize with sensible responsive settings and autoplay.
      if ($.fn.owlCarousel) {
        $g.owlCarousel({
          items: 3,
          loop: true,
          margin: 10,
          autoplay: false,
          responsive: {
            0: {
              items: 1
            },
            600: {
              items: 2
            },
            1000: {
              items: 3
            }
          }
        });
      }
    });
  })(jQuery);
</script>
<!-- Simple lightbox modal for gallery items -->
<style>
  /* lightbox styles (scoped) */
  #gal-lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999
  }

  #gal-lightbox .lb-inner {
    max-width: 90%;
    max-height: 90%;
    position: relative
  }

  #gal-lightbox img {
    max-width: 100%;
    max-height: 100%;
    display: block;
    margin: 0 auto
  }

  #gal-lightbox .lb-close,
  #gal-lightbox .lb-prev,
  #gal-lightbox .lb-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #fff;
    background: rgba(0, 0, 0, 0.4);
    border: none;
    padding: 12px 14px;
    cursor: pointer
  }

  #gal-lightbox .lb-close {
    top: 10px;
    right: 10px;
    transform: none
  }

  #gal-lightbox .lb-prev {
    left: -60px
  }

  #gal-lightbox .lb-next {
    right: -60px
  }

  @media(max-width:768px) {
    #gal-lightbox .lb-prev {
      left: 10px
    }

    #gal-lightbox .lb-next {
      right: 10px
    }
  }
</style>
<div id="gal-lightbox" aria-hidden="true">
  <div class="lb-inner">
    <button class="lb-close" aria-label="Close">✕</button>
    <button class="lb-prev" aria-label="Previous">‹</button>
    <button class="lb-next" aria-label="Next">›</button>
    <img src="" alt="Gallery image" />
  </div>
</div>
<script>
  (function($) {
    $(function() {
      var $g = $('#owl-gal');
      var $lb = $('#gal-lightbox');
      var $img = $lb.find('img');
      var $links = $g.find('.gal-link');
      var currentIndex = -1;

      function openAt(i) {
        var $a = $links.eq(i);
        if (!$a.length) return;
        var href = $a.attr('href');
        $img.attr('src', href);
        currentIndex = i;
        $lb.fadeIn(150).attr('aria-hidden', 'false');
        // pause carousel while modal open
        // autoplay intentionally removed for gallery; do not stop autoplay here
      }

      function closeLB() {
        $lb.fadeOut(150).attr('aria-hidden', 'true');
        $img.attr('src', '');
        // autoplay intentionally removed for gallery; do not start autoplay here
      }

      $g.on('click', '.gal-link', function(e) {
        e.preventDefault();
        var idx = $links.index(this);
        openAt(idx);
      });

      $lb.on('click', '.lb-close', closeLB);
      $lb.on('click', '.lb-prev', function() {
        openAt((currentIndex - 1 + $links.length) % $links.length);
      });
      $lb.on('click', '.lb-next', function() {
        openAt((currentIndex + 1) % $links.length);
      });
      $lb.on('click', function(e) {
        if (e.target === this) closeLB();
      });
      $(document).on('keydown', function(e) {
        if ($lb.is(':visible')) {
          if (e.key === 'Escape') closeLB();
          if (e.key === 'ArrowLeft') openAt((currentIndex - 1 + $links.length) % $links.length);
          if (e.key === 'ArrowRight') openAt((currentIndex + 1) % $links.length);
        }
      });
      // If carousel was not initialized by previous script, initialize it here with autoplay and then re-select links
      if ($.fn.owlCarousel && !$g.hasClass('owl-loaded')) {
        $g.owlCarousel({
          items: 3,
          loop: true,
          margin: 10,
          autoplay: false,
          responsive: {
            0: {
              items: 1
            },
            600: {
              items: 2
            },
            1000: {
              items: 3
            }
          }
        });
        // requery links after init
        $links = $g.find('.gal-link');
      }
    });
  })(jQuery);
</script>



<!-- testimony -->
<section id="testimony" class="h-bg color-page no-padding">
  <div class="container-fluid">
    <div class="row">

      <div class="image-container col-lg-6 pull-right hidden-md hidden-sm hidden-xs onStep" data-animation="fadeInRight" data-time="300"></div>

      <div class="col-lg-6 col-md-12 p-90">
        <div class="space-half hidden-md hidden-sm hidden-xs"></div>
        <div class="testimonial-wrap" style="position:relative;">
          <button class="test-arrow test-prev" aria-label="Previous testimonial">&#10094;</button>
          <div id="owl-testimonial" class="owl-carousel">

            <div class="item">
              <blockquote>
                <p>The team transformed our home with a clean, modern design that perfectly matched our lifestyle. The attention to detail, quality materials, and timely delivery exceeded our expectations.</p>
                <small>Home Owner in <cite title="Residential Project" class="color">Residential Project</cite></small>
              </blockquote>
              <img alt="imagetesti" class="tal" src="img/yash.jpg">
              <h3>Yash</h3>
            </div>

            <div class="item">
              <blockquote>
                <p>From concept to execution, the entire process was smooth and well-organized. The design team understood our requirements clearly and delivered a stylish yet functional space.</p>
                <small>Client in <cite title="Interior Design" class="color">Interior Design</cite></small>
              </blockquote>
              <img alt="imagetesti" class="tal" src="img/alekhya.jpg">
              <h3>Alekhya</h3>
            </div>

            <div class="item">
              <blockquote>
                <p>Excellent workmanship and professional coordination throughout the project. The final outcome was elegant, practical, and completed within the promised timeline.</p>
                <small>Customer in <cite title="Commercial Interior" class="color">Commercial Interior</cite></small>
              </blockquote>
              <img alt="imagetesti" class="tal" src="img/pratap.jpg">
              <h3>Pratap</h3>
            </div>

          </div>
          <button class="test-arrow test-next" aria-label="Next testimonial">&#10095;</button>
        </div>
      </div>



    </div>
  </div>
</section>
<!-- testimony end-->

<style>
  /* Testimony controls: hide non-active items and style arrows */
  /* =============================== */
  /* TESTIMONY – MANUAL SLIDER ONLY */
  /* =============================== */

  #owl-testimonial {
    position: relative;
    min-height: 320px;
  }

  /* All items hidden by default */
  #owl-testimonial .item {
    position: absolute;
    inset: 0;
    opacity: 0;
    visibility: hidden;
    transform: translateX(40px);
    transition: opacity 0.45s ease, transform 0.45s ease;
  }

  /* Active item */
  #owl-testimonial .item.active-testimony {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
    position: relative;
  }

  /* Arrows */
  .testimonial-wrap .test-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: none;
    background: #fff;
    color: #111;
    font-size: 20px;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    z-index: 5;
  }

  .testimonial-wrap .test-prev {
    left: -18px;
  }

  .testimonial-wrap .test-next {
    right: -18px;
  }

  /* Mobile – show arrows inside content (visible and accessible on small screens) */
  @media (max-width: 768px) {

    .testimonial-wrap .test-prev,
    .testimonial-wrap .test-next {
      display: flex;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 44px;
      height: 44px;
      border-radius: 50%;
      border: none;
      align-items: center;
      justify-content: center;
      background: #fff;
      color: #111;
      font-size: 20px;
      cursor: pointer;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
      z-index: 5;
    }

    .testimonial-wrap .test-prev {
      left: 8px;
    }

    .testimonial-wrap .test-next {
      right: 8px;
    }
  }

  /* Disable Owl behavior for testimony only */
  #owl-testimonial.owl-carousel {
    display: block !important;
  }

  #owl-testimonial .owl-wrapper,
  #owl-testimonial .owl-wrapper-outer {
    display: block !important;
    transform: none !important;
  }

  #owl-testimonial .owl-item {
    float: none !important;
  }

  #owl-testimonial .owl-wrapper-outer.autoHeight {
    transition: none !important;
  }
</style>

<section>
  <div class="container-fluid m-5-hor">
    <div class="row goldpage">

      <div class="col-lg-9 col-md-12 text-left">
        <i class="fa fa-users color"></i>
        <h3>Work with Our Professional and Honest Team!</h3>
      </div>

      <div class="col-lg-3 col-md-12">
        <div class="btn-content">
          <span class="shine"></span>
          <a href="contact.php">Join Our Team</a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- brands-->
<section id="brand" class="no-top" aria-label="brands">
  <div class="container-fluid m-5-hor">
    <div class="row">

      <div class="owl-carousel" id="owl-brand">

        <?php
        $partners = $conn->query("SELECT logo FROM partners WHERE status='active' ORDER BY id DESC");
        while ($partner = $partners->fetch_assoc()) {
          echo '<div class="item">';
          echo '<img alt="Partner Logo" src="uploads/partners/' . htmlspecialchars($partner['logo']) . '">';
          echo '</div>';
        }
        ?>

      </div>

    </div>
  </div>
</section>

<!-- brands end-->

<!-- Continuous brand marquee (left → right) -->
<style>
  .brand-marquee {
    overflow: hidden;
    padding: 38px 0;
  }

  .brand-marquee-track {
    display: flex;
    gap: 48px;
    align-items: center;
    white-space: nowrap;
    transform: translateX(-50%);
  }

  .brand-marquee-track .marquee-item {
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Increased brand logo sizing for better visibility */
  .brand-marquee-track .marquee-item img {
    height: 200px;
    width: auto;
    max-width: 240px;
    object-fit: contain;
    display: block;
  }

  @keyframes marqueeRight {
    0% {
      transform: translateX(-50%);
    }

    100% {
      transform: translateX(0%);
    }
  }

  .brand-marquee-track {
    animation-name: marqueeRight;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
    animation-play-state: running;
  }

  @media (max-width:768px) {
    .brand-marquee-track .marquee-item img {
      height: 220px;
      max-width: 160px;
    }

    .brand-marquee-track {
      gap: 28px;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('owl-brand');
    if (!carousel) return;
    const items = Array.from(carousel.querySelectorAll('.item')).map(it => it.innerHTML.trim()).filter(Boolean);
    if (items.length === 0) return;

    // Build marquee wrapper and duplicate items for seamless loop
    const marquee = document.createElement('div');
    marquee.className = 'brand-marquee';
    const track = document.createElement('div');
    track.className = 'brand-marquee-track';

    items.forEach(html => {
      const d = document.createElement('div');
      d.className = 'marquee-item';
      d.innerHTML = html;
      track.appendChild(d);
    });
    items.forEach(html => {
      const d = document.createElement('div');
      d.className = 'marquee-item';
      d.innerHTML = html;
      track.appendChild(d);
    });

    marquee.appendChild(track);
    // Replace the original carousel with marquee
    carousel.parentNode.replaceChild(marquee, carousel);

    // Compute duration so speed feels consistent regardless of number/width
    function computeDuration() {
      const trackEl = document.querySelector('.brand-marquee-track');
      if (!trackEl) return;
      const uniqueWidth = trackEl.scrollWidth / 2; // width of one set
      // Increase pxPerSecond for a faster marquee (higher = faster)
      const pxPerSecond = 160; // speed: adjust (px/sec)
      // Allow shorter minimum duration so the loop feels snappier
      const secs = Math.max(4, Math.round(uniqueWidth / pxPerSecond));
      trackEl.style.animationDuration = secs + 's';
    }
    // run after images load
    window.addEventListener('load', computeDuration);
    window.addEventListener('resize', computeDuration);
    // Also try once after short delay in case images cached
    setTimeout(computeDuration, 250);
  });
</script>
<script>
  // Testimony previous/next controller (about page)
  document.addEventListener('DOMContentLoaded', function() {
    const wrap = document.querySelector('.testimonial-wrap');
    if (!wrap) return;
    const container = wrap.querySelector('#owl-testimonial');
    if (!container) return;
    const items = Array.from(container.querySelectorAll('.item'));
    if (items.length === 0) return;

    let idx = 0;

    function show(i) {
      idx = (i + items.length) % items.length;
      items.forEach((it, j) => {
        it.classList.toggle('active-testimony', j === idx);
      });
    }

    // init
    show(0);

    const prev = wrap.querySelector('.test-prev');
    const next = wrap.querySelector('.test-next');
    if (prev) prev.addEventListener('click', () => show(idx - 1));
    if (next) next.addEventListener('click', () => show(idx + 1));
  });
</script>
<!-- Continuous brand marquee end-->


<?php include 'footer.php'; ?>