<!DOCTYPE html>
<html lang="zxx">
  <head>
    <meta charset="utf-8">
    <title>SRI VS INTERIORS</title>
    <meta content="" name="description">
    <meta content="" name="author">
    <meta content="" name="keywords">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
    <!-- favicon -->
    <link href="img/logo-white.png" rel="icon" sizes="42x42" type="image/png">
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Themify + Font Awesome -->
    <link rel="stylesheet" href="css/themify-icons.css">
    <!-- Font Awesome 6.5.2 (Full Bundle: Solid + Regular + Brands) -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
      integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <!-- Revolution Slider -->
    <link rel="stylesheet" type="text/css" href="css/fullscreen.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="rs-plugin/css/settings.css" media="screen" />
    <link rel="stylesheet" href="css/rev-settings.css" type="text/css">
    <!-- On3step + Owl -->
    <link href="css/animated-on3step.css" rel="stylesheet">
    <link href="css/owl.carousel.css" rel="stylesheet">
    <link href="css/owl.theme.css" rel="stylesheet">
    <link href="css/owl.transitions.css" rel="stylesheet">
    <link href="css/on3step-style.css" rel="stylesheet">
    <link href="css/queries-on3step.css" media="all" rel="stylesheet" type="text/css">
  </head>
  <body>
  <!-- ===== HIDE HEADER ON SCROLL SCRIPT ===== -->
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    let lastScrollTop = 0;
    const header = document.querySelector("header.init");
    const socialIcons = document.querySelector(".social-icons-subnav");
    if (!header) return;

    header.style.position = "fixed";
    header.style.top = "0";
    header.style.width = "100%";
    header.style.zIndex = "9999";
    header.style.transition = "transform 0.6s ease-in-out";

    window.addEventListener("scroll", function() {
      let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
      if (currentScroll > lastScrollTop + 10) {
        header.style.transform = "translateY(-150px)";
        if (socialIcons) socialIcons.style.opacity = "0";
      } else if (currentScroll < lastScrollTop - 10) {
        header.style.transform = "translateY(0)";
        if (socialIcons) socialIcons.style.opacity = "1";
      }
      lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });
  });

  // Reusable Loader Logic
  window.showPreloader = function() {
    const preloader = document.querySelector(".preloader-white");
    const bg = document.querySelector(".bg-preloader-white");
    if (preloader) preloader.style.display = "flex";
    if (bg) bg.style.display = "block";
  };

  window.hidePreloader = function() {
    const preloader = document.querySelector(".preloader-white");
    const bg = document.querySelector(".bg-preloader-white");
    if (preloader) preloader.style.display = "none";
    if (bg) bg.style.display = "none";
  };

  // Show loader on all standard form submissions
  document.addEventListener("DOMContentLoaded", function() {
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
      if (!form.classList.contains('no-loader')) {
        form.addEventListener("submit", function() {
          window.showPreloader();
        });
      }
    });
  });
  </script>

  <!-- preloader -->
  <div class="bg-preloader-white"></div>
  <div class="preloader-white">
    <div class="mainpreloader">
      <span></span>
    </div>
  </div>
  <!-- preloader end -->
  
  <div class="content-wrapper">
  <header class="init">
    
    <!-- subnav -->
    <div class="container-fluid m-5-hor">
      <div class="row">
        <div class="subnav">
          <div class="col-md-12">
            <div class="right">
              <div class="social-icons-subnav">
                <div>
                  <a href="tel:+919347703015" aria-label="Call +91 9347703015" style="color:inherit; text-decoration:none;">
                    Call Us : +91 9347703015
                  </a>
                </div>
              </div>
              <div id="sub-icon" class="social-icons-subnav">
                <a href="https://www.facebook.com/SrivsInterior/"><span class="ti-facebook"></span></a>
                <a href="https://youtube.com/@srivsinteriors?si=rcYDlGyxRBSfo_1z"><span class="ti-youtube"></span></a>
                <a href="https://www.instagram.com/sri_vs_interior9?igsh=dmJhcjExc2R4d3U2"><span class="ti-instagram"></span></a>
                <a href="#"><span class="ti-linkedin"></span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- subnav end -->
    
    <!-- navigation -->
    <div class="navbar-default-white navbar-fixed-top">
      <div class="container-fluid m-5-hor">
        <div class="row">
          <!-- mobile toggle -->
          <button class="navbar-toggle" data-target=".navbar-collapse" data-toggle="collapse">
            <span class="icon icon-bar"></span> 
            <span class="icon icon-bar"></span> 
            <span class="icon icon-bar"></span>
          </button> 

          <!-- logo -->
          <a class="navbar-brand white" href="index.php">
            <img class="white" id="adminLogo" alt="logo" src="img/logo.png" style="cursor:pointer;">
            <img class="black" id="adminLogoSticky" alt="logo" src="img/logo.png" style="cursor:pointer;">
          </a> 
          <!-- logo end -->
          
          <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

          <!-- main menu -->
          <div class="white menu-init" id="main-menu">
            <nav id="menu-center">
              <ul>
                <li class="<?php if($currentPage == 'index.php') echo 'current_page_item'; ?>">
                  <a href="index.php">Home</a>
                </li>
                <li class="<?php if($currentPage == 'about.php') echo 'current_page_item'; ?>">
                  <a href="about.php">About Us</a>
                </li>
                <li class="<?php if($currentPage == 'services.php') echo 'current_page_item'; ?>">
                  <a href="services.php">Services</a>
                </li>
                <li class="parent-menu <?php if(in_array($currentPage, ['project.php', 'projects-past.php', 'projectsDetail.php', 'projectsDetail2.php', 'projectsFuture.php'])) echo 'current_page_item'; ?>">
                  <a href="project.php">Projects</a>
                </li>
                <li class="<?php if($currentPage == 'contact.php') echo 'current_page_item'; ?>">
                  <a href="contact.php">Contact</a>
                </li>
              </ul>
            </nav>
          </div>
          <!-- main menu end -->
        </div>
      </div>
    </div>
    <!-- navigation end -->

