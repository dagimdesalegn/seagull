<?php
require_once __DIR__ . '/../includes/functions.php';
if (!is_admin_logged_in()) redirect(SITE_URL . '/admin/login.php');
$pageTitle = 'Products';
if (isset($_GET['delete'])) { $pdo->prepare("DELETE FROM products WHERE id=?")->execute([(int)$_GET['delete']]); flash('success','Product deleted.'); redirect(SITE_URL . '/admin/products.php'); }
$products = $pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.created_at DESC")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="admin-top"><h1>Products</h1><a class="btn" href="<?= SITE_URL ?>/admin/product_edit.php">+ Add Product</a></div>
<div class="panel">
  <table class="table">
    <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
    <tbody>
    <?php if (!$products): ?><tr><td colspan="6" class="muted">No products yet.</td></tr>
    <?php else: foreach ($products as $p): ?>
      <tr>
        <td><?= (int)$p['id'] ?></td>
        <td><?= e($p['name']) ?></td>
        <td><?= e($p['category_name'] ?? 'â€”') ?></td>
        <td><?= number_format($p['price'],2) ?> ETB</td>
        <td><?= (int)$p['stock'] ?></td>
        <td class="actions-cell">
          <a class="btn btn-sm" href="<?= SITE_URL ?>/admin/product_edit.php?id=<?= (int)$p['id'] ?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="?delete=<?= (int)$p['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
