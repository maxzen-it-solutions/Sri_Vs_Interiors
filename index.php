<?php
session_start(); // keep this if you show “Welcome, User” or similar
include 'db_connect.php';
?>


      <?php include 'header.php'; ?>
      <!-- home -->
	  <!-- background slider -->
      <div id="home" style="margin-top: 60px;">
      
      <!-- revolution slider -->
      <section class="fullwidthbanner-container no-bottom no-top" aria-label="section-slider">
      <div id="revolution-slider">
                    <ul>
                    
                        <li data-transition="parallaxtobottom" data-slotamount="10" data-masterspeed="1200" data-delay="5000">
                            <!--  BACKGROUND IMAGE -->
                            <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1400&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="" data-start="0" data-bgposition="center center" data-kenburns="on" data-duration="10000" data-ease="Linear.easeNone" data-bgfit="120" data-bgfitend="100" data-bgpositionend="center center"/>
                            <div class="tp-caption big-heading sft" style="font-weight: 900;"
                                data-x="50"
                                data-y="200"
                                data-speed="800"
                                data-start="400"
                                data-easing="easeInOutExpo"
                                data-endspeed="450">
                                Experience Design
                            </div>

                            <div class="tp-caption sub-heading sft" style="font-weight: 900;"
                                data-x="50"
                                data-y="280"
                                data-speed="1000"
					            data-start="800"
					            data-easing="easeOutExpo"
                                data-endspeed="400">
                                with precision
                            </div>

                            <div class="tp-caption sfb"
                                data-x="50"
                                data-y="350"
                                data-speed="400"
                                data-start="800"
                                data-easing="easeInOutExpo">
                                
                            </div>
                        </li>
                        
                        <li data-transition="parallaxtobottom" data-slotamount="10" data-masterspeed="1200" data-delay="5000">
                            <!--  BACKGROUND IMAGE -->
                            <img src="https://images.unsplash.com/photo-1722858810982-ee587200c6a5?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="" data-start="0" data-bgposition="center center" data-kenburns="on" data-duration="10000" data-ease="Linear.easeNone" data-bgfit="120" data-bgfitend="100" data-bgpositionend="center center" />
                            <div class="tp-caption big-heading sft" style="font-weight: 900;"
                                data-x="50"
                                data-y="200"
                                data-speed="800"
                                data-start="400"
                                data-easing="easeInOutExpo"
                                data-endspeed="450">
                                Concept Design
                            </div>

                            <div class="tp-caption sub-heading sft" style="font-weight: 900;"
                                data-x="50"
                                data-y="280"
                                data-speed="1000"
					            data-start="800"
					            data-easing="easeOutExpo"
                                data-endspeed="400">
                                stylish living
                            </div>

                            <div class="tp-caption sfb"
                                data-x="50"
                                data-y="350"
                                data-speed="400"
                                data-start="800"
                                data-easing="easeInOutExpo">
                                
                            </div>
                        </li>
                        
                        <li data-transition="parallaxtobottom" data-slotamount="10" data-masterspeed="1200" data-delay="5000">
                            <!--  BACKGROUND IMAGE -->
                            <img src="https://images.unsplash.com/photo-1721522288380-b5ea044d1cbb?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="" data-start="0" data-bgposition="center center" data-kenburns="on" data-duration="10000" data-ease="Linear.easeNone" data-bgfit="120" data-bgfitend="100" data-bgpositionend="center center" />
                            <div class="tp-caption big-heading sft" style="font-weight: 900;"
                                data-x="50"
                                data-y="200"
                                data-speed="800"
                                data-start="400"
                                data-easing="easeInOutExpo"
                                data-endspeed="450">
                                Choice of Residence 
                            </div>

                            <div class="tp-caption sub-heading sft" style="font-weight: 900;"
                                data-x="50"
                                data-y="280"
                                data-speed="1000"
                                data-start="800"
                                data-easing="easeOutExpo"
                                data-endspeed="400">
                                according lifestyle
                            </div>

                            <div class="tp-caption sfb"
                                data-x="50"
                                data-y="350"
                                data-speed="400"
                                data-start="800"
                                data-easing="easeInOutExpo">
                                
                            </div>
                        </li>
                       
                    </ul>
                    <div class="tp-bannertimer hide"></div>
                </div>
        </section>
        
        <style>
            /* ---------------------------------------------- */
          /* 🔥 MOBILE SLIDER FIX — Reduce height to 80%     */
          /* ---------------------------------------------- */
