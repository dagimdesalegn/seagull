<?php
require_once __DIR__ . '/../includes/functions.php';
if (!is_admin_logged_in()) redirect(SITE_URL . '/admin/login.php');
$pageTitle = 'Orders';
$orders = $pdo->query("SELECT o.*, c.name AS customer_name, c.email AS customer_email FROM orders o LEFT JOIN customers c ON o.customer_id=c.id ORDER BY o.created_at DESC")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<h1>Orders</h1>
<div class="panel"><table class="table">
  <thead><tr><th>#</th><th>Customer</th><th>Email</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr></thead>
  <tbody>
  <?php if (!$orders): ?><tr><td colspan="7" class="muted">No orders yet.</td></tr>
  <?php else: foreach ($orders as $o): ?>
    <tr>
      <td>#<?= (int)$o['id'] ?></td>
      <td><?= e($o['customer_name'] ?? 'Guest') ?></td>
      <td><?= e($o['customer_email'] ?? 'â€”') ?></td>
      <td><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
      <td><?= number_format($o['total'],2) ?> ETB</td>
      <td><span class="pill <?= e($o['status']) ?>"><?= e(ucfirst($o['status'])) ?></span></td>
      <td><a class="btn btn-sm" href="<?= SITE_URL ?>/admin/order_view.php?id=<?= (int)$o['id'] ?>">View</a></td>
    </tr>
  <?php endforeach; endif; ?>
  </tbody>
</table></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
