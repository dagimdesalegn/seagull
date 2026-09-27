<?php
require_once __DIR__ . '/../includes/functions.php';
if (!is_admin_logged_in()) redirect(SITE_URL . '/admin/login.php');
$pageTitle = 'Order Details';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $allowed = ['pending','processing','shipped','delivered','cancelled'];
  $st = $_POST['status'] ?? 'pending';
  if (in_array($st,$allowed)) { $pdo->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$st,$id]); flash('success','Status updated.'); }
  redirect(SITE_URL.'/admin/order_view.php?id='.$id);
}
$stmt = $pdo->prepare("SELECT o.*, c.name AS customer_name, c.email AS customer_email, c.phone AS customer_phone, c.address AS customer_address FROM orders o LEFT JOIN customers c ON o.customer_id=c.id WHERE o.id=?");
$stmt->execute([$id]); $order = $stmt->fetch();
if (!$order) { flash('error','Not found.'); redirect(SITE_URL.'/admin/orders.php'); }
$stmt = $pdo->prepare("SELECT oi.*, p.name AS product_name FROM order_items oi LEFT JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
$stmt->execute([$id]); $items = $stmt->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="admin-top"><h1>Order #<?= (int)$order['id'] ?></h1><a class="btn btn-sm" href="<?= SITE_URL ?>/admin/orders.php">â† Back</a></div>
<div class="two-col">
  <div class="panel"><div class="panel-head"><h2>Customer</h2></div><div class="panel-pad">
    <p><strong>Name:</strong> <?= e($order['customer_name'] ?? 'Guest') ?></p>
    <p><strong>Email:</strong> <?= e($order['customer_email'] ?? 'â€”') ?></p>
    <p><strong>Phone:</strong> <?= e($order['customer_phone'] ?? 'â€”') ?></p>
    <p><strong>Address:</strong> <?= e($order['customer_address'] ?? 'â€”') ?></p>
    <p><strong>Placed:</strong> <?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></p>
  </div></div>
  <div class="panel"><div class="panel-head"><h2>Status</h2></div><div class="panel-pad">
    <form method="post">
      <label>Update Status</label>
      <select name="status"><?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?><option value="<?= $s ?>" <?= $order['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option><?php endforeach; ?></select>
      <button class="btn btn-block" style="margin-top:12px">Save</button>
    </form>
  </div></div>
</div>
<div class="panel"><div class="panel-head"><h2>Items</h2></div>
<table class="table">
  <thead><tr><th>Product</th><th>Unit Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
  <tbody><?php foreach ($items as $it): ?>
    <tr><td><?= e($it['product_name'] ?? '(deleted)') ?></td><td><?= number_format($it['price'],2) ?> ETB</td><td><?= (int)$it['quantity'] ?></td><td><?= number_format($it['price']*$it['quantity'],2) ?> ETB</td></tr>
  <?php endforeach; ?>
  <tr><th colspan="3" style="text-align:right">Total</th><th><?= number_format($order['total'],2) ?> ETB</th></tr></tbody>
</table></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