@media (max-width: 767px) {

    /* Reduce slider height on mobile */
    #revolution-slider,
    .fullwidthbanner-container,
    .fullwidthbanner-container ul li,
    .fullwidthbanner-container ul li img {
        height: 80vh !important;  /* ⭐ 80% of mobile screen */
        max-height: 80vh !important;
    }

    /* Reduce the zoom effect */
    #revolution-slider ul li img {
        object-fit: cover !important; 
        object-position: center !important;
        transform: none !important;       /* Remove forced zoom */
    }

    /* Move text slightly higher so it's visible */
    .tp-caption.big-heading,
    .tp-caption.sub-heading,
    .tp-caption.sfb {
        transform: translateY(-40px) !important;
    }
}

        </style>
        <!-- revolution slider end -->
            
  </div>
  <!-- background slider end -->
   
        <!-- ===== Modern Features Section ===== -->
        <section class="modern-features">
        <div class="features-container">

            <!-- Feature Item 1 -->
            <div class="feature-item">
            <i class="fa-solid fa-couch"></i>
            <h3>Modern<br>Interior Design</h3>
            </div>

            <!-- Feature Item 2 -->
            <div class="feature-item">
            <i class="fa-solid fa-pencil-ruler"></i>
            <h3>Customised<br>To Your Taste</h3>
            </div>

            <!-- Feature Item 3 -->
            <div class="feature-item">
            <i class="fa-solid fa-comments"></i>
            <h3>Free Consultation<br>& Budgeting</h3>
            </div>

            <!-- Feature Item 4 -->
            <div class="feature-item">
            <i class="fa-solid fa-hand-holding-dollar"></i>
            <h3>Transparent<br>Material Pricing</h3>
            </div>

            <!-- Feature Item 5 -->
            <div class="feature-item">
            <i class="fa-solid fa-bolt"></i>
            <h3>Professionally-led<br>Execution</h3>
            </div>

            <!-- Feature Item 6 -->
            <div class="feature-item">
            <i class="fa-regular fa-clock"></i>
            <h3>On-time<br>Delivery</h3>
            </div>

        </div>
        </section>
        <style>
                /* ===== Modern Features Section ===== */
                .modern-features {
                background: linear-gradient(180deg, #1b1b1b 0%, #0f0f0f 100%); /* dark charcoal gradient */
                color: #f8d48b;
                padding: 80px 20px;
                text-align: center;
                font-family: 'Poppins', sans-serif;
                }

                /* Container to keep all items in one row */
                .features-container {
                max-width: 1300px;
                margin: 0 auto;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                flex-wrap: nowrap;
                gap: 20px;
                }

                /* Each feature item */
                .feature-item {
                flex: 1 1 16%;
                text-align: center;
                transition: all 0.3s ease;
                }

                .feature-item i {
                font-size: 48px;
                color: #f8d48b;
                margin-bottom: 15px;
                transition: transform 0.3s ease, color 0.3s ease;
                }

                .feature-item h3 {
                font-size: 16px;
                font-weight: 600;
                color: #f8d48b;
                line-height: 1.4;
                margin: 0;
                }

                /* Hover effects */
                .feature-item:hover {
                transform: translateY(-6px);
                }

                .feature-item:hover i {
                color: #ffd86f; /* lighter gold on hover */
                transform: scale(1.1);
                }

                /* Animation for entry */
                .feature-item {
                opacity: 0;
                transform: translateY(30px);
                animation: fadeUp 0.8s ease forwards;
                }

                .feature-item:nth-child(1) { animation-delay: 0.2s; }
                .feature-item:nth-child(2) { animation-delay: 0.3s; }
                .feature-item:nth-child(3) { animation-delay: 0.4s; }
                .feature-item:nth-child(4) { animation-delay: 0.5s; }
                .feature-item:nth-child(5) { animation-delay: 0.6s; }
                .feature-item:nth-child(6) { animation-delay: 0.7s; }

                @keyframes fadeUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
                }

                /* ===== Responsive Design ===== */

                /* Tablets (2 per row) */
                @media (max-width: 992px) {
                .features-container {
                    flex-wrap: wrap;
                    justify-content: center;
                }

                .feature-item {
                    flex: 1 1 40%;
                    margin-bottom: 40px;
                }
                }

                /* Mobile (1 per row) */
                @media (max-width: 576px) {
                .feature-item {
                    flex: 1 1 100%;
                    margin-bottom: 30px;
                }

                .feature-item i {
                    font-size: 36px;
                }

                .feature-item h3 {
                    font-size: 14px;
                }
                }

            </style>

        <!-- Font Awesome CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


        <!-- ===== How We Work Section ===== -->
