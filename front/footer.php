<!-- end ft-page -->
</div>

<!-- ═══════════════════ FOOTER ═══════════════════ -->
<footer class="ft-footer" aria-label="Site footer">
  <div class="ft-footer-inner">
    <div class="ft-footer-top">

      <!-- Brand -->
      <div class="ft-footer-brand">
        <a href="<?= $web_url ?>/" class="ft-logo" aria-label="<?= htmlspecialchars($pageTitle) ?> home">
          <img src="<?= $web_url ?>/assets/images/logo/<?= htmlspecialchars($page['image'] ?? 'logo.png') ?>" alt="<?= htmlspecialchars($pageTitle) ?> logo" />
          <span class="ft-logo-text"><?= htmlspecialchars($pageTitle) ?></span>
        </a>
        <p>Modern banking built for speed, security, and simplicity. Trusted by millions worldwide.</p>
        <div class="ft-footer-social">
          <a href="#" aria-label="Twitter"><i class="ri-twitter-x-line"></i></a>
          <a href="#" aria-label="Facebook"><i class="ri-facebook-line"></i></a>
          <a href="#" aria-label="Instagram"><i class="ri-instagram-line"></i></a>
          <a href="#" aria-label="LinkedIn"><i class="ri-linkedin-line"></i></a>
        </div>
      </div>

      <!-- Company -->
      <div class="ft-footer-col">
        <h5>Company</h5>
        <ul>
          <li><a href="<?= $web_url ?>/p/about.php">About Us</a></li>
          <li><a href="<?= $web_url ?>/p/contact.php">Contact</a></li>
          <li><a href="<?= $web_url ?>/p/about.php">Careers</a></li>
          <li><a href="<?= $web_url ?>/p/privacy-policy.php">Privacy Policy</a></li>
        </ul>
      </div>

      <!-- Personal -->
      <div class="ft-footer-col">
        <h5>Personal</h5>
        <ul>
          <li><a href="<?= $web_url ?>/p/ultimate-checking.php">Checking</a></li>
          <li><a href="<?= $web_url ?>/p/health-savings-account.php">Health Savings</a></li>
          <li><a href="<?= $web_url ?>/p/individual-retirement-account.php">Retirement (IRA)</a></li>
          <li><a href="<?= $web_url ?>/p/personal-loans.php">Personal Loans</a></li>
        </ul>
      </div>

      <!-- Business -->
      <div class="ft-footer-col">
        <h5>Business</h5>
        <ul>
          <li><a href="<?= $web_url ?>/p/business-essential-checking.php">Checking</a></li>
          <li><a href="<?= $web_url ?>/p/business-savings-account.php">Savings</a></li>
          <li><a href="<?= $web_url ?>/p/working-capital-loans.php">Working Capital</a></li>
          <li><a href="<?= $web_url ?>/p/business-term-loans.php">Term Loans</a></li>
        </ul>
      </div>

      <!-- Help -->
      <div class="ft-footer-col">
        <h5>Help</h5>
        <ul>
          <li><a href="<?= $web_url ?>/p/online-banking.php">Online Banking</a></li>
          <li><a href="<?= $web_url ?>/p/wire-transfers.php">Wire Transfers</a></li>
          <li><a href="<?= $web_url ?>/p/lost-cards.php">Lost Cards</a></li>
          <li><a href="<?= $web_url ?>/p/contact.php">Support</a></li>
        </ul>
      </div>

    </div>

    <div class="ft-footer-bottom">
      <p class="ft-footer-copy">© <?= date('Y') ?> <?= htmlspecialchars($pageTitle) ?>. All rights reserved. FDIC Insured.</p>
      <div class="ft-footer-legal">
        <a href="<?= $web_url ?>/p/privacy-policy.php">Privacy</a>
        <a href="<?= $web_url ?>/p/privacy-policy.php">Terms</a>
        <a href="<?= $web_url ?>/p/privacy-policy.php">Security</a>
        <a href="<?= $web_url ?>/p/privacy-policy.php">Compliance</a>
      </div>
    </div>

  </div>
</footer>

<!-- Scroll to Top -->
<button class="ft-scroll-top" id="ftScrollTop" aria-label="Scroll to top">
  <i class="ri-arrow-up-line"></i>
</button>

<?php if (empty($isHomePage)): ?>
<!-- Legacy JS for inner pages -->
<script src="<?= $web_url ?>/front/js/jquery.js"></script>
<script src="<?= $web_url ?>/front/js/popper.min.js"></script>
<script src="<?= $web_url ?>/front/js/bootstrap.min.js"></script>
<script src="<?= $web_url ?>/front/js/owl.js"></script>
<script src="<?= $web_url ?>/front/js/wow.js"></script>
<script src="<?= $web_url ?>/front/js/appear.js"></script>
<script src="<?= $web_url ?>/front/js/jquery.fancybox.js"></script>
<script src="<?= $web_url ?>/front/js/jquery-ui.js"></script>
<script src="<?= $web_url ?>/front/js/validate.js"></script>
<script>
(function(){
  "use strict";
  if(typeof jQuery !== 'undefined') {
    jQuery(document).ready(function($){
      /* Scroll to top */
      $(window).on('scroll', function(){
        if($(window).scrollTop() > 100){
          $('#ftScrollTop').css('display','flex');
        } else {
          $('#ftScrollTop').css('display','none');
        }
      });
      /* OWL carousels on inner pages */
      if($('.sponsors-carousel').length){
        $('.sponsors-carousel').owlCarousel({items:5,loop:true,autoplay:true,margin:30,nav:false,dots:false,responsive:{0:{items:2},576:{items:3},768:{items:4},992:{items:5}}});
      }
    });
  }
})();
</script>
<?php endif; ?>

<?= $page['livechat'] ?? '' ?>

<!-- ═══════════════════ SCRIPTS ═══════════════════ -->
<script>
(function () {
  "use strict";

  /* ── Navbar scroll effect ── */
  var nav = document.getElementById('ftNav');
  var scrollBtn = document.getElementById('ftScrollTop');

  function onScroll() {
    if (window.scrollY > 40) {
      nav.classList.add('scrolled');
      scrollBtn.classList.add('visible');
    } else {
      nav.classList.remove('scrolled');
      scrollBtn.classList.remove('visible');
    }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ── Mobile menu toggle ── */
  var hamburger = document.getElementById('ftHamburger');
  var mobileMenu = document.getElementById('ftMobileMenu');

  hamburger.addEventListener('click', function () {
    var isOpen = mobileMenu.classList.toggle('open');
    hamburger.classList.toggle('open', isOpen);
    hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    document.body.style.overflow = isOpen ? 'hidden' : '';
  });

  /* Close mobile menu on link click */
  mobileMenu.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', function () {
      mobileMenu.classList.remove('open');
      hamburger.classList.remove('open');
      hamburger.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
    });
  });

  /* ── Scroll to top ── */
  scrollBtn.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  /* ── FAQ Accordion ── */
  document.querySelectorAll('.ft-accordion-header').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var item = btn.closest('.ft-accordion-item');
      var isOpen = item.classList.contains('open');
      /* Close all */
      document.querySelectorAll('.ft-accordion-item').forEach(function (el) {
        el.classList.remove('open');
      });
      /* Open clicked (if it was closed) */
      if (!isOpen) item.classList.add('open');
    });
  });

  /* ── Scroll reveal animations ── */
  var reveals = document.querySelectorAll('.ft-reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('visible'); });
  }

  /* ── Animated counters ── */
  function animateCounter(el) {
    var target = parseFloat(el.dataset.target);
    var suffix = el.dataset.suffix || '';
    var prefix = el.dataset.prefix || '';
    var duration = 1800;
    var start = null;
    function step(ts) {
      if (!start) start = ts;
      var progress = Math.min((ts - start) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      var val = target * eased;
      el.textContent = prefix + (Number.isInteger(target) ? Math.round(val) : val.toFixed(1)) + suffix;
      if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  var counters = document.querySelectorAll('[data-target]');
  if ('IntersectionObserver' in window) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          cio.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (el) { cio.observe(el); });
  }

})();
</script>

</body>
</html>
