<?php
require_once __DIR__ . '/includes/functions.php';
$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.slug=?");
$stmt->execute([$slug]);
$product = $stmt->fetch();
if (!$product) redirect(SITE_URL . '/shop.php');
$pageTitle = $product['name'];

$wishIds = get_wishlist_ids($pdo);
$inWishlist = in_array((int)$product['id'], $wishIds, true);

$img = $product['image'] ? UPLOAD_URL . e($product['image']) : SITE_URL . '/assets/img/placeholder.png';

// related products
$rel = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.category_id = ? AND p.id != ? ORDER BY RAND() LIMIT 4");
$rel->execute([$product['category_id'], $product['id']]);
$related = $rel->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div style="margin-top:10px">
  <div class="breadcrumb" style="font-size:13px;color:var(--ink-3);margin-bottom:10px">
    <a href="<?= SITE_URL ?>/index.php" style="color:var(--primary-light)">Home</a> â€º
    <a href="<?= SITE_URL ?>/shop.php" style="color:var(--primary-light)">Shop</a> â€º
    <?= e($product['category_name'] ?? 'Product') ?>
  </div>
</div>

<div class="product-detail">
  <div style="position:relative">
    <img id="mainProductImg" src="<?= $img ?>" alt="<?= e($product['name']) ?>" style="cursor:zoom-in" onclick="openLightbox('<?= $img ?>', '<?= addslashes($product['name']) ?>')">
    <button class="wish-btn <?= $inWishlist ? 'active' : '' ?>" data-product="<?= (int)$product['id'] ?>" style="position:absolute;top:16px;right:16px;width:48px;height:48px" aria-label="Wishlist">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
    </button>
  </div>

  <div>
    <span class="category"><?= e($product['category_name'] ?? 'Product') ?></span>
    <h1><?= e($product['name']) ?></h1>
    <div class="price"><?= number_format($product['price'], 2) ?> ETB</div>
    <p><?= nl2br(e($product['description'])) ?></p>
    <p style="color:var(--ink-3);font-size:13px">Stock: <?= (int)$product['stock'] ?> available</p>

    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px">
      <a class="btn btn-primary" href="<?= SITE_URL ?>/cart.php?add=<?= (int)$product['id'] ?>">Add to Cart</a>
      <a class="btn btn-outline" style="border-color:var(--line);color:var(--navy)" href="<?= SITE_URL ?>/checkout.php?add=<?= (int)$product['id'] ?>">Buy Now</a>
    </div>
  </div>
</div>

<?php if ($related): ?>
<section class="section">
  <div class="section-head" style="text-align:left;margin-bottom:22px">
    <h2 style="font-size:20px">Related Products</h2>
  </div>
  <div class="grid">
    <?php foreach ($related as $p) echo render_product_card($p, in_array((int)$p['id'], $wishIds, true)); ?>
  </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>