<section class="how-we-work">
  <h2 class="section-title">How We <strong>Work</strong></h2>

  <!-- Timeline -->
  <div class="timeline">
    <div class="step active" data-step="1"><span>1</span><p>Online Consultation</p></div>
    <div class="step" data-step="2"><span>2</span><p>Approximate Quotation</p></div>
    <div class="step" data-step="3"><span>3</span><p>In Person Discussion</p></div>
    <div class="step" data-step="4"><span>4</span><p>Advance Booking</p></div>
    <div class="step" data-step="5"><span>5</span><p>2D/3D Design</p></div>
    <div class="step" data-step="6"><span>6</span><p>Material Presentation</p></div>
    <div class="step" data-step="7"><span>7</span><p>Final Quotation</p></div>
    <div class="step" data-step="8"><span>8</span><p>Execution Begins</p></div>
    <div class="step" data-step="9"><span>9</span><p>Project Handover</p></div>
    <div class="step" data-step="10"><span>10</span><p>Client Review</p></div>
  </div>

  <!-- Content Area -->
  <div class="content-area">
    <button class="nav-btn prev">&#10094;</button>

    <div class="content-item active" data-step="1">
      <div class="text">
        <h3>Online Consultation</h3>
        <p>Ready to assist you regarding all things related to home interiors.</p>
      </div>
      <div class="image">
        <img src="img/steps/OnlineConsultation.jpg" alt="Online Consultation">
      </div>
    </div>

    <div class="content-item" data-step="2">
      <div class="text">
        <h3>Approximate Quotation</h3>
        <p>We provide a clear cost estimation after understanding your needs.</p>
      </div>
      <div class="image">
        <img src="img/steps/Quotation.jpg" alt="Approximate Quotation">
      </div>
    </div>

    <div class="content-item" data-step="3">
      <div class="text">
        <h3>In Person Discussion</h3>
        <p>We meet personally to finalize your ideas, layout, and designs.</p>
      </div>
      <div class="image">
        <img src="img/steps/InpersonDiscussion.jpg" alt="In Person Discussion">
      </div>
    </div>

    <div class="content-item" data-step="4">
      <div class="text">
        <h3>Advance Booking</h3>
        <p>Once confirmed, we move ahead with the booking process quickly.</p>
      </div>
      <div class="image">
        <img src="img/steps/AdvanceBooking.jpg" alt="Advance Booking">
      </div>
    </div>

    <div class="content-item" data-step="5">
      <div class="text">
        <h3>2D/3D Design</h3>
        <p>Avail digital 2D & 3D visuals for a clear glimpse of your future home design.</p>
      </div>
      <div class="image">
        <img src="img/steps/2_3Design.jpg" alt="2D/3D Design">
      </div>
    </div>

    <div class="content-item" data-step="6">
      <div class="text">
        <h3>Material Presentation</h3>
        <p>Avail digital 2D & 3D visuals for a clear glimpse of your future home design.</p>
      </div>
      <div class="image">
        <img src="img/steps/MaterialPresentation.jpg" alt="Material Presentation">
      </div>
    </div>

    <div class="content-item" data-step="7">
      <div class="text">
        <h3>Final Quotation</h3>
        <p>Customize the quote to your preference, budget, and design execution viability. </p>
      </div>
      <div class="image">
        <img src="img/steps/FinalQuotation.jpg" alt="Final Quotation">
      </div>
    </div>

    <div class="content-item" data-step="8">
      <div class="text">
        <h3>Execution Begins</h3>
        <p>Shape your vision and breathe life into your interior decor dreams.</p>
      </div>
      <div class="image">
        <img src="img/steps/ExecutionBegins.jpg" alt="Execution Begins">
      </div>
    </div>

    <div class="content-item" data-step="9">
      <div class="text">
        <h3>Project Handover</h3>
        <p>Shape your vision and breathe life into your interior decor dreams.</p>
      </div>
      <div class="image">
        <img src="img/steps/ProjectHandover.jpg" alt="Project Handover">
      </div>
    </div>

    <!-- Step 10: Client Review -->
    <div class="content-item" data-step="10">
      <div style="width:100%; text-align:center; padding:40px 20px;">
        <h3 style="font-size:28px; color:#000; margin-bottom:10px;">Client Feedback</h3>
        <p style="font-size:16px; color:#666; margin-bottom:30px;">Share your experience with us. Your feedback helps us improve!</p>
        
        <!-- Feedback Form -->
        <form id="clientFeedbackForm" action="save_client_feedback.php" method="POST" style="max-width:600px; margin:0 auto;">
          
          <!-- Name Input -->
          <div style="margin-bottom:20px;">
            <input type="text" name="reviewer_name" placeholder="Your Name" required
              style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px; font-size:14px;">
          </div>

          <!-- Star Rating -->
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

          <!-- Review Textarea -->
          <div style="margin-bottom:25px;">
            <textarea name="review" placeholder="Share your thoughts and feedback..." required
              style="
                width:100%;
                height:150px;
                padding:15px;
                border:1px solid #ccc;
                border-radius:6px;
                font-family:Arial, sans-serif;
                font-size:14px;
                color:#333;
                resize:vertical;
              "></textarea>
          </div>

          <!-- Submit Button -->
          <button type="submit" style="
            background-color:#f8d48b;
            color:#000;
            padding:14px 40px;
            font-size:16px;
            font-weight:600;
            border:none;
            border-radius:6px;
            cursor:pointer;
            transition:all 0.3s ease;
          " onmouseover="this.style.backgroundColor='#e0be6f'" onmouseout="this.style.backgroundColor='#f8d48b'">
            Submit Feedback
          </button>

          <p id="feedbackMessage" style="margin-top:15px; font-size:14px; color:#666;"></p>
        </form>
      </div>
    </div>

    <button class="nav-btn next">&#10095;</button>
  </div>
