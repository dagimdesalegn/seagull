<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'My Wishlist';

if (!is_customer_logged_in()) {
  $_SESSION['login_redirect'] = SITE_URL . '/wishlist.php';
  flash('error', 'Please log in to view your wishlist.');
  redirect(SITE_URL . '/login.php?redirect=/wishlist.php');
}

$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM wishlists w
  JOIN products p ON p.id = w.product_id
  LEFT JOIN categories c ON p.category_id = c.id
  WHERE w.customer_id = ? ORDER BY w.created_at DESC");
$stmt->execute([$_SESSION['customer_id']]);
$items = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<h1>My Wishlist</h1>
<p class="muted" style="margin-bottom:24px;color:var(--ink-3)">
  <?= count($items) ?> saved item<?= count($items) === 1 ? '' : 's' ?>
</p>

<?php if (!$items): ?>
  <div class="wishlist-empty">
    <div class="icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
    </div>
    <h2>Your wishlist is empty</h2>
    <p>Tap the heart on any product to save it for later.</p>
    <a class="btn btn-primary" href="<?= SITE_URL ?>/shop.php">Browse Products</a>
  </div>
<?php else: ?>
  <div class="grid">
    <?php foreach ($items as $p) echo render_product_card($p, true); ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>