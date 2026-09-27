<?php
require_once __DIR__ . '/includes/functions.php';
if (!is_customer_logged_in()) {
  $_SESSION['login_redirect'] = SITE_URL . '/account.php';
  flash('error', 'Please log in.');
  redirect(SITE_URL . '/login.php');
}
$pageTitle = 'Order Details';
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) redirect(SITE_URL . '/account.php');

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND customer_id = ?");
$stmt->execute([$id, $_SESSION['customer_id']]);
$order = $stmt->fetch();
if (!$order) { flash('error','Order not found.'); redirect(SITE_URL . '/account.php'); }

$stmt = $pdo->prepare("SELECT oi.*, p.name AS product_name, p.slug AS product_slug, p.image AS product_image FROM order_items oi LEFT JOIN products p ON oi.product_id=p.id WHERE oi.order_id = ?");
$stmt->execute([$id]);
$items = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<a href="<?= SITE_URL ?>/account.php" style="display:inline-flex;align-items:center;gap:6px;color:var(--ink-3);font-size:13.5px;margin-bottom:12px">
  â† Back to My Account
</a>

<div class="order-head-card">
  <div>
    <h1>Order #<?= (int)$order['id'] ?></h1>
    <div class="meta">Placed on <?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?></div>
  </div>
  <span class="pill <?= e($order['status']) ?>"><?= e(ucfirst($order['status'])) ?></span>
</div>

<div class="panel" style="background:#fff;border-radius:16px;border:1px solid var(--line);box-shadow:var(--shadow-1);overflow:hidden;margin-bottom:20px">
  <table class="table">
    <thead><tr><th>Product</th><th>Unit Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
    <tbody>
      <?php $sum = 0; foreach ($items as $it): $sub = $it['price'] * $it['quantity']; $sum += $sub; ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:12px">
              <img src="<?= $it['product_image'] ? UPLOAD_URL . e($it['product_image']) : SITE_URL . '/assets/img/placeholder.png' ?>" alt="" style="width:52px;height:52px;object-fit:contain;background:#F6F8FB;border-radius:8px;padding:4px">
              <div>
                <?php if ($it['product_slug']): ?>
                  <a href="<?= SITE_URL ?>/product.php?slug=<?= e($it['product_slug']) ?>" style="font-weight:700;color:var(--navy)"><?= e($it['product_name']) ?></a>
                <?php else: ?>
                  <strong><?= e($it['product_name'] ?? '(deleted product)') ?></strong>
                <?php endif; ?>
              </div>
            </div>
          </td>
          <td><?= number_format($it['price'],2) ?> ETB</td>
          <td><?= (int)$it['quantity'] ?></td>
          <td><strong><?= number_format($sub,2) ?> ETB</strong></td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <th colspan="3" style="text-align:right">Total</th>
        <th><?= number_format($order['total'],2) ?> ETB</th>
      </tr>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>