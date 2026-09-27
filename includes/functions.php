<?php
require_once __DIR__ . '/db.php';

/* ---------- Auto-migrate wishlist table ---------- */
try {
  $pdo->exec("CREATE TABLE IF NOT EXISTS wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_cust_prod (customer_id, product_id),
    KEY idx_customer (customer_id),
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (Exception $e) { /* ignore if migration fails on shared hosts */ }

function e($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect($u) { header("Location: $u"); exit; }

function flash($key, $msg = null) {
  if ($msg === null) {
    $v = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $v;
  }
  $_SESSION['flash'][$key] = $msg;
}

function is_customer_logged_in() { return isset($_SESSION['customer_id']); }
function is_admin_logged_in() { return isset($_SESSION['admin_id']); }
function cart_count() { return isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; }

function get_categories($pdo) {
  return $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
}

function get_products($pdo, $limit = null, $category_id = null, $search = null) {
  $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE 1";
  $params = [];
  if ($category_id) { $sql .= " AND p.category_id = ?"; $params[] = $category_id; }
  if ($search) { $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
  $sql .= " ORDER BY p.created_at DESC";
  if ($limit) { $sql .= " LIMIT " . (int)$limit; }
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  return $stmt->fetchAll();
}

/* ---------- Wishlist helpers ---------- */
function get_wishlist_ids($pdo) {
  if (!is_customer_logged_in()) return [];
  $stmt = $pdo->prepare("SELECT product_id FROM wishlists WHERE customer_id = ?");
  $stmt->execute([$_SESSION['customer_id']]);
  return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}
function wishlist_count($pdo) {
  if (!is_customer_logged_in()) return 0;
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlists WHERE customer_id = ?");
  $stmt->execute([$_SESSION['customer_id']]);
  return (int)$stmt->fetchColumn();
}

/* ---------- Product card (single source of truth) ---------- */
function render_product_card($p, $inWishlist = false) {
  $img = $p['image'] ? UPLOAD_URL . e($p['image']) : SITE_URL . '/assets/img/placeholder.png';
  $name = e($p['name']);
  $url = SITE_URL . '/product.php?slug=' . e($p['slug']);
  $cls = $inWishlist ? 'wish-btn active' : 'wish-btn';
  ob_start(); ?>
  <div class="product-card">
    <div class="product-img">
      <a href="<?= $url ?>"><img src="<?= $img ?>" alt="<?= $name ?>"></a>
      <button class="lb-btn" type="button" aria-label="View larger" onclick="openLightbox('<?= $img ?>', '<?= addslashes($p['name']) ?>')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
      </button>
      <button class="<?= $cls ?>" type="button" data-product="<?= (int)$p['id'] ?>" aria-label="Wishlist">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
      </button>
    </div>
    <div class="product-body">
      <div class="product-cat"><?= e($p['category_name'] ?? 'Product') ?></div>
      <div class="product-name"><a href="<?= $url ?>"><?= $name ?></a></div>
      <div class="product-price"><?= number_format($p['price'], 2) ?> <small>ETB</small></div>
      <a class="btn" href="<?= SITE_URL ?>/cart.php?add=<?= (int)$p['id'] ?>">Add to Cart</a>
    </div>
  </div>
  <?php return ob_get_clean();
}
?>