<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Home';
$featured = get_products($pdo, 12);
$wishIds = get_wishlist_ids($pdo);
include __DIR__ . '/includes/header.php';
?>

<section class="hero hero-centered">
  <div class="hero-orb o1"></div>
  <div class="hero-orb o2"></div>
  <div class="hero-inner">
    <span class="hero-badge">Ethiopia's Trusted Trading Partner</span>
    <h1>Everything your home, office &amp; school <em>needs</em> - delivered.</h1>
    <p>Furniture, office equipment, educational materials and everyday essentials - sourced globally and delivered reliably across Ethiopia.</p>
    <div class="hero-actions">
      <a href="<?= SITE_URL ?>/shop.php" class="btn btn-primary">Shop Now</a>
      <a href="<?= SITE_URL ?>/shop.php" class="btn btn-outline">Browse Products</a>
    </div>
  </div>
</section>

<section class="section reveal">
  <div class="section-head">
    <h2>Featured Products</h2>
    <p>Popular picks from our catalogue</p>
    <div class="divider"></div>
  </div>
  <?php if (!$featured): ?>
    <div class="alert alert-error">No products yet. Add products from the admin panel.</div>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($featured as $p) echo render_product_card($p, in_array((int)$p['id'], $wishIds, true)); ?>
    </div>
    <div style="text-align:center;margin-top:44px">
      <a class="btn btn-primary" href="<?= SITE_URL ?>/shop.php">View All Products</a>
    </div>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>