<?php
require_once __DIR__ . '/../includes/functions.php';
if (!is_admin_logged_in()) redirect(SITE_URL . '/admin/login.php');
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$pageTitle = $isEdit ? 'Edit Product' : 'Add Product';
$product = ['name'=>'','category_id'=>'','description'=>'','price'=>'','stock'=>0,'image'=>''];
if ($isEdit) { $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?"); $stmt->execute([$id]); $product = $stmt->fetch() ?: $product; }
$categories = get_categories($pdo);
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? ''); $cat = (int)($_POST['category_id'] ?? 0);
  $desc = trim($_POST['description'] ?? ''); $price = (float)($_POST['price'] ?? 0); $stock = (int)($_POST['stock'] ?? 0);
  if ($name === '') $errors[] = 'Name required.';
  if ($price <= 0) $errors[] = 'Price must be > 0.';
  $imageName = $product['image'] ?? '';
  if (!empty($_FILES['image']['name'])) {
    $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
    $mime = mime_content_type($_FILES['image']['tmp_name']);
    if (!in_array($mime, $allowed)) $errors[] = 'Only JPG/PNG/GIF/WEBP.';
    elseif ($_FILES['image']['size'] > 3145728) $errors[] = 'Max 3 MB.';
    else {
      $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $imageName = uniqid('p_', true) . '.' . strtolower($ext);
      if (!move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $imageName)) { $errors[]='Upload failed.'; $imageName = $product['image'] ?? ''; }
    }
  }
  if (!$errors) {
    if ($isEdit) { $pdo->prepare("UPDATE products SET name=?,category_id=?,description=?,price=?,stock=?,image=? WHERE id=?")->execute([$name,$cat,$desc,$price,$stock,$imageName,$id]); flash('success','Updated.'); }
    else { $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/','-',$name)) . '-' . time(); $pdo->prepare("INSERT INTO products (name,slug,category_id,description,price,stock,image) VALUES (?,?,?,?,?,?,?)")->execute([$name,$slug,$cat,$desc,$price,$stock,$imageName]); flash('success','Added.'); }
    redirect(SITE_URL . '/admin/products.php');
  }
}
include __DIR__ . '/includes/header.php';
?>
<div class="admin-top"><h1><?= $isEdit?'Edit':'Add' ?> Product</h1><a class="btn btn-sm" href="<?= SITE_URL ?>/admin/products.php">â† Back</a></div>
<?php if ($errors): ?><div class="alert alert-error"><?php foreach ($errors as $e) echo '<div>â€¢ '.e($e).'</div>'; ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="admin-form">
  <label>Product Name *</label><input type="text" name="name" value="<?= e($_POST['name'] ?? $product['name']) ?>" required>
  <label>Category</label>
  <select name="category_id"><option value="">â€” None â€”</option>
    <?php foreach ($categories as $c): ?><option value="<?= (int)$c['id'] ?>" <?= (int)($product['category_id']??0)===(int)$c['id']?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
  </select>
  <label>Description</label><textarea name="description" rows="5"><?= e($_POST['description'] ?? $product['description']) ?></textarea>
  <div class="two-col">
    <div><label>Price (ETB) *</label><input type="number" step="0.01" name="price" value="<?= e($_POST['price'] ?? $product['price']) ?>" required></div>
    <div><label>Stock</label><input type="number" name="stock" value="<?= e($_POST['stock'] ?? $product['stock']) ?>"></div>
  </div>
  <label>Product Image</label>
  <?php if (!empty($product['image'])): ?><div class="current-image"><img src="<?= UPLOAD_URL . e($product['image']) ?>" alt=""><span>Current image</span></div><?php endif; ?>
  <input type="file" name="image" accept="image/*">
  <button type="submit" class="btn btn-block"><?= $isEdit?'Update':'Add' ?> Product</button>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>
