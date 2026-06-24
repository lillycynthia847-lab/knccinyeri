/* ===========================
   KNCCI NYERI — MAIN.JS
   =========================== */

// ── Sticky header shadow
const header = document.getElementById('header');
window.addEventListener('scroll', () => {
  header.classList.toggle('scrolled', window.scrollY > 50);
});

// ── Mobile nav toggle
const hamburger   = document.getElementById('hamburger');
const navLinks    = document.getElementById('navLinks');
const body        = document.body;

// Create overlay element
const overlay = document.createElement('div');
overlay.className = 'nav-overlay';
body.appendChild(overlay);

function openNav() {
  body.classList.add('nav-open');
  hamburger.setAttribute('aria-expanded', 'true');
}
function closeNav() {
  body.classList.remove('nav-open');
  hamburger.setAttribute('aria-expanded', 'false');
}

hamburger.addEventListener('click', () => {
  body.classList.contains('nav-open') ? closeNav() : openNav();
});
overlay.addEventListener('click', closeNav);

// Close nav on link click (mobile)
navLinks.querySelectorAll('a').forEach(link => {
  link.addEventListener('click', closeNav);
});


// ── Active nav link on scroll
const sections = document.querySelectorAll('section[id]');
const navItems = document.querySelectorAll('.nav-link');

const sectionObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      navItems.forEach(link => link.classList.remove('active'));
      const active = document.querySelector(`.nav-link[href="#${entry.target.id}"]`);
      if (active) active.classList.add('active');
    }
  });
}, { threshold: 0.4 });

sections.forEach(s => sectionObserver.observe(s));

// ── Premium Smooth Spring Reveal on Scroll
const revealEls = document.querySelectorAll('.section-header, .about-visual, .about-content, .mgmt-card, .event-card, .news-card, .patrons-marquee-container, .pillar');
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      // Add a slight staggered delay based on vertical position if elements are close
      const rect = entry.target.getBoundingClientRect();
      const delay = Math.min(Math.floor(rect.top / 150) * 50, 200);
      
      entry.target.style.transitionDelay = `${delay}ms`;
      entry.target.classList.add('revealed');
      revealObserver.unobserve(entry.target);
    }
  });
}, { 
  threshold: 0.05, // Trigger as soon as 5% of the element is visible
  rootMargin: '0px 0px -50px 0px' // Trigger slightly before it enters the viewport fully
});

revealEls.forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(35px) scale(0.98)';
  // Using a custom cubic-bezier for a premium, high-end "spring" bounce effect
  el.style.transition = 'opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1), transform 0.95s cubic-bezier(0.34, 1.56, 0.64, 1)';
  revealObserver.observe(el);
});

document.head.insertAdjacentHTML('beforeend', `
  <style>
    .revealed { opacity: 1 !important; transform: translateY(0) scale(1) !important; }
  </style>
`);

// ── Hero Slideshow
(function () {
  const slides  = document.querySelectorAll('.slide');
  const dots    = document.querySelectorAll('.slider-dot');
  const caption = document.getElementById('sliderCaption');
  const prev    = document.querySelector('.slider-arrow--prev');
  const next    = document.querySelector('.slider-arrow--next');

  if (slides.length <= 1) return; // No slideshow needed for a single image

  let current   = 0;
  let interval  = null;
  const DELAY   = 6000; // 6 seconds per slide

  // Build caption array from PHP-rendered data attributes
  const captions = [];
  slides.forEach(s => {
    // We'll read caption from the slider-caption element if it's present
    captions.push('');
  });
  // Actually read from the initial caption element's text
  if (caption) {
    // The caption is set server-side for the first slide; for the rest we need data
    // Let's just pull from the DOM attribute
  }

  function goTo(index) {
    slides[current].classList.remove('slide--active');
    if (dots[current]) dots[current].classList.remove('dot--active');

    current = (index + slides.length) % slides.length;

    slides[current].classList.add('slide--active');
    if (dots[current]) dots[current].classList.add('dot--active');
  }

  function nextSlide() { goTo(current + 1); }
  function prevSlide() { goTo(current - 1); }

  function startAuto() {
    stopAuto();
    interval = setInterval(nextSlide, DELAY);
  }
  function stopAuto() {
    if (interval) clearInterval(interval);
  }

  // Dot clicks
  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      goTo(parseInt(dot.dataset.index, 10));
      startAuto(); // Reset timer
    });
  });

  // Arrow clicks
  if (prev) prev.addEventListener('click', () => { prevSlide(); startAuto(); });
  if (next) next.addEventListener('click', () => { nextSlide(); startAuto(); });

  // Pause on hover
  const slider = document.querySelector('.hero-slider');
  if (slider) {
    slider.addEventListener('mouseenter', stopAuto);
    slider.addEventListener('mouseleave', startAuto);
  }

  // Keyboard support
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft')  { prevSlide(); startAuto(); }
    if (e.key === 'ArrowRight') { nextSlide(); startAuto(); }
  });

  startAuto();
})();
