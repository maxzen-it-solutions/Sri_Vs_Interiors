<!-- footer -->
<footer class="main text-center">
  <div class="container-fluid m-5-hor">
    <div class="row align-items-center justify-content-center">

      <!-- Left Section -->
      <div class="col-md-4 text-left mb-4 mb-md-0" style="margin-top: 30px;">
        <span style="font-size: 15px; display: block;">
          <a href="#" style="text-decoration: none; color: inherit;">Email: koteswararao158@gmail.com</a>
        </span>
        <span style="font-size: 15px; font-weight: bold; display: block;">
          Sri VS Interiors
        </span>
        <span style="font-size: 15px; display: block;">
          We Make Your Home Stylish and Pleasant
        </span>
        <span style="font-size: 15px; display: block; margin-top:8px; font-weight:600;">Call Us:</span>
        <div id="footer-phones" class="footer-phones" style="margin-top:6px;">
          <ul class="phone-list">
            <li><a class="phone-link" href="tel:+919347703015" aria-label="Call +91 93477 03015">+91-93477 03015</a></li>
            <li><a class="phone-link" href="tel:+919550566131" aria-label="Call +91 95505 66131">+91-95505 66131</a></li>
            <li><a class="phone-link" href="tel:+919959328339" aria-label="Call +91 99593 28339">+91-99593 28339</a></li>
          </ul>
        </div>
      </div>



      <!-- Center Logo -->
      <div class="col-md-4 text-center mb-4 mb-md-0">
        <span class="logo">
          <img id="footerLogo" alt="logo" src="img/logo.png" style="width: 200px; height: auto; cursor: pointer;">
        </span>
      </div>

      <!-- Right Section (Navigation + Address aligned left) -->
      <div class="col-md-4 footer-right">
        <nav id="menu-center" class="footer-nav">
          <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="project.php">Projects</a></li>
            <li><a href="contact.php">Contact</a></li>
          </ul>
        </nav>
        <div class="footer-address">
          <span>GROUND FLOOR, CHANDA RESIDENCY, LALAMMA GARDENS STREET NO-4, POKKALWADA, SRIRAM NAGAR COLONY, PUPPALAGUDA, HYD, TELANGANA-500089.</span>
        </div>

        <!-- Social Icons -->
        <div class="social-icons-footer">
          <a href="https://www.facebook.com/SrivsInterior/" class="social-icon"><span class="ti-facebook"></span></a>
          <a href="https://youtube.com/@srivsinteriors?si=rcYDlGyxRBSfo_1z" class="social-icon"><span class="ti-youtube"></span></a>
          <a href="https://www.instagram.com/sri_vs_interior9?igsh=dmJhcjExc2R4d3U2" class="social-icon"><span class="ti-instagram"></span></a>
          <a href="#" class="social-icon"><span class="ti-linkedin"></span></a>
        </div>
      </div>

    </div>
  </div>
</footer>

<!-- footer end -->

<!-- ScrolltoTop -->
<div id="totop" class="init">
  <span class="ti-angle-up"></span>
</div>

<!-- plugin JS -->
<script src="plugin/pluginson3step.js"></script> <!-- jQuery -->
<script src="plugin/sticky.js"></script>
<script type="text/javascript" src="rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
<script src="js/on3step.js"></script>
<script src="js/plugin-set.js"></script>

<!-- Initialize Revolution Slider after everything else -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    if (window.jQuery && window.jQuery.fn.revolution) {
      jQuery("#revolution-slider").revolution({
        delay: 5000,
        startwidth: 1170,
        startheight: 500,
        hideThumbs: 10,
        fullWidth: "off",
        fullScreen: "on",
        fullScreenOffsetContainer: ""
      });
    }
  });
</script>
<!-- Floating WhatsApp Chat Button -->
<a href="https://wa.me/919347703015"
  class="whatsapp-float"
  target="_blank"
  aria-label="Chat with us on WhatsApp">
  <i class="fa-brands fa-whatsapp"></i>
</a>



<style>
  /* ===== Floating WhatsApp Button ===== */
  .whatsapp-float {
    position: fixed;
    bottom: 65px;
    right: 25px;
    background-color: #25D366;
    color: #fff;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    font-size: 32px;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    z-index: 99999;
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .whatsapp-float:hover {
    background-color: #1ebe5d;
    transform: scale(1.1);
  }

  /* Smaller for mobile */
  @media (max-width: 768px) {
    .whatsapp-float {
      width: 50px;
      height: 50px;
      font-size: 26px;
      bottom: 40px;
      right: 20px;
      display: flex;
      /* <-- keep flex on mobile too */
      justify-content: center;
      align-items: center;
    }
  }

  #mainProjectImg {
    transition: opacity 0.2s ease;
  }

  /* Footer phone styles: responsive, clickable chips */
  .footer-phones .phone-list {
    list-style: none;
    padding: 0;
    margin: 6px 0 0 0;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-start;
  }

  .footer-phones .phone-list li {
    margin: 0;
  }

  .footer-phones .phone-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    border-radius: 8px;
    background: rgba(200, 177, 111, 0.05);
    color: #c8b16f;
    text-decoration: none;
    font-weight: 600;
    transition: background .18s ease, transform .12s ease;
  }

  .footer-phones .phone-link:hover,
  .footer-phones .phone-link:focus {
    background: rgba(200, 177, 111, 0.12);
    transform: translateY(-2px);
  }

  /* add a small phone glyph before each number (uses system glyph) */
  .footer-phones .phone-link::before {
    content: "☎";
    font-size: 13px;
    opacity: 0.9;
  }

  @media (max-width: 768px) {
    .footer-phones .phone-list {
      justify-content: center;
      gap: 12px;
    }

    .footer-phones .phone-link {
      padding: 8px 12px;
      font-size: 14px;
    }
  }
</style>

<script>
  // Unified image-swap function used across pages
  function changeImage(thumbnail, mainId) {
    var id = mainId || 'mainProjectImg';
    var main = document.getElementById(id);
    if (!main) return;
    main.style.opacity = 0;
    setTimeout(function() {
      main.src = thumbnail.src;
      main.style.opacity = 1;
    }, 180);
  }

  // Backwards-compatible wrapper (older pages call this)
  function changeMainImage(thumbnail) {
    changeImage(thumbnail, 'mainProjectImg');
  }
</script>

<script>
  (function() {
    let clickCount = 0;
    let timer = null;

    // Select all logo variations (Desktop, Sticky, Mobile)
    const logos = document.querySelectorAll("#adminLogo, #adminLogoSticky, #adminLogoMobile, #footerLogo");

    logos.forEach(logo => {
      logo.addEventListener("click", function(e) {
        e.preventDefault(); // Prevent default navigation on every click to allow counting

        // 1. Haptic Feedback: Vibrate for 40ms on supported mobile devices
        if (navigator.vibrate) navigator.vibrate(40);

        // 2. Visual Feedback: Brief opacity dip to register the click
        this.style.opacity = "0.5";
        setTimeout(() => {
          this.style.opacity = "1";
        }, 100);

        clickCount++;

        clearTimeout(timer);

        if (clickCount === 3) {
          clickCount = 0;
          window.location.href = "login.php";
        } else {
          // Wait a short time to see if more clicks follow, otherwise navigate to home
          timer = setTimeout(() => {
            clickCount = 0;
            window.location.href = "index.php";
          }, 400);
        }
      });
    });
  })();
</script>

</body>

</html>