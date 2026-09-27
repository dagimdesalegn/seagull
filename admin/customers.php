<?php
require_once __DIR__ . '/../includes/functions.php';
if (!is_admin_logged_in()) redirect(SITE_URL . '/admin/login.php');
$pageTitle = 'Customers';

$search = trim($_GET['q'] ?? '');
$sql = "SELECT c.*, (SELECT COUNT(*) FROM orders WHERE customer_id=c.id) AS order_count,
        (SELECT COALESCE(SUM(total),0) FROM orders WHERE customer_id=c.id AND status != 'cancelled') AS total_spent
        FROM customers c";
$params = [];
if ($search !== '') {
  $sql .= " WHERE c.name LIKE ? OR c.email LIKE ? OR c.phone LIKE ?";
  $params = ["%$search%","%$search%","%$search%"];
}
$sql .= " ORDER BY c.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$customers = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="admin-head">
  <div>
    <h1>Customers</h1>
    <p><?= count($customers) ?> registered customer<?= count($customers) === 1 ? '' : 's' ?></p>
  </div>
  <form method="get" class="admin-search-mini" style="min-width:260px;background:#fff;border:1px solid var(--line)">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="q" placeholder="Search by name, email, phoneâ€¦" value="<?= e($search) ?>">
  </form>
</div>

<div class="panel">
  <table class="table">
    <thead>
      <tr><th>Customer</th><th>Contact</th><th>Orders</th><th>Spent</th><th>Joined</th></tr>
    </thead>
    <tbody>
    <?php if (!$customers): ?>
      <tr><td colspan="5" class="muted" style="text-align:center;padding:30px">No customers yet.</td></tr>
    <?php else: foreach ($customers as $i => $c):
      $colors = ['coral','blue','green','purple','amber'];
      $color = $colors[$i % 5];
    ?>
      <tr>
        <td>
          <div class="user-cell">
            <span class="avatar <?= $color ?>"><?= e(strtoupper(mb_substr($c['name'], 0, 1))) ?></span>
            <div>
              <strong><?= e($c['name']) ?></strong>
            </div>
          </div>
        </td>
        <td>
          <div><?= e($c['email']) ?></div>
          <div class="email"><?= e($c['phone'] ?: 'â€”') ?></div>
        </td>
        <td><strong><?= (int)$c['order_count'] ?></strong></td>
        <td><strong><?= number_format($c['total_spent'], 2) ?> ETB</strong></td>
        <td><?= date('M j, Y', strtotime($c['created_at'])) ?></td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>