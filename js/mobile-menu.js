document.addEventListener("DOMContentLoaded", function() {
  const mobileMenu = document.getElementById("mobileMenu");
  const openBtn = document.querySelector(".navbar-toggle");
  const closeBtn = document.querySelector(".close-menu");

  if (openBtn) {
    openBtn.addEventListener("click", function(e) {
      e.stopPropagation(); // Prevent the click from bubbling up to the document
      mobileMenu.classList.add("active");
      document.body.style.overflow = "hidden"; // prevent scroll

      // Robust fallback: force visible styles in case CSS media queries/animations
      // didn't apply (happens in some responsive/devtools scenarios).
      if (mobileMenu) {
        mobileMenu.style.right = '0';
        mobileMenu.style.opacity = '1';
        // make sure nav items are visible (override animation fallback)
        const items = mobileMenu.querySelectorAll('.mobile-nav li');
        items.forEach(it => {
          it.style.opacity = '1';
          it.style.transform = 'none';
        });
      }
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener("click", function() {
      mobileMenu.classList.remove("active");
      document.body.style.overflow = "";
      if (mobileMenu) {
        mobileMenu.style.right = '';
        mobileMenu.style.opacity = '';
        const items = mobileMenu.querySelectorAll('.mobile-nav li');
        items.forEach(it => {
          it.style.opacity = '';
          it.style.transform = '';
        });
      }
    });
  }

  // Close when clicking outside the menu
  document.addEventListener("click", function(e) {
    if (mobileMenu.classList.contains('active') && !mobileMenu.contains(e.target)) {
      mobileMenu.classList.remove("active");
      document.body.style.overflow = "";
      if (mobileMenu) {
        mobileMenu.style.right = '';
        mobileMenu.style.opacity = '';
        const items = mobileMenu.querySelectorAll('.mobile-nav li');
        items.forEach(it => {
          it.style.opacity = '';
          it.style.transform = '';
        });
      }
    }
  });
});