<!-- ===== MOBILE FULLSCREEN SLIDE-IN MENU ===== -->
<style>
/* ===== MOBILE MENU (VISIBLE ONLY ON MOBILE) ===== */
.mobile-menu {
  display: none; /* hide by default on desktop */
}

/* Hide mobile logo on desktop */
@media (min-width: 769px) {
  .mobile-logo {
    display: none !important;
  }
}

/* Show only on mobile */
@media (max-width: 768px) {

  /* Hide desktop main menu */
  #main-menu {
    display: none !important;
  }

  /* ===== GOLD TOGGLE BUTTON ===== */
@media (max-width: 768px) {
  .navbar-toggle .icon-bar {
    background-color: #d4af37 !important; /* Metallic Gold */
    height: 3px;
    width: 25px;
    display: block;
    margin: 5px 0;
    border-radius: 2px;
    transition: all 0.3s ease;
  }

  /* Hover/active effect for toggle */
  .navbar-toggle:hover .icon-bar,
  .navbar-toggle:focus .icon-bar {
    background-color: #ffd700 !important; /* Bright Gold */
    box-shadow: 0 0 6px #ffd700;
  }
}


  /* Mobile menu container */
  .mobile-menu {
    display: flex;
    position: fixed;
    top: 0;
    right: -100%;
    width: 100%;
    height: 100vh;
    background-color: rgba(0,0,0,0.96);
    z-index: 99999;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: all 0.5s ease-in-out;
  }

  /* Active state (menu visible) */
  .mobile-menu.active {
    right: 0;
    opacity: 1;
  }

  /* Close (X) button */
  .close-menu {
    position: absolute;
    top: 25px;
    right: 25px;
    font-size: 36px;
    color: #f8d48b;
    background: none;
    border: none;
    cursor: pointer;
    transition: color 0.3s ease;
  }

  .close-menu:hover {
    color: #fff;
  }

  /* Menu list */
  .mobile-nav {
    list-style: none;
    margin: 40px 0 0 0;
    padding: 0;
    text-align: center;
  }

  .mobile-nav li {
    margin: 20px 0;
    transform: translateY(30px);
    opacity: 0;
    animation: slideUp 0.6s ease forwards;
  }

  /* Staggered animation for links */
  .mobile-nav li:nth-child(1) { animation-delay: 0.2s; }
  .mobile-nav li:nth-child(2) { animation-delay: 0.3s; }
  .mobile-nav li:nth-child(3) { animation-delay: 0.4s; }
  .mobile-nav li:nth-child(4) { animation-delay: 0.5s; }

  .mobile-nav li a {
    color: #f8d48b;
    font-size: 22px;
    font-weight: 600;
    text-decoration: none;
    padding: 10px 25px;
    border: 2px solid transparent;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: inline-block;
  }

  .mobile-nav li a:hover,
  .mobile-nav li.active a {
    color: #ffffff;
    border-color: #f8d48b;
  }

  /* Social icons section */
  .mobile-social-icons {
    display: flex;
    justify-content: center;
    gap: 16px;
    margin-top: 50px;
    opacity: 0;
    animation: fadeIn 0.8s ease forwards;
    animation-delay: 0.6s;
  }

  .mobile-social-icons a {
    color: #f8d48b;
    font-size: 20px;
    border: 1px solid #f8d48b;
    border-radius: 8px;
    padding: 10px;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
  }

  .mobile-social-icons a:hover {
    background-color: #f8d48b;
    color: #000;
  }

  /* ===== MOBILE LOGO VISIBILITY ===== */
  .mobile-logo {
    display: flex !important;
    position: fixed;
    top: -5px;
    left: 20px;
    z-index: 100001;
    align-items: center;
  }

  .mobile-logo img {
    height: 100px;
    width: auto;
    filter: brightness(1.1);
    transition: opacity 0.4s ease-in-out;
  }

  /* Hide desktop navbar logo on mobile */
  .navbar-brand.white {
    display: none !important;
  }

  /* Optional subtle fade-in */
  .mobile-menu.active ~ .mobile-logo img {
    opacity: 1;
  }

  /* ===== Animations ===== */
  @keyframes slideUp {
    from {
      transform: translateY(30px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: scale(0.95);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }
}
</style>

    <!-- Mobile Logo (Visible when menu opens) -->
    <div class="mobile-logo">
      <a href="index.php">
        <img src="img/logo.png" id="adminLogoMobile" alt="SRI VS INTERIORS" style="cursor:pointer;">
      </a>
    </div>

    <!-- ===== MOBILE MENU STRUCTURE ===== -->
    <div class="mobile-menu" id="mobileMenu">
      <button class="close-menu">&times;</button>
      <ul class="mobile-nav">
        <li class="<?php if($currentPage == 'index.php') echo 'active'; ?>">
          <a href="index.php">Home</a>
        </li>
        <li class="<?php if($currentPage == 'about.php') echo 'active'; ?>">
          <a href="about.php">About Us</a>
        </li>
        <li class="<?php if($currentPage == 'services.php') echo 'active'; ?>">
          <a href="services.php">Services</a>
        </li>
        <li class="<?php if(in_array($currentPage, ['project.php', 'projects-past.php', 'projectsDetail.php', 'projectsDetail2.php', 'projectsFuture.php'])) echo 'active'; ?>">
          <a href="project.php">Projects</a>
        </li>
        <li class="<?php if($currentPage == 'contact.php') echo 'active'; ?>">
          <a href="contact.php">Contact</a>
        </li>
      </ul>
      <div class="mobile-social-icons">
        <a href="https://www.facebook.com/SrivsInterior/"><span class="ti-facebook"></span></a>
        <a href="https://youtube.com/@srivsinteriors?si=rcYDlGyxRBSfo_1z"><span class="ti-youtube"></span></a>
        <a href="https://www.instagram.com/sri_vs_interior9?igsh=dmJhcjExc2R4d3U2"><span class="ti-instagram"></span></a>
        <a href="#"><span class="ti-linkedin"></span></a>
      </div>
    </div>

    <!-- ===== MOBILE MENU SCRIPT ===== -->
    <script src="js/mobile-menu.js" defer></script>
    <script>
window.addEventListener("load", function () {
  document.querySelector(".preloader-white").style.display = "none";
  document.querySelector(".bg-preloader-white").style.display = "none";
});
</script>


</header>