</section>

<style>
        /* ===== HOW WE WORK SECTION ===== */
.how-we-work {
  text-align: center;
  font-family: 'Poppins', sans-serif;
  background: #fff;
  padding: 80px 20px;
}

.section-title {
  font-size: 36px;
  color: #000;
  margin-bottom: 60px;
}

/* TIMELINE */
.timeline {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  flex-wrap: wrap;
  position: relative;
  gap: 30px;
  margin-bottom: 70px;
}

.timeline .step {
  display: flex;
  flex-direction: column;
  align-items: center;
  cursor: pointer;
  position: relative;
}

.timeline .step span {
  background: #fdf3dc;
  color: #000;
  font-weight: 700;
  width: 70px;
  height: 70px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 22px;
  transition: all 0.3s ease;
  position: relative;
  z-index: 2;
}

.timeline .step.active span {
  background: #f8d48b;
  color: #000;
  transform: scale(1.1);
  box-shadow: 0 0 0 6px rgba(248, 212, 139, 0.3);
}

.timeline .step p {
  font-size: 13px;
  font-weight: 500;
  color: #333;
  width: 100px;
  margin-top: 10px;
  text-align: center;
}

/* CONNECTING LINE */
.timeline::before {
  content: "";
  position: absolute;
  top: 35px;
  left: 50%;
  width: 80%;
  height: 2px;
  background: repeating-linear-gradient(
    to right,
    #d1d1d1,
    #d1d1d1 5px,
    transparent 8px,
    transparent 12px
  );
  transform: translateX(-50%);
  z-index: 1;
}

/* CONTENT AREA */
.content-area {
  position: relative;
  max-width: 1100px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
}

.content-item {
  display: none;
  align-items: center;
  justify-content: space-between;
  gap: 40px;
  width: 100%;
  animation: fadeSlide 0.6s ease forwards;
}

.content-item.active {
  display: flex;
}

