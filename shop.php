<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Shop';
$categories = get_categories($pdo);
$search = $_GET['q'] ?? null;
$cat_id = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
$products = get_products($pdo, null, $cat_id, $search);
$wishIds = get_wishlist_ids($pdo);
include __DIR__ . '/includes/header.php';
?>
<h1>Shop</h1>
<form method="get" class="filters">
  <input type="text" name="q" placeholder="Search products..." value="<?= e($search) ?>">
  <select name="cat">
    <option value="">All Categories</option>
    <?php foreach ($categories as $c): ?>
      <option value="<?= (int)$c['id'] ?>" <?= $cat_id==$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn">Filter</button>
</form>
<?php if (!$products): ?>
  <div class="alert alert-error">No products found.</div>
<?php else: ?>
  <div class="grid">
    <?php foreach ($products as $p) echo render_product_card($p, in_array((int)$p['id'], $wishIds, true)); ?>
  </div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>