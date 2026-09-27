<?php
require_once __DIR__ . '/includes/functions.php';
if (!is_customer_logged_in()) { flash('error','Please log in.'); redirect(SITE_URL . '/login.php'); }
$pageTitle = 'My Account';

$stmt = $pdo->prepare("SELECT * FROM customers WHERE id=?"); $stmt->execute([$_SESSION['customer_id']]); $c = $stmt->fetch();
$stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_id=? ORDER BY created_at DESC"); $stmt->execute([$_SESSION['customer_id']]); $orders = $stmt->fetchAll();
$wishCount = wishlist_count($pdo);

include __DIR__ . '/includes/header.php';
?>
<h1>My Account</h1>
<div class="account-grid">
  <div class="account-card">
    <h2>Profile</h2>
    <p><strong>Name:</strong> <?= e($c['name']) ?></p>
    <p><strong>Email:</strong> <?= e($c['email']) ?></p>
    <p><strong>Phone:</strong> <?= e($c['phone'] ?: 'â€”') ?></p>
    <p><strong>Address:</strong> <?= e($c['address'] ?: 'â€”') ?></p>
    <p><strong>Member since:</strong> <?= date('M j, Y', strtotime($c['created_at'])) ?></p>
    <p style="margin-top:16px">
      <a class="btn btn-sm" href="<?= SITE_URL ?>/wishlist.php">My Wishlist (<?= (int)$wishCount ?>)</a>
    </p>
  </div>

  <div class="account-card">
    <h2>Order History</h2>
    <?php if (!$orders): ?>
      <p>You haven't placed any orders yet.</p>
      <a class="btn btn-primary" href="<?= SITE_URL ?>/shop.php">Start Shopping</a>
    <?php else: ?>
      <table class="table">
        <thead><tr><th>#</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td><strong>#<?= (int)$o['id'] ?></strong></td>
              <td><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
              <td><?= number_format($o['total'],2) ?> ETB</td>
              <td><span class="pill <?= e($o['status']) ?>"><?= e(ucfirst($o['status'])) ?></span></td>
              <td><a class="order-link" href="<?= SITE_URL ?>/order_detail.php?id=<?= (int)$o['id'] ?>">View â†’</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>