@keyframes fadeSlide {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.content-item .text {
  flex: 1;
  text-align: left;
}

.content-item .text h3 {
  font-size: 28px;
  color: #000;
  margin-bottom: 15px;
}

.content-item .text p {
  font-size: 16px;
  color: #333;
  line-height: 1.6;
  margin-bottom: 25px;
}

/* IMAGE RATIO CONTROL */
.content-item .image {
  position: relative;
  width: 100%;
  max-width: 500px;
  aspect-ratio: 4 / 3; /* ✅ Uniform aspect ratio for all images */
  overflow: visible; /* ✅ Allow full image to show */
  border-radius: 10px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f5f5f5; /* Light background to fill empty space */
}

.content-item .image img {
  width: 100%;
  height: 100%;
  object-fit: contain; /* ✅ Show entire image without cropping */
  padding: 10px; /* Small padding around image */
  border-radius: inherit;
}


.btn-know-more {
  background: #f8d48b;
  color: #000;
  font-weight: 600;
  padding: 12px 25px;
  text-decoration: none;
  border-radius: 5px;
  transition: 0.3s;
  border: 2px solid #f8d48b;
}

.btn-know-more:hover {
  background: #000;
  color: #f8d48b;
}

/* ARROWS */
.nav-btn {
  position: absolute;              /* 🔥 important */
  top: 50%;
  transform: translateY(-50%);
  background: #fff;
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  font-size: 24px;
  color: #000;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 10;
}
/* Left arrow */
.nav-btn.prev {
  left: -85px;     /* move left outside content */
}

/* Right arrow */
.nav-btn.next {
  right: -85px;    /* move right outside content */
}


.nav-btn:hover {
  background: #f8d48b;
  color: #000;
}

/* ===== CSS-ONLY STAR RATING SYSTEM ===== */
.star-rating {
  display: flex;
  flex-direction: row-reverse;
  justify-content: center;
  align-items: center;
  gap: 12px;
  font-size: 40px;
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

/* Responsive */
@media (max-width: 992px) {
  .content-item {
    flex-direction: column;
    text-align: center;
  }
  .content-item .image img {
    max-width: 90%;
  }
  .timeline .step span {
    width: 55px;
    height: 55px;
    font-size: 18px;
  }
  .timeline .step p {
    width: 80px;
  }
}
/* Make nav arrows visible and inside content on small screens */
@media (max-width: 768px) {
  .content-area { overflow: visible; }
  .nav-btn { display: flex; position: absolute; top: 50%; transform: translateY(-50%); z-index: 50; width:44px; height:44px; }
  .nav-btn.prev { left: 8px; }
  .nav-btn.next { right: 8px; }
  .nav-btn { box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
  
  /* Star rating responsive */
  .star-rating {
    font-size: 36px;
    gap: 12px;
  }
}
</style>

       <script>
document.addEventListener("DOMContentLoaded", function() {
  const steps = document.querySelectorAll(".timeline .step");
  const contents = document.querySelectorAll(".content-item");
  const nextBtn = document.querySelector(".next");
  const prevBtn = document.querySelector(".prev");
  let current = 1;

  function showStep(step) {
    steps.forEach(s => s.classList.remove("active"));
    contents.forEach(c => c.classList.remove("active"));

    document.querySelector(`.timeline .step[data-step="${step}"]`).classList.add("active");
    document.querySelector(`.content-item[data-step="${step}"]`).classList.add("active");
    current = step;
  }

  steps.forEach(step => {
    step.addEventListener("click", () => {
      showStep(parseInt(step.dataset.step));
    });
  });

  nextBtn.addEventListener("click", () => {
    let next = current + 1;
    if (next > steps.length) next = 1;
    showStep(next);
  });

  prevBtn.addEventListener("click", () => {
    let prev = current - 1;
    if (prev < 1) prev = steps.length;
    showStep(prev);
  });
  
  // Position arrows to vertically center on active image for mobile
  const contentArea = document.querySelector('.content-area');
  function positionArrows(){
    const prevBtnEl = document.querySelector('.nav-btn.prev');
    const nextBtnEl = document.querySelector('.nav-btn.next');
    if(!prevBtnEl || !nextBtnEl || !contentArea) return;
    if(window.innerWidth > 768){
      // reset to default CSS behavior on larger screens
      prevBtnEl.style.top = '';
      nextBtnEl.style.top = '';
      prevBtnEl.style.left = '';
      nextBtnEl.style.right = '';
      return;
    }
    const active = document.querySelector('.content-item.active');
    if(!active) return;
    const imgWrap = active.querySelector('.image');
    if(!imgWrap) return;
    const trackRect = contentArea.getBoundingClientRect();
    const imgRect = imgWrap.getBoundingClientRect();
    const centerY = imgRect.top + imgRect.height/2 - trackRect.top;
    // set arrow positions relative to content-area
    prevBtnEl.style.top = centerY + 'px';
    nextBtnEl.style.top = centerY + 'px';
    prevBtnEl.style.left = '8px';
    nextBtnEl.style.right = '8px';
    prevBtnEl.style.transform = 'translateY(-50%)';
    nextBtnEl.style.transform = 'translateY(-50%)';
  }

  let _resizeTimer;
  window.addEventListener('resize', ()=>{ clearTimeout(_resizeTimer); _resizeTimer = setTimeout(positionArrows, 120); });
  // call once to set initial position
  positionArrows();
});
</script>


  <!-- home end -->
  
      <section class="color-page">
      
                <div class="container-fluid m-5-hor">
                    <div class="row">
                    
                        <div class="col-md-12 onStep" data-animation="fadeInLeft" data-time="0" 
                            style="width:100%; background-color:#f8d48b; padding:60px 0; text-align:center;">

                        <div style="max-width:1200px; margin:0 auto; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                            
                            <!-- Icon + Heading -->
                            <div style="display:flex; align-items:center; gap:12px; justify-content:center; margin-bottom:15px;">
                            <i class="icon-Icon_WhyChooseUs" 
                                style="font-size:40px; color:#000;"></i>
                            <h2 style="font-size:36px; color:#000; font-weight:700; margin:0;">
                                Why Choose <span style="font-weight:800;">Us?</span>
                            </h2>
                            </div>

                            <!-- Description -->
                            <p style="font-size:18px; color:#000; margin-bottom:10px;">
                            We listen carefully to understand what our clients need.
                            </p>

                            <!-- Divider Line -->
                            <span style="display:block; width:80px; height:3px; background-color:#000; margin-top:10px;"></span>

                        </div>
                        </div>

                        <div style="width: 100%; margin-top: 40px;">
                            <div class="row" style="max-width: 1200px; margin: 0 auto;">
                                <div class="col-md-4 onStep" data-animation="fadeInUp" data-time="300">
                                    <div class="box-icon">
                                        <span class="icon-choose fa-regular fa-building"></span>
                                        <div class="text">
                                        <h3><span class="color">MODERN DESIGN</span></h3>
                                            <p>Stylish and functional interiors tailored to your lifestyle.</p>
                                      </div>
                                  </div>
                                </div>

                                <div class="col-md-4 onStep" data-animation="fadeInUp" data-time="300">
                                    <div class="box-icon">
                                        <span class="icon-choose fa fa-life-ring"></span>
                                        <div class="text">
                                        <h3><span class="color">COMPLETE PROJECT SUPPORT</span></h3>
                                            <p>Complete guidance from planning to project handover.</p>
                                      </div>
                                  </div>
                                </div>

                                <div class="col-md-4 onStep" data-animation="fadeInUp" data-time="300">
                                    <div class="box-icon">
                                        <span class="icon-choose fa-regular fa-lightbulb"></span>
                                        <div class="text">
                                        <h3><span class="color">MAINTENANCE SUPPORT</span></h3>
                                            <p>Reliable post-installation care and upkeep services.</p>
                                      </div>
                                  </div>
                                </div>

                                <div class="col-md-4 onStep" data-animation="fadeInUp" data-time="600">
                                    <div class="box-icon">
                                        <span class="icon-choose fa fa-users"></span>
                                        <div class="text">
                                        <h3><span class="color">EXPERT DESIGNERS</span></h3>
                                            <p>Skilled professionals delivering quality interior solutions.</p>
                                      </div>
                                  </div>
                                </div>

                                <div class="col-md-4 onStep" data-animation="fadeInUp" data-time="600">
                                    <div class="box-icon">
                                        <span class="icon-choose fa fa-cubes"></span>
                                        <div class="text">
                                        <h3><span class="color">QUALITY & COMPLIANCE ASSURANCE</span></h3>
                                            <p>Projects executed with safety, quality, and industry standards.</p>
                                      </div>
                                  </div>
                                </div>

                                <div class="col-md-4 onStep" data-animation="fadeInUp" data-time="600">
                                    <div class="box-icon">
                                        <span class="icon-choose fa fa-headphones"></span>
                                        <div class="text">
                                        <h3><span class="color">24 / 7 Support</span></h3>
                                            <p>Always available to assist whenever you need us.</p>
                                      </div>
                                  </div>
                                </div>
                            </div>
                            
                            <!-- Third Row - Client Satisfaction Centered -->
                            <div class="row" style="max-width: 1200px; margin: 0 auto; justify-content: center;">
                                <div class="col-md-4 onStep" data-animation="fadeInUp" data-time="600">
                                    <div class="box-icon">
                                        <span class="icon-choose fas fa-smile"></span>
                                        <div class="text">
                                            <h3><span class="color">Client Satisfaction</span></h3>
                                            <p>We prioritize your happiness in every project.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                

                            </div>
                        </div>
                    </div>
                </div>
            </section>
 
 
    <!-- Projects Title -->
            <section style="padding: 60px 0 40px; text-align:center; background:#fff;">
                <div style="max-width: 1100px; margin: 0 auto; background:#f8d48b; border-radius: 15px; padding: 40px 0;">
                    <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                        <h2 style="font-size:36px; color:#000; font-weight:700; margin:0; text-transform:uppercase;">
                            Our Projects
                        </h2>
                        <span style="display:block; width: 160px; height:3px; background-color:#000; margin:15px auto 0;"></span>
                        </div>
                    </div>
                    </div>
                </div>
                </section>


 <!-- gallery -->           
            <section style="padding:50px 0 140px; background-color:#1c1c1c;">
            <div class="container-fluid" style="max-width:1400px;">
                <!-- Increased bottom gap between rows -->
                <div class="row justify-content-center gx-4 gy-5">

                <?php
                $stmt = $conn->prepare("SELECT id, name, category, description 
                            FROM products WHERE status='active' 
                            ORDER BY RAND() LIMIT 6");
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    while ($p = $result->fetch_assoc()) {

                    // Always link to the unified details page for a project
                    $targetPage = 'projectsDetail.php?id=' . $p['id'];

                    $thumb = 'img/placeholder.jpg';
                    $imgStmt = $conn->prepare("SELECT image_path FROM project_images 
                                WHERE project_id = ? 
                                ORDER BY order_index ASC, id ASC LIMIT 1");
                    $imgStmt->bind_param('i', $p['id']);
                    $imgStmt->execute();
                    $imgRes = $imgStmt->get_result();
                    if ($imgRes && $row = $imgRes->fetch_assoc()) {
                        if (!empty($row['image_path'])) $thumb = $row['image_path'];
                    }
                    $imgStmt->close();
                ?>

                <!-- Project Card -->
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 d-flex justify-content-center" style="margin-bottom:60px;">
                    <div class="project-card" style="
                    width:100%;
                    max-width:460px;
                    height:480px;
                    border-radius:12px;
                    overflow:hidden;
                    position:relative;
                    background-color:#000;
                    box-shadow:0 5px 25px rgba(0,0,0,0.45);
                    transition:transform 0.3s ease, box-shadow 0.3s ease;
                    ">
                    <img src="<?php echo htmlspecialchars($thumb); ?>" 
                        alt="<?php echo htmlspecialchars($p['name']); ?>" 
                        style="width:100%; height:100%; object-fit:cover; border-radius:12px;">

                    <!-- Bottom Text Overlay: grid keeps button fixed while title can wrap -->
                    <div style="
                      position:absolute;
                      bottom:0;
                      left:0;
                      width:100%;
                      display:grid;
                      grid-template-columns: 1fr auto;
                      align-items:end;
                      gap:10px;
                      padding:20px;
                      background:linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.25) 70%, transparent 100%);
                    ">
                      <div style="flex:1;">
                      <h4 style="margin:0; font-size:19px; font-weight:700; color:#f8d48b; word-break:break-word;">
                        <?php echo strtoupper($p['name']); ?>
                      </h4>
                      </div>

                      <!-- View More Button (kept no-wrap) -->
                      <a href="<?php echo $targetPage; ?>" style="
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
                      " onmouseover="this.style.backgroundColor='#e0be6f';"
                      onmouseout="this.style.backgroundColor='#f8d48b';">View More</a>
                    </div>
                    </div>
                </div>

                <?php
                    }
                } else {
                    echo '<p style="color:#fff; text-align:center;">No projects found.</p>';
                }
                ?>
                </div>

                <!-- More Projects Button -->
                <div style="text-align:center; margin-top:40px;">
                <a href="project.php" 
                    style="
                        background-color:#1c1c1c;
                        color:#f8d48b;
                        padding:25px 45px;
                        font-size:24px;
                        font-weight:600;
                        border-radius:5px;
                        text-decoration:none;
                        transition:all 0.3s ease;
                    "
                    onmouseover="this.style.backgroundColor='#e0be6f'; this.style.color='#000';" 
                    onmouseout="this.style.backgroundColor='#1c1c1c'; this.style.color='#f8d48b';">
                    More Projects
                </a>
                </div>

            </div>
            </section>

            <style>
              @media (max-width: 767px) {
                a[href="project.php"] {
                  padding: 15px 30px !important;
                  font-size: 18px !important;
                  border-radius: 4px !important;
                }
              }
                @media (max-width: 768px) {
                section[style*="padding: 60px 0 40px"] div[style*="background:#f8d48b"] {
                    padding: 20px 0 !important;
                    border-radius: 10px !important;
                    max-width: 90% !important;
                }

                section[style*="padding: 60px 0 40px"] h2 {
                    font-size: 24px !important;
                    margin: 0 !important;
                }

                section[style*="padding: 60px 0 40px"] span {
                    width: 100px !important;
                    height: 2px !important;
                    margin: 10px auto 0 !important;
                }

                section[style*="padding: 60px 0 40px"] {
                    padding: 40px 0 30px !important;
                }
            }

            .project-card:hover {
            transform:translateY(-6px);
            box-shadow:0 12px 30px rgba(0,0,0,0.6);
            }

            @media (min-width:1200px){
            .col-xl-4{padding:0 20px;} /* even left-right gaps */
            }

            @media (max-width:991px){
            .project-card{max-width:100%;height:420px;}
            }

            /* ------------------------------------------------------ */
/* 🔥 MOBILE INDEX.PHP - MATCH PROJECT CARD SIZES */
/* ------------------------------------------------------ */
@media (max-width: 767px) {

  
    /* White background for grid gaps */
    .row.justify-content-center.gx-4.gy-5 {
        background: #fff !important;
    }

    /* Center project cards in all bootstrap columns */
    .col-sm-12.d-flex,
    .col-md-6.d-flex,
    .col-lg-4.d-flex {
        justify-content: center !important;
    }

    /* Match past/present/future card size on mobile */
    .project-card {
        width: 100% !important;
        max-width: 400px !important;   /* Same as grid min-width */
        height: 360px !important;      /* Mobile height (same as project pages) */
        margin-left: auto !important;
        margin-right: auto !important;
        border-radius: 16px !important;
        display: block !important;
    }

    /* Same image sizing */
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


            </style>


 <!-- gallery end -->
 
 
 
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
                                 <p>This is placeholder text used to show how words will look on a page. It has no real meaning, but it gives an idea of what the final content might feel like. The point is to fill space with natural-looking text so the design stands out without being distracted by real words. By doing this, designers and writers can focus on layout, readability, and balance before the actual content is ready.</p>
                                  <small>Someone famous in <cite title="Source Title" class="color">Source Title</cite></small>
                                </blockquote>
                                <img alt="imagetesti" class="tal" src="img/alekhya.jpg">
                                <h3>Alekhya</h3>
                            </div>
                            
                            <div class="item">
                                <blockquote>
                                 <p>This is filler text often used in design. It suggests that hard work and effort bring results, but even small rewards require real commitment. It also warns that chasing pleasure without thought can lead to pain, and that not everything that feels good is without consequences.</p>
                                  <small>Someone famous in <cite title="Source Title" class="color">Source Title</cite></small>
                                </blockquote>
                                <img alt="imagetesti" class="tal" src="img/yash.jpg">
                                <h3>Yash</h3>
                            </div>  
                             
                            <div class="item">
                                <blockquote>
                                 <p>This is placeholder text. It’s meant to show what written content might look like once real words are added. The idea is that work and effort can bring results, even small benefits take practice and discipline, and not every pleasure is free from consequences.</p>
                                  <small>Someone famous in <cite title="Source Title" class="color">Source Title</cite></small>
                                </blockquote>
                                <img alt="imagetesti" class="tal" src="img/mani.jpg">
                                <h3>Mani</h3>
                            </div>                        
                  </div>
                  <button class="test-arrow test-next" aria-label="Next testimonial">&#10095;</button>
                </div>
               </div>
               
               
               
            </div>
            </div>
       </section>
       <!-- testimony end --> 
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
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
  z-index: 5;
}

.testimonial-wrap .test-prev { left: -18px; }
.testimonial-wrap .test-next { right: -18px; }

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
    align-items: center;
    justify-content: center;
    background: #fff;
    color: #111;
    font-size: 20px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    z-index: 5;
  }
  .testimonial-wrap .test-prev { left: 8px; }
  .testimonial-wrap .test-next { right: 8px; }
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
          
             <div class="col-lg-9 col-md-12 text-center">
                  <h3>Looking for a quality and affordable interior design for your next project?</h3>
              </div>

              <div class="col-lg-3 col-md-12">
                <div class="btn-content" >
                             <span class="shine"></span>
                             <a href="contact.php">Let us know</a>
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
         .brand-marquee { overflow:hidden; padding:38px 0; }
         .brand-marquee-track { display:flex; gap:48px; align-items:center; white-space:nowrap; transform: translateX(-50%); }
         .brand-marquee-track .marquee-item { flex:0 0 auto; display:flex; align-items:center; justify-content:center; }
         /* Increased brand logo sizing for better visibility */
         .brand-marquee-track .marquee-item img { height:200px; width:auto; max-width:240px; object-fit:contain; display:block; }
         @keyframes marqueeRight { 0% { transform: translateX(-50%); } 100% { transform: translateX(0%); } }
         .brand-marquee-track { animation-name: marqueeRight; animation-timing-function: linear; animation-iteration-count: infinite; animation-play-state: running; }
         @media (max-width:768px){ .brand-marquee-track .marquee-item img { height:220px; max-width:160px; } .brand-marquee-track { gap:28px; } }
       </style>

       <script>
       document.addEventListener('DOMContentLoaded', function(){
         const carousel = document.getElementById('owl-brand');
         if(!carousel) return;
         const items = Array.from(carousel.querySelectorAll('.item')).map(it => it.innerHTML.trim()).filter(Boolean);
         if(items.length === 0) return;

         // Build marquee wrapper and duplicate items for seamless loop
         const marquee = document.createElement('div');
         marquee.className = 'brand-marquee';
         const track = document.createElement('div');
         track.className = 'brand-marquee-track';

         items.forEach(html => { const d = document.createElement('div'); d.className='marquee-item'; d.innerHTML = html; track.appendChild(d); });
         items.forEach(html => { const d = document.createElement('div'); d.className='marquee-item'; d.innerHTML = html; track.appendChild(d); });

         marquee.appendChild(track);
         // Replace the original carousel with marquee
         if (carousel && carousel.parentNode) {
            carousel.parentNode.replaceChild(marquee, carousel);
        }


         // Compute duration so speed feels consistent regardless of number/width
         function computeDuration(){
           const trackEl = document.querySelector('.brand-marquee-track');
           if(!trackEl) return;
           const uniqueWidth = trackEl.scrollWidth / 2; // width of one set
          // Increased pxPerSecond to make the marquee scroll faster (higher = faster)
          const pxPerSecond = 160; // speed: adjust (px/sec)
          const secs = Math.max(8, Math.round(uniqueWidth / pxPerSecond));
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
      // Testimony previous/next controller
      document.addEventListener('DOMContentLoaded', function(){
        const wrap = document.querySelector('.testimonial-wrap');
        if(!wrap) return;
        const container = wrap.querySelector('#owl-testimonial');
        if(!container) return;
        const items = Array.from(container.querySelectorAll('.item'));
        if(items.length === 0) return;

        let idx = 0;
        function show(i){
          idx = (i + items.length) % items.length;
          items.forEach((it, j)=>{
            it.classList.toggle('active-testimony', j === idx);
          });
        }

        // init
        show(0);

        const prev = wrap.querySelector('.test-prev');
        const next = wrap.querySelector('.test-next');
        if(prev) prev.addEventListener('click', ()=> show(idx-1));
        if(next) next.addEventListener('click', ()=> show(idx+1));
      });
      </script>

       <?php include 'footer.php'; ?>