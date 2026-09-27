</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-main">
      <div class="footer-cols">
        <div class="footer-brand-block">
          <div class="footer-brand">
            <img src="<?= SITE_URL ?>/assets/img/logo.jpg" alt="<?= SITE_NAME ?>">
            <div>
              <strong>SEAGULL</strong>
              <small>Trading PLC</small>
            </div>
          </div>
          <p>Reliable access to quality furniture, office and home equipment, educational materials, and everyday essentials - sourced globally and delivered across Ethiopia.</p>

          <ul class="footer-contact-list">
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <span><?= e(ADDRESS) ?></span>
            </li>
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <a href="mailto:<?= e(ADMIN_EMAIL) ?>"><?= e(ADMIN_EMAIL) ?></a>
            </li>
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              <a href="tel:<?= e(PHONE) ?>"><?= e(PHONE) ?></a>
            </li>
          </ul>
        </div>

        <div class="footer-links-column">
          <div class="footer-col">
            <h4>Shop</h4>
            <ul>
              <li><a href="<?= SITE_URL ?>/shop.php">All Products</a></li>
              <li><a href="<?= SITE_URL ?>/cart.php">Cart</a></li>
              <li><a href="<?= SITE_URL ?>/wishlist.php">Wishlist</a></li>
              <li><a href="<?= SITE_URL ?>/account.php">My Account</a></li>
            </ul>
          </div>

          <div class="footer-col">
            <h4>Get in Touch</h4>
            <ul>
              <li><a href="mailto:<?= e(ADMIN_EMAIL) ?>">Email Us</a></li>
              <li><a href="tel:<?= e(PHONE) ?>">Call Us</a></li>
              <li><a href="<?= SITE_URL ?>/shop.php">Visit Our Shop</a></li>
            </ul>
            <div class="footer-social">
              <a href="#" class="fb" aria-label="Facebook">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </a>
              <a href="#" class="ig" aria-label="Instagram">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
              </a>
              <a href="#" class="tw" aria-label="Twitter">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
              </a>
              <a href="#" class="li" aria-label="LinkedIn">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
              </a>
              <a href="#" class="yt" aria-label="YouTube">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
      <div class="footer-bottom-links">
        <a href="#">Privacy</a>
        <a href="#">Terms</a>
        <a href="#">Shipping</a>
      </div>
    </div>
  </div>
</footer>

<div id="toast" style="position:fixed;bottom:24px;left:24px;z-index:1200;display:flex;flex-direction:column;gap:10px;pointer-events:none"></div>

<!-- Lightbox -->
<div class="lightbox-overlay" id="lightbox">
  <button class="lightbox-close" aria-label="Close" onclick="closeLightbox()">&times;</button>
  <img class="lightbox-img" src="" alt="">
  <div class="lightbox-caption"></div>
</div>

<script>window.SITE_URL = '<?= SITE_URL ?>';</script>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
<script>
window.showToast = window.showToast || function(msg, type){
  var t = document.getElementById('toast'); if(!t) return;
  var el = document.createElement('div');
  el.style.background = 'linear-gradient(135deg,#0A2540,#0F3454)';
  el.style.color = '#fff'; el.style.padding = '14px 20px';
  el.style.borderRadius = '12px'; el.style.fontSize = '14px';
  el.style.fontWeight = '600'; el.style.boxShadow = '0 20px 50px rgba(10,37,64,.3)';
  el.style.borderLeft = '4px solid ' + (type === 'error' ? '#EF4444' : '#10B981');
  el.textContent = (type === 'error' ? 'Warning: ' : 'OK: ') + msg;
  t.appendChild(el);
  setTimeout(function(){ el.style.opacity = '0'; el.style.transition = 'opacity .3s'; setTimeout(function(){ el.remove(); }, 320); }, 2600);
};
</script>
</body>
</html>