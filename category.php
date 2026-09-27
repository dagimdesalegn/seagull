<?php
require_once __DIR__ . '/includes/functions.php';
$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM categories WHERE slug=?");
$stmt->execute([$slug]);
$cat = $stmt->fetch();
if (!$cat) redirect(SITE_URL . '/shop.php');
$pageTitle = $cat['name'];
$products = get_products($pdo, null, $cat['id']);
$wishIds = get_wishlist_ids($pdo);
include __DIR__ . '/includes/header.php';
?>
<h1><?= e($cat['name']) ?></h1>
<?php if (!$products): ?>
  <div class="alert alert-error">No products in this category yet.</div>
<?php else: ?>
  <div class="grid">
    <?php foreach ($products as $p) echo render_product_card($p, in_array((int)$p['id'], $wishIds, true)); ?>
  </div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>