<?php
require_once __DIR__ . '/../includes/functions.php';
if (!is_admin_logged_in()) redirect(SITE_URL . '/admin/login.php');
$pageTitle = 'Dashboard';

$products  = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$orders    = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$customers = (int)$pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$revenue   = (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status != 'cancelled'")->fetchColumn();
$pending   = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn();
$lowStock  = (int)$pdo->query("SELECT COUNT(*) FROM products WHERE stock < 10")->fetchColumn();
$today     = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()")->fetchColumn();

$recent = $pdo->query("SELECT o.*, c.name AS customer_name FROM orders o LEFT JOIN customers c ON o.customer_id=c.id ORDER BY o.created_at DESC LIMIT 6")->fetchAll();
$topProducts = $pdo->query("SELECT p.id, p.name, p.price, p.stock, c.name AS cat FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.created_at DESC LIMIT 5")->fetchAll();
$recentCustomers = $pdo->query("SELECT id, name, email, created_at FROM customers ORDER BY created_at DESC LIMIT 5")->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="admin-head">
  <div>
    <h1>Overview</h1>
    <p>Welcome back. Here's what's happening with your store today.</p>
  </div>
  <a href="<?= SITE_URL ?>/admin/product_edit.php" class="btn btn-primary">
    <svg style="width:15px;height:15px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Add Product
  </a>
</div>

<div class="stat-grid">
  <div class="stat-card sc-1">
    <div class="label">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
      Products
    </div>
    <div class="value"><?= number_format($products) ?></div>
    <div class="trend up"><?= $lowStock ?> low stock</div>
  </div>
  <div class="stat-card sc-2">
    <div class="label">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      Orders
    </div>
    <div class="value"><?= number_format($orders) ?></div>
    <div class="trend up"><?= $today ?> today</div>
  </div>
  <div class="stat-card sc-3">
    <div class="label">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      Customers
    </div>
    <div class="value"><?= number_format($customers) ?></div>
    <div class="trend up">Active accounts</div>
  </div>
  <div class="stat-card sc-4">
    <div class="label">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      Revenue
    </div>
    <div class="value"><?= number_format($revenue, 0) ?></div>
    <div class="trend up">ETB total</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1.35fr 1fr;gap:22px;align-items:start">
  <div class="panel">
    <div class="panel-head">
      <div>
        <h2>Recent Orders</h2>
        <p class="head-sub"><?= $pending ?> pending review</p>
      </div>
      <a class="btn btn-sm" href="<?= SITE_URL ?>/admin/orders.php">View All</a>
    </div>
    <table class="table">
      <thead>
        <tr><th>#</th><th>Customer</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr>
      </thead>
      <tbody>
      <?php if (!$recent): ?>
        <tr><td colspan="6" class="muted" style="text-align:center;padding:30px">No orders yet.</td></tr>
      <?php else: foreach ($recent as $o): ?>
        <tr>
          <td><strong>#<?= (int)$o['id'] ?></strong></td>
          <td>
            <div class="user-cell">
              <span class="avatar"><?= e(strtoupper(mb_substr($o['customer_name'] ?? 'G', 0, 1))) ?></span>
              <span><?= e($o['customer_name'] ?? 'Guest') ?></span>
            </div>
          </td>
          <td><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
          <td><strong><?= number_format($o['total'],2) ?> ETB</strong></td>
          <td><span class="pill <?= e($o['status']) ?>"><?= e(ucfirst($o['status'])) ?></span></td>
          <td><a class="btn btn-sm" href="<?= SITE_URL ?>/admin/order_view.php?id=<?= (int)$o['id'] ?>">View</a></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <div class="panel">
    <div class="panel-head">
      <div>
        <h2>Recent Customers</h2>
        <p class="head-sub">Newest sign-ups</p>
      </div>
      <a class="btn btn-sm" href="<?= SITE_URL ?>/admin/customers.php">View All</a>
    </div>
    <ul class="activity-feed">
      <?php if (!$recentCustomers): ?>
        <li class="muted" style="justify-content:center;padding:30px">No customers yet.</li>
      <?php else: foreach ($recentCustomers as $i => $c):
        $colors = ['coral','blue','green','purple','amber'];
        $color = $colors[$i % 5];
      ?>
        <li>
          <span class="dot <?= $color ?>"><?= e(strtoupper(mb_substr($c['name'], 0, 1))) ?></span>
          <div class="info">
            <strong><?= e($c['name']) ?></strong>
            <small><?= e($c['email']) ?></small>
          </div>
          <span class="time"><?= date('M j', strtotime($c['created_at'])) ?></span>
        </li>
      <?php endforeach; endif; ?>
    </ul>
  </div>
</div>

<div class="panel">
  <div class="panel-head">
    <div>
      <h2>Latest Products</h2>
      <p class="head-sub">Recently added to your catalogue</p>
    </div>
    <a class="btn btn-sm" href="<?= SITE_URL ?>/admin/products.php">Manage Products</a>
  </div>
  <table class="table">
    <thead>
      <tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th></th></tr>
    </thead>
    <tbody>
    <?php if (!$topProducts): ?>
      <tr><td colspan="5" class="muted" style="text-align:center;padding:30px">No products yet.</td></tr>
    <?php else: foreach ($topProducts as $p):
      $stockPill = $p['stock'] < 10 ? 'pending' : 'delivered';
    ?>
      <tr>
        <td><strong><?= e($p['name']) ?></strong></td>
        <td><?= e($p['cat'] ?? 'â€”') ?></td>
        <td><?= number_format($p['price'],2) ?> ETB</td>
        <td><span class="pill <?= $stockPill ?>"><?= (int)$p['stock'] ?> in stock</span></td>
        <td class="actions-cell">
          <a class="btn btn-sm" href="<?= SITE_URL ?>/admin/product_edit.php?id=<?= (int)$p['id'] ?>">Edit</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>