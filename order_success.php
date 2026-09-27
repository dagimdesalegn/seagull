<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Order Confirmed';
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) redirect(SITE_URL . '/shop.php');

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();
if (!$order) redirect(SITE_URL . '/shop.php');

// restrict: customers see only their own orders
if ($order['customer_id'] && is_customer_logged_in() && (int)$order['customer_id'] !== (int)$_SESSION['customer_id']) {
  flash('error', 'You cannot view that order.');
  redirect(SITE_URL . '/account.php');
}

include __DIR__ . '/includes/header.php';
?>
<div class="order-success-wrap">
  <div class="order-success">
    <div class="check-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <h1>Thank you for your order!</h1>
    <p class="sub">We've received your order and will contact you shortly to confirm delivery.</p>
    <div class="order-num">Order <span>#<?= (int)$order['id'] ?></span></div>
    <div class="actions">
      <?php if (is_customer_logged_in()): ?>
        <a class="btn btn-primary" href="<?= SITE_URL ?>/order_detail.php?id=<?= (int)$order['id'] ?>">View Order Details</a>
      <?php endif; ?>
      <a class="btn btn-ghost" href="<?= SITE_URL ?>/shop.php">Continue Shopping</